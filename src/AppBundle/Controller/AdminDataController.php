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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Service\Settings;
use AppBundle\Service\Charts;

class AdminDataController extends Controller
{

    /**
     * @Route("/admin_data", name="admin_data")
     * @param Request $request
     * @param Settings $settings
     * @param Charts $charts
     * @return Response
     */
    public function indexAction(Request $request, Settings $settings, Charts $charts) {

        // this function returns latest campaign, can work for all data sources that have relation with campaign
        $lastCamp = $settings->latestCampaign('AdminData');
        // this function takes two parameters 1:table name to be joined with campaign table, 2: how many campaigns
        // to be returned (optional) by default it returns the last 3 campaigns (only ids)
        $campaignIds = $settings->lastFewCampaigns('AdminData');

        $category = [['column'=>'Region'], ['column'=>'CID', 'substitute'=>['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']]];

        /**
         * The below method call is a dynamic function returning the data from different data-sources
         * however, you have to define a callMe() function in your Repository Class with the same structure as below
         * Then you would not need to call that function with Doctrine EntityManager, you just call chartData and pass
         * the tableName, functionName, and parameters for the original function in your repository
         */
        $regionAdminData = $charts->chartData('AdminData', 'regionAgg', $campaignIds);
        $lastCampAdminData = $charts->chartData('AdminData', 'campaignStatistics', $lastCamp[0]['id']);
        // Category 1 (name must be in the result set)
        // Category 2 (name must be in the result set)
        // Array of columns to show on chart (the index is the label and the value is the column name in the result set
        // Data returned above
        $missedChildChart = $charts->chartData2Categories($category[0], $category[1],
            ['RemainingRefusal'=>'Refusal',
                'RemainingNSS' => 'NSS', 'RemainingAbsent' => 'Absent'], $regionAdminData);
        $missedChildChart['title'] = "Remaining children by reasons";
        // For absent children
        $chartDataAbsent = $charts->chartData2Categories($category[0], $category[1],
            ['RemainingAbsent'=>'Remaining Absent',
                'VaccAbsent' => 'Vacc Absent'], $regionAdminData);
        $chartDataAbsent['title'] = "Recovering absent children during campaign";
        // For NSS
        $chartDataNss = $charts->chartData2Categories($category[0], $category[1],
            ['RemainingNSS'=>'Remaining NSS',
                'VaccNSS' => 'Vacc NSS'], $regionAdminData);
        $chartDataNss['title'] = "Recovering New born, sleep and sick children during campaign";
        // For Refusal
        $chartDataRefusal = $charts->chartData2Categories($category[0], $category[1],
            ['RemainingRefusal'=>'Remaining Refusal',
                'VaccRefusal' => 'Vacc Refusal'], $regionAdminData);
        $chartDataRefusal['title'] = "Recovering refusal children during campaign";
        $lastCampVaccUsageChart = $charts->chartData2Categories($category[0], $category[1],
            ['RVials'=>'ReceivedVials',
                'UVials' => 'UsedVials', 'VaccWastage' => 'Wastage'], $regionAdminData);
        $lastCampVaccUsageChart['title'] = "Vaccines usage";
        return $this->render("pages/index.html.twig",
            ['chart1data' => json_encode($missedChildChart),
                'chartDataAbsent' => json_encode($chartDataAbsent),
                'chartDataNss' => json_encode($chartDataNss),
                'chartDataRefusal' => json_encode($chartDataRefusal),
                'chartVaccineUsage' => json_encode($lastCampVaccUsageChart),
                'lastCampData' => $lastCampAdminData]);

    }

    /**
     * @Route("/admin_data/download/{type}", name="admin_data_download", options={"expose"=true})
     * @Method("GET")
     */
    public function downloadAction(Request $request, $type='all') {

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