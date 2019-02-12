<?php

namespace AppBundle\Controller;

use AppBundle\Service\Settings;
use GuzzleHttp\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\Charts;
use AppBundle\Service\Triangle;

class TestController extends Controller
{

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @Route("/test_api")
     */
    public function indexAction(Settings $settings) {

//        $campaign = null;
//        if($campaign === null) {
//            $campaign = $campaign ?? $settings->latestCampaign("CatchupData");
//            $campaign = $campaign[0]['id'];
//        }
//        $data = $this->getDoctrine()->getRepository("AppBundle:CatchupData")
//            ->aggByCampaign([$campaign], ['by'=>'district', 'district'=>['all']]);

//        dump($data); die;

        $client = new Client();
        $res = $client->post("http://afg-poliodb.info/api/token", [
            'auth' => ['rabia.sadat', 'a password']
        ]);

        $token = json_decode($res->getBody()->getContents());

        // Test below for admin data api
        $data = $client->get("http://afg-poliodb.info/api/admindata/by_district/30", [
           'headers' => ['Authorization' => 'Bearer '.$token->token]
        ]);

        // Test below for campaign api
//        $data = $client->get("http://localhost/visualizer/web/app_dev.php/api/campaign/all", [
//            'headers' => ['Authorization' => 'Bearer '.$token->token]
//        ]);

        // Test below for catchup data api
//        $data = $client->get("http://localhost/visualizer/web/app_dev.php/api/catchup/by_district", [
//            'headers' => ['Authorization' => 'Bearer '.$token->token]
//        ]);

        echo $data->getBody(); die;
    }


    /**
     * @Route("/test", name="testing")
     * @param Request $request
     * @param Charts $charts
     * @param Settings $settings
     * @param Triangle $triangle
     * @return Response
     */
    public function testAction(Request $request, Charts $charts, Settings $settings, Triangle $triangle) {


        //Test for the General campaignStatistics Function
        $province = ['by' => 'province', 'value' => [33, 6]];
        $district = ['by' => 'district', 'district' =>
                        [1701,1702,1703,1704,1705,1706,1707]
                    ];
        $region = ['by' => 'region', 'value' => ['ER']];
        $regionState = $charts->chartData('CoverageData', 'aggByCampaign',
            [31], $district);

        $missedByReasonPie = $charts->pieData(['RemAbsent'=>'Absent',
            'RemNSS'=>'NSS',
            'RemRefusal'=>'Refusal'], $regionState);
        $missedByReasonPie['title'] = "Remaining Children By Reason";
        $data['missed_by_reason_pie_1'] = $missedByReasonPie;

        dump($data);
        die;
        /*


        // Test for the General aggByCampaign Function
        $province = ['by' => 'province', 'value' => [33]];
        $district = ['by' => 'district', 'district' =>
                        [3301, 3302]
                    ];
        $region = ['by' => 'region', 'value' => ['SR']];
        $regionState = $charts->chartData('CatchupData', 'aggByCampaign',
            [23, 22], $region);
        dump($regionState);
        $aggData = $charts->chartData('CatchupData', 'aggByLocation',
            [23, 22], $region);
        dump($aggData);
        die;
        */


        /*
        $clusterAgg = $charts->chartData("CatchupData", 'clusterAggByLocation',
                                         [23, 22], ['district'=>[3302, 3303]]);
        dump($clusterAgg); die;
        */

//        $regionData = $charts->chartData('CatchupData', 'regionAggByCampaignRegion',
//            [22], ['SR']);
//
//        return new Response(json_encode(['statistics'=>$regionState,
//            'region'=>$regionData]));

//
//        $xAxises = ['attendance', 'profile', 'tallying'];
//        $yAxis = ['col'=>'pcode', 'label'=>'provinceName'];
//
//        $source = $settings->getMonths("OdkSmMonitoring");
//
//        $tenCampCatchupData = $charts->chartData("CatchupData",
//            'campaignsStatisticsByRegion', [24, 23, 22], ['SER']);
//


//        $tenCampCatchupData = $charts->chartData("CoverageData",
//            'clusterAggByCampaign', [16, 17, 18, 19, 20, 21], ['district'=>[601, 3301]]);

//        $lastCampStackChart = $charts->chartData1Category(['column'=>'CID', 'substitute'=>['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']],
//            ['RemAbsent' => 'Absent',
//                'RemNSS' => 'NSS',
//                'RemRefusal' => 'Refusal'], $tenCampCatchupData);
//        $lastCampStackChart['title'] = 'Missed Children By Campaign';
//        $lastCampStackChart['subTitle'] = null;

//
//        //$source = $charts->heatMap($source, $xAxises, $yAxis, 'percent');
//
//
//        return new Response(json_encode($tenCampCatchupData));


//        return Exporter::exportCSV($tenCampCatchupData);


//        $map = Maps::loadGeoJson($this->getParameter('kernel.root_dir'));
//        return new JsonResponse($map);

    }
}
