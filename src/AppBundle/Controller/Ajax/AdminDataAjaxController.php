<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 1/24/2018
 * Time: 12:07 PM
 */

namespace AppBundle\Controller\Ajax;


use AppBundle\Service\Charts;
use AppBundle\Service\HtmlTable;
use AppBundle\Service\Settings;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class AdminDataAjaxController extends Controller
{
    /**
     * @param Request $request
     * @param Settings $settings
     * @param Charts $charts
     * @Route("api/camp-statistics", name="ajax_camp_statistics")
     * @Method("POST")
     * @return Response
     */
    public function ajaxCampStatisticsAction(Request $request, Settings $settings, Charts $charts) {

        $campType = $request->get('campType');
        $entity = $request->get('entity');
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

        $category = [['column'=>'Region'], ['column'=>'CID', 'substitute'=>['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']]];

        $indicators = ['TotalVac'=>'Vaccinated Children'];
        $title = "Vaccinated children trends in ";
        if($chartType === 'missed_10camp') {
            $indicators = ['TotalRemaining' => 'Missed Children'];
                $title = "Missed children trends in ";
        }
        if($chartType === 'missed_type_10camp') {
            $indicators = ['RemAbsent' => 'Absent', 'RemNSS' => 'NSS', 'RemRefusal' => 'Refusal'];
            $title = "Missed children by reasons trends in ";
        }

        $campData = null;
        $campaignIds = $campaigns;

        if($campType === 'Reset') {
            if(count($campaignIds) < 2)
                $campaignIds = $settings->lastFewCampaigns($entity, $settings::NUM_CAMP_CHARTS);
            $campData = $charts->chartData($entity, $function, $campaignIds, $setting);
        } else {
            if(count($campaignIds) < 2)
                $campData = $charts->chartData($entity, $function, $settings::NUM_CAMP_LIMIT, $setting);
            else {
                $function = "campaignsStatistics";
                $campData = $charts->chartData($entity, $function, $campaignIds, $setting);
            }
        }

        $chartData = $charts->chartData1Category($category[1], $indicators, $campData);
        $chartData['title'] = $title.($campType=="Reset"?"last 10":$campType).' Campaigns';
        $chartData['subTitle'] = $subTitle;

        return new Response(json_encode([
            $chartType => $chartData,
        ]));
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/api/clusters/admin_data", name="ajax_cluster_admin_data", options={"expose"=true})
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
                }

                // merge the data of all sub districts
                $heatMapData = array_merge(...$heatMapData);
            }

            // if there's no sub district
            if(count($subDistrictArray) <= 0 || $subDistrictArray === null){
                $heatMapData = $em->getRepository('AppBundle:CoverageData')
                    ->clusterAggBySubDistrictCluster($campaignIds, $districts, $clusterArray);
            }

            //return new Response(json_encode($heatMapData));

            // covert the database data into heatmap array for a give indicator
            $heatMapDataTotalRemaining = $charts->clusterDataForHeatMap($heatMapData, 'TotalRemaining',
                ['column'=>'CID', 'substitute' => 'shortName'], $clusters, $calcTypeArray);
            $heatMapDataTotalRemaining['title'] = 'Trends of total remaining children after campaign';
            $heatMapDataTotalRemaining['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne('CoverageData', 'TotalRemaining'.$dbIndicatorPostfix);
            $data['heatMapTotalRemaining'] = $heatMapDataTotalRemaining;

            // covert the database data into heatmap array for a give indicator
            $heatMapDataTotalAbsent = $charts->clusterDataForHeatMap($heatMapData, 'RemAbsent',
                ['column'=>'CID', 'substitute' => 'shortName'], $clusters, $calcTypeArray);
            $heatMapDataTotalAbsent['title'] = 'Tends of total absent children after campaign';
            $heatMapDataTotalAbsent['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne('CoverageData', 'RemAbsent'.$dbIndicatorPostfix);
            $data['heatMapTotalAbsent'] = $heatMapDataTotalAbsent;

            // covert the database data into heatmap array for a give indicator
            $heatMapTotalNSS = $charts->clusterDataForHeatMap($heatMapData, 'RemNSS',
                ['column'=>'CID', 'substitute' => 'shortName'], $clusters, $calcTypeArray);
            $heatMapTotalNSS['title'] = 'Tends of total NSS children after campaign';
            $heatMapTotalNSS['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne('CoverageData', 'RemNSS'.$dbIndicatorPostfix);
            $data['heatMapTotalNSS'] = $heatMapTotalNSS;

            // covert the database data into heatmap array for a give indicator
            $heatMapDataTotalRefusal = $charts->clusterDataForHeatMap($heatMapData, 'RemRefusal',
                ['column'=>'CID', 'substitute' => 'shortName'], $clusters, $calcTypeArray);
            $heatMapDataTotalRefusal['title'] = 'Tends of total refusal children after campaign';
            $heatMapDataTotalRefusal['stops'] = $em->getRepository("AppBundle:HeatmapBenchmark")
                ->findOne('CoverageData', 'RemRefusal'.$dbIndicatorPostfix);
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
                        $lastCampClustersData[] = $em->getRepository('AppBundle:CoverageData')
                            ->clusterAggBySubDistrictCluster([$lastCampaignId], $districts, $clusterArray, $item);
                    }

                    // merge the data of all sub districts
                    $lastCampClustersData = array_merge(...$lastCampClustersData);
                }

                // if there's no sub district
                if (count($subDistrictArray) <= 0 || $subDistrictArray === null) {
                    $lastCampClustersData = $em->getRepository('AppBundle:CoverageData')
                        ->clusterAggBySubDistrictCluster([$lastCampaignId], $districts, $clusterArray);
                }

                $lastCampBarChart = $charts->chartData1Category(['column' => 'Cluster'],
                    [
                        'RemAbsent' => 'Absent',
                        'RemNSS' => 'NSS',
                        'RemRefusal' => 'Refusal',
                        'TotalVac' => 'Vaccinated',
                    ],
                    $lastCampClustersData, false);
                $campaign = "No data for this campaign as per current filter";
                if(count($lastCampClustersData) > 0)
                    $campaign = $lastCampClustersData[0]['CName']." Recovered and Remaining Children";
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
     * @Route("/api/filter/admin_data", name="ajax_filter_admin_data", options={"expose"=true})
     * @Method("POST")
     */
    function filterAdminDashboardAction(Request $request, Settings $settings, Charts $charts) {

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
            if(array_search("VHR", $districts) > -1 ||
                array_search('HR', $districts) > -1 ||
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
        } else if(count($selectedCampaignIds) == 1) {
            $subTitle = null;
        }

        //$regionAdminData = $charts->chartData('CoverageData', 'regionAgg', $campaignIds);
        $campaignIds = $settings->lastFewCampaigns('CoverageData', $settings::NUM_CAMP_CHARTS);

        if(count($selectedCampaignIds) > 1) {
            // this function returns latest campaign, can work for all data sources that have relation with campaign
            $lastCamp = $settings->latestCampaign('CoverageData');
            $campaignIds = $selectedCampaignIds;
            $during = "Selected Campaigns";
            $selectedCampaignIds = [$lastCamp[0]['id']];
        }
        $lastCampAdminData = $charts->chartData('CoverageData', $functionCampaignsStatistics, $selectedCampaignIds, $secondParam);
        $lastCampRegionsData = $charts->chartData('CoverageData', $functionRegion, $selectedCampaignIds, $secondParam);
        //return new Response(json_encode(['func' => $lastCampRegionsData]));
        //Total Vac Children Last 10 Campaigns

        $tenCampAdminData = $charts->chartData('CoverageData', $functionCampaignsStatistics, $campaignIds, $secondParam);

        $tenCampVacChildChart = $charts->chartData1Category($category[1], ['TotalVac'=>'Vaccinated Children'], $tenCampAdminData);
        $tenCampVacChildChart['title'] = 'Vaccinated Children '.$during;
        $tenCampVacChildChart['subTitle'] = $subTitle;

        $tenCampMissedChildChart = $charts->chartData1Category($category[1], ['TotalRemaining'=>'Missed Children'], $tenCampAdminData);
        $tenCampMissedChildChart['title'] = 'Missed Children During '.$during;
        $tenCampMissedChildChart['subTitle'] = $subTitle;

        $tenCampMissedTypeChart = $charts->chartData1Category($category[1],
            ['RemAbsent'=>'Absent', 'RemNSS'=>'NSS', 'RemRefusal'=>'Refusal'], $tenCampAdminData);
        $tenCampMissedTypeChart['title'] = 'Missed Children By Reason '.$during;
        $tenCampMissedTypeChart['subTitle'] = $subTitle;

        // Ten campaign missed recovery charts
        $tenCampMissedRecovered = $charts->chartData1Category($category[1],
            ['TotalRemaining'=>'Remaining', 'RecoveredDay4'=>'Day5', 'Recovered3Days'=>'3Days' ],
            $tenCampAdminData);
        $tenCampMissedRecovered['title'] = "Missed Children Recovery Camp/Revisit";
        $tenCampMissedRecovered['subTitle'] = $subTitle;

        $tenCampAbsentRecovered = $charts->chartData1Category($category[1],
            ['RemAbsent'=>'Remaining', 'VacAbsentDay4'=>'Day5' , 'VacAbsent3Days'=>'3Days'],
            $tenCampAdminData);
        $tenCampAbsentRecovered['title'] = "Absent Children Recovery Camp/Revisit";
        $tenCampAbsentRecovered['subTitle'] = $subTitle;

        $tenCampNSSRecovered = $charts->chartData1Category($category[1],
            ['RemNSS'=>'Remaining', 'VacNSSDay4'=>'Day5', 'VacNSS3Days'=>'3Days' ],
            $tenCampAdminData);
        $tenCampNSSRecovered['title'] = "NSS Children Recovery Camp/Revisit";
        $tenCampNSSRecovered['subTitle'] = $subTitle;

        $tenCampRefusalRecovered = $charts->chartData1Category($category[1],
            ['RemRefusal'=>'Remaining', 'VacRefusalDay4'=>'Day5', 'VacRefusal3Days'=>'3Days'],
            $tenCampAdminData);
        $tenCampRefusalRecovered['title'] = "Refusal Children Recovery Camp/Revisit";
        $tenCampRefusalRecovered['subTitle'] = $subTitle;

        // Last campaign missed by reason
        $lastCampMissedPieChart = $charts->pieData(['RemAbsent'=>'Absent', 'RemNSS'=>'NSS', 'RemRefusal'=>'Refusal'], $lastCampAdminData);
        $lastCampMissedPieChart['title'] = "Missed Children By Reason";
        //$lastCampMissedPieChart['subTitle'] = $subTitle;


        // last campaign recovered all type by 3days, 4th day
        $lastCampRecovered = $charts->pieData(['Recovered3Days'=>'3Days', 'RecoveredDay4'=>'Day5', 'TotalRemaining'=>'Remaining'],
            $lastCampAdminData);
        $lastCampRecovered['title'] = "Missed Children Recovery Camp/Revisit";
        $lastCampRecovered['subTitle'] = $subTitle;

        // last campaign Absent recovered by 3days and 4th day
        $lastCampAbsentRecovered = $charts->pieData(['VacAbsent3Days'=>'3Days', 'VacAbsentDay4'=>'Day5', 'RemAbsent'=>'Remaining'],
            $lastCampAdminData);
        $lastCampAbsentRecovered['title'] = "Absent Children Recovery Camp/Revisit";
        $lastCampAbsentRecovered['subTitle'] = $subTitle;

        // last campaign NSS recovered by 3days and 4th day
        $lastCampNSSRecovered = $charts->pieData(['VacNSS3Days'=>'3Days', 'VacNSSDay4'=>'Day5', 'RemNSS'=>'Remaining'],
            $lastCampAdminData);
        $lastCampNSSRecovered['title'] = "NSS Children Recovery Camp/Revisit";
        $lastCampNSSRecovered['subTitle'] = $subTitle;

        // last campaign Refusal recovered by 3days and 4th day
        $lastCampRefusalRecovered = $charts->pieData(['VacRefusal3Days'=>'3Days', 'VacRefusalDay4'=>'Day5', 'RemRefusal'=>'Remaining'],
            $lastCampAdminData);
        $lastCampRefusalRecovered['title'] = "Refusal Children Recovery Camp/Revisit";
        $lastCampRefusalRecovered['subTitle'] = $subTitle;

        // last campaign Refusal recovered by 3days and 4th day
        $last10CampRecovered = $charts->chartData1Category($category[1],
            ['TotalRemaining'=>'Remaining',
                'VacAbsent'=>'Recovered Absent',
                'VacNSS'=>'Recovered NSS',
                'VacRefusal'=>'Recovered Refusal'],
            $tenCampAdminData);
        $last10CampRecovered['title'] = "Recovering Missed Children By Reason During ".$during;
        $last10CampRecovered['subTitle'] = $subTitle;
        $last10CampRecovered['subTitle'] = $subTitle;

        // last campaign vaccine wastage by region
        $lastCampVaccineData = $charts->chartData1Category($category[0], ['VacWastage'=>'Wastage'], $lastCampRegionsData);
        $lastCampVaccineData['title'] = 'Vaccine Wastage';
        $lastCampVaccineData['subTitle'] = $subTitle;
        //return new Response(json_encode(['func' => $category]));
        // call the static table function fo HtmlTable class
        $table['table'] = HtmlTable::tableForAdminData($lastCampRegionsData, $type);
        $info = HtmlTable::infoForAdminData($lastCampAdminData);

        $campaign = "No data for this campaign as per current filter";
        if(count($lastCampAdminData) > 0)
            $campaign = $lastCampAdminData[0]['CName'];

        $data = [
            'chartVacChild10Camp' => $tenCampVacChildChart,
            'chartMissed10Camp' => $tenCampMissedChildChart,
            'chartMissedType10camp' => $tenCampMissedTypeChart,
            'chartMissedRec10Camp' => $tenCampMissedRecovered,
            'chartAbsentRec10Camp' => $tenCampAbsentRecovered,
            'chartNSSRec10Camp' => $tenCampNSSRecovered,
            'chartRefusalRec10Camp' => $tenCampRefusalRecovered,
            'lastCampPieData' => $lastCampMissedPieChart,
            'lastCampVacData' => $lastCampVaccineData,
            'lastCampRegionData' => $lastCampRegionsData,
            'recoveredAll' => $lastCampRecovered,
            'recoveredAbsent' => $lastCampAbsentRecovered,
            'recoveredNSS' => $lastCampNSSRecovered,
            'recoveredRefusal' => $lastCampRefusalRecovered,
            'last10CampRecovered' => $last10CampRecovered,
            'campaign' => $campaign,
            'info' => $info,
            'table' => $table
        ];
        return new Response(json_encode($data));

    }


}