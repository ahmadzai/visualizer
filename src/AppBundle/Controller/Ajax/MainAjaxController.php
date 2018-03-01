<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 1/24/2018
 * Time: 12:07 PM
 */

namespace AppBundle\Controller\Ajax;


use AppBundle\Service\Charts;
use AppBundle\Service\Triangle;
use AppBundle\Service\Settings;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class MainAjaxController extends Controller
{
    /**
     * @param Request $request
     * @param Settings $settings
     * @param Charts $charts
     * @Route("api/camp-statistics/main", name="ajax_camp_statistics_main")
     * @Method("POST")
     * @return Response
     */
    public function ajaxCampStatisticsAction(Request $request, Settings $settings, Charts $charts) {

        $campType = $request->get('campType');
        $entity = "CoverageData";
        $campaigns = $request->get('campaigns');
        $districts = $request->get('districts');
        $provinces = $request->get("provinces");
        $regions = $request->get("regions");
        // this chart type is keeping track from which chart
        // the filter is triggered
        $chartType = $request->get('chartType');

        $setting['type'] = $campType;

        $subTitle = null;

        if($districts !== null && count($districts)>0) {

            $subTitle = 'for selected districts';
            $setting['entity'] = 'district';
            $setting['filter'] = $districts;

        } else if($provinces !== null && count($provinces) > 0) {
            $subTitle = 'for selected provinces';
            $setting['entity'] = 'province';
            $setting['filter'] = $provinces;
        } else if($regions !== null && count($regions) > 0) {
            $subTitle = 'for selected regions';
            $setting['entity'] = 'region';
            $setting['filter'] = $regions;
        }


        $function = "campaignsStatisticsByTypeLimit";
        if($campType==="Reset")
            $function = "campaignsStatistics";
        if(!isset($entity))
            $entity = "CoverageData";

        $campData = null;
        $catchupCampData = null;
        $campaignIds = $campaigns;

        if($campType === 'Reset') {
            if(count($campaignIds) < 2)
                $campaignIds = $settings->lastFewCampaigns($entity, $settings::NUM_CAMP_CHARTS);
            $campData = $charts->chartData($entity, $function, $campaignIds, $setting);
            $catchupCampData = $charts->chartData("CatchupData", $function, $campaignIds, $setting);
        } else {
            if(count($campaignIds) < 2) {
                $campData = $charts->chartData($entity, $function, $settings::NUM_CAMP_LIMIT, $setting);
                $catchupCampData = $charts->chartData("CatchupData", $function, $settings::NUM_CAMP_LIMIT, $setting);
            }
            else {
                $function = "campaignsStatistics";
                $campData = $charts->chartData($entity, $function, $campaignIds, $setting);
                $catchupCampData = $charts->chartData("CatchupData", $function, $campaignIds, $setting);
            }
        }

        $tenCampData = Triangle::triangulateCustom([
            $campData,
            ['data'=>$catchupCampData,
                'indexes'=>['RegMissed', 'TotalRecovered', 'TotalVac',
                    'RegAbsent', 'VacAbsent',
                    'RegNSS', 'VacNSS', 'RegRefusal', 'VacRefusal'],
                'prefix'=>'c']
        ], 'joinkey');

        $category = [['column'=>'Region'], ['column'=>'CID', 'substitute'=>['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']]];
        $indicators = ['TotalVac'=>'Vaccinated Children'];
        $title = "Vaccinated children trends in ";
        /*
        ten_camp_vac
        ten_camp_missed
        ten_camp_missed_type
        ten_camp_absent
        ten_camp_nss
        ten_camp_refusal
        ten_camp_missed_vac
        */
        $finalData = null;
        if($chartType === 'ten_camp_vac' || $chartType === 'ten_camp_missed' ||
            $chartType === 'ten_camp_missed_type' || $chartType === 'ten_camp_missed_vac') {
            // 10 campaign vaccinated in campaign and catchup
            if($chartType === 'ten_camp_vac') {
                $finalData = $charts->chartData1Category($category[1],
                    ['TotalVac' => 'Vaccinated Campaign',
                        'cTotalVac' => 'Vaccinated Catchup'], $tenCampData);
                $finalData['title'] = 'Vaccinated Children During Campaigns and Catchup';
                $finalData['subTitle'] = $subTitle;
            } else {

                // 10 campaigns missed after campaign and catchup
                $calData = Triangle::mathOps($tenCampData, ['cTotalRecovered', 'cRegMissed'], '/', 'cPerRec');
                $calData = Triangle::mathOps($calData, ['cPerRec', 'TotalRemaining'], '*', 'CatchupRecovered');
                $calData = Triangle::mathOps($calData, ['TotalRemaining', 'CatchupRecovered'], '-', 'FinalTotalRemaining');

                if($chartType === 'ten_camp_missed') {
                    $finalData = $charts->chartData1Category($category[1],
                        ['TotalRemaining' => 'Missed After Campaign',
                            'FinalTotalRemaining' => 'Missed After Catchup'], $calData);
                    $finalData['title'] = 'Missed Children After Campaigns and Catchup';
                    $finalData['subTitle'] = $subTitle;
                } else if($chartType === 'ten_camp_missed_type') {

                    // 10 campaign missed recovered all reasons
                    $finalData = $charts->chartData1Category($category[1],
                        ['FinalTotalRemaining' => 'Remaining',
                            'CatchupRecovered' => 'Catchup',
                            'RecoveredDay4' => 'Day4',
                            'Recovered3Days' => '3Days']
                        , $calData);
                    $finalData['title'] = 'Missed Children Recovery in Camp/Revisit/Catchup';
                    $finalData['subTitle'] = $subTitle;
                } else if($chartType === 'ten_camp_missed_vac') {

                    // 10 campaign area chart missed recovered all reasons
                    $finalData = $charts->chartData1Category($category[1], ['FinalTotalRemaining' => 'Remaining after Catchup',
                        'CatchupRecovered' => 'Recovered in Catchup',
                        'RecoveredDay4' => 'Recovered in Revisit',
                        'Recovered3Days' => 'Recovered in Campaign',
                    ],
                        $calData);
                    $finalData['title'] = "Recovering Missed Children During Campaigns and Catchup";
                    $finalData['subTitle'] = $subTitle;
                }
            }

        } else if($chartType === "ten_camp_absent") {
            $calcData = Triangle::mathOps($tenCampData, ['cVacAbsent', 'cRegAbsent'], '/', 'cPerRecAbsent');
            $calcData = Triangle::mathOps($calcData, ['cPerRecAbsent', 'RemAbsent'], '*', 'CatchupRecovered');
            $calcData = Triangle::mathOps($calcData, ['RemAbsent', 'CatchupRecovered'], '-', 'FinalRemAbsent');
            $finalData = $charts->chartData1Category($category[1],
                ['FinalRemAbsent'=>'Remaining',
                    'CatchupRecovered'=>'Catchup',
                    'VacAbsentDay4'=>'Day4',
                    'VacAbsent3Days'=>'3Days']
                , $calcData);
            $finalData['title'] = 'Absent Children Recovery in Camp/Revisit/Catchup';
            $finalData['subTitle'] = $subTitle;

        } else if($chartType === "ten_camp_nss") {
            // 10 campaign stack/percent chart for recovering NSS during camp/catchup
            $calcData = Triangle::mathOps($tenCampData, ['cVacNSS', 'cRegNSS'], '/', 'cPerRecNSS');
            $calcData = Triangle::mathOps($calcData, ['cPerRecNSS', 'RemNSS'], '*', 'CatchupRecovered');
            $calcData = Triangle::mathOps($calcData, ['RemNSS', 'CatchupRecovered'], '-', 'FinalRemNSS');
            $finalData = $charts->chartData1Category($category[1],
                ['FinalRemNSS'=>'Remaining',
                    'CatchupRecovered'=>'Catchup',
                    'VacNSSDay4'=>'Day4',
                    'VacNSS3Days'=>'3Days']
                , $calcData);
            $finalData['title'] = 'NSS Children Recovery in Camp/Revisit/Catchup';
            $finalData['subTitle'] = $subTitle;

        } else if($chartType === "ten_camp_refusal") {

            // 10 campaign stack/percent chart for recovering NSS during camp/catchup
            $calcData = Triangle::mathOps($tenCampData, ['cVacRefusal', 'cRegRefusal'], '/', 'cPerRecRefusal');
            $calcData = Triangle::mathOps($calcData, ['cPerRecRefusal', 'RemRefusal'], '*', 'CatchupRecovered');
            $calcData = Triangle::mathOps($calcData, ['RemRefusal', 'CatchupRecovered'], '-', 'FinalRemRefusal');
            $finalData = $charts->chartData1Category($category[1],
                ['FinalRemRefusal'=>'Remaining',
                    'CatchupRecovered'=>'Catchup',
                    'VacRefusalDay4'=>'Day4',
                    'VacRefusal3Days'=>'3Days']
                , $calcData);
            $finalData['title'] = 'Refusal Children Recovery in Camp/Revisit/Catchup';
            $finalData['subTitle'] = $subTitle;

        }

        $chartData = $charts->chartData1Category($category[1], $indicators, $campData);
        $chartData['title'] = $title.($campType=="Reset"?"last 10":$campType).' Campaigns';
        $chartData['subTitle'] = $subTitle;

        return new Response(json_encode([
            $chartType => $finalData,
        ]));
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/api/clusters/main", name="ajax_cluster_main", options={"expose"=true})
     * @Method("POST")
     */
    public  function ajaxClusterLevelAction(Request $request, Charts $charts, Settings $settings) {

        $selectedCampaignIds = $request->get('campaign');
        $clusters = $request->get('cluster');
        $provinces = $request->get('province');
        $districts = $request->get('district');

        $calcType = $request->get('calcType');
        $calcTypeArray = ['type'=>'number'];
        $dbIndicatorPostfix = "";
        if($calcType === 'percent') {
            $calcTypeArray = ['type'=>'percent', 'column'=>'CalcTarget'];
            $dbIndicatorPostfix = "Per";
        }

        $clusterArray = array();
        $subDistrictArray = array();
        if(count($clusters) > 0 ) {
            foreach($clusters as $cluster) {
                if(stristr($cluster, "|") !== false) {
                    $subDistrictCluster = explode("|", $cluster);
                    $subDistrictArray[] = $subDistrictCluster[0];
                    $clusterArray[] = $subDistrictCluster[1];
                } else {
                    $clusterArray[] = $cluster;
                }
            }
        }

        if(count($subDistrictArray) > 0)
            $subDistrictArray = array_unique($subDistrictArray);

        $data = array();
        $campaignIds = $selectedCampaignIds;
        if(count($districts) > 0) {
            // get last 6 campaigns if the selected campaigns are <2
            if(count($campaignIds) <= 1)
                $campaignIds = $settings->lastFewCampaigns('CoverageData', $settings::NUM_CAMP_CLUSTERS);

            $em = $this->getDoctrine()->getManager();

            // generating data for the heatmap
            $heatMapData = array();
            // in case there was any sub district of a district
            if(count($subDistrictArray) > 0) {
                foreach($subDistrictArray as $item) {
                    // find the clusters data
                    $heatMapData[] = $em->getRepository('AppBundle:CoverageData')
                        ->clusterAggBySubDistrictCluster($campaignIds, $districts, $clusterArray, $item);

                    $adminData = $em->getRepository('AppBundle:CoverageData')
                        ->clusterAggBySubDistrictCluster($campaignIds, $districts, $clusterArray, $item);
                    $catchupData = $em->getRepository('AppBundle:CatchupData')
                        ->clusterAggBySubDistrictCluster($campaignIds, $districts, $clusterArray, $item);
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
            if(count($subDistrictArray) <= 0 || $subDistrictArray === null){
                $adminData = $em->getRepository('AppBundle:CoverageData')
                    ->clusterAggBySubDistrictCluster($campaignIds, $districts, $clusterArray);
                $catchupData = $em->getRepository('AppBundle:CatchupData')
                    ->clusterAggBySubDistrictCluster($campaignIds, $districts, $clusterArray);

                $heatMapData = Triangle::triangulateCustom([
                    $adminData,
                    ['data'=>$catchupData,
                        'indexes'=>['RegMissed', 'TotalRecovered', 'TotalVac',
                            'RegAbsent', 'VacAbsent',
                            'RegNSS', 'VacNSS', 'RegRefusal', 'VacRefusal'],
                        'prefix'=>'c']
                ], 'joinkey');
            }

            //return new Response(json_encode($heatMapData));

            // covert the database data into heatmap array for a give indicator
            $heatMapDataCalc = Triangle::mathOps($heatMapData, ['TotalRemaining', 'cTotalRecovered'], '-', 'FinalTotalRemaining');
            $heatMapDataTotalRemaining = $charts->clusterDataForHeatMap($heatMapDataCalc, 'FinalTotalRemaining',
                ['column'=>'CID', 'substitute' => 'shortName'], $clusters, $calcTypeArray);
            $heatMapDataTotalRemaining['title'] = 'Trends of total remaining children after catchup';
            $heatMapDataTotalRemaining['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne('TriangleData', 'TotalRemaining'.$dbIndicatorPostfix);
            $data['heatMapTotalRemaining'] = $heatMapDataTotalRemaining;

            // covert the database data into heatmap array for a give indicator
            $heatMapDataCalc = Triangle::mathOps($heatMapData, ['RemAbsent', 'cVacAbsent'], '-', 'FinalTotalAbsent');
            $heatMapDataTotalAbsent = $charts->clusterDataForHeatMap($heatMapDataCalc, 'FinalTotalAbsent',
                ['column'=>'CID', 'substitute' => 'shortName'], $clusters, $calcTypeArray);
            $heatMapDataTotalAbsent['title'] = 'Tends of total absent children after catchup';
            $heatMapDataTotalAbsent['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne('TriangleData', 'RemAbsent'.$dbIndicatorPostfix);
            $data['heatMapTotalAbsent'] = $heatMapDataTotalAbsent;

            // covert the database data into heatmap array for a give indicator
            $heatMapDataCalc = Triangle::mathOps($heatMapData, ['RemNSS', 'cVacNSS'], '-', 'FinalTotalNSS');
            $heatMapTotalNSS = $charts->clusterDataForHeatMap($heatMapDataCalc, 'FinalTotalNSS',
                ['column'=>'CID', 'substitute' => 'shortName'], $clusters, $calcTypeArray);
            $heatMapTotalNSS['title'] = 'Tends of total NSS children after catchup';
            $heatMapTotalNSS['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne('TriangleData', 'RemNSS'.$dbIndicatorPostfix);
            $data['heatMapTotalNSS'] = $heatMapTotalNSS;

            // covert the database data into heatmap array for a give indicator
            $heatMapDataCalc = Triangle::mathOps($heatMapData, ['RemRefusal', 'cVacRefusal'], '-', 'FinalTotalRefusal');
            $heatMapDataTotalRefusal = $charts->clusterDataForHeatMap($heatMapDataCalc, 'FinalTotalRefusal',
                ['column'=>'CID', 'substitute' => 'shortName'], $clusters, $calcTypeArray);
            $heatMapDataTotalRefusal['title'] = 'Tends of total refusal children after catchup';
            $heatMapDataTotalRefusal['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne('TriangleData', 'RemRefusal'.$dbIndicatorPostfix);
            $data['heatMapTotalRefusal'] = $heatMapDataTotalRefusal;

            //------------------------------- Last Campaign Bar Chart Filter ---------------------------------
            // check if the ajax request are coming from the calculation type method

            if($calcType !== 'percent') {
                $lastCampaignId = $selectedCampaignIds;
                $lastCampaign = $settings->latestCampaign("CoverageData");
                if (count($lastCampaignId) > 1) {

                    $lastCampaignId = $lastCampaign[0]['id'];
                }

                $lastCampClustersData = array();
                if (count($subDistrictArray) > 0) {
                    foreach ($subDistrictArray as $item) {
                        // find the clusters data
                        $lastAdm = $em->getRepository('AppBundle:CoverageData')
                            ->clusterAggBySubDistrictCluster([$lastCampaignId], $districts, $clusterArray, $item);
                        $lastCtp = $em->getRepository('AppBundle:CatchupData')
                            ->clusterAggBySubDistrictCluster([$lastCampaignId], $districts, $clusterArray, $item);
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
                if (count($subDistrictArray) <= 0 || $subDistrictArray === null) {
                    $lastAdm = $em->getRepository('AppBundle:CoverageData')
                        ->clusterAggBySubDistrictCluster([$lastCampaignId], $districts, $clusterArray);
                    $lastCtp = $em->getRepository('AppBundle:CatchupData')
                        ->clusterAggBySubDistrictCluster([$lastCampaignId], $districts, $clusterArray);
                    $lastCampClustersData = Triangle::triangulateCustom([
                        $lastAdm,
                        ['data'=>$lastCtp,
                            'indexes'=>['RegMissed', 'TotalRecovered', 'TotalVac',
                                'RegAbsent', 'VacAbsent',
                                'RegNSS', 'VacNSS', 'RegRefusal', 'VacRefusal'],
                            'prefix'=>'c']
                    ], 'joinkey');
                }

//                $lastCampCltrCalc = Triangle::mathOps($lastCampClustersData, ['cTotalRecovered', 'cRegMissed'], '/', 'cPerRec');
//                $lastCampCltrCalc = Triangle::mathOps($lastCampCltrCalc, ['cPerRec', 'TotalRemaining'], '*', 'CatchupRecovered');
//                $lastCampCltrCalc = Triangle::mathOps($lastCampCltrCalc, ['TotalRemaining', 'CatchupRecovered'], '-', 'FinalTotalRemaining');

                $lastCampCltrCalc = Triangle::mathOps($lastCampClustersData, ['TotalRemaining', 'cRegMissed'], '-', 'cDisc');
                $lastCampCltrCalc = Triangle::mathOps($lastCampCltrCalc, ['TotalRemaining', 'cTotalRecovered'], '-', 'RemTotal');
                $lastCampCltrCalc = Triangle::mathOps($lastCampCltrCalc, ['RemTotal', 'cDisc'], '-', 'FinalTotalRemaining');
                $lastCampBarChart = $charts->chartData1Category(['column'=>'Cluster'],
                    [
                        'cDisc' => 'Discrep',
                        'FinalTotalRemaining'=>'Remaining',
                        'cTotalRecovered'=>'Catchup',
                        'RecoveredDay4'=>'Day5',
                        'Recovered3Days'=>'3Days'
                    ],
                    $lastCampCltrCalc, false);

                $campaign = "No data for this campaign as per current filter";
                if(count($lastCampaign) > 0)
                    $campaign = $lastCampaign[0]['campaignName']." Missed Children Recovery Camp/Revisit/Catchup";
                $lastCampBarChart['title'] = $campaign;
                $data['lastCampBarChart'] = $lastCampBarChart;
            }
            // ------------------------------------------------------------------------------------------------------
        }

        return new Response(json_encode($data));
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/api/filter/main", name="ajax_filter_main", options={"expose"=true})
     * @Method("POST")
     */
    function filterMainDashboardAction(Request $request, Settings $settings, Charts $charts) {

        // ========================== Handling Ajax Request =================================
        $selectedCampaignIds = $request->get('campaign');
        $regions = $request->get('region');
        $provinces = $request->get('province');
        $districts = $request->get('district');

        $type = 'Region';
        $subTitle = "for selected campaigns";
        $during = "Last 10 Campaigns";

        //$districts = ["Non-V/HR districts"];
        //=========================== End of Ajax Request ===================================

        // Default Categories for the Charts (the value below should be column names in the result set
        $category = [['column'=>'Region'], ['column'=>'CID', 'substitute'=>['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']]];
        // Default function name, it will change based on the selected filter
        $functionRegion = 'regionAgg';
        $functionCampaignsStatistics= "campaignsStatistics";
        $secondParam = array();

        if($districts !== null && count($districts)>0) {
            $type = "District";
            $functionRegion = 'districtAggByCampaignDistrict';
            $functionCampaignsStatistics = $functionCampaignsStatistics."ByDistrict";
            $category[0] = ['column' => 'DCODE', 'substitute' => 'District'];

            $subTitle = "for selected districts";

            $secondParam = $districts;
            if(array_search("VHR", $districts) > -1 || array_search('HR', $districts) > -1 ||
                array_search('Non-V/HR districts', $districts) > -1) {
                $functionRegion = $functionRegion.'Risk';
                $functionCampaignsStatistics = $functionCampaignsStatistics."Risk";
                $nonVhrIndex = array_search('Non-V/HR districts', $districts);
                $subTitle = "for selected province's HR/VHR districts";
                $newParam['risk'] = $districts;
                $newParam['province'] = $provinces;
                if($nonVhrIndex>-1) {
                    $functionRegion = $functionRegion.'Null';
                    $functionCampaignsStatistics = $functionCampaignsStatistics."Null";
                    $subTitle = "for selected province's Non HR/VHR districts";
                }

                $secondParam = $newParam;
            }
        } else if($provinces !== null && count($provinces) > 0) {
            $type = "Province";
            $functionRegion = "provinceAggByCampaignProvince";
            $functionCampaignsStatistics = $functionCampaignsStatistics."ByProvince";
            $category[0] = ['column' => 'PCODE', 'substitute' => 'Province'];
            $secondParam = $provinces;
            $subTitle = "for selected provinces";
        } else if($regions !== null && count($regions) > 0) {
            $functionRegion = $functionRegion."ByCampaignRegion";
            $functionCampaignsStatistics = $functionCampaignsStatistics."ByRegion";
            $secondParam = $regions;
            $subTitle = "for selected regions";
        } else if(count($selectedCampaignIds) === 1) {
            $subTitle = null;
        }

        $campaignIds = $settings->lastFewCampaigns('CoverageData', $settings::NUM_CAMP_CHARTS);

        if(count($selectedCampaignIds) > 1) {
            // this function returns latest campaign, can work for all data sources that have relation with campaign
            $lastCamp = $settings->latestCampaign('CoverageData');
            $campaignIds = $selectedCampaignIds;
            $during = "Selected Campaigns";
            $selectedCampaignIds = [$lastCamp[0]['id']];
        }

        $tenCampData = $charts->chartData('CoverageData', $functionCampaignsStatistics, $campaignIds, $secondParam);
        $tenCampCatchupData = $charts->chartData("CatchupData", $functionCampaignsStatistics, $campaignIds, $secondParam);

        // triangulate the 10 campaigns data with catchup data
        $tenCampData = Triangle::triangulateCustom([
            $tenCampData,
            ['data'=>$tenCampCatchupData,
             'indexes'=>['RegMissed', 'TotalRecovered', 'TotalVac',
                         'RegAbsent', 'VacAbsent',
                         'RegNSS', 'VacNSS', 'RegRefusal', 'VacRefusal'],
             'prefix'=>'c']
            ], 'joinkey');

        // 10 campaign vaccinated in campaign and catchup
        $tenCampVacChildChart = $charts->chartData1Category($category[1],
               ['TotalVac'=>'Vaccinated Campaign',
                'cTotalVac'=>'Vaccinated Catchup'], $tenCampData);
        $tenCampVacChildChart['title'] = 'Vaccinated Children During '.$during.' and Catchup';
        $tenCampVacChildChart['subTitle'] = $subTitle;

        // 10 campaigns missed after campaign and catchup
        $tenCampMissedCalcData = Triangle::mathOps($tenCampData,
                                                    ['TotalRemaining', 'cTotalRecovered'], '-',
                                                    'FinalTotalRemaining');
        $tenCampMissedChildChart = $charts->chartData1Category($category[1],
            ['TotalRemaining'=>'Missed After Campaign',
                'FinalTotalRemaining'=>'Missed After Catchup'], $tenCampMissedCalcData);
        $tenCampMissedChildChart['title'] = 'Missed Children After '.$during.' and Catchup';
        $tenCampMissedChildChart['subTitle'] = $subTitle;

        // 10 campaign missed recovered all reasons
        $tenCampMissedCalcData = Triangle::mathOps($tenCampData, ['TotalRemaining', 'cRegMissed'], '-', 'cDisc');
        $tenCampMissedCalcData = Triangle::mathOps($tenCampMissedCalcData, ['TotalRemaining', 'cTotalRecovered'], '-', 'RemTotal');
        $tenCampMissedCalcData = Triangle::mathOps($tenCampMissedCalcData, ['RemTotal', 'cDisc'], '-', 'FinalTotalRemaining');
        $tenCampMissedTypeChart = $charts->chartData1Category($category[1],
            [   'cDisc'=>'Discrep',
                'FinalTotalRemaining'=>'Remaining',
                'cTotalRecovered'=>'Catchup',
                'RecoveredDay4'=>'Day5',
                'Recovered3Days'=>'3Days'
            ]
            , $tenCampMissedCalcData);
        $tenCampMissedTypeChart['title'] = 'Missed Children Recovery in Camp/Revisit/Catchup';
        $tenCampMissedTypeChart['subTitle'] = $subTitle;

        // 10 campaign area chart missed recovered all reasons
        $last10CampRecovered = $charts->chartData1Category($category[1],
            [
                'cDisc'=>'Discrepancy',
                'FinalTotalRemaining'=>'Remaining after Catchup',
                'cTotalRecovered'=>'Recovered in Catchup',
                'RecoveredDay4'=>'Recovered in Day5',
                'Recovered3Days'=>'Recovered in 3Days',
            ],
            $tenCampMissedCalcData);
        $last10CampRecovered['title'] = "Recovering missed children during campaign and catchup";
        $last10CampRecovered['subTitle'] = $subTitle;

        // 10 campaign stack/percent chart for recovering absent during camp/catchup
        $tenCampMissedCalcData = Triangle::mathOps($tenCampData, ['RemAbsent', 'cRegAbsent'], '-', 'cDisc');
        $tenCampMissedCalcData = Triangle::mathOps($tenCampMissedCalcData, ['RemAbsent', 'cVacAbsent'], '-', 'RemTotal');
        $tenCampMissedCalcData = Triangle::mathOps($tenCampMissedCalcData, ['RemTotal', 'cDisc'], '-', 'FinalRemAbsent');
        $tenCampAbsentChart = $charts->chartData1Category($category[1],
            ['cDisc'=>'Discrep',
                'FinalRemAbsent'=>'Remaining',
                'cVacAbsent'=>'Catchup',
                'VacAbsentDay4'=>'Day5',
                'VacAbsent3Days'=>'3Days']
            , $tenCampMissedCalcData);
        $tenCampAbsentChart['title'] = 'Absent Children Recovery in Camp/Revisit/Catchup';
        $tenCampAbsentChart['subTitle'] = $subTitle;

        // 10 campaign stack/percent chart for recovering NSS during camp/catchup
        $tenCampMissedCalcData = Triangle::mathOps($tenCampData, ['RemNSS', 'cRegNSS'], '-', 'cDisc');
        $tenCampMissedCalcData = Triangle::mathOps($tenCampMissedCalcData, ['RemNSS', 'cVacNSS'], '-', 'RemTotal');
        $tenCampMissedCalcData = Triangle::mathOps($tenCampMissedCalcData, ['RemTotal', 'cDisc'], '-', 'FinalRemNSS');
        $tenCampNSSChart = $charts->chartData1Category($category[1],
            ['cDisc'=>'Discrep',
                'FinalRemNSS'=>'Remaining',
                'cVacNSS'=>'Catchup',
                'VacNSSDay4'=>'Day5',
                'VacNSS3Days'=>'3Days']
            , $tenCampMissedCalcData);
        $tenCampNSSChart['title'] = 'NSS Children Recovery in Camp/Revisit/Catchup';
        $tenCampNSSChart['subTitle'] = $subTitle;

        // 10 campaign stack/percent chart for recovering NSS during camp/catchup
        $tenCampMissedCalcData = Triangle::mathOps($tenCampData, ['RemRefusal', 'cRegRefusal'], '-', 'cDisc');
        $tenCampMissedCalcData = Triangle::mathOps($tenCampMissedCalcData, ['RemRefusal', 'cVacRefusal'], '-', 'RemTotal');
        $tenCampMissedCalcData = Triangle::mathOps($tenCampMissedCalcData, ['RemTotal', 'cDisc'], '-', 'FinalRemRefusal');
        $tenCampRefusalChart = $charts->chartData1Category($category[1],
            ['cDisc'=>'Discrep',
                'FinalRemRefusal'=>'Remaining',
                'cVacRefusal'=>'Catchup',
                'VacRefusalDay4'=>'Day5',
                'VacRefusal3Days'=>'3Days']
            , $tenCampMissedCalcData);
        $tenCampRefusalChart['title'] = 'Refusal Children Recovery in Camp/Revisit/Catchup';
        $tenCampRefusalChart['subTitle'] = $subTitle;

        // +++++++++++++++++++++++++++++++++++++++++++++++++++ End of 10 Campaign +++++++++++++++++++++++++++++++++++

        // =================================================== Data and Charts Processing for Latest Campaign =======
        //$lastCamp = $settings->latestCampaign('CoverageData');
        $lastCampData = $charts->chartData('CoverageData', $functionCampaignsStatistics, $selectedCampaignIds, $secondParam);
        $lastCampCatchupData = $charts->chartData('CatchupData', $functionCampaignsStatistics, $selectedCampaignIds, $secondParam);
        //return new Response(json_encode($lastCampData));
        // triangulate the data with catchup data
        $lastCampData = Triangle::triangulateCustom([
            $lastCampData,
            ['data'=>$lastCampCatchupData,
             'indexes'=>['RegMissed', 'TotalRecovered', 'TotalVac',
                         'RegAbsent', 'VacAbsent', 'RegNSS', 'VacNSS', 'RegRefusal', 'VacRefusal'],
             'prefix'=>'c']
            ], 'joinkey');
        // last campaign recovered all type by 3days, 4th day and catchup
        $lastCampRecoveredData = Triangle::mathOps($lastCampData, ['TotalRemaining', 'cRegMissed'], '-', 'cDisc');
        $lastCampRecoveredData = Triangle::mathOps($lastCampRecoveredData, ['TotalRemaining', 'cTotalRecovered'], '-', 'RemTotal');
        $lastCampRecoveredData = Triangle::mathOps($lastCampRecoveredData, ['RemTotal', 'cDisc'], '-', 'FinalTotalRemaining');
        $lastCampRecovered = $charts->pieData(['Recovered3Days'=>'3Days',
                                                'RecoveredDay4'=>'Day5',
                                                'cTotalRecovered'=>'Catchup',
                                                'FinalTotalRemaining'=>'Remaining',
                                                'cDisc' => 'Discrep'
                                                ],
                                                $lastCampRecoveredData);
        $lastCampRecovered['title'] = "Missed Children Recovery Camp/Revisit/Catchup";
        $lastCampRecovered['subTitle'] = $subTitle;

        // last campaign Absent recovered by 3days and 4th day and catchup
        $lastCampRecoveredData = Triangle::mathOps($lastCampData, ['RemAbsent', 'cRegAbsent'], '-', 'cDisc');
        $lastCampRecoveredData = Triangle::mathOps($lastCampRecoveredData, ['RemAbsent', 'cVacAbsent'], '-', 'RemTotal');
        $lastCampRecoveredData = Triangle::mathOps($lastCampRecoveredData, ['RemTotal', 'cDisc'], '-', 'FinalRemAbsent');
        $lastCampAbsentRecovered = $charts->pieData(['VacAbsent3Days'=>'3Days',
                                                        'VacAbsentDay4'=>'Day5',
                                                        'cVacAbsent'=>'Catchup',
                                                        'FinalRemAbsent'=>'Remaining',
                                                        'cDisc' => 'Discrep'
                                                        ],
                                                        $lastCampRecoveredData);
        $lastCampAbsentRecovered['title'] = "Absent Children Recovery Camp/Revisit/Catchup";
        $lastCampAbsentRecovered['subTitle'] = $subTitle;

        // last campaign NSS recovered by 3days and 4th day and catchup
        $lastCampRecoveredData = Triangle::mathOps($lastCampData, ['RemNSS', 'cRegNSS'], '-', 'cDisc');
        $lastCampRecoveredData = Triangle::mathOps($lastCampRecoveredData, ['RemNSS', 'cVacNSS'], '-', 'RemTotal');
        $lastCampRecoveredData = Triangle::mathOps($lastCampRecoveredData, ['RemTotal', 'cDisc'], '-', 'FinalRemNSS');
        $lastCampNSSRecovered = $charts->pieData([  'VacNSS3Days'=>'3Days',
                                                    'VacNSSDay4'=>'Day5',
                                                    'cVacNSS'=>'Catchup',
                                                    'FinalRemNSS'=>'Remaining',
                                                    'cDisc'=>'Discrep'
                                                    ],
                                                    $lastCampRecoveredData);
        $lastCampNSSRecovered['title'] = "NSS Children Recovery Camp/Revisit/Catchup";
        $lastCampNSSRecovered['subTitle'] = $subTitle;

        // last campaign Refusal recovered by 3days and 4th day and catchup
        $lastCampRecoveredData = Triangle::mathOps($lastCampData, ['RemRefusal', 'cRegRefusal'], '-', 'cDisc');
        $lastCampRecoveredData = Triangle::mathOps($lastCampRecoveredData, ['RemRefusal', 'cVacRefusal'], '-', 'RemTotal');
        $lastCampRecoveredData = Triangle::mathOps($lastCampRecoveredData, ['RemTotal', 'cDisc'], '-', 'FinalRemRefusal');
        $lastCampRefusalRecovered = $charts->pieData(['VacRefusal3Days'=>'3Days',
                                                        'VacRefusalDay4'=>'Day5',
                                                        'cVacRefusal'=>'Catchup',
                                                        'FinalRemRefusal'=>'Remaining',
                                                        'cDisc' => 'Discrep'
                                                        ],
                                                        $lastCampRecoveredData);
        $lastCampRefusalRecovered['title'] = "Refusal Children Recovery Camp/Revisit/Catchup";
        $lastCampRefusalRecovered['subTitle'] = $subTitle;

        // =============================================== End of last campaign data ==================================

        $campaign = count($lastCampData)>0?$lastCampData[0]['CName']:0;

        $data =
            [
                'chartVacChild10Camp' => $tenCampVacChildChart,
                'chartMissed10Camp' => $tenCampMissedChildChart,
                'chartMissedType10camp' => $tenCampMissedTypeChart,
                'recoveredAll' => $lastCampRecovered,
                'recoveredAbsent' => $lastCampAbsentRecovered,
                'recoveredNSS' => $lastCampNSSRecovered,
                'recoveredRefusal' => $lastCampRefusalRecovered,
                'last10CampRecovered' => $last10CampRecovered,
                'tenCampAbsent' => $tenCampAbsentChart,
                'tenCampNSS' => $tenCampNSSChart,
                'tenCampRefusal' => $tenCampRefusalChart,
                'campaign' => $campaign
            ];

        return new Response(json_encode($data));

    }


}