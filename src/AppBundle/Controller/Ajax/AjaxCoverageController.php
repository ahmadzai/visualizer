<?php
/**
 * Created by PhpStorm.
 * User: Wazir Khan Ahmadzai
 * Date: 8/12/2018
 * Time: 9:08 PM
 */

namespace AppBundle\Controller\Ajax;


use AppBundle\Service\Settings;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Service\HtmlTable;

class AjaxCoverageController extends CommonDashboardController
{

    /**
     * @param Request $request
     * @param Settings $settings
     * @return mixed
     * @Route("/ajax/coverage_data", name="ajax_coverage_data")
     */
    public function mainAction(Request $request, Settings $settings)
    {
        return parent::mainAction($request, $settings);
    }

    /**
     * @param Request $request
     * @return mixed
     * @Route("/ajax/cluster/coverage_data/", name="ajax_cluster_coverage_data")
     */
    public function clusterAction(Request $request, Settings $settings)
    {
        return parent::clusterAction($request, $settings);
    }

    protected function trendAction($entity, $campaigns, $params, $titles)
    {
        $trends =  $this->campaignsData($entity, $campaigns, $params);
        $trends = $trends['trend']; // it comes in the array index = trend
        $category = [['column'=>'Region'], 
                     ['column'=>'CID', 'substitute'=>
                         ['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']
                     ]
                    ];
        $subTitle = $titles['subTitle'];
        $during = $titles['midTitle'];

        // --------------------------- Trend of Vaccinated Children --------------------------------
        $vacChildTrend = $this->chart->chartData1Category($category[1], 
                                                           ['TotalVac'=>'Vaccinated Children'],
                                                           $trends);
        $vacChildTrend['title'] = 'Vaccinated Children '.$during;
        $vacChildTrend['subTitle'] = $subTitle;
        $data['vac_child_trend'] = $vacChildTrend;

        // --------------------------- Trend of Missed Children -------------------------------------
        $missedChildTrend = $this->chart->chartData1Category($category[1],
                                      ['TotalRemaining'=>'Remaining Children'], $trends);
        $missedChildTrend['title'] = 'Remaining Children During '.$during;
        $missedChildTrend['subTitle'] = $subTitle;
        $data['missed_child_trend'] = $missedChildTrend;

        // --------------------------- Trend of Missed Children By Type -----------------------------
        $missedByTypeTrend = $this->chart->chartData1Category($category[1],
            ['RemAbsent'=>'Absent', 'RemNSS'=>'NSS', 'RemRefusal'=>'Refusal'], $trends);
        $missedByTypeTrend['title'] = 'Remaining Children By Reason '.$during;
        $missedByTypeTrend['subTitle'] = $subTitle;
        $data['missed_by_type_trend'] = $missedByTypeTrend;

        // --------------------------- Trend of Recovering Missed -----------------------------------
        $missedRecoveredTrend = $this->chart->chartData1Category($category[1],
            ['TotalRemaining'=>'Remaining',
                'RecoveredDay4'=>'Day5',
                'Recovered3Days'=>'3Days' ],
            $trends);
        $missedRecoveredTrend['title'] = "Remaining Children Recovery Camp/Revisit";
        $missedRecoveredTrend['subTitle'] = $subTitle;
        $data['missed_recovered_trend'] = $missedRecoveredTrend;

        // ----------------------------- Trend of Absent Recovering --------------------------------
        $absentRecoveredTrend = $this->chart->chartData1Category($category[1],
            ['RemAbsent'=>'Remaining', 'VacAbsentDay4'=>'Day5' , 'VacAbsent3Days'=>'3Days'],
            $trends);
        $absentRecoveredTrend['title'] = "Absent Children Recovery Camp/Revisit";
        $absentRecoveredTrend['subTitle'] = $subTitle;
        $data['absent_recovered_trend'] = $absentRecoveredTrend;

        // ----------------------------- Trend of NSS Recovering -----------------------------------
        $nssRecoveredTrend = $this->chart->chartData1Category($category[1],
            ['RemNSS'=>'Remaining', 'VacNSSDay4'=>'Day5', 'VacNSS3Days'=>'3Days' ],
            $trends);
        $nssRecoveredTrend['title'] = "NSS Children Recovery Camp/Revisit";
        $nssRecoveredTrend['subTitle'] = $subTitle;
        $data['nss_recovered_trend'] = $nssRecoveredTrend;
        // ------------------------------ Trend of Refusal Recovering -----------------------------
        $refusalRecoveredTrend = $this->chart->chartData1Category($category[1],
            ['RemRefusal'=>'Remaining', 'VacRefusalDay4'=>'Day5', 'VacRefusal3Days'=>'3Days'],
            $trends);
        $refusalRecoveredTrend['title'] = "Refusal Children Recovery Camp/Revisit";
        $refusalRecoveredTrend['subTitle'] = $subTitle;
        $data['refusal_recovered_trend'] = $refusalRecoveredTrend;

        // ------------------------------ Trend of Missed Recovery -------------------------------
        $missedChildRecoveryTrend = $this->chart->chartData1Category($category[1],
            ['TotalRemaining'=>'Remaining',
                'VacAbsent'=>'Recovered Absent',
                'VacNSS'=>'Recovered NSS',
                'VacRefusal'=>'Recovered Refusal'],
            $trends);
        $missedChildRecoveryTrend['title'] = "Recovering Missed Children By Reason ".$during;
        $missedChildRecoveryTrend['subTitle'] = $subTitle;
        $data['missed_child_recovery_trend'] = $missedChildRecoveryTrend;

        return new JsonResponse($data);
    }

