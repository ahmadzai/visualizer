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
use AppBundle\Datatables\CoverageDataDatatable;
use AppBundle\Datatables\CoverageDataSummaryDatatable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Service\Settings;
use AppBundle\Service\Charts;


/**
 * @Security("has_role('ROLE_USER')")
 */
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
        $lastCamp = $settings->latestCampaign('CoverageData');
        // this function takes two parameters 1:table name to be joined with campaign table, 2: how many campaigns
        // to be returned (optional) by default it returns the last 3 campaigns (only ids)
        $campaignIds = $settings->lastFewCampaigns('CoverageData');

        $category = [['column'=>'Region'], ['column'=>'CID', 'substitute'=>['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']]];

        /**
         * The below method call is a dynamic function returning the data from different data-sources
         * however, you have to define a callMe() function in your Repository Class with the same structure as below
         * Then you would not need to call that function with Doctrine EntityManager, you just call chartData and pass
         * the tableName, functionName, and parameters for the original function in your repository
         */
        //$regionAdminData = $charts->chartData('CoverageData', 'regionAgg', $campaignIds);
        $lastCampAdminData = $charts->chartData('CoverageData', 'campaignStatistics', $lastCamp[0]['id']);
        $lastCampRegionsData = $charts->chartData('CoverageData', 'regionAgg', $lastCamp[0]['id']);

        //Total Vac Children Last 10 Campaigns
        $campaignIds = $settings->lastFewCampaigns('CoverageData', $settings::NUM_CAMP_CHARTS);
        $tenCampAdminData = $charts->chartData('CoverageData', 'campaignsStatistics', $campaignIds);
        $tenCampVacChildChart = $charts->chartData1Category($category[1], ['TotalVac'=>'Vaccinated Children'], $tenCampAdminData);
        $tenCampVacChildChart['title'] = 'Vaccinated Children During Last 10 Campaigns';

        $tenCampMissedChildChart = $charts->chartData1Category($category[1], ['TotalRemaining'=>'Missed Children'], $tenCampAdminData);
        $tenCampMissedChildChart['title'] = 'Missed Children During Last 10 Campaigns';

        $tenCampMissedTypeChart = $charts->chartData1Category($category[1],
            ['RemAbsent'=>'Absent', 'RemNSS'=>'NSS', 'RemRefusal'=>'Refusal'], $tenCampAdminData);
        $tenCampMissedTypeChart['title'] = 'Missed Children By Reason Last 10 Campaigns';

        // Last campaign missed by reason
        $lastCampMissedPieChart = $charts->pieData(['RemAbsent'=>'Absent', 'RemNSS'=>'NSS', 'RemRefusal'=>'Refusal'], $lastCampAdminData);
        $lastCampMissedPieChart['title'] = "Missed Children By Reason";

        // last campaign recovered all type by 3days, 4th day
        $lastCampRecovered = $charts->pieData(['Recovered3Days'=>'3Days', 'RecoveredDay4'=>'Day4', 'TotalRemaining'=>'Remaining'],
            $lastCampAdminData);
        $lastCampRecovered['title'] = "Missed Children Recovery Camp/Revisit";

        // last campaign Absent recovered by 3days and 4th day
        $lastCampAbsentRecovered = $charts->pieData(['VacAbsent3Days'=>'3Days', 'VacAbsentDay4'=>'Day4', 'RemAbsent'=>'Remaining'],
            $lastCampAdminData);
        $lastCampAbsentRecovered['title'] = "Absent Children Recovery Camp/Revisit";

        // last campaign NSS recovered by 3days and 4th day
        $lastCampNSSRecovered = $charts->pieData(['VacNSS3Days'=>'3Days', 'VacNSSDay4'=>'Day4', 'RemNSS'=>'Remaining'],
            $lastCampAdminData);
        $lastCampNSSRecovered['title'] = "NSS Children Recovery Camp/Revisit";

        // last campaign Refusal recovered by 3days and 4th day
        $lastCampRefusalRecovered = $charts->pieData(['VacRefusal3Days'=>'3Day', 'VacRefusalDay4'=>'Day4', 'RemRefusal'=>'Remaining'],
            $lastCampAdminData);
        $lastCampRefusalRecovered['title'] = "Refusal Children Recovery Camp/Revisit";

        // last campaign Refusal recovered by 3days and 4th day
        $last10CampRecovered = $charts->chartData1Category($category[1],
            ['TotalRemaining'=>'Remaining',
            'VacAbsent'=>'Recovered Absent',
            'VacNSS'=>'Recovered NSS',
            'VacRefusal'=>'Recovered Refusal'],
            $tenCampAdminData);
        $last10CampRecovered['title'] = "Recovering Missed Children By Reason During Last 10 Campaigns";

        // last campaign vaccine wastage by region
        $lastCampVaccineData = $charts->chartData1Category($category[0], ['VacWastage'=>'Wastage'], $lastCampRegionsData);
        $lastCampVaccineData['title'] = 'Regions Vaccine Wastage';
        return $this->render("pages/index.html.twig",
            [
                'chartVacChild10Camp' => json_encode($tenCampVacChildChart),
                'chartMissed10Camp' => json_encode($tenCampMissedChildChart),
                'chartMissedType10camp' => json_encode($tenCampMissedTypeChart),
                'lastCampPieData' => json_encode($lastCampMissedPieChart),
                'lastCampVacData' => json_encode($lastCampVaccineData),
                'lastCampRegionData' => $lastCampRegionsData,
                'recoveredAll' => json_encode($lastCampRecovered),
                'recoveredAbsent' => json_encode($lastCampAbsentRecovered),
                'recoveredNSS' => json_encode($lastCampNSSRecovered),
                'recoveredRefusal' => json_encode($lastCampRefusalRecovered),
                'last10CampRecovered' => json_encode($last10CampRecovered),
                'lastCampData' => $lastCampAdminData
            ]);

    }


    /**
     * @Route("/admin_data/download/{type}", name="admin_data_download", options={"expose"=true})
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
            $datatable = $this->get('sg_datatables.factory')->create(CoverageDataDatatable::class);
        else if($type == 'summary') {
            $datatable = $this->get('sg_datatables.factory')->create(CoverageDataSummaryDatatable::class);
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
                $qb->addOrderBy('coveragedata.subDistrict');
                $qb->addOrderBy('coveragedata.clusterNo');
                $qb->addOrderBy('coveragedata.vacDay');

            } else if($type == 'summary')
            {

                $qb->addOrderBy('coverageclustersummary.campaign', 'DESC');
                $qb->addOrderBy('coverageclustersummary.province');
                $qb->addOrderBy('coverageclustersummary.district');
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
     * @param Request $request
     * @param null $district
     * @return Response
     * @Route("/admin_data/clusters/{district}", name="cluster_admin_data", options={"expose"=true})
     */
    public  function clusterLevelAction(Request $request, $district = null, Charts $charts, Settings $settings) {
        $data = ['district' => $district===null?0:$district];

        if($district !== null) {
            // get last 6 campaigns
            $campaignIds = $settings->lastFewCampaigns('CoverageData', $settings::NUM_CAMP_CLUSTERS);

            $em = $this->getDoctrine()->getManager();
            // get sub district of a given district within the range of the campaigns
            $subDistrict = $em->getRepository('AppBundle:CoverageData')->subDistrictByDistrict($district, $campaignIds);

            // get all the clusters of a give district within the range of the campaigns
            $clustersArray = $em->getRepository('AppBundle:CoverageData')->clustersByDistrictCampaign([$district], $campaignIds);

            // make a one dimensional array of the clusters
            $clusters = array();
            $clustersWithSubDistrict = array();
            foreach ($clustersArray as $item) {
                $clusters[] = $item['clusterNo'];
                $clustersWithSubDistrict[] = $item['cluster'];
            }

            // generating data for the heatmap
            $heatMapData = array();
            // in case there was any sub district of a district
            if(count($subDistrict) > 0) {
                foreach($subDistrict as $item) {
                    // find the clusters data
                    $heatMapData[] = $em->getRepository('AppBundle:CoverageData')
                        ->clusterAggBySubDistrictCluster($campaignIds, $district, $clusters, $item['subDistrict']);
                }

                // merge the data of all sub districts
                $heatMapData = array_merge(...$heatMapData);
            }

            // if there's no sub district
            if(count($subDistrict) <= 0 || $subDistrict === null){
                $heatMapData = $em->getRepository('AppBundle:CoverageData')
                    ->clusterAggBySubDistrictCluster($campaignIds, $district, $clusters);
            }


            // covert the database data into heatmap array for a give indicator
            // if substitute was shortName, the function will make a short name for the campaign
            $heatMapDataTotalRemaining = $charts->clusterDataForHeatMap($heatMapData, 'TotalRemaining',
                                                                        ['column'=>'CID', 'substitute' => 'shortName'],
                                                                        $clustersWithSubDistrict);
            $heatMapDataTotalRemaining['title'] = 'Tends of total remaining children after campaign';
            $heatMapDataTotalRemaining['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                                                  ->findOne('CoverageData', 'TotalRemaining');
            $data['heatMapTotalRemaining'] = json_encode($heatMapDataTotalRemaining);

            // covert the database data into heatmap array for a give indicator
            $heatMapDataTotalAbsent = $charts->clusterDataForHeatMap($heatMapData, 'RemAbsent',
                                                                     ['column'=>'CID', 'substitute' => 'shortName'],
                                                                      $clustersWithSubDistrict);
            $heatMapDataTotalAbsent['title'] = 'Tends of total absent children after campaign';
            $heatMapDataTotalAbsent['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne('CoverageData', 'RemAbsent');
            $data['heatMapTotalAbsent'] = json_encode($heatMapDataTotalAbsent);

            // covert the database data into heatmap array for a give indicator
            $heatMapTotalNSS = $charts->clusterDataForHeatMap($heatMapData, 'RemNSS',
                                                              ['column'=>'CID', 'substitute' => 'shortName'],
                                                              $clustersWithSubDistrict);
            $heatMapTotalNSS['title'] = 'Tends of total NSS children after campaign';
            $heatMapTotalNSS['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne('CoverageData', 'RemNSS');
            $data['heatMapTotalNSS'] = json_encode($heatMapTotalNSS);

            // covert the database data into heatmap array for a give indicator
            $heatMapDataTotalRefusal = $charts->clusterDataForHeatMap($heatMapData, 'RemRefusal',
                                                                     ['column'=>'CID', 'substitute' => 'shortName'],
                                                                     $clustersWithSubDistrict);
            $heatMapDataTotalRefusal['title'] = 'Tends of total refusal children after campaign';
            $heatMapDataTotalRefusal['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne('CoverageData', 'RemRefusal');
            $data['heatMapTotalRefusal'] = json_encode($heatMapDataTotalRefusal);

            // Data of the latest one campaign and preparing the chart data
            $lastCampaign = $settings->latestCampaign("CoverageData");
            $lastCampaignId = $lastCampaign[0]['id'];

            $lastCampClustersData = array();
            if(count($subDistrict) > 0) {
                foreach($subDistrict as $item) {
                    // find the clusters data
                    $lastCampClustersData[] = $em->getRepository('AppBundle:CoverageData')
                        ->clusterAggBySubDistrictCluster([$lastCampaignId], $district, $clusters, $item['subDistrict']);
                }

                // merge the data of all sub districts
                $lastCampClustersData = array_merge(...$lastCampClustersData);
            }

            // if there's no sub district
            if(count($subDistrict) <= 0 || $subDistrict === null){
                $lastCampClustersData = $em->getRepository('AppBundle:CoverageData')
                    ->clusterAggBySubDistrictCluster([$lastCampaignId], $district, $clusters);
            }

//            dump($lastCampClustersData);
//            die;
            $lastCampBarChart = $charts->chartData1Category(['column'=>'Cluster'],
                                                            [
                                                                'RemAbsent'=>'Absent',
                                                                'RemNSS'=>'NSS',
                                                                'RemRefusal'=>'Refusal',
                                                                'TotalVac'=>'Vaccinated',
                                                            ],
                                                            $lastCampClustersData, false);
            $lastCampBarChart['title'] = $lastCampaign[0]['campaignName']." Vaccinated and Remaining Children";

            $data['lastCampBarChart'] = json_encode($lastCampBarChart);


        }

        return $this->render("pages/admin_data/clusters.html.twig",
            $data
        );

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
     *
     * @Security("has_role('ROLE_ADMIN')")
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
            $repository = $em->getRepository('AppBundle:CoverageData');

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