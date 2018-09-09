<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/26/2017
 * Time: 11:20 AM
 */

namespace AppBundle\Controller;


use AppBundle\Service\Charts;
use AppBundle\Service\Exporter;
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

    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function indexAction() {
        return $this->render("pages/index.html.twig",
            [
                'source'=>'CoverageData',
                'url' => 'main_dashboard',
                'urlCluster' => "main_cluster_dashboard"
            ]
            );

    }

    /**
     * @param Request $request
     * @param null $district
     * @return Response
     * @Route("/main/clusters/{district}", name="main_cluster_dashboard", options={"expose"=true})
     */
    public  function clusterLevelAction(Request $request, $district = null) {

        $data = ['district' => $district===null?0:$district];
        $data['title'] = 'Triangulated Clusters Trends';
        $data['pageTitle'] = "Triangulated Data (Admin, Catchup) Trends By Clusters";
        $data['source'] = 'CoverageData';
        $data['ajaxUrl'] = 'main_dashboard';
        return $this->render("pages/clusters-table.html.twig",
            $data
        );

    }


    /**
     * @Route("/test", name="testing")
     * @param Request $request
     * @param Charts $charts
     * @param Settings $settings
     * @param Triangle $triangle
     * @return \Symfony\Component\HttpFoundation\Response* @Security("has_role('ROLE_ADMIN')")
     */
    public function testAction(Request $request, Charts $charts, Settings $settings, Triangle $triangle) {


        /*
        //Test for the General campaignStatistics Function
        $province = ['by' => 'province', 'value' => [33, 6]];
        $district = ['by' => 'district', 'district' =>
                        [601, 602, 603]
                    ];
        $region = ['by' => 'region', 'value' => ['ER']];
        //$regionState = $charts->chartData('CoverageData', 'aggByCampaign',
            //[23], $region);
        //dump($regionState);
        //die;



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


        $tenCampCatchupData = $charts->chartData("CoverageData",
            'clusterAggByCampaign', [16, 17, 18, 19, 20, 21], ['district'=>[601, 3301]]);

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


        return Exporter::exportCSV($tenCampCatchupData);

    }


}