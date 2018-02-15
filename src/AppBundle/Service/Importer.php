<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 9/1/2017
 * Time: 2:04 PM
 */

namespace AppBundle\Service;

use AppBundle\Entity\ImportedFiles;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\PDOException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata,
    Doctrine\Common\Util\Inflector,
    Exception;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bridge\Doctrine\PropertyInfo\DoctrineExtractor;

class Importer
{

    protected $container;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $_em;
    /**
     * @var int
     */
    protected $_recursionDepth = 0;
    /**
     * @var int
     */
    protected $_maxRecursionDepth = 0;

    //protected $entity;

    function __construct(EntityManagerInterface $_em, ContainerInterface $container)
    {
        $this->_em = $_em;
        $this->container = $container;
    }

    /**
     * @param Array|null $except
     * @param bool $allowTemp
     * @return array
     */
    public function listAllTables($except = null, $allowTemp = false)
    {
        $tables = $this->_em->getConnection()->getSchemaManager()->listTableNames();
        $data = array();
        foreach ($tables as $table) {

            $name = $table; //->getName();
            // remove the doctrine migration table
            if(strpos($name, "migration") === false)
                $data[$name] = $name;
        }

        // remove the excluded tables
        if($except !== null and is_array($except)) {
            foreach ($except as $item) {

                if(array_key_exists($item, $data))
                    unset($data[$item]);
            }
        }

        // remove the temp tables
        if($allowTemp === false) {
            foreach ($data as $datum) {
                $tempPos = strpos($datum, "temp");
                if($tempPos !== false)
                    unset($data[$datum]);
            }
        }

        return $data;
    }

    /**
     * @param $entity
     * @param string $entityBundle
     * @return array
     */
    public function listColumns($entity, $entityBundle = "AppBundle\\Entity\\") {
        $entity = $this->remove_($entity, true);
        $entityName = $entityBundle.$entity;

        return $this->toDropDownArray(new $entityName());
    }

    protected function _serializeEntity($entity, $name = false)
    {
        $className = $entity;
        if($name === true)
            $entity = new $className;
        if($name === false)
            $className = get_class($entity);
        $metadata = $this->_em->getClassMetadata($className);
        $data = array();
        foreach ($metadata->fieldMappings as $field => $mapping) {
            $value = $metadata->reflFields[$field]->getValue($entity);
            $field = Inflector::tableize($field);
            if ($value instanceof \DateTime) {
                // We cast DateTime to array to keep consistency with array result
                $data[$field] = (array)$value;
            } elseif (is_object($value)) {
                $data[$field] = (string)$value;
            } else {
                $data[$field] = $value;
            }
        }
        foreach ($metadata->associationMappings as $field => $mapping) {
            $key = Inflector::tableize($field);
            if ($mapping['isCascadeDetach']) {
                $data[$key] = $metadata->reflFields[$field]->getValue($entity);
                if (null !== $data[$key]) {
                    $data[$key] = $this->_serializeEntity($data[$key]);
                }
            } elseif ($mapping['isOwningSide'] && $mapping['type'] & ClassMetadata::TO_ONE) {
                if (null !== $metadata->reflFields[$field]->getValue($entity)) {
                    if ($this->_recursionDepth < $this->_maxRecursionDepth) {
                        $this->_recursionDepth++;
                        $data[$key] = $this->_serializeEntity(
                            $metadata->reflFields[$field]
                                ->getValue($entity)
                        );
                        $this->_recursionDepth--;
                    } else {
                        $data[$key] = $this->getEntityManager()
                            ->getUnitOfWork()
                            ->getEntityIdentifier(
                                $metadata->reflFields[$field]
                                    ->getValue($entity)
                            );
                    }
                } else {
                    // In some case the relationship may not exist, but we want
                    // to know about it
                    $data[$key] = null;
                }
            }
        }
        return $data;
    }
    /**
     * Serialize an entity to an array
     *
     * @param The entity $entity
     * @param the excluded columns $excludedColumns
     * @return array
     */
    public function toArray($entity, $excludedColumns = null)
    {
        $data = $this->_serializeEntity($entity);

        if($excludedColumns !== null) {
            foreach ($excludedColumns as $col) {
                unset($data[$col]);
            }
        }
        return $data;
    }

    /**
     * @param $entity
     * @param null $excludedColumns
     * @return array
     */
    public function toDropDownArray($entity, $excludedColumns = null)
    {
        $entityCols = $this->toArray($entity, $excludedColumns);
        $entityDropDown = array();
        foreach(array_keys($entityCols) as $key) {
            $entityDropDown[ucwords(str_replace("_", " ", $key))] = $key;
        }

        return $entityDropDown;
    }


    /**
     * @param $excelCols
     * @return array
     */
    public function cleanExcelColumns($excelCols)
    {
        $readableCols = array();
        foreach ($excelCols as $key=>$value) {
            if($value !== null and (!ctype_digit($value) or !is_numeric($value))) {
                $readable = preg_replace('/[^A-Za-z0-9\ -]/', '', $value);
                $readable = ucfirst($readable);
                $readable = preg_replace('/\\s+/', '-', $readable);
                //$optionValue = preg_replace("/[^A-Za-z0-9]/", "", $value);
                $readableCols[$key] = $readable;
            }
        }

        return $readableCols;
    }


    /**
     * Convert an entity to a JSON object
     *
     * @param The entity $entity
     * @param the excluded columns $excludedColumns
     * @return string
     */
    public function toJson($entity, $excludedColumns = null)
    {
        $data = $this->toArray($entity, $excludedColumns);
        return json_encode($data);
    }


    /**
     * @param Path to Excel file $excel
     * @return mixed
     */
    public function readExcel($excel)
    {
        $excelWb = $this->container->get('phpexcel')->createPHPExcelObject($excel);

        $highestRow = $excelWb->getActiveSheet()->getHighestRow();
        $highestCol = $excelWb->getActiveSheet()->getHighestColumn();

        $headers = $excelWb->getActiveSheet()
            ->rangeToArray(
                'A1:' . $highestCol . '1',
                null,
                false,
                false,
                true
            );
        $data['rows'] = "There're " . $highestRow . " rows including header-row in your file";
        $data['columns'] = $headers[1];


        $excelData = $excelWb->getActiveSheet()->rangeToArray(
            "A2:" . $highestCol . $highestRow,
            null,
            false,
            true,
            true
        );

        $data['data'] = $excelData;

        return $data;
    }

    /**
     * @param $var
     * @return int|null|string
     */
    public function chekType($var) {
        if($var === null)
            return null;
        $var = trim($var);
        if(is_numeric($var))
            $var = (int) $var;
        return $var;
    }

    /**
     * @param $text
     * @param bool $capitalize
     * @return mixed|string
     */
    public function remove_($text, $capitalize = false) {
        if($capitalize === false) {
            if (strpos($text, "_") === false)
                return $text;
            else {
                $text = str_replace("_", " ", $text);
                $text = ucwords($text);
                //return $text;
                return lcfirst(preg_replace("/\\s+/", "", $text));
            }
        } else if($capitalize === true) {
            if (strpos($text, "_") === false)
                return ucfirst($text);
            else {
                $text = str_replace("_", " ", $text);
                $text = ucwords($text);
                //return $text;
                return preg_replace("/\\s+/", "", $text);
            }
        }
    }

    /**
     * @param $excelData
     * @param $mappingKeys
     * @return array
     */
    public function replaceKeys($excelData, $mappingKeys) {
        $newData = array();

        foreach($excelData as $excelRows) {
            $row = array();
            foreach ($excelRows as $excelCol=>$celValue) {
                foreach ($mappingKeys as $key=>$value) {
                    if($excelCol == $key) {
                        $row[$this->remove_($value)] = $this->chekType($celValue);
                    }
                }
            }
            $newData[] = $row;
        }

        return $newData;
    }

    /**
     * @param $className
     */
    public function truncate($className) {
        $className = get_class($className);
        $classMetaData = $this->_em->getClassMetadata($className);
        $connection = $this->_em->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $q = $dbPlatform->getTruncateTableSql($classMetaData->getTableName());
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        }
        catch (\Exception $e) {
            $connection->rollback();
        }
    }

    /**
     * @param $className
     * @param $data
     * @param $mappedArray
     * @param ImportedFiles $fileId
     * @return array|bool|null
     */
    public function processData($className, $data, $mappedArray, $fileId) {

        $readyData = $this->replaceKeys($data, $mappedArray);

        //$entity = new $className();

//        $doctrineExtractor = new DoctrineExtractor($this->_em->getMetadataFactory());
//
//        $properties = $doctrineExtractor->getProperties($className);
//
//        $propertyTypes = array();
//
//        foreach($properties as $property) {
//            $propertyTypes[$property] = $doctrineExtractor->getTypes($className, $property);
//        }
//
//        $properties = null;

        //$this->truncate($entity);
        //dump($mappedArray);
        //dump($data);
        //die;

        $exceptions = null;
        $batchSize = 50;

        $counter = 0;

        $this->_em->getConnection()->getConfiguration()->setSQLLogger(null);

        foreach($readyData as $index => $dataRow) {

            $entity = new $className();
            $types = $this->_em->getClassMetadata($className)->fieldMappings;
            foreach($dataRow as $col=>$value) {

                $func = "set".ucfirst($col);
                $dataValue = trim($value) == ''?null:trim($value);
                $type = $types[$col]['type'];

                if($type == "integer" || $type == "float" || $type == "double") {
                    if( preg_match("/^-?[0-9]+$/", $dataValue) ||
                        preg_match('/^-?[0-9]+(\.[0-9]+)?$/', $dataValue) ||
                        is_numeric($dataValue)) {
                        $dataValue = $dataValue;
                    } else
                        $dataValue = $dataValue === null ? null : 0;
                }
                $entity->$func($dataValue);


            }

            // now setting the file id if file was not equal to -1, which means no file field in entity
            if($fileId !== -1)
                $entity->setFile($fileId);

            try {
                $this->_em->persist($entity);
            } catch (ForeignKeyConstraintViolationException $exception) {
                $exceptions[] = "Foreign-key violation occurred (some of the fk are not in the lookup tables) in row: ".$index;
                //$this->_em->rollback();
                continue;

            } catch (DBALException $exception) {
                $exceptions[] = "Some exception occurred at row: ".$index;
                continue;
                //$this->_em->rollback();
            } catch (DriverException $exception) {
                $exceptions[] = "Incorrect type detected and escaped at row: ".$index;
                continue;
                //$this->_em->rollback();
            } catch (PDOException $exception) {
                $exceptions[] = "Incorrect type detected and escaped at row: ".$index;
                continue;

            } catch (Exception $exception) {
                $exceptions[] = "Exception occurred and escaped at row: ".$index. ". Exception: ".$exception;
                continue;
                //$this->_em->rollback();
            }

            if($counter%$batchSize == 0) {
                $this->_em->flush();
                $this->_em->clear();
            }

            $counter ++;
        }

        $this->_em->flush();
        $this->_em->clear();

        if(count($exceptions) == null)
            $exceptions = true;

        return $exceptions;


    }



}