<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/26/2017
 * Time: 11:20 AM
 */

namespace AppBundle\Controller;


use AppBundle\Datatables\CatchupDataDatatable;
use AppBundle\Service\Charts;
use AppBundle\Service\HtmlTable;
use AppBundle\Service\Settings;
use AppBundle\Service\Triangle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class CatchupDataController
 * @package AppBundle\Controller
 * @Security("has_role('ROLE_USER')")
 */
class CatchupDataController extends Controller
{

    /**
     * @param Request $request
     * @param Settings $settings
     * @param Charts $charts
     * @return Response
     * @Route("/catchup_data", name="catchup_data")
     */
    public function indexAction(Request $request, Settings $settings, Charts $charts) {

        // this function returns latest campaign, can work for all data sources that have relation with campaign
        $lastCamp = $settings->latestCampaign('CatchupData');
        // this function takes two parameters 1:table name to be joined with campaign table, 2: how many campaigns
        // to be returned (optional) by default it returns the last 3 campaigns (only ids)
        $campaignIds = $settings->lastFewCampaigns('CatchupData');

        $category = [['column'=>'Region'], ['column'=>'CID', 'substitute'=>['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']]];

        /**
         * The below method call is a dynamic function returning the data from different data-sources
         * however, you have to define a callMe() function in your Repository Class with the same structure as below
         * Then you would not need to call that function with Doctrine EntityManager, you just call chartData and pass
         * the tableName, functionName, and parameters for the original function in your repository
         */
        //$regionAdminData = $charts->chartData('CatchupData', 'regionAgg', $campaignIds);
        $lastCampAdminData = $charts->chartData('CatchupData', 'campaignStatistics', $lastCamp[0]['id']);
        $lastCampRegionsData = $charts->chartData('CatchupData', 'regionAgg', $lastCamp[0]['id']);

        //Total Vac Children Last 10 Campaigns
        $campaignIds = $settings->lastFewCampaigns('CatchupData', $settings::NUM_CAMP_CHARTS);
        $tenCampAdminData = $charts->chartData('CatchupData', 'campaignsStatistics', $campaignIds);
        $tenCampVacChildChart = $charts->chartData1Category($category[1], ['TotalRecovered'=>'Recovered Children'], $tenCampAdminData);
        $tenCampVacChildChart['title'] = 'Recovered Children During Last 10 Campaigns';

        $tenCampMissedChildChart = $charts->chartData1Category($category[1], ['TotalRemaining'=>'Still Missed Children'], $tenCampAdminData);
        $tenCampMissedChildChart['title'] = 'Remaining Children During Last 10 Campaigns';

        $tenCampMissedTypeChart = $charts->chartData1Category($category[1],
            ['RemAbsent'=>'Absent', 'RemNSS'=>'NSS', 'RemRefusal'=>'Refusal'], $tenCampAdminData);
        $tenCampMissedTypeChart['title'] = 'Remaining Children By Reason Last 10 Campaigns';

        // new row for missed recovery
        $tenCampMissedRecovered = $charts->chartData1Category($category[1],
            ['TotalRemaining'=>'Remaining', 'TotalRecovered'=>'Recovered'],
            $tenCampAdminData);
        $tenCampMissedRecovered['title'] = "Missed Children Recovery in Catchup";

        $tenCampAbsentRecovered = $charts->chartData1Category($category[1],
            ['RemAbsent'=>'Remaining', 'VacAbsent'=>'Recovered'],
            $tenCampAdminData);
        $tenCampAbsentRecovered['title'] = "Absent Children Recovery in Catchup";

        $tenCampNSSRecovered = $charts->chartData1Category($category[1],
            ['RemNSS'=>'Remaining', 'VacNSS'=>'Recovered'],
            $tenCampAdminData);
        $tenCampNSSRecovered['title'] = "NSS Children Recovery in Catchup";

        $tenCampRefusalRecovered = $charts->chartData1Category($category[1],
            ['RemRefusal'=>'Remaining', 'VacRefusal'=>'Recovered'],
            $tenCampAdminData);
        $tenCampRefusalRecovered['title'] = "Refusal Children Recovery in Catchup";

        // Last campaign missed by reason
        $lastCampMissedPieChart = $charts->pieData(['RemAbsent'=>'Absent', 'RemNSS'=>'NSS', 'RemRefusal'=>'Refusal'], $lastCampAdminData);
        $lastCampMissedPieChart['title'] = "Remaining Children By Reason";

        // last campaign recovered all type by 3days, 4th day
        $lastCampRecovered = $charts->pieData(['TotalRecovered'=>'Recovered', 'TotalRemaining'=>'Remaining'],
            $lastCampAdminData);
        $lastCampRecovered['title'] = "Missed Children Recovery in Catchup";

        // last campaign Absent recovered by 3days and 4th day
        $lastCampAbsentRecovered = $charts->pieData(['VacAbsent'=>'Recovered', 'RemAbsent'=>'Remaining'],
            $lastCampAdminData);
        $lastCampAbsentRecovered['title'] = "Absent Children Recovery in Catchup";

        // last campaign NSS recovered by 3days and 4th day
        $lastCampNSSRecovered = $charts->pieData(['VacNSS'=>'Recovered', 'RemNSS'=>'Remaining'],
            $lastCampAdminData);
        $lastCampNSSRecovered['title'] = "NSS Children Recovery in Catchup";

        // last campaign Refusal recovered by 3days and 4th day
        $lastCampRefusalRecovered = $charts->pieData(['VacRefusal'=>'Recovered', 'RemRefusal'=>'Remaining'],
            $lastCampAdminData);
        $lastCampRefusalRecovered['title'] = "Refusal Children Recovery in Catchup";

        // last campaign Refusal recovered by 3days and 4th day
        $last10CampRecovered = $charts->chartData1Category($category[1],
            ['TotalRemaining'=>'Remaining',
                'VacAbsent'=>'Recovered Absent',
                'VacNSS'=>'Recovered NSS',
                'VacRefusal'=>'Recovered Refusal'],
            $tenCampAdminData);
        $last10CampRecovered['title'] = "Recovering Missed Children By Reason During Last 10 Campaigns";

        // last campaign vaccine wastage by region
        //$calcData = Triangle::mathOps()
        $lastCampVaccineData = $charts->chartData1Category($category[0], ['TotalRemaining'=>'Remaining'], $lastCampRegionsData);
        $lastCampVaccineData['title'] = 'Remaining children after catchup';

        $table = HtmlTable::tableForCatchupData($lastCampRegionsData);
        $info = HtmlTable::infoForCatchup($lastCampAdminData);

        return $this->render("pages/fieldbook/index.html.twig",
            [
                'chartVacChild10Camp' => json_encode($tenCampVacChildChart),
                'chartMissed10Camp' => json_encode($tenCampMissedChildChart),
                'chartMissedType10camp' => json_encode($tenCampMissedTypeChart),
                'chartMissedRec10Camp' => json_encode($tenCampMissedRecovered),
                'chartAbsentRec10Camp' => json_encode($tenCampAbsentRecovered),
                'chartNSSRec10Camp' => json_encode($tenCampNSSRecovered),
                'chartRefusalRec10Camp' => json_encode($tenCampRefusalRecovered),
                'lastCampPieData' => json_encode($lastCampMissedPieChart),
                'lastCampVacData' => json_encode($lastCampVaccineData),
                'lastCampRegionData' => $lastCampRegionsData,
                'recoveredAll' => json_encode($lastCampRecovered),
                'recoveredAbsent' => json_encode($lastCampAbsentRecovered),
                'recoveredNSS' => json_encode($lastCampNSSRecovered),
                'recoveredRefusal' => json_encode($lastCampRefusalRecovered),
                'last10CampRecovered' => json_encode($last10CampRecovered),
                'campaign' => $lastCampAdminData[0]['CName'],
                'info'=>$info,
                'table'=>$table
            ]);
    }