    protected function latestInfoAction($entity, $campaigns, $params, $titles)
    {
        //echo "Coverage Data Class is Called"; die;
        $info =  $this->campaignsData($entity, $campaigns, $params);
        $campInfo = $info['oneCamp']; // it comes in the array index = trend
        $campAgg = $info['oneCampAgg']; // get the aggregated data

        $subTitle = $titles['subTitle'];
        $type = $titles['aggType'];

        // ---------------------------- Pie Chart one campaign missed by reason ----------------------------
        $missedByReasonPie = $this->chart->pieData(['RemAbsent'=>'Absent',
            'RemNSS'=>'NSS',
            'RemRefusal'=>'Refusal'], $campInfo);
        $missedByReasonPie['title'] = "Remaining Children By Reason";
        $data['missed_by_reason_pie_1'] = $missedByReasonPie;

        // ---------------------------- one campaign recovered all type -----------------------------------
        $recoveredAllType = $this->chart->pieData(['Recovered3Days'=>'3Days',
                                                   'RecoveredDay4'=>'Day5',
                                                   'TotalRemaining'=>'Remaining'],
                                                  $campInfo);
        $recoveredAllType['title'] = "Missed Children Recovery Camp/Revisit";
        $recoveredAllType['subTitle'] = $subTitle;
        $data['recovered_all_type_1'] = $recoveredAllType;

        // ---------------------------- last campaign Absent recovered by campaign  ----------------------
        $recoveredAbsent = $this->chart->pieData(['VacAbsent3Days'=>'3Days',
                                                  'VacAbsentDay4'=>'Day5',
                                                  'RemAbsent'=>'Remaining'],
                                                            $campInfo);
        $recoveredAbsent['title'] = "Absent Children Recovery Camp/Revisit";
        $recoveredAbsent['subTitle'] = $subTitle;
        $data['recovered_absent_1'] = $recoveredAbsent;

        // ---------------------------- last campaign NSS recovered in Campaign ----------------------------
        $recoveredNss = $this->chart->pieData(['VacNSS3Days'=>'3Days',
                                                'VacNSSDay4'=>'Day5', 'RemNSS'=>'Remaining'],
                                                $campInfo);
        $recoveredNss['title'] = "NSS Children Recovery Camp/Revisit";
        $recoveredNss['subTitle'] = $subTitle;
        $data['recovered_nss_1'] = $recoveredNss;

        // --------------------------- last campaign Refusal recovered in Campaign -------------------------
        $recoveredRefusal = $this->chart->pieData(['VacRefusal3Days'=>'3Days',
                                                   'VacRefusalDay4'=>'Day5',
                                                   'RemRefusal'=>'Remaining'],
                                                           $campInfo);
        $recoveredRefusal['title'] = "Refusal Children Recovery Camp/Revisit";
        $recoveredRefusal['subTitle'] = $subTitle;
        $data['recovered_refusal_1'] = $recoveredRefusal;

        // ---------------------------- last campaign total missed by region/province/district -------------
        $vacWastage = $this->chart->chartData1Category(['column'=>$titles['aggType']],
                                                ['VacWastage'=>'Wastage'], $campAgg);
        $vacWastage['title'] = 'Vaccine wastage';
        $vacWastage['subTitle'] = $subTitle;
        $data['vaccine_wastage_1'] = $vacWastage;

        // ---------------------------- Tabular information of the campaign -------------------------------
        $table = HtmlTable::tableForAdminData($campAgg, $type);
        $data['info_table'] = $table;

        // ---------------------------- Header Tiles Information of the campaign --------------------------
        $info_header = HtmlTable::infoForAdminData($campInfo);
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
                'RemAbsent' => 'Absent',
                'RemNSS' => 'NSS',
                'RemRefusal' => 'Refusal',
                'MissedVaccinated' => 'Recovered',
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
        // get the clusters from the params as they needed for the table
        $clusters = $params['cluster'];

        // get the selectType from controlParams
        $selectType = $controlParams["selectType"];
        // set the calcTypeArray
        $calcTypeArray = ['type'=>'number'];
        if($controlParams['calcType'] === 'percent') {
            $calcTypeArray = ['type'=>'percent', 'column'=>'CalcTarget'];
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

        return new JsonResponse(['cluster_trend'=>$table]);
    }

    /**
     * @Route("/test_controller")
     */
    public function testAction() {
        $last10Camp = $this->setting->lastFewCampaigns("CatchupData", 6);
        $region = ['by'=> 'region', 'value' => ['ER', 'SR']];
        $province = ['by' => 'province', 'value'=> [33, 32], 'district'=>['VHR']];
        $district = ['by' => 'district', 'district' =>[3301, 3302, 3305]];
        $data = $this->fetchCampaignData("CatchupData", $last10Camp, $province);
        dump($data);
        die;

        

    }

}