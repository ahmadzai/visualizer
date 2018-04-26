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

        //$source = $charts->chartData('OdkSmMonitoring', 'aggByProvince', ['SR']);

        $xAxises = ['attendance', 'profile', 'tallying'];
        $yAxis = ['col'=>'pcode', 'label'=>'provinceName'];

        $source = $settings->getMonths("OdkSmMonitoring");

        $tenCampCatchupData = $charts->chartData("CatchupData",
            'campaignsStatisticsByRegion', [24, 23, 22], ['SER']);

        //$tenCampCatchupData = $charts->chartData("CoverageData",
            //'campaignsStatisticsByRegion', [24, 23, 22], ['SER']);

        //$source = $charts->heatMap($source, $xAxises, $yAxis, 'percent');


        return new Response(json_encode($tenCampCatchupData));

//        return $this->render("pages/test.html.twig",
//            ['testData' => json_encode([])]);

    }


}