    /**
     * @param Request $request
     * @param null $district
     * @return Response
     * @Route("/catchup_data/clusters/{district}", name="cluster_catchup_data", options={"expose"=true})
     */
    public  function clusterLevelAction(Request $request, $district = null, Charts $charts, Settings $settings) {
        $data = ['district' => $district===null?0:$district];

        if($district !== null) {
            $entity = "CatchupData";
            // get last 6 campaigns
            $campaignIds = $settings->lastFewCampaigns($entity, $settings::NUM_CAMP_CLUSTERS);

            $em = $this->getDoctrine()->getManager();
            // get sub district of a given district within the range of the campaigns
            $subDistrict = $em->getRepository('AppBundle:'.$entity)->subDistrictByDistrict($district, $campaignIds);

            // get all the clusters of a give district within the range of the campaigns
            $clustersArray = $em->getRepository('AppBundle:'.$entity)->clustersByDistrictCampaign([$district], $campaignIds);

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
                    $heatMapData[] = $em->getRepository('AppBundle:CatchupData')
                        ->clusterAggBySubDistrictCluster($campaignIds, $district, $clusters, $item['subDistrict']);
                }

                // merge the data of all sub districts
                $heatMapData = array_merge(...$heatMapData);
            }

            // if there's no sub district
            if(count($subDistrict) <= 0 || $subDistrict === null){
                $heatMapData = $em->getRepository('AppBundle:CatchupData')
                    ->clusterAggBySubDistrictCluster($campaignIds, $district, $clusters);
            }


