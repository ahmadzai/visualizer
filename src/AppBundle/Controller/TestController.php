<?php

namespace AppBundle\Controller;

use GuzzleHttp\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @Route("/test_api")
     */
    public function indexAction() {
        $client = new Client();
        $res = $client->post("http://localhost/visualizer/web/app_dev.php/api/token", [
            'auth' => ['wazir', 'this is not a pass']
        ]);

        $token = json_decode($res->getBody()->getContents());

        $data = $client->get("http://localhost/visualizer/web/app_dev.php/api/region", [
           'headers' => ['Authorization' => 'Bearer '.$token->token]
        ]);

        echo $data->getBody(); die;
    }


//    /**
//     * @Route("/test", name="testing")
//     * @param Request $request
//     * @param Charts $charts
//     * @param Settings $settings
//     * @param Triangle $triangle
//     * @return \Symfony\Component\HttpFoundation\Response*
//     * @Security("has_role('ROLE_ADMIN')")
//     */
//    public function testAction(Request $request, Charts $charts, Settings $settings, Triangle $triangle) {
//
//        /*
//        //Test for the General campaignStatistics Function
//        $province = ['by' => 'province', 'value' => [33, 6]];
//        $district = ['by' => 'district', 'district' =>
//                        [601, 602, 603]
//                    ];
//        $region = ['by' => 'region', 'value' => ['ER']];
//        //$regionState = $charts->chartData('CoverageData', 'aggByCampaign',
//            //[23], $region);
//        //dump($regionState);
//        //die;
//
//
//
//        // Test for the General aggByCampaign Function
//        $province = ['by' => 'province', 'value' => [33]];
//        $district = ['by' => 'district', 'district' =>
//                        [3301, 3302]
//                    ];
//        $region = ['by' => 'region', 'value' => ['SR']];
//        $regionState = $charts->chartData('CatchupData', 'aggByCampaign',
//            [23, 22], $region);
//        dump($regionState);
//        $aggData = $charts->chartData('CatchupData', 'aggByLocation',
//            [23, 22], $region);
//        dump($aggData);
//        die;
//        */
//
//
//        /*
//        $clusterAgg = $charts->chartData("CatchupData", 'clusterAggByLocation',
//                                         [23, 22], ['district'=>[3302, 3303]]);
//        dump($clusterAgg); die;
//        */
//
////        $regionData = $charts->chartData('CatchupData', 'regionAggByCampaignRegion',
////            [22], ['SR']);
////
////        return new Response(json_encode(['statistics'=>$regionState,
////            'region'=>$regionData]));
//
////
////        $xAxises = ['attendance', 'profile', 'tallying'];
////        $yAxis = ['col'=>'pcode', 'label'=>'provinceName'];
////
////        $source = $settings->getMonths("OdkSmMonitoring");
////
////        $tenCampCatchupData = $charts->chartData("CatchupData",
////            'campaignsStatisticsByRegion', [24, 23, 22], ['SER']);
////
//
//
////        $tenCampCatchupData = $charts->chartData("CoverageData",
////            'clusterAggByCampaign', [16, 17, 18, 19, 20, 21], ['district'=>[601, 3301]]);
//
////        $lastCampStackChart = $charts->chartData1Category(['column'=>'CID', 'substitute'=>['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']],
////            ['RemAbsent' => 'Absent',
////                'RemNSS' => 'NSS',
////                'RemRefusal' => 'Refusal'], $tenCampCatchupData);
////        $lastCampStackChart['title'] = 'Missed Children By Campaign';
////        $lastCampStackChart['subTitle'] = null;
//
////
////        //$source = $charts->heatMap($source, $xAxises, $yAxis, 'percent');
////
////
////        return new Response(json_encode($tenCampCatchupData));
//
//
////        return Exporter::exportCSV($tenCampCatchupData);
//
//
//        $map = Maps::loadGeoJson($this->getParameter('kernel.root_dir'));
//        return new JsonResponse($map);
//
//    }
}
