<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/26/2017
 * Time: 11:20 AM
 */

namespace AppBundle\Controller\Lookup;


use AppBundle\Datatables\AdminDataDatatable;
use AppBundle\Datatables\ImportedFilesDatatable;
use AppBundle\Entity\AdminData;
use AppBundle\Entity\ImportedFiles;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Service\Importer;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class ImportedFilesController
 * @package AppBundle\Controller
 * @Security("has_role('ROLE_ADMIN')")
 */
class ImportedFilesController extends Controller
{

    /**
     * @Route("/uploaded/files/manage", name="manage_uploaded_files", options={"expose"=true})
     * @Method("GET")
     */
    public function indexAction(Request $request) {

        $isAjax = $request->isXmlHttpRequest();

        // Get your Datatable ...
        //$datatable = $this->get('app.datatable.post');
        //$datatable->buildDatatable();

        // or use the DatatableFactory
        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(ImportedFilesDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $dbQueryBuilder = $responseService->getDatatableQueryBuilder();

            $qb = $dbQueryBuilder->getQb();
            $qb->addOrderBy('importedfiles.id', 'DESC');
            return $responseService->getResponse();
        }



        return $this->render('pages/table.html.twig',
            ['datatable'=>$datatable, 'title'=>'Uploaded Files Management',
             'change_breadcrumb' => true,
             'breadcrumb_text' => 'Manage<small> uplaoded files</small>']);
    }

    /**
     * Bulk delete action.
     *
     * @param Request $request
     *
     * @Route("/bulk/delete/files", name="imported_files_bulk_delete")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function bulkDeleteAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $choices = $request->request->get('data');
            $token = $request->request->get('token');

            if (!$this->isCsrfTokenValid('multiselect', $token)) {
                throw new AccessDeniedException('The CSRF token is invalid.');
            }

            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:ImportedFiles');

            foreach ($choices as $choice) {
                $entity = $repository->find($choice['id']);
                $em->remove($entity);
            }

            $em->flush();

            return new Response('Success', 200);
        }

        return new Response('Bad Request', 400);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @internal param ImportedFiles $file
     * @Route("/uploaded/files/download/{id}", name="uploaded_files_download")
     */
    public function downloadFileAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository("AppBundle:ImportedFiles")->find($id);
        if($file !== null) {
            $downloadHandler = $this->get('vich_uploader.download_handler');

            return $downloadHandler->downloadObject($file, $fileField = 'importedFile');
        } else
            throw new FileNotFoundException("Sorry you have requested a bad file");
    }




}