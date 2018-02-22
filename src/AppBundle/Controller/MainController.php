<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/26/2017
 * Time: 11:20 AM
 */

namespace AppBundle\Controller;


use AppBundle\Service\Charts;
use AppBundle\Service\HtmlTable;
use AppBundle\Service\Settings;
use AppBundle\Entity\AdminData;
use AppBundle\Service\Triangle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Service\Importer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class MainController
 * @package AppBundle\Controller
 * @Security("has_role('ROLE_USER')")
 */
class MainController extends Controller
{

//    /**
//     * @Route("/", name="home")
//     * @param Request $request
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
////    public function indexAction(Request $request) {
////        return $this->render('pages/index.html.twig');
////    }

    /**
     * @Route("/", name="home")
     * @param Request $request
     * @param Settings $settings
     * @param Charts $charts
     * @return Response
     */
    public function indexAction(Request $request, Settings $settings, Charts $charts) {

        // Category for Charts (most of the charts)
        $category = [['column'=>'Region'], ['column'=>'CID', 'substitute'=>['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']]];


        // ++++++++++++++++++++++++++++++++++++ Last 10 Campaigns (Admin, Catchup) data and charts processing +++++++++
        $campaignIds = $settings->lastFewCampaigns('CoverageData', $settings::NUM_CAMP_CHARTS);
        $tenCampData = $charts->chartData('CoverageData', 'campaignsStatistics', $campaignIds);
        $tenCampCatchupData = $charts->chartData("CatchupData", "campaignsStatistics", $campaignIds);

        // triangulate the 10 campaigns data with catchup data
        $tenCampData = Triangle::triangulateCustom([
                                                    $tenCampData,
                                                    ['data'=>$tenCampCatchupData, 'indexes'=>'all', 'prefix'=>'c']
                                                   ], 'joinkey');

        // 10 campaign vaccinated in campaign and catchup
        $tenCampVacChildChart = $charts->chartData1Category($category[1],
                                                            ['TotalVac'=>'Vaccinated Children',
                                                             'cTotalVac'=>'Vaccinated Catchup'], $tenCampData);
        $tenCampVacChildChart['title'] = 'Vaccinated Children During Last 10 Campaigns and Catchup';

        // 10 campaigns missed after campaign and catchup
        $tenCampMissedCalcData = Triangle::mathOps($tenCampData, ['cTotalRecovered', 'cRegMissed'], '/', 'cPerRec');
        $tenCampMissedCalcData = Triangle::mathOps($tenCampMissedCalcData, ['cPerRec', 'TotalRemaining'], '*', 'CatchupRecovered');
        $tenCampMissedCalcData = Triangle::mathOps($tenCampMissedCalcData, ['TotalRemaining', 'CatchupRecovered'], '-', 'FinalTotalRemaining');
        $tenCampMissedChildChart = $charts->chartData1Category($category[1],
                                                                ['TotalRemaining'=>'Missed After Campaign',
                                                                 'FinalTotalRemaining'=>'Missed After Catchup'], $tenCampMissedCalcData);
        $tenCampMissedChildChart['title'] = 'Missed Children After Last 10 Campaigns and Catchup';

        // 10 campaign missed recovered all reasons
        $tenCampMissedTypeChart = $charts->chartData1Category($category[1],
                                                              ['FinalTotalRemaining'=>'Remaining',
                                                               'CatchupRecovered'=>'Catchup',
                                                               'RecoveredDay4'=>'Day4',
                                                               'Recovered3Days'=>'3Days']
                                                        , $tenCampMissedCalcData);
        $tenCampMissedTypeChart['title'] = 'Missed Children Recovery in Camp/Revisit/Catchup';

        // 10 campaign area chart missed recovered all reasons
        $last10CampRecovered = $charts->chartData1Category($category[1],['FinalTotalRemaining'=>'Remaining after Catchup',
            'CatchupRecovered'=>'Recovered in Catchup',
            'RecoveredDay4'=>'Recovered in Revisit',
            'Recovered3Days'=>'Recovered in Campaign',
        ],
            $tenCampMissedCalcData);
        $last10CampRecovered['title'] = "Recovering missed children during campaign and catchup";

        // 10 campaign stack/percent chart for recovering absent during camp/catchup
        $tenCampMissedCalcData = Triangle::mathOps($tenCampData, ['cVacAbsent', 'cRegAbsent'], '/', 'cPerRecAbsent');
        $tenCampMissedCalcData = Triangle::mathOps($tenCampMissedCalcData, ['cPerRecAbsent', 'RemAbsent'], '*', 'CatchupRecovered');
        $tenCampMissedCalcData = Triangle::mathOps($tenCampMissedCalcData, ['RemAbsent', 'CatchupRecovered'], '-', 'FinalRemAbsent');
        $tenCampAbsentChart = $charts->chartData1Category($category[1],
            ['FinalRemAbsent'=>'Remaining',
                'CatchupRecovered'=>'Catchup',
                'VacAbsentDay4'=>'Day4',
                'VacAbsent3Days'=>'3Days']
            , $tenCampMissedCalcData);
        $tenCampAbsentChart['title'] = 'Absent Children Recovery in Camp/Revisit/Catchup';

        // 10 campaign stack/percent chart for recovering NSS during camp/catchup
        $tenCampMissedCalcData = Triangle::mathOps($tenCampData, ['cVacNSS', 'cRegNSS'], '/', 'cPerRecNSS');
        $tenCampMissedCalcData = Triangle::mathOps($tenCampMissedCalcData, ['cPerRecNSS', 'RemNSS'], '*', 'CatchupRecovered');
        $tenCampMissedCalcData = Triangle::mathOps($tenCampMissedCalcData, ['RemNSS', 'CatchupRecovered'], '-', 'FinalRemNSS');
        $tenCampNSSChart = $charts->chartData1Category($category[1],
            ['FinalRemNSS'=>'Remaining',
                'CatchupRecovered'=>'Catchup',
                'VacNSSDay4'=>'Day4',
                'VacNSS3Days'=>'3Days']
            , $tenCampMissedCalcData);
        $tenCampNSSChart['title'] = 'NSS Children Recovery in Camp/Revisit/Catchup';

        // 10 campaign stack/percent chart for recovering NSS during camp/catchup
        $tenCampMissedCalcData = Triangle::mathOps($tenCampData, ['cVacRefusal', 'cRegRefusal'], '/', 'cPerRecRefusal');
        $tenCampMissedCalcData = Triangle::mathOps($tenCampMissedCalcData, ['cPerRecRefusal', 'RemRefusal'], '*', 'CatchupRecovered');
        $tenCampMissedCalcData = Triangle::mathOps($tenCampMissedCalcData, ['RemRefusal', 'CatchupRecovered'], '-', 'FinalRemRefusal');
        $tenCampRefusalChart = $charts->chartData1Category($category[1],
            ['FinalRemRefusal'=>'Remaining',
                'CatchupRecovered'=>'Catchup',
                'VacRefusalDay4'=>'Day4',
                'VacRefusal3Days'=>'3Days']
            , $tenCampMissedCalcData);
        $tenCampRefusalChart['title'] = 'Refusal Children Recovery in Camp/Revisit/Catchup';

        // +++++++++++++++++++++++++++++++++++++++++++++++++++ End of 10 Campaign +++++++++++++++++++++++++++++++++++

        // =================================================== Data and Charts Processing for Latest Campaign =======
        $lastCamp = $settings->latestCampaign('CoverageData');
        $lastCampData = $charts->chartData('CoverageData', 'campaignStatistics', $lastCamp[0]['id']);
        $lastCampCatchupData = $charts->chartData('CatchupData', 'campaignStatistics', $lastCamp[0]['id']);

        // triangulate the data with catchup data
        $lastCampData = Triangle::triangulateCustom([
            $lastCampData,
            ['data'=>$lastCampCatchupData, 'indexes'=>'all', 'prefix'=>'c']
        ], 'joinkey');
        // last campaign recovered all type by 3days, 4th day and catchup
        $lastCampRecoveredData = Triangle::mathOps($lastCampData, ['cTotalRecovered', 'cRegMissed'], '/', 'cPerRec');
        $lastCampRecoveredData = Triangle::mathOps($lastCampRecoveredData, ['cPerRec', 'TotalRemaining'], '*', 'CatchupRecovered');
        $lastCampRecoveredData = Triangle::mathOps($lastCampRecoveredData, ['TotalRemaining', 'CatchupRecovered'], '-', 'FinalTotalRemaining');
        $lastCampRecovered = $charts->pieData(['Recovered3Days'=>'3Days',
                                               'RecoveredDay4'=>'Day4',
                                                'CatchupRecovered'=>'Catchup',
                                                'FinalTotalRemaining'=>'Remaining'],
            $lastCampRecoveredData);
        $lastCampRecovered['title'] = "Missed Children Recovery Camp/Revisit/Catchup";

        // last campaign Absent recovered by 3days and 4th day and catchup
        $lastCampRecoveredData = Triangle::mathOps($lastCampData, ['cVacAbsent', 'cRegAbsent'], '/', 'cPerRecAbsent');
        $lastCampRecoveredData = Triangle::mathOps($lastCampRecoveredData, ['cPerRecAbsent', 'RemAbsent'], '*', 'CatchupRecovered');
        $lastCampRecoveredData = Triangle::mathOps($lastCampRecoveredData, ['RemAbsent', 'CatchupRecovered'], '-', 'FinalRemAbsent');
        $lastCampAbsentRecovered = $charts->pieData(['VacAbsent3Days'=>'3Days',
                                                     'VacAbsentDay4'=>'Day4',
                                                     'CatchupRecovered'=>'Catchup',
                                                     'FinalRemAbsent'=>'Remaining'],
            $lastCampRecoveredData);
        $lastCampAbsentRecovered['title'] = "Absent Children Recovery Camp/Revisit/Catchup";

        // last campaign NSS recovered by 3days and 4th day and catchup
        $lastCampRecoveredData = Triangle::mathOps($lastCampData, ['cVacNSS', 'cRegNSS'], '/', 'cPerRecNSS');
        $lastCampRecoveredData = Triangle::mathOps($lastCampRecoveredData, ['cPerRecNSS', 'RemNSS'], '*', 'CatchupRecovered');
        $lastCampRecoveredData = Triangle::mathOps($lastCampRecoveredData, ['RemNSS', 'CatchupRecovered'], '-', 'FinalRemNSS');
        $lastCampNSSRecovered = $charts->pieData(['VacNSS3Days'=>'3Days', 'VacNSSDay4'=>'Day4', 'CatchupRecovered'=>'Catchup', 'FinalRemNSS'=>'Remaining'],
            $lastCampRecoveredData);
        $lastCampNSSRecovered['title'] = "NSS Children Recovery Camp/Revisit/Catchup";

        // last campaign Refusal recovered by 3days and 4th day and catchup
        $lastCampRecoveredData = Triangle::mathOps($lastCampData, ['cVacRefusal', 'cRegRefusal'], '/', 'cPerRecRefusal');
        $lastCampRecoveredData = Triangle::mathOps($lastCampRecoveredData, ['cPerRecRefusal', 'RemRefusal'], '*', 'CatchupRecovered');
        $lastCampRecoveredData = Triangle::mathOps($lastCampRecoveredData, ['RemRefusal', 'CatchupRecovered'], '-', 'FinalRemRefusal');
        $lastCampRefusalRecovered = $charts->pieData(['VacRefusal3Days'=>'3Days', 'VacRefusalDay4'=>'Day4', 'CatchupRecovered'=>'Catchup', 'FinalRemRefusal'=>'Remaining'],
            $lastCampRecoveredData);
        $lastCampRefusalRecovered['title'] = "Refusal Children Recovery Camp/Revisit/Catchup";

        // =============================================== End of last campaign data ==================================

        return $this->render("pages/index.html.twig",
            [
                'chartVacChild10Camp' => json_encode($tenCampVacChildChart),
                'chartMissed10Camp' => json_encode($tenCampMissedChildChart),
                'chartMissedType10camp' => json_encode($tenCampMissedTypeChart),
                'recoveredAll' => json_encode($lastCampRecovered),
                'recoveredAbsent' => json_encode($lastCampAbsentRecovered),
                'recoveredNSS' => json_encode($lastCampNSSRecovered),
                'recoveredRefusal' => json_encode($lastCampRefusalRecovered),
                'last10CampRecovered' => json_encode($last10CampRecovered),
                'tenCampAbsent' => json_encode($tenCampAbsentChart),
                'tenCampNSS' => json_encode($tenCampNSSChart),
                'tenCampRefusal' => json_encode($tenCampRefusalChart),
                'campaign' => $lastCampData[0]['CName']
            ]);

    }