            // covert the database data into heatmap array for a give indicator
            // if substitute was shortName, the function will make a short name for the campaign
            $heatMapDataTotalRemaining = $charts->clusterDataForHeatMap($heatMapData, 'TotalRemaining',
                ['column'=>'CID', 'substitute' => 'shortName'],
                $clustersWithSubDistrict);
            $heatMapDataTotalRemaining['title'] = 'Tends of total remaining children after catchup';
            $heatMapDataTotalRemaining['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne($entity, 'TotalRemaining');
            $data['heatMapTotalRemaining'] = json_encode($heatMapDataTotalRemaining);

            // covert the database data into heatmap array for a give indicator
            $heatMapDataTotalAbsent = $charts->clusterDataForHeatMap($heatMapData, 'RemAbsent',
                ['column'=>'CID', 'substitute' => 'shortName'],
                $clustersWithSubDistrict);
            $heatMapDataTotalAbsent['title'] = 'Tends of total absent children after catchup';
            $heatMapDataTotalAbsent['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne($entity, 'RemAbsent');
            $data['heatMapTotalAbsent'] = json_encode($heatMapDataTotalAbsent);

            // covert the database data into heatmap array for a give indicator
            $heatMapTotalNSS = $charts->clusterDataForHeatMap($heatMapData, 'RemNSS',
                ['column'=>'CID', 'substitute' => 'shortName'],
                $clustersWithSubDistrict);
            $heatMapTotalNSS['title'] = 'Tends of total NSS children after catchup';
            $heatMapTotalNSS['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne($entity, 'RemNSS');
            $data['heatMapTotalNSS'] = json_encode($heatMapTotalNSS);

            // covert the database data into heatmap array for a give indicator
            $heatMapDataTotalRefusal = $charts->clusterDataForHeatMap($heatMapData, 'RemRefusal',
                ['column'=>'CID', 'substitute' => 'shortName'],
                $clustersWithSubDistrict);
            $heatMapDataTotalRefusal['title'] = 'Tends of total refusal children after catchup';
            $heatMapDataTotalRefusal['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne($entity, 'RemRefusal');
            $data['heatMapTotalRefusal'] = json_encode($heatMapDataTotalRefusal);

            // Data of the latest one campaign and preparing the chart data
            $lastCampaign = $settings->latestCampaign($entity);
            $lastCampaignId = $lastCampaign[0]['id'];

            $lastCampClustersData = array();
            if(count($subDistrict) > 0) {
                foreach($subDistrict as $item) {
                    // find the clusters data
                    $lastCampClustersData[] = $em->getRepository('AppBundle:CatchupData')
                        ->clusterAggBySubDistrictCluster([$lastCampaignId], $district, $clusters, $item['subDistrict']);
                }

                // merge the data of all sub districts
                $lastCampClustersData = array_merge(...$lastCampClustersData);
            }

            // if there's no sub district
            if(count($subDistrict) <= 0 || $subDistrict === null){
                $lastCampClustersData = $em->getRepository('AppBundle:CatchupData')
                    ->clusterAggBySubDistrictCluster([$lastCampaignId], $district, $clusters);
            }

//            dump($lastCampClustersData);
//            die;
            $lastCampBarChart = $charts->chartData1Category(['column'=>'Cluster'],
                [
                    'RemAbsent'=>'Absent',
                    'RemNSS'=>'NSS',
                    'RemRefusal'=>'Refusal',
                    'TotalRecovered'=>'Recovered',
                ],
                $lastCampClustersData, false);
            $lastCampBarChart['title'] = $lastCampaign[0]['campaignName']." Recovered and Remaining Children";

            $data['lastCampBarChart'] = json_encode($lastCampBarChart);


        }

        return $this->render("pages/fieldbook/clusters.html.twig",
            $data
        );

    }

    /**
     * @param Request $request
     * @param $type
     * @Route("/catchup_data/download/{type}", name="catchup_data_download", options={"expose"=true})
     * @Method("GET")
     * @return Response
     */
    public function downloadAction(Request $request, $type='all') {

        $isAjax = $request->isXmlHttpRequest();

        // Get your Datatable ...
        //$datatable = $this->get('app.datatable.post');
        //$datatable->buildDatatable();

        // or use the DatatableFactory
        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(CatchupDataDatatable::class);
        $datatable->buildDatatable(['type'=>$type]);

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $dbQueryBuilder = $responseService->getDatatableQueryBuilder();

            $qb = $dbQueryBuilder->getQb();
            if($type != "all") {
                $qb->where("catchupdata.dataSource = :type");
                $qb->setParameter('type', $type);
            }
            $qb->addOrderBy('campaign.id', 'DESC');
            $qb->addOrderBy('district.province');
            $qb->addOrderBy('district.id');
            return $responseService->getResponse();
        }


        // creating buttons
        $buttons = array(
            'a' => ['route'=>'#', 'title'=>'New Record', 'class'=>'btn-info'],
//            'btn-group' =>['class'=>'btn-default', 'title'=>'Options', 'options' => array(
//                ['route' => 'catchup_data_download',
//                'params' => ['type' => "all"], 'title'=>'All Data'],
//                ['route' => 'catchup_data_download',
//                    'params' => ['type' => "fb"], 'title'=>'Fieldbook only'],
//                ['route' => 'catchup_data_download',
//                    'params' => ['type' => "nfb"], 'title'=>'No Fieldbook'],
//            )]
        );
        return $this->render('pages/table.html.twig',
            ['datatable'=>$datatable,'title'=>'Catchup (Fieldbook) Data', 'buttons' => $buttons]);
    }

    /**
     * Bulk delete action.
     *
     * @param Request $request
     *
     * @Route("/bulk/delete/catchup_data", name="catchup_data_bulk_delete")
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
            $repository = $em->getRepository('AppBundle:CatchupData');

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