<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 1/24/2018
 * Time: 12:07 PM
 */

namespace AppBundle\Controller\Ajax;


use AppBundle\Service\Exporter;
use AppBundle\Service\HtmlTable;
use AppBundle\Service\Triangle;
use AppBundle\Service\Settings;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class AjaxRefusalCommitteesController extends CommonDashboardController
{
    /**
     * @param Request $request
     * @param Settings $settings
     * @return mixed
     * @Route("/ajax/ref_committees", name="ajax_ref_committees")
     */
    public function mainAction(Request $request, Settings $settings)
    {
        return parent::mainAction($request, $settings);
    }

    /**
     * @param Request $request
     * @return mixed
     * @Route("/ajax/cluster/ref_committees/",
     *     name="ajax_cluster_ref_committees",
     *     options={"expose"=true})
     */
    public function clusterAction(Request $request, Settings $settings)
    {
        return parent::clusterAction($request, $settings);
    }

    protected function trendAction($entity, $campaigns, $params, $titles)
    {

        // location trends, default for three campaigns
        $locTrends = $this->campaignLocationData($entity, $titles['locTrendIds'], $params);

        $trends =  $this->campaignsData($entity, $campaigns, $params);

        $trends = $this->loadAndMixData($trends, $campaigns, $params, "campaignsData", 'trend');
        $locTrends = $this->loadAndMixData($locTrends, $campaigns, $params, "campaignLocationData", null);

        $trends = $this->allMathOps($trends);
        $locTrends = $this->allMathOps($locTrends);

        $category = [['column'=>'Region'],
            ['column'=>'CID', 'substitute'=>
                ['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']
            ]
        ];
        $subTitle = $titles['subTitle'];
        $during = $titles['midTitle'];



        // --------------------------- Loc Trend of Missed Children --------------------------------
        $locTrendAllType = $this->chart->chartData2Categories(
            ['column'=>$titles['aggType']],
            $category[1],
            [
                'cmpVacRefusal'=>'In Campaign',
                'chpVacRefusal'=>'In Catchup',
                'totalRefusalVacByRefComm'=>'By Committees',
                'totalRemainingRefusal'=>'Remaining'
            ],
            $locTrends
        );
        $locTrendAllType['title'] = 'Refusals Recovery';
        $locTrendAllType['subTitle'] = $subTitle;
        $data['loc_trend_general'] = $locTrendAllType;

        // --------------------------- Loc Trend of Absent Children --------------------------------
        $locTrendAllType = $this->chart->chartData2Categories(
            ['column'=>$titles['aggType']],
            $category[1],
            [
                'chpVacRefusal'=>'In Catchup',
                'refusalVacByCRC'=>'By CRC',
                'refusalVacByRC'=>'By RC',
                'refusalVacByCIP'=>'By CIP',
                'refusalVacBySenior'=>'By Senior',
                'totalRemainingRefusal'=>'Remaining'
            ],
            $locTrends
        );
        $locTrendAllType['title'] = 'Refusal Recovery After Revisit Day';
        $locTrendAllType['subTitle'] = $subTitle;
        $data['loc_trend_detail'] = $locTrendAllType;



        // --------------------------- Trend of Vaccinated Children --------------------------------
        $vacChildTrend = $this->chart
            ->chartData1Category($category[1],
                [
                    'cmpVacRefusal'=>'Campaign',
                    'refusalVacInCatchup'=>'Catchup',
                    'totalRefusalVacByRefComm'=>'Committees',
                    'totalRemainingRefusal'=>'Remaining'

                ],
                $trends);
        $vacChildTrend['title'] = 'Trend of Refusals Recovery '.$during;
        $vacChildTrend['subTitle'] = $subTitle;
        $data['general_refusal_recovery_trend'] = $vacChildTrend;



        // --------------------------- Trend of Missed Children By Type -----------------------------
        $missedByTypeTrend = $this->chart->chartData1Category($category[1],
            [

                'cmpVacRefusal'=>'Campaign',
                'refusalVacInCatchup'=>'Catchup',
                'refusalVacByCRC'=>'CRC',
                'refusalVacByRC'=>'RC',
                'refusalVacByCIP'=>'CIP',
                'refusalVacBySenior'=>'Senior',
                'totalRemainingRefusal'=>'Remaining'
            ], $trends);
        $missedByTypeTrend['title'] = 'Trends of Refusals Recovery By Category '.$during;
        $missedByTypeTrend['subTitle'] = $subTitle;
        $data['detail_refusal_recovery_trend'] = $missedByTypeTrend;


        return new JsonResponse($data);

        //return new JsonResponse(['data'=>null]);
    }

    protected function latestInfoAction($entity, $campaigns, $params, $titles)
    {
        //dump($params); die;
        //echo "Catchup Data Class is Called"; die;
        $info =  $this->campaignsData($entity, $campaigns, $params);
        $campInfo = $info['oneCamp']; // it comes in the array index = trend
        $campAgg = $info['oneCampAgg']; // get the aggregated data

        $subTitle = $titles['subTitle'];
        $type = $titles['aggType'];

        /*
        // ---------------------------- Pie Chart one campaign missed by reason ----------------------------
        $missedByReasonPie = $this->chart->pieData([
            'TotalRecovered'=>'Catchup',
            'refusalVacByCRC'=>'CRC',
            'refusalVacByRC'=>'RC',
            'refusalVacByCIP'=>'CIP',
            'refusalVacBySeniorStaff'=>'Senior',
            'totalRemaining'=>'Remaining'
        ], $campInfo);
        $missedByReasonPie['title'] = "Refusals Recovery Breakdown";
        $data['missed_by_reason_pie_1'] = $missedByReasonPie;

        // ---------------------------- one campaign recovered all type by Catchup -------------------------
        $recoveredAllType = $this->chart->pieData([
            'TotalRecovered'=>'Recovered',
            'TotalRemaining'=>'Remaining'
        ], $campInfo);
        $recoveredAllType['title'] = "Refusals Recovery";
        $recoveredAllType['subTitle'] = $subTitle;
        $data['recovered_all_type_1'] = $recoveredAllType;

        // ---------------------------- last campaign Absent recovered by Catchup  -------------------------
        $recoveredAbsent = $this->chart->pieData([
            'VacRefusal'=>'Campaign',
            'TotalRecovered'=>'Catchup',
            'totalRefusalVacByRefComm'=>'Committees',
            'totalRemaining'=>'Remaining'
        ],
            $campInfo);
        $recoveredAbsent['title'] = "Refusals Recovery in Campaign, Catchup & after Catchup";
        $recoveredAbsent['subTitle'] = $subTitle;
        $data['recovered_absent_1'] = $recoveredAbsent;

        // ---------------------------- last campaign NSS recovered by Catchup -----------------------------
        $recoveredNss = $this->chart->pieData([
            'VacRefusal'=>'Campaign',
            'TotalRecovered'=>'Catchup',
            'refusalVacByCRC'=>'CRC',
            'refusalVacByRC'=>'RC',
            'refusalVacByCIP'=>'CIP',
            'refusalVacBySeniorStaff'=>'Senior',
            'totalRemaining'=>'Remaining'
        ],
            $campInfo);
        $recoveredNss['title'] = "Refusals Recovery in Campaign, Catchup & By Refusal Committees";
        $recoveredNss['subTitle'] = $subTitle;
        $data['recovered_nss_1'] = $recoveredNss;


        // ---------------------------- last campaign total missed by region -------------------------------
        $totalRemaining = $this->chart->chartData1Category(['column'=>$titles['aggType']],
            ['TotalRemaining'=>'Remaining'], $campAgg);
        $totalRemaining['title'] = 'Final Remaining Refusals';
        $totalRemaining['subTitle'] = $subTitle;
        $data['total_remaining_1'] = $totalRemaining;

        /*
        // ---------------------------- last campaign total missed by location -------------------------------
        $oneCat = $titles['aggType'] === 'Region' ? true : false;
        if($oneCat) {
            $totalRemaining = $this->chart->chartData1Category(['column' => $titles['aggType']],
                [
                    'TotalRecovered' => 'Recovered',
                    'TotalRemaining' => 'Remaining',
                ], $campAgg);
            $totalRemaining['title'] = 'ICN Reduced Missed Children';
            $totalRemaining['subTitle'] = $subTitle;
            $data['total_recovered_remaining_1'] = $totalRemaining;
        } else {
            $cat1 = ['column' => 'Region'];
            if($titles['aggType'] === "District")
                $cat1 = ['column' => 'Province'];
            $totalRemaining = $this->chart->chartData2Categories(
                $cat1,
                ['column' => $titles['aggType']],
                ['TotalRemaining' => 'Remaining',
                    'TotalRecovered' => 'Recovered'], $campAgg);
            $totalRemaining['title'] = 'ICN Reduced Missed Children';
            $totalRemaining['subTitle'] = $subTitle;
            $data['total_recovered_remaining_1'] = $totalRemaining;
        }
        */

        // ---------------------------- Tabular information of the campaign -------------------------------
        /*
        $table = HtmlTable::tableForCatchupData($campAgg, $type);
        $data['info_table'] = $table;

        // just for the map data
        $data['map_data'] = json_encode($campAgg);
        */

        // ---------------------------- Header Tiles Information of the campaign --------------------------
        $info_header = HtmlTable::infoForRefusalComm($campInfo);
        $data['info_box'] = $info_header;

        // ---------------------------- Title of the one campaign information -----------------------------
        $campaign = "No data for this campaign as per current filter";
        if(count($campInfo) > 0)
            $campaign = $campInfo[0]['CName'];
        $data['campaign_title'] = $campaign;

        return new JsonResponse($data);
    }

    protected function clustersInfoAction($entity, $campaigns, $params, $controlParams = null)
    {
        $oneCampData = $this->clustersData($entity, $campaigns, $params);

        $oneCampBarChart = $this->chart->chartData1Category(['column'=>'Cluster'],
            [
                'TotalRecovered' => 'Recovered',
                'RemAbsent' => 'Absent',
                'RemNSS' => 'NSS',
                'RemRefusal' => 'Refusal',
            ],
            $oneCampData, true);
        $campaign = "No data for this campaign as per current filter";
        if(count($oneCampData) > 0)
            $campaign = $oneCampData[0]['CName']." Recovered and Remaining Children";
        $oneCampBarChart['title'] = $campaign;
        $data['missed_recovery_chart_1'] = $oneCampBarChart;
        return new JsonResponse($data);
    }

    protected function clustersTrendAction($entity, $campaigns, $params, $controlParams)
    {
        // fetch the data
        $heatMapData = $this->clustersData($entity, $campaigns, $params);

        $locTrends = $this->clustersData($entity, $controlParams['locTrendIds'], $params);

        // get the clusters from the params as they needed for the table
        $clusters = $params['cluster'];

        // get the selectType from controlParams
        $selectType = $controlParams["selectType"];

        // set the calcTypeArray
        $calcTypeArray = ['type'=>'number'];
        if($controlParams['calcType'] === 'percent') {
            $col = 'RegMissed';
            if($selectType == 'RemAbsentPer')
                $col = 'RegAbsent';
            elseif($selectType == 'RemNSSPer')
                $col = 'RegNSS';
            elseif($selectType == 'RemRefusalPer')
                $col = 'RegRefusal';

            $calcTypeArray = ['type'=>'percent', 'column'=>$col];
        }

        $rawData = $this->chart->clusterDataForHeatMap($heatMapData,
            str_replace("Per", "", $selectType),
            ['column' => 'CID', 'substitute' => 'shortName'],
            $clusters, $calcTypeArray, 'table');
        // get the entity manager for the benckmarks
        $em = $this->getDoctrine()->getManager();
        $stops= $em->getRepository("AppBundle:HeatmapBenchmark")
            ->findOne($entity, $selectType);

        // create columns for the table
        $cols = array(['col' => 'rowName', 'label' => 'Cluster', 'calc' => 'none']);
        foreach ($rawData['xAxis'] as $axi) {
            $cols[] = ['col' => $axi, 'label' => $axi, 'calc' => 'rev'];
        }

        $table = HtmlTable::heatMapTable($rawData['data'],
            $cols, HtmlTable::heatMapTableHeader($selectType),
            $stops['minValue'],
            $stops['maxValue']);

        $data['cluster_trend'] = $table;

        //======================================== New Cluster Level Trends Charts ================
        $category = [['column'=>'Region'],
            ['column'=>'CID', 'substitute'=>
                ['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']
            ]
        ];
        // --------------------------- Loc Trend of Missed Children --------------------------------
        $locTrendAllType = $this->chart->chartData2Categories(
            ['column'=>'Cluster'],
            $category[1],
            ['TotalRecovered'=>'Recovered', 'TotalRemaining'=>'Remaining'],
            $locTrends
        );
        $locTrendAllType['title'] = 'Missed Children Recovery';
        $locTrendAllType['subTitle'] = null;
        $data['loc_trend_all_type'] = $locTrendAllType;

        // --------------------------- Loc Trend of Absent Children --------------------------------
        $locTrendAllType = $this->chart->chartData2Categories(
            ['column'=>'Cluster'],
            $category[1],
            ['VacAbsent'=>'Recovered', 'RemAbsent'=>'Remaining'],
            $locTrends
        );
        $locTrendAllType['title'] = 'Absent Children Recovery';
        $locTrendAllType['subTitle'] = null;
        $data['loc_trend_absent'] = $locTrendAllType;

        // --------------------------- Loc Trend of NSS Children --------------------------------
        $locTrendAllType = $this->chart->chartData2Categories(
            ['column'=>'Cluster'],
            $category[1],
            ['VacNSS'=>'Recovered', 'RemNSS'=>'Remaining'],
            $locTrends
        );
        $locTrendAllType['title'] = 'NSS Children Recovery';
        $locTrendAllType['subTitle'] = null;
        $data['loc_trend_nss'] = $locTrendAllType;

        // --------------------------- Loc Trend of Refusal Children --------------------------------
        $locTrendAllType = $this->chart->chartData2Categories(
            ['column'=>'Cluster'],
            $category[1],
            ['VacRefusal'=>'Recovered', 'RemRefusal'=>'Remaining'],
            $locTrends
        );
        $locTrendAllType['title'] = 'Refusal Children Recovery';
        $locTrendAllType['subTitle'] = null;
        $data['loc_trend_refusal'] = $locTrendAllType;


        //return new Response($table);
        return new JsonResponse($data);
    }

    private function loadAndMixData($data, $campaigns, $params, $method, $index = null) {

        // clusters needed for extracting catchup and coverage data
        $clusters = $this->chart->chartData("RefusalComm", 'selectClustersByCondition', $campaigns, $params);

        $params['extra'] = $clusters;
        //$catchup = $this->combineData("both", $campaigns, $params['extra'] = $clusters);
        $catchup = $this->$method("CatchupData", $campaigns, $params);
        $coverage = $this->$method("CoverageData", $campaigns, $params);

        // Below indices required from other data sources
        $coverageIndices = ['CalcTarget', 'RegRefusal', 'RemRefusal', 'VacRefusal', 'VacRefusal3Days', 'VacRefusalDay4'];
        $catchupIndices = ['RegRefusal', 'VacRefusal'];

        $triangulatedData = Triangle::triangulateCustom([$index===null?$data:$data[$index],
            ['data'=>$index===null?$coverage:$coverage[$index], 'indexes'=>$coverageIndices, 'prefix'=>'cmp' ]],
            'joinkey');
        $triangulatedData = Triangle::triangulateCustom([$triangulatedData,
            ['data'=>$index===null?$catchup:$catchup[$index], 'indexes'=>$catchupIndices, 'prefix'=>'chp']], 'joinkey');

        return $triangulatedData;
    }

    private function allMathOps($data) {

        $data = Triangle::mathOps($data, ['cmpVacRefusal', 'chpVacRefusal'],
            '+', 'refusalVacInCampCatchup');
        $data = Triangle::mathOps($data, ['refusalVacInCampCatchup', 'totalRefusalVacByRefComm'],
            '+', 'totalVacRefusal');
        $data = Triangle::mathOps($data, ['cmpRegRefusal', 'totalVacRefusal'], '-',
            'totalRemainingRefusal');

        return $data;

    }
}