    /**
     * @param Request $request
     * @param null $district
     * @return Response
     * @Route("/main/clusters/{district}", name="cluster_main", options={"expose"=true})
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
                    $adminData = $em->getRepository('AppBundle:CoverageData')
                        ->clusterAggBySubDistrictCluster($campaignIds, $district, $clusters, $item['subDistrict']);
                    $catchupData = $em->getRepository('AppBundle:CatchupData')
                        ->clusterAggBySubDistrictCluster($campaignIds, $district, $clusters, $item['subDistrict']);
                    $heatMapData[] = Triangle::triangulateCustom([
                        $adminData,
                        ['data'=>$catchupData,
                            'indexes'=>['RegMissed', 'TotalRecovered', 'TotalVac',
                                'RegAbsent', 'VacAbsent',
                                'RegNSS', 'VacNSS', 'RegRefusal', 'VacRefusal'],
                            'prefix'=>'c']
                    ], 'joinkey');
                }

                // merge the data of all sub districts
                $heatMapData = array_merge(...$heatMapData);
            }

            // if there's no sub district
            if(count($subDistrict) <= 0 || $subDistrict === null){
                $adminData = $em->getRepository('AppBundle:CoverageData')
                    ->clusterAggBySubDistrictCluster($campaignIds, $district, $clusters);
                $catchupData = $em->getRepository('AppBundle:CatchupData')
                    ->clusterAggBySubDistrictCluster($campaignIds, $district, $clusters);

                $heatMapData = Triangle::triangulateCustom([
                    $adminData,
                    ['data'=>$catchupData,
                        'indexes'=>['RegMissed', 'TotalRecovered', 'TotalVac',
                            'RegAbsent', 'VacAbsent',
                            'RegNSS', 'VacNSS', 'RegRefusal', 'VacRefusal'],
                        'prefix'=>'c']
                ], 'joinkey');

            }


            // covert the database data into heatmap array for a give indicator
            // if substitute was shortName, the function will make a short name for the campaign
            $heatMapDataCalc = Triangle::mathOps($heatMapData, ['cTotalRecovered', 'cRegMissed'], '/', 'cPerRec');
            $heatMapDataCalc = Triangle::mathOps($heatMapDataCalc, ['cPerRec', 'TotalRemaining'], '*', 'CatchupRecovered');
            $heatMapDataCalc = Triangle::mathOps($heatMapDataCalc, ['TotalRemaining', 'CatchupRecovered'], '-', 'FinalTotalRemaining');
            $heatMapDataTotalRemaining = $charts->clusterDataForHeatMap($heatMapDataCalc, 'FinalTotalRemaining',
                ['column'=>'CID', 'substitute' => 'shortName'],
                $clustersWithSubDistrict);
            $heatMapDataTotalRemaining['title'] = 'Tends of total remaining children after catchup';
            $heatMapDataTotalRemaining['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne('TriangleData', 'TotalRemaining');
            $data['heatMapTotalRemaining'] = json_encode($heatMapDataTotalRemaining);

            // covert the database data into heatmap array for a give indicator
            $heatMapDataCalc = Triangle::mathOps($heatMapData, ['cVacAbsent', 'cRegAbsent'], '/', 'cPerRec');
            $heatMapDataCalc = Triangle::mathOps($heatMapDataCalc, ['cPerRec', 'RemAbsent'], '*', 'CatchupRecovered');
            $heatMapDataCalc = Triangle::mathOps($heatMapDataCalc, ['RemAbsent', 'CatchupRecovered'], '-', 'FinalTotalAbsent');
            $heatMapDataTotalAbsent = $charts->clusterDataForHeatMap($heatMapDataCalc, 'FinalTotalAbsent',
                ['column'=>'CID', 'substitute' => 'shortName'],
                $clustersWithSubDistrict);
            $heatMapDataTotalAbsent['title'] = 'Tends of total absent children after catchup';
            $heatMapDataTotalAbsent['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne('TriangleData', 'RemAbsent');
            $data['heatMapTotalAbsent'] = json_encode($heatMapDataTotalAbsent);

            // covert the database data into heatmap array for a give indicator
            $heatMapDataCalc = Triangle::mathOps($heatMapData, ['cVacNSS', 'cRegNSS'], '/', 'cPerRec');
            $heatMapDataCalc = Triangle::mathOps($heatMapDataCalc, ['cPerRec', 'RemNSS'], '*', 'CatchupRecovered');
            $heatMapDataCalc = Triangle::mathOps($heatMapDataCalc, ['RemNSS', 'CatchupRecovered'], '-', 'FinalTotalNSS');
            $heatMapTotalNSS = $charts->clusterDataForHeatMap($heatMapDataCalc, 'FinalTotalNSS',
                ['column'=>'CID', 'substitute' => 'shortName'],
                $clustersWithSubDistrict);
            $heatMapTotalNSS['title'] = 'Tends of total NSS children after catchup';
            $heatMapTotalNSS['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne('TriangleData', 'RemNSS');
            $data['heatMapTotalNSS'] = json_encode($heatMapTotalNSS);

            // covert the database data into heatmap array for a give indicator
            $heatMapDataCalc = Triangle::mathOps($heatMapData, ['cVacRefusal', 'cRegRefusal'], '/', 'cPerRec');
            $heatMapDataCalc = Triangle::mathOps($heatMapDataCalc, ['cPerRec', 'RemRefusal'], '*', 'CatchupRecovered');
            $heatMapDataCalc = Triangle::mathOps($heatMapDataCalc, ['RemRefusal', 'CatchupRecovered'], '-', 'FinalTotalRefusal');
            $heatMapDataTotalRefusal = $charts->clusterDataForHeatMap($heatMapDataCalc, 'FinalTotalRefusal',
                ['column'=>'CID', 'substitute' => 'shortName'],
                $clustersWithSubDistrict);
            $heatMapDataTotalRefusal['title'] = 'Tends of total refusal children after catchup';
            $heatMapDataTotalRefusal['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne('TriangleData', 'RemRefusal');
            $data['heatMapTotalRefusal'] = json_encode($heatMapDataTotalRefusal);

            // Data of the latest one campaign and preparing the chart data
            $lastCampaign = $settings->latestCampaign("CoverageData");
            $lastCampaignId = $lastCampaign[0]['id'];

            $lastCampClustersData = array();
            if(count($subDistrict) > 0) {
                foreach($subDistrict as $item) {
                    // find the clusters data
                    $lastAdm = $em->getRepository('AppBundle:CoverageData')
                        ->clusterAggBySubDistrictCluster([$lastCampaignId], $district, $clusters, $item['subDistrict']);
                    $lastCtp = $em->getRepository('AppBundle:CatchupData')
                        ->clusterAggBySubDistrictCluster([$lastCampaignId], $district, $clusters, $item['subDistrict']);
                    $lastCampClustersData[] = Triangle::triangulateCustom([
                                                $lastAdm,
                                                ['data'=>$lastCtp,
                                                    'indexes'=>['RegMissed', 'TotalRecovered', 'TotalVac',
                                                        'RegAbsent', 'VacAbsent',
                                                        'RegNSS', 'VacNSS', 'RegRefusal', 'VacRefusal'],
                                                    'prefix'=>'c']
                                                 ], 'joinkey');
                }

                // merge the data of all sub districts
                $lastCampClustersData = array_merge(...$lastCampClustersData);
            }

            // if there's no sub district
            if(count($subDistrict) <= 0 || $subDistrict === null){

                $lastAdm = $em->getRepository('AppBundle:CoverageData')
                    ->clusterAggBySubDistrictCluster([$lastCampaignId], $district, $clusters);
                $lastCtp = $em->getRepository('AppBundle:CatchupData')
                    ->clusterAggBySubDistrictCluster([$lastCampaignId], $district, $clusters);
                $lastCampClustersData = Triangle::triangulateCustom([
                    $lastAdm,
                    ['data'=>$lastCtp,
                        'indexes'=>['RegMissed', 'TotalRecovered', 'TotalVac',
                            'RegAbsent', 'VacAbsent',
                            'RegNSS', 'VacNSS', 'RegRefusal', 'VacRefusal'],
                        'prefix'=>'c']
                    ], 'joinkey');
            }

            $lastCampCltrCalc = Triangle::mathOps($lastCampClustersData, ['cTotalRecovered', 'cRegMissed'], '/', 'cPerRec');
            $lastCampCltrCalc = Triangle::mathOps($lastCampCltrCalc, ['cPerRec', 'TotalRemaining'], '*', 'CatchupRecovered');
            $lastCampCltrCalc = Triangle::mathOps($lastCampCltrCalc, ['TotalRemaining', 'CatchupRecovered'], '-', 'FinalTotalRemaining');
            $lastCampBarChart = $charts->chartData1Category(['column'=>'Cluster'],[
                                                        'FinalTotalRemaining'=>'Remaining',
                                                        'CatchupRecovered'=>'Catchup',
                                                        'RecoveredDay4'=>'Day4',
                                                        'Recovered3Days'=>'3Days'
                                                    ],
                                                    $lastCampCltrCalc, false);
            $lastCampBarChart['title'] = $lastCampaign[0]['campaignName']." Missed Children Recovery Camp/Revisit/Catchup";

            $data['lastCampBarChart'] = json_encode($lastCampBarChart);

        }

        return $this->render("pages/clusters.html.twig",
            $data
        );

    }


    /**
     * @Route("/test", name="testing")
     * @param $var
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction(Request $request, Charts $charts, Settings $settings, Triangle $triangle) {
//        $category = [['column'=>'Region'], ['column'=>'CID', 'substitute'=>['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']]];
        $campaignIds = $settings->lastFewCampaigns('CatchupData');
        $source = $charts->chartData('CoverageData', 'regionAgg', $campaignIds, 601);
        $second = $charts->chartData('CatchupData', 'regionAgg', $campaignIds, 601);

//        $tenCampData=$triangle->triangulateCustom([$source,
//            ['data'=>$second, 'indexes'=>['RegMissed', 'TotalRecovered']],
//            ['data'=>$second, 'indexes'=>['RegMissed', 'TotalRecovered'], 'prefix'=>'C']
//            ], 'joinkey');
        $tenCampData=Triangle::triangulateCustom([$source,
            ['data'=>$second, 'indexes'=>['TotalRecovered']]
        ], 'joinkey');

        $tenCampData = Triangle::mathOps($tenCampData, ['TotalRemaining', 'TotalRecovered'],'-', 'RemAfterCatchup');
//
//        $otherFunction = $charts->chartData('CoverageData', 'campaignsStatistics', [$campaignIds[0]['id']]);
//
//        $data['single'] = $tenCampData;
//        $data['plural'] = $otherFunction;
//        //$data = $charts->pieData(['RemAbsent'=>'Absent', 'RemNSS'=>'NSS', 'RemRefusal'=>'Refusal'], $tenCampData);
//        //$data['title'] = 'Missed Children During Last 10 Campaigns';
////        $campaignIds = $settings->lastFewCampaigns('CoverageData', 10);
////        $data = $charts->chartData('CoverageData', 'clusterAgg', $campaignIds, 1510);
////        $data = $charts->clusterDataForHeatMap($data, 'RemAbsent', ['column'=>'CID', 'substitute' => 'CName']);
////        $data['title'] = 'Missed Children During Last 10 Campaigns';
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('AppBundle:Campaign')->selectCampaignBySource("CoverageData");
////            ->selectDistrictByProvince(6);
        return new Response(json_encode($tenCampData));

//        return $this->render("pages/test.html.twig",
//            ['testData' => json_encode([])]);

    }


}