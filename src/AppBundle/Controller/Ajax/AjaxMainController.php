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
use AppBundle\Service\Triangle;
use AppBundle\Service\Settings;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class AjaxMainController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/api/clusters/main", name="ajax_cluster_main", options={"expose"=true})
     * @Method("POST")
     */
    public  function ajaxClusterLevelAction(Request $request, Charts $charts, Settings $settings) {

        /*
        this function will return two kind of response
        a table, or a json, if the callType was main then
        it will return JSON otherwise a table.
        */
        $selectedCampaignIds = $request->get('campaign');
        $clusters = $request->get('cluster');
        $districts = $request->get('district');
        $selectType = $request->get('selectType');

        // important key
        $calcType = $request->get('calcType');

        $dataSource = "CoverageData";

        $calcTypeArray = ['type'=>'number'];
        if($calcType === 'percent') {
            $calcTypeArray = ['type'=>'percent', 'column'=>'CalcTarget'];
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
        $em = $this->getDoctrine()->getManager();
        // if the request was only for one chart
        if($calcType === 'main') {
            $lastCampaignId = $selectedCampaignIds;
            $lastCampaign = $settings->latestCampaign($dataSource);
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
                $lastCampCltrCalc, true);
            $campaign = "No data for this campaign as per current filter";
            if(count($lastCampClustersData) > 0)
                $campaign = $lastCampClustersData[0]['CName']." Recovered and Remaining Children";
            $lastCampBarChart['title'] = $campaign;
            $data['lastCampBarChart'] = $lastCampBarChart;
            return new Response(json_encode($data));
        }
        // if the request was for table data
        else {

            $campaignIds = $selectedCampaignIds;
            if (count($districts) > 0) {
                // get last 6 campaigns if the selected campaigns are <2
                if (count($campaignIds) <= 1)
                    $campaignIds = $settings->lastFewCampaigns($dataSource, $settings::NUM_CAMP_CLUSTERS);

                // generating data for the heatmap
                $heatMapData = array();
                // in case there was any sub district of a district
                if (count($subDistrictArray) > 0) {
                    foreach ($subDistrictArray as $item) {
                        // find the clusters data
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
                if (count($subDistrictArray) <= 0 || $subDistrictArray === null) {
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

                // covert the database data into heatmap array for a given indicator
                // used str_replace to remove Per from the indicator,
                // because it's not part of the database result fields
                $newIndicator = Triangle::trIndicators($selectType);
                $heatMapDataCalc = Triangle::mathOps($heatMapData,
                    [str_replace("Per", "", $selectType), $newIndicator['cIndicator']],
                    '-', $newIndicator['fIndicator']);
                $rawData = $charts->clusterDataForHeatMap($heatMapDataCalc, $newIndicator['fIndicator'],
                    ['column' => 'CID', 'substitute' => 'shortName'], $clusters, $calcTypeArray, 'table');
                $stops= $em->getRepository("AppBundle:HeatmapBenchmark")
                    ->findOne("TriangleData", $selectType);

                // new code start here
                $cols = array(['col' => 'rowName', 'label' => 'Cluster', 'calc' => 'none']);
                foreach ($rawData['xAxis'] as $axi) {
                    $cols[] = ['col' => $axi, 'label' => $axi, 'calc' => 'rev'];
                }

                $table = HtmlTable::heatMapTable($rawData['data'],
                    $cols, HtmlTable::heatMapTableHeader($selectType),
                    $stops['minValue'],
                    $stops['maxValue']);

                return new Response($table);
                // and end here

            }

        }
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/api/filter/main", name="ajax_filter_main", options={"expose"=true})
     * @Method("POST")
     */
    function filterMainDashboardAction(Request $request, Settings $settings, Charts $charts) {

        // ========================== Handling Ajax Request =================================
        $campaignIds = $request->get('campaign');
        $regions = $request->get('region');
        $provinces = $request->get('province');
        $districts = $request->get('district');

        $campaignIds = count($campaignIds) > 0 ? $campaignIds:
                       $settings->lastFewCampaigns('CoverageData', $settings::NUM_CAMP_CHARTS);

        $data = [];
        $requestType = "Campaigns";
        if($districts !== null && count($districts)>0) {
            $data = $this->selectByDistrict($districts, $campaignIds);
            $requestType = "District";
        }
        else if($provinces !== null && count($provinces) > 0) {
            $data = $this->selectByProvince($provinces, $campaignIds);
            $requestType = "Province";
        }
        else if($regions !== null && count($regions) > 0) {
            $data = $this->selectByRegion($regions, $campaignIds);
            $requestType = "Region";
        }
        else
            $data = $this->selectByRegion("all", $campaignIds);

        $data = $this->combineData($data, $requestType);

        return new Response($data);

    }

    /**
     * @param $district
     * @param int $campaigns default 0 which means last 10 campaigns
     */
    private function selectByDistrict($district, $campaigns = 0) {

    }

    /**
     * @param $province
     * @param int $campaigns default 0 which means last 10 campaigns
     */
    private function selectByProvince($province, $campaigns = 0) {

    }

    /**
     * @param string $regions default all regions
     * @param int $campaigns default 0 which means last 10 campaigns
     */
    private function selectByRegion($regions = "all", $campaigns = 0) {

    }

    /**
     * @param $data
     * @param string $type (which will be used for the title of the charts)
     * @return string
     */
    private function combineData($data, $type = "region") {

        return json_encode($data);
    }


}