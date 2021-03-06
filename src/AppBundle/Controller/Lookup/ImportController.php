<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/26/2017
 * Time: 11:20 AM
 */

namespace AppBundle\Controller\Lookup;


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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Note: The Import/Upload Workflow: 1: import(DataTable)Action is called, 2: importDataTable)HandleAction
 * 3: createSyncViewAction is called (just to create the sync view), 4: if the user click the Sync button
 * syncDataAction is called, if cancel, then cancelUploadAction() is called
 * @Security("has_role('ROLE_USER')")
 */
class ImportController extends Controller
{

    /**
     * @Route("/import/{entity}", name="import_data")
     * @param Request $request
     * @param EntityName $entity
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_EDITOR')")
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

                return $this->render('import/import.html.twig', array(
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

            // below function call performing huge task regarding reading file and giving us back the data
            $data = $this->checkFileData($entityObject, $excludedCols, $fileId, $importer);
            // if no data or any errors (flash messages also set above) redirect
            if ($data === false) {
                return $this->redirectToRoute('import_data', ['entity' => $entity]);
            }

            // this function call create the whole form for mapping columns (excel to database)
            $form = $this->createMapperForm($data['cols_excel'], $data['cols_entity']);
            // when user click map button (submit)
            if ($request->getMethod() == "POST") {
                $form->handleRequest($request);
                if ($form->isValid()) {

                    $mappedArray = $form->getData();
                    $excelData = $data['excel_data'];
                    $flashMessage = "";
                    $file_id = -1;
                    $table = 'table';
                    if ($hasTemp) {
                        $entityClass = "\\AppBundle\\Entity\\Temp" . $importer->remove_($entity, true);
                        $flashMessage = ", please synchronize it with main table!";
                        $file_id = $fileId;
                        $table = 'temporary table';
                    }

                    // get entity and unique cols
                    $uniqueCols = $uploadMgr->getUniqueColumns();
                    $entityCols = $uploadMgr->getEntityColumns();
                    //Todo: Uploading directly without having a temporary table
                    $result = $importer->processData($entityClass, $excelData, $mappedArray, $file_id, $uniqueCols, $entityCols);

                    if ($result === true) {
                        $this->addFlash("success", "The data has been successfully shifted to the $table " . $flashMessage);
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
                    $session->set("uniqueCols", $uniqueCols);
                    $session->set("entityCols", $entityCols);
                    //Todo: also check the uniqueness for those entities who don't have temp
                    if($hasTemp)
                        return $this->redirectToRoute("sync_data_view", ['entity' => $entity, 'fileId' => $fileId]);
                    elseif (!$hasTemp)
                        return $this->redirectToRoute("import_data", ['entity'=>$entity]);
                }
            }

            return $this->render('import/import_handle.html.twig',
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
        return $this->render("import/import_sync.html.twig", [
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
     * @Security("has_role('ROLE_EDITOR')")
     */
    public function syncDataAction(Request $request, $entity, $fileId, Importer $importer) {

        $em = $this->getDoctrine()->getManager();
        // check the provided entity name in the upload manager
        $uploadMgr = $em->getRepository("AppBundle:UploadManager")->findOneBy(['tableName'=>$entity]);

        // if the entity has an uploader activated
        if($uploadMgr !== null) {
            // create symfony slandered entity name from
            // the provided entity that has _
            $entityName = $importer->remove_($entity, true);
            // create path to the entity (normally every uploading entity will have
            // another entity with the same name prefixed by Temp
            $sourceEntity = "AppBundle\\Entity\\Temp" . $entityName;
            // in case some entity doesn't have temp entity then
            // this is just for caution otherwise if someone is coming to this function
            // that entity will have temp entity as well, and the file field should be included
            if(!$uploadMgr->getHasTemp())
                $sourceEntity = "AppBundle\\Entity\\" . $entityName; // create a path directly to that entity

            // get the data from the Temp entity by fileId (the recent file uploaded)
            $sourceData = $em->getRepository($sourceEntity)->findBy(['file' => $fileId]);

            // again just for the caution, if there was any data
            if ($sourceData !== null) {
                //$sourceEntity = new $sourceEntity();
                // now create a path to the target entity
                $targetEntity = "AppBundle\\Entity\\" . $entityName;

                // session to receive the variables set in another controller
                $session = $request->getSession();
                $columns = $session->get("requiredCols");
                $uniqueCols = $session->get("uniqueCols");
                $entityCols = $session->get("entityCols");

                // set batch size for cleaning the entity manager time by time
                $batchSize = 50;
                $errors = null; // store exceptions
                $updated = 0;   // variable to keep track of the updated rows
                $inserted = 0;  // variable to keep track of inserted rows
                // set SQL logger off, for the performance purpose
                $em->getConnection()->getConfiguration()->setSQLLogger(null);
                $counter = 0;   // just for the batch counter
                // loop through the uploaded data to shift to the dest table
                foreach ($sourceData as $index => $data) {

                    $criteria = array();
                    // make a criteria from the columns set in upload manager to
                    // define a row as a unique
                    // loop over those columns
                    foreach ($uniqueCols as $uniqueCol) {
                        $uniqueCol = $importer->remove_($uniqueCol, true);
                        // prepare the get function of those columns
                        $getFuncUniqueCol = "get" . $uniqueCol;
                        // initialize the array ['columnName'] = value (value from the data)
                        $criteria[lcfirst($uniqueCol)] = $data->$getFuncUniqueCol();
                    }

                    // now create the object of target entity
                    // first check if the record is already there by criteria
                    // we created above
                    $tEntity = $em->getRepository($targetEntity)->findOneBy($criteria);
                    $updated++;  // we assume that this is an update
                    // check if there is any data, if not then create an empty object
                    if ($tEntity === null) {
                        $updated--;   // decrease the update back
                        $inserted++;  // increase the new inserted rows
                        $tEntity = new $targetEntity();  // create an empty object
                    }
                    // loop through the required columns
                    foreach ($columns as $column) {
                        // make an entity field out of the column by removing _
                        $column = $importer->remove_($column, true);
                        $getFunc = "get" . $column;    // make a get function for this field
                        $setFunc = "set" . $column;    // make a set function for this field

                        $newData = $data->$getFunc();  // now get the data from the source data

                        // if this column was an entity column (FK)
                        $isEntityCol = in_array(lcfirst($column), $entityCols);
                        if ($isEntityCol === true) {
                            // path to that entity
                            $entityPath = "AppBundle\\Entity\\" . $column;
                            // get the object of that entity by id of that column (should be id)
                            $entityCol = $em->getRepository($entityPath)->findOneById($newData);
                            // so in this case (if the column is entity), update the newdata to be an object
                            $newData = $entityCol;
                        }
                        // now set this new data in the target entity
                        $tEntity->$setFunc($newData);

                    }

                    // at this point all the columns would be set, so let's presist it to the db
                    try {
                        $em->persist($tEntity);
                    } catch (DriverException $exception) {
                        $errors[] = "An exception occurred at row: ".($counter + 1)." and we escaped that row";
                    }
                    // clear the entity manager when this condition matched
                    if (($counter % $batchSize) === 0) {
                        $em->flush();
                        $em->clear();
                    }

                    $counter++;

                }

                $em->flush();
                $em->clear();

                // deleting the uploaded records from Temp table
                // we have to truncate/delete all the records from temp table now
                $query = $em->createQuery("Delete from " . $sourceEntity . " temp Where temp.file = " . $fileId);
                $numDeleted = $query->execute();

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
        if(count($colsExcel) < count($colsEntity)) {

            $this->addFlash("error", "The uploaded file doesn't have all the required information, 
            the file has been deleted. Please upload the correct file");

            if(count($colsExcel) < count($uploadedFileInfo['columns']))
                $this->addFlash('warning', "The uploaded file doesn't have columns headers, or some of the 
                columns headers are incorrect. Please upload the file with correct headers that match the information in
                the database");

            $em->remove($file);
            $em->flush();

            // redirect it to the upload page
            return false;

        } elseif (count($colsExcel) > count($colsEntity))
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


        $cols = array();
        $index = 0;
        foreach ($columns as $name=>$column) {
            $cols[$index] = $name;
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
                ->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($key)."1", $col);
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