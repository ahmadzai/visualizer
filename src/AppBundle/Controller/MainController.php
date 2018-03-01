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

    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function indexAction() {
        return $this->render("pages/index.html.twig",[]
            );

    }

    /**
     * @param Request $request
     * @param null $district
     * @return Response
     * @Route("/main/clusters/{district}", name="cluster_main", options={"expose"=true})
     */
    public  function clusterLevelAction(Request $request, $district = null) {
        $data = ['district' => $district===null?0:$district];
        return $this->render("pages/clusters.html.twig",
            $data
        );

    }


    /**
     * @Route("/test", name="testing")
     * @param $var
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function testAction(Request $request, Charts $charts, Settings $settings, Triangle $triangle) {
//        $category = [['column'=>'Region'], ['column'=>'CID', 'substitute'=>['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']]];
        $campaignIds = $settings->lastFewCampaigns('CatchupData');
        $source = $charts->chartData('CoverageData', 'campaignsStatisticsByDistrict', [21], [3302]);
        $second = $charts->chartData('CatchupData', 'campaignsStatisticsByDistrict', [21], [3302]);

//        $tenCampData=$triangle->triangulateCustom([$source,
//            ['data'=>$second, 'indexes'=>['RegMissed', 'TotalRecovered']],
//            ['data'=>$second, 'indexes'=>['RegMissed', 'TotalRecovered'], 'prefix'=>'C']
//            ], 'joinkey');
        $tenCampData=Triangle::triangulateCustom([$source,
            ['data'=>$second, 'indexes'=>'all', 'prefix'=>'c']
        ], 'joinkey');

        //$tenCampData = Triangle::mathOps($tenCampData, ['TotalRemaining', 'TotalRecovered'],'-', 'RemAfterCatchup');
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