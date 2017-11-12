<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/26/2017
 * Time: 11:20 AM
 */

namespace AppBundle\Controller;


use AppBundle\Datatables\AdminDataDatatable;
use AppBundle\Datatables\AdminDataSummaryDatatable;
use AppBundle\Entity\AdminData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Service\Importer;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class AdminDataController extends Controller
{

    /**
     * @Route("/admin_data/download/{type}", name="admin_data_download", options={"expose"=true})
     * @Method("GET")
     */
    public function indexAction(Request $request, $type='all') {

        $isAjax = $request->isXmlHttpRequest();

        $title = "Coverage (Admin) Data";
        $alink = ['route'=>'#', 'title'=>'New Record', 'class'=>'btn-info'];
        $info = null;
        // Get your Datatable ...
        //$datatable = $this->get('app.datatable.post');
        //$datatable->buildDatatable();

        // or use the DatatableFactory
        /** @var DatatableInterface $datatable */
        $datatable = null;
        if($type == 'all')
            $datatable = $this->get('sg_datatables.factory')->create(AdminDataDatatable::class);
        else if($type == 'summary') {
            $datatable = $this->get('sg_datatables.factory')->create(AdminDataSummaryDatatable::class);
            $alink = null;
            $title = "Coverage (Admin) Data Summary";
            $info = "Please wait as the summary calculation will take some time!";
        }
        $datatable->buildDatatable();


        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $dbQueryBuilder = $responseService->getDatatableQueryBuilder();
            $dbQueryBuilder->useQueryCache(true);            // (1)
            $dbQueryBuilder->useCountQueryCache(true);       // (2)
            $dbQueryBuilder->useResultCache(true, 60);       // (3)
            $dbQueryBuilder->useCountResultCache(true, 60);  // (4)
            $qb = $dbQueryBuilder->getQb();
            if($type == 'all')
            {

                $qb->addOrderBy('campaign.id', 'DESC');
                $qb->addOrderBy('district.province');
                $qb->addOrderBy('district.id');

            } else if($type == 'summary')
            {

                $qb->addOrderBy('admclssum.campaign', 'DESC');
                $qb->addOrderBy('admclssum.province');
                $qb->addOrderBy('admclssum.district');
            }

            return $responseService->getResponse();
        }

        // creating buttons
        $buttons = array(
            'a' => $alink,
            'btn-group' =>['class'=>'btn-default', 'title'=>'Options', 'options' => array(
                ['route' => 'admin_data_download',
                    'params' => [], 'title'=>'Raw Data'],
                ['route' => 'admin_data_download',
                    'params' => ['type'=>'summary'], 'title'=>'Summary Data'],
            )]
        );

        return $this->render('pages/table.html.twig',
            ['datatable'=>$datatable,'title'=>$title,'buttons'=>$buttons, 'info' => $info]);
    }

    /**
     * @Route("/admin_data/data/summ", name="admin_data_download_summary", options={"expose"=true})
     * @Method("GET")
     */
    public function summaryAction(Request $request) {

//        $isAjax = $request->isXmlHttpRequest();
//
//        // Get your Datatable ...
//        //$datatable = $this->get('app.datatable.post');
//        //$datatable->buildDatatable();
//
//        // or use the DatatableFactory
//        /** @var DatatableInterface $datatable */
//        $datatable = $this->get('sg_datatables.factory')->create(AdminDataSummaryDatatable::class);
//        $datatable->buildDatatable();
//
//        if ($isAjax) {
//            $responseService = $this->get('sg_datatables.response');
//            $responseService->setDatatable($datatable);
//            $dbQueryBuilder = $responseService->getDatatableQueryBuilder();
//
//            $qb = $dbQueryBuilder->getQb();
//
//
//            $qb->addOrderBy('admclssum.campaign', 'DESC');
//            $qb->addOrderBy('admclssum.province');
//            $qb->addOrderBy('admclssum.district');
//
////            dump($qb->getDQL());
////            die();
//
//            return $responseService->getResponse();
//        }

        $em = $this->getDoctrine()->getManager();
        $rows = $em->getRepository("AppBundle:AdminData")->clusterAgg();



        // creating buttons
        $buttons = array(
            'a' => ['route'=>'#', 'title'=>'New Record', 'class'=>'btn-info'],
            'btn-group' =>['class'=>'btn-default', 'title'=>'Options', 'options' => array(
                ['route' => 'admin_data_download',
                    'params' => [], 'title'=>'Raw Data'],
                ['route' => 'admin_data_download',
                    'params' => [], 'title'=>'Summary Data'],
            )]
        );

        return $this->render('pages/table_client.html.twig',
            ['datatable'=>$rows,'title'=>'Coverage (Admin) Data','buttons'=>$buttons]);
    }

    /**
     * Bulk delete action.
     *
     * @param Request $request
     *
     * @Route("/bulk/delete/admin_data", name="admin_data_bulk_delete")
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
            $repository = $em->getRepository('AppBundle:AdminData');

            foreach ($choices as $choice) {
                $entity = $repository->find($choice['id']);
                $em->remove($entity);
            }

            $em->flush();

            return new Response('Success', 200);
        }

        return new Response('Bad Request', 400);
    }




}