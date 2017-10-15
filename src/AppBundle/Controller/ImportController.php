<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/26/2017
 * Time: 11:20 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\ImportedFiles;
use Doctrine\DBAL\Exception\DriverException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\Importer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Note: The Import/Upload Workflow: 1: import(DataTable)Action is called, 2: importDataTable)HandleAction
 * 3: createSyncViewAction is called (just to create the sync view), 4: if the user click the Sync button
 * syncDataAction is called, if cancel, then cancelUploadAction() is called
 */
class ImportController extends Controller
{

    /**
     * @Route("/import/{entity}", name="import_data")
     * @param Request $request
     * @param EntityName $entity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function importDataAction(Request $request, $entity) {

        /*
         * First checking if the provided entity has upload enable?
         */
        $em = $this->getDoctrine()->getManager();

        $uploadMgr = $em->getRepository("AppBundle:UploadManager")->findOneBy(['tableName'=>$entity]);
        if($uploadMgr !== null) {
            if($uploadMgr->getEnabled()) {
                $file = new ImportedFiles();
                $form = $this->createImportForm($file);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    return $this->persistFileAndReturn($file, $entity, 'import_data_handle');
                }

                return $this->render('pages/import.html.twig', array(
                    'file' => $file,
                    'form' => $form->createView(),
                    'entity' => $entity
                ));
            } else
                throw new FileNotFoundException("Bad Request! this entity doesn't support upload");

        } else
            throw new FileNotFoundException("Bad Request! this entity doesn't support upload");
    }

    /**
     * @param FileID $fileId
     * @param Importer $importer
     * @param Request $request
     * @param EntityName $entity
     * @Route("/import/{entity}/{fileId}/handle", name="import_data_handle")
     * @return Response
     */

    public function importDataHandleAction(Request $request, $entity, $fileId, Importer $importer)
    {
        $em = $this->getDoctrine()->getManager();

        $uploadMgr = $em->getRepository("AppBundle:UploadManager")->findOneBy(['tableName'=>$entity]);
        if($uploadMgr !== null) {
            $excludedCols = $uploadMgr->getExcludedColumns();
            $hasTemp = $uploadMgr->getHasTemp();


            $entityClass = "AppBundle\\Entity\\" . $importer->remove_($entity, true);
            $entityObject = new $entityClass();

            $data = $this->checkFileData($entityObject, $excludedCols, $fileId, $importer);

            if ($data === false) {
                return $this->redirectToRoute('import_data', ['entity' => $entity]);
            }

            $form = $this->createMapperForm($data['cols_excel'], $data['cols_entity']);

            if ($request->getMethod() == "POST") {
                $form->handleRequest($request);
                if ($form->isValid()) {

                    $mappedArray = $form->getData();
                    $excelData = $data['excel_data'];
                    $flashMessage = "";
                    if ($hasTemp) {
                        $entityClass = "\\AppBundle\\Entity\\Temp" . $importer->remove_($entity, true);
                        $flashMessage = ", please synchronize it with main table!";
                    }
                    $result = $importer->processData($entityClass, $excelData, $mappedArray, $fileId);

                    if ($result === true) {
                        $this->addFlash("success", "The data has been successfully shifted to the temporary table" . $flashMessage);
                    } else {
                        $message = "<ul>";
                        foreach ($result as $exception) {
                            $message .= "<li>" . $exception . "</li>";
                        }
                        $message .= "</ul>";

                        $this->addFlash("warning", $message);
                    }

                    // set the columns in session that are required for sync function
                    $session = $request->getSession();
                    $session->set("requiredCols", $mappedArray);
                    $session->set("uniqueCols", $uploadMgr->getUniqueColumns());
                    $session->set("entityCols", $uploadMgr->getEntityColumns());

                    if($hasTemp)
                        return $this->redirectToRoute("sync_data_view", ['entity' => $entity, 'fileId' => $fileId]);
                    elseif (!$hasTemp)
                        return $this->redirectToRoute("import_data", ['entity'=>$entity]);
                }
            }

            return $this->render('pages/import_handle.html.twig',
                    ['form' => $form->createView(),
                     'cols_excel' => $data['cols_excel'],
                     'entity' => $entity,
                     'file' => $fileId]);
        } else
            throw new FileNotFoundException("Sorry you have requested a bad file");

    }


    /**
     * @Route("/sync/{entity}/{fileId}", name="sync_data_view")
     * @param Request $request
     * @param $entity
     * @param $fileId
     * @param Importer $importer
     * @return Response
     */
    public function createSyncViewAction(Request $request, $entity, $fileId, Importer $importer) {

//        $referrer = $request->headers->get("referer");
//        $match = "(import)(".$entity.")(".$fileId.")(handle)";
//        if(!preg_match("/$match/", $referrer))
//            throw $this->createNotFoundException("You can't access this route directly!");
        $breadcrumb = $importer->remove_($entity, true);
        return $this->render("pages/import_sync.html.twig", [
            'breadcrumb' => $breadcrumb, 'entity'=>$entity, 'file'=>$fileId
        ]);
    }

    /**
     * @Route("/cancel/upload/{entity}/{fileId}/{del}", name="cancel_upload")
     * @param $entity
     * @param $fileId
     * @param $del
     * @param Importer $importer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cancelUploadAction(Request $request, $entity, $fileId, $del = 2, Importer $importer) {


        //TODO: Add fileId to all those entities that can have upload
        $em = $this->getDoctrine()->getManager();

        if($del == 1) {
            $file = $em->getRepository("AppBundle:ImportedFiles")->find($fileId);
            if ($file !== null)
                $em->remove($file);
            $em->flush();
            $this->addFlash("warning", "As you have canceled the process so your uploaded file has been deleted, upload new file if required!");
            return $this->redirectToRoute("import_data", ['entity'=>$entity]);
        }

        $uploadMgr = $em->getRepository("AppBundle:UploadManager")->findOneBy(['tableName'=>$entity]);
        if($uploadMgr !== null) {
            $entityClass = $importer->remove_($entity, true);
            $sourceEntity = "AppBundle\\Entity\\" . $entityClass;
            if($uploadMgr->getHasTemp())
                $sourceEntity = "AppBundle\\Entity\\Temp" . $entityClass;

            $sourceData = $em->getRepository($sourceEntity)->findBy(['file' => $fileId]);

            if ($sourceData !== null) {

                $query = $em->createQuery("Delete from " . $sourceEntity . " temp Where temp.file = " . $fileId);
                $query->execute();

                // Delete the file
                $file = $em->getRepository("AppBundle:ImportedFiles")->find($fileId);
                if ($file !== null)
                    $em->remove($file);
                $em->flush();

            }

            $this->addFlash("warning", "As you have canceled the process so your uploaded file has been deleted, upload new file if required!");
            return $this->redirectToRoute("import_data", ['entity'=>$entity]);
        } else
            throw  new FileNotFoundException("You have requested a bad file");
    }


    /**
     * @Route("/do-sync/{entity}/{fileId}", name="sync_entity_data")
     * @param Request $request
     * @param $entity
     * @param $fileId
     * @param Importer $importer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function syncDataAction(Request $request, $entity, $fileId, Importer $importer) {

        $em = $this->getDoctrine()->getManager();
        $uploadMgr = $em->getRepository("AppBundle:UploadManager")->findOneBy(['tableName'=>$entity]);

        if($uploadMgr !== null) {
            $entityName = $importer->remove_($entity, true);
            $sourceEntity = "AppBundle\\Entity\\Temp" . $entityName;
            if(!$uploadMgr->getHasTemp())
                $sourceEntity = "AppBundle\\Entity\\" . $entityName;

            $sourceData = $em->getRepository($sourceEntity)->findBy(['file' => $fileId]);

            if ($sourceData !== null) {
                //$sourceEntity = new $sourceEntity();

                $targetEntity = "AppBundle\\Entity\\" . $entityName;

                $session = $request->getSession();
                $columns = $session->get("requiredCols");
                $uniqueCols = $session->get("uniqueCols");
                $entityCols = $session->get("entityCols");

                $batchSize = 20;
                $errors = null; // store exceptions
                $updated = 0;
                $inserted = 0;
                foreach ($sourceData as $index => $data) {

                    $criteria = array();

                    foreach ($uniqueCols as $uniqueCol) {
                        $uniqueCol = $importer->remove_($uniqueCol, true);
                        $getFuncUniqueCol = "get" . $uniqueCol;
                        $criteria[lcfirst($uniqueCol)] = $data->$getFuncUniqueCol();
                    }

                    $tEntity = $em->getRepository($targetEntity)->findOneBy($criteria);
                    $updated++;
                    if ($tEntity === null) {
                        $updated--;
                        $inserted++;
                        $tEntity = new $targetEntity();
                    }

                    foreach ($columns as $column) {

                        $column = $importer->remove_($column, true);
                        $getFunc = "get" . $column;
                        $setFunc = "set" . $column;

                        $newData = $data->$getFunc();

                        $isEntityCol = in_array(lcfirst($column), $entityCols);
                        if ($isEntityCol === true) {
                            $entityPath = "AppBundle\\Entity\\" . $column;
                            $entityCol = $em->getRepository($entityPath)->find($newData);
                            $newData = $entityCol;
                        }

                        $tEntity->$setFunc($newData);

                    }

                    try {
                        $em->persist($tEntity);
                    } catch (DriverException $exception) {
                        $errors[] = "An exception occurred at row: " . $index + 1 . " and we escaped that row";
                    }
                    if (($index % $batchSize) === 0) {
                        $em->flush();
                        $em->clear();
                    }

                }

                // deleting the uploaded records from Temp table

                $query = $em->createQuery("Delete from " . $sourceEntity . " temp Where temp.file = " . $fileId);
                $numDeleted = $query->execute();


                $em->flush();
                $em->clear();

                $this->addFlash("success", "In total " . $inserted . " rows inserted and " . $updated . " have updated as they were already existed");

                if (count($errors) > 0) {
                    $message = "<ul>";
                    $message .= "<li>" . count($errors) . " rows (see below) has been escaped due to wrong types or other reasons </li>";
                    foreach ($errors as $error) {
                        $message .= "<li>" . $error . "</li>";
                    }
                    $message .= "</ul>";

                    $this->addFlash("warning", $message);
                }
            }

            return $this->redirectToRoute("import_data", ['entity'=>$entity]);
        } else
            throw new FileNotFoundException("You have requested a bad file");

    }

    /**
     * @param ImportedFiles $importedFiles
     * @return Form
     * Create Upload form
     */
    private function createImportForm(ImportedFiles $importedFiles) {
        return $this->createForm('AppBundle\Form\UploadType', $importedFiles);
    }

    /**
     * @param ImportedFiles $importedFiles
     * @param $entity
     * @param null $redirectUrl
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function persistFileAndReturn(ImportedFiles $importedFiles, $entity, $redirectUrl = null) {
        $em = $this->getDoctrine()->getManager();

        $importedFiles->setDataType($entity);
        $em->persist($importedFiles);
        $em->flush();

        $this->addFlash('success', 'The files '.$importedFiles->getFileName().' stored successfully');

        return $this->redirectToRoute($redirectUrl,
            ['fileId'=>$importedFiles->getId(), 'entity'=>$entity]
        );
    }

    /**
     * @param $excelCols
     * @param $entityCols
     * @return Form|\Symfony\Component\Form\FormInterface
     */
    private function createMapperForm($excelCols, $entityCols) {

        if($excelCols > $entityCols)
            $entityCols['Exclude this field'] = '-1';
        $formBuilder = $this->createFormBuilder($excelCols);
        foreach ($excelCols as $index=>$column)
        {
            //$fieldLabel = preg_split("|", $column);
            $formBuilder->add($index, ChoiceType::class, array('label'=> $column, 'choices' => $entityCols,
                'data' => $this->compareColumns($column, $entityCols), 'attr' => ['class'=>'form-control select2',
                    'style'=>"width:100%"]) );
        }
        $form = $formBuilder->getForm();


        return $form;

    }

    private function compareColumns($col, $cols)
    {
        $prev = 0;
        $key = 0;
        foreach ($cols as $k=>$v) {

            similar_text($col, $v, $per);
            if($per > $prev) {
                $prev = $per;
                $key = $v;
            }

        }

        return $key;

    }

    private function checkFileData($entity, $excludedCols, $fileId, Importer $importer) {
        $uploadDir = "data_files/";

        $em = $this->getDoctrine()->getManager();

        $file = $em->getRepository('AppBundle:ImportedFiles')->find($fileId);

        // path to uploaded file
        $uploadedFile = $uploadDir.$file->getFileName();

        // read all the required fields of entity
        $colsEntity = $importer->toDropDownArray($entity, $excludedCols);

        // return all the information about the file including cols and rows
        $uploadedFileInfo = $importer->readExcel($uploadedFile);

        // clean the columns of the uploaded file
        $colsExcel = $importer->cleanExcelColumns($uploadedFileInfo['columns']);

        // check if the columns were not matching with database or if the columns header were not there.
        if($colsExcel < $colsEntity) {
            $this->addFlash("error", "The uploaded file doesn't have all the required information, 
            the file has been deleted. Please upload the correct file");

            if($colsExcel < $uploadedFileInfo['columns'])
                $this->addFlash('warning', "The uploaded file doesn't have columns headers, or some of the 
                columns headers are incorrect. Please upload the file with correct headers that match the information in
                the database");

            $em->remove($file);
            $em->flush();

            // redirect it to the upload page
            return false;

        } elseif ($colsExcel > $colsEntity)
            $this->addFlash("warning", "The uploaded file have more columns, please map all the columns
            correctly, map the extra columns with Exclude this field");

        $this->addFlash('info', $uploadedFileInfo['rows']." and ".count($colsExcel)." valid columns");
        $data['cols_excel'] = $colsExcel;
        $data['cols_entity'] = $colsEntity;
        $data['excel_data'] = $uploadedFileInfo['data'];


        return $data;

    }


    /**
     * @Route("/download/{entity}/template", name="download_template")
     * @param $entity
     * @param Importer $importer
     * @return mixed
     */
    public function downloadTemplateAction($entity, Importer $importer)
    {
        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $em = $this->getDoctrine()->getManager();
        $uploadMgr = $em->getRepository("AppBundle:UploadManager")->findOneBy(['tableName'=>$entity]);
        $excludedCols = $uploadMgr->getExcludedColumns();

        $table = $importer->remove_($entity, true);

        $table = "AppBundle\\Entity\\".$table;
        $obj = new $table();
        $columns = $importer->toDropDownArray($obj, $excludedCols);

        $excelCols = range("A", "Z");

        $cols = array();
        $index = 0;
        foreach ($columns as $name=>$column) {
            $cols[$excelCols[$index]] = $name;
            $index ++;
        }

        unset($columns);
        unset($excelCols);

        $phpExcelObject->getProperties()->setCreator("PolioDB")
            ->setLastModifiedBy("Polio DB Server")
            ->setTitle("Data Upload Template for ".$importer->remove_($entity, true))
            ->setSubject("Upload data using this template")
            ->setKeywords("Microsoft Excel Generated by PHP Office")
            ->setCategory("Template");
        foreach($cols as $key=>$col) {
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue($key."1", $col);
        }

        $phpExcelObject->getActiveSheet()->setTitle('template_'.$entity);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        $sheet = $phpExcelObject->getActiveSheet();
        $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(true);
        /** @var PHPExcel_Cell $cell */
        foreach ($cellIterator as $cell) {
            $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
        }
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            "template_".$entity.'.xlsx'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }



}