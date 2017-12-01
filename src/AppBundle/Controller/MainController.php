<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/26/2017
 * Time: 11:20 AM
 */

namespace AppBundle\Controller;


use AppBundle\Service\Charts;
use AppBundle\Service\Settings;
use AppBundle\Entity\AdminData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Service\Importer;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{

//    /**
//     * @Route("/", name="home")
//     * @param Request $request
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
////    public function indexAction(Request $request) {
////        return $this->render('pages/index.html.twig');
////    }

    /**
     * @Route("/", name="home")
     * @param Request $request
     * @param Settings $settings
     * @param Charts $charts
     * @return Response
     */
    public function indexAction(Request $request, Settings $settings, Charts $charts) {

        // this function returns latest campaign, can work for all data sources that have relation with campaign
        $lastCamp = $settings->latestCampaign('AdminData');
        // this function takes two parameters 1:table name to be joined with campaign table, 2: how many campaigns
        // to be returned (optional) by default it returns the last 3 campaigns (only ids)
        $campaignIds = $settings->lastFewCampaigns('AdminData');

        $category = [['column'=>'Region'], ['column'=>'CID', 'substitute'=>['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']]];

        /**
         * The below method call is a dynamic function returning the data from different data-sources
         * however, you have to define a callMe() function in your Repository Class with the same structure as below
         * Then you would not need to call that function with Doctrine EntityManager, you just call chartData and pass
         * the tableName, functionName, and parameters for the original function in your repository
         */
        $regionAdminData = $charts->chartData('AdminData', 'regionAgg', $campaignIds);
        $lastCampAdminData = $charts->chartData('AdminData', 'campaignStatistics', $lastCamp[0]['id']);
        // Category 1 (name must be in the result set)
        // Category 2 (name must be in the result set)
        // Array of columns to show on chart (the index is the label and the value is the column name in the result set
        // Data returned above
        $missedChildChart = $charts->chartData2Categories($category[0], $category[1],
            ['RemainingRefusal'=>'Refusal',
                'RemainingNSS' => 'NSS', 'RemainingAbsent' => 'Absent'], $regionAdminData);
        $missedChildChart['title'] = "Remaining children by reasons";
        // For absent children
        $chartDataAbsent = $charts->chartData2Categories($category[0], $category[1],
            ['RemainingAbsent'=>'Remaining Absent',
                'VaccAbsent' => 'Vacc Absent'], $regionAdminData);
        $chartDataAbsent['title'] = "Recovering absent children during campaign";
        // For NSS
        $chartDataNss = $charts->chartData2Categories($category[0], $category[1],
            ['RemainingNSS'=>'Remaining NSS',
                'VaccNSS' => 'Vacc NSS'], $regionAdminData);
        $chartDataNss['title'] = "Recovering New born, sleep and sick children during campaign";
        // For Refusal
        $chartDataRefusal = $charts->chartData2Categories($category[0], $category[1],
            ['RemainingRefusal'=>'Remaining Refusal',
                'VaccRefusal' => 'Vacc Refusal'], $regionAdminData);
        $chartDataRefusal['title'] = "Recovering refusal children during campaign";
        $lastCampVaccUsageChart = $charts->chartData2Categories($category[0], $category[1],
            ['RVials'=>'ReceivedVials',
                'UVials' => 'UsedVials', 'VaccWastage' => 'Wastage'], $regionAdminData);
        $lastCampVaccUsageChart['title'] = "Vaccines usage";
        return $this->render("pages/index.html.twig",
            ['chart1data' => json_encode($missedChildChart),
                'chartDataAbsent' => json_encode($chartDataAbsent),
                'chartDataNss' => json_encode($chartDataNss),
                'chartDataRefusal' => json_encode($chartDataRefusal),
                'chartVaccineUsage' => json_encode($lastCampVaccUsageChart),
                'lastCampData' => $lastCampAdminData]);

    }


//    /**
//     * @Route("/admindata", name="admin_data")
//     * @param $request
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function adminDataAction(Request $request) {
//
//        return $this->render('pages/index.html.twig');
//    }


    /**
     * @Route("/test/{var}", name="testing")
     * @param $var
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction($var, Settings $settings) {

        $data = $settings->campaignMenu('AdminData');
        return new Response(json_encode($data));
    }


}