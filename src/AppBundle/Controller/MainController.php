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

/**
 * Class MainController
 * @package AppBundle\Controller
 * @Security("has_role('ROLE_USER')")
 */
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
        $lastCamp = $settings->latestCampaign('CoverageData');
        // this function takes two parameters 1:table name to be joined with campaign table, 2: how many campaigns
        // to be returned (optional) by default it returns the last 3 campaigns (only ids)
        $campaignIds = $settings->lastFewCampaigns('CoverageData');

        $category = [['column'=>'Region'], ['column'=>'CID', 'substitute'=>['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']]];

        /**
         * The below method call is a dynamic function returning the data from different data-sources
         * however, you have to define a callMe() function in your Repository Class with the same structure as below
         * Then you would not need to call that function with Doctrine EntityManager, you just call chartData and pass
         * the tableName, functionName, and parameters for the original function in your repository
         */
        //$regionAdminData = $charts->chartData('CoverageData', 'regionAgg', $campaignIds);
        $lastCampAdminData = $charts->chartData('CoverageData', 'campaignStatistics', $lastCamp[0]['id']);
        $lastCampRegionsData = $charts->chartData('CoverageData', 'regionAgg', $lastCamp[0]['id']);
//        // Category 1 (name must be in the result set)
//        // Category 2 (name must be in the result set)
//        // Array of columns to show on chart (the index is the label and the value is the column name in the result set
//        // Data returned above
//        $missedChildChart = $charts->chartData2Categories($category[0], $category[1],
//            ['RemRefusal'=>'Refusal',
//                'RemNSS' => 'NSS', 'RemAbsent' => 'Absent'], $regionAdminData);
//        $missedChildChart['title'] = "Remaining children by reasons";
//        // For absent children
//        $chartDataAbsent = $charts->chartData2Categories($category[0], $category[1],
//            ['RemAbsent'=>'Remaining Absent',
//                'VacAbsent' => 'Vac Absent'], $regionAdminData);
//        $chartDataAbsent['title'] = "Recovering absent children during campaign";
//        // For NSS
//        $chartDataNss = $charts->chartData2Categories($category[0], $category[1],
//            ['RemNSS'=>'Remaining NSS',
//                'VacNSS' => 'Vac NSS'], $regionAdminData);
//        $chartDataNss['title'] = "Recovering New born, sleep and sick children during campaign";
//        // For Refusal
//        $chartDataRefusal = $charts->chartData2Categories($category[0], $category[1],
//            ['RemRefusal'=>'Remaining Refusal',
//                'VacRefusal' => 'Vac Refusal'], $regionAdminData);
//        $chartDataRefusal['title'] = "Recovering refusal children during campaign";
//        $lastCampVaccUsageChart = $charts->chartData2Categories($category[0], $category[1],
//            ['RVials'=>'ReceivedVials',
//                'UVials' => 'UsedVials', 'VacWastage' => 'Wastage'], $regionAdminData);
//        $lastCampVaccUsageChart['title'] = "Vaccines usage";

        //Total Vac Children Last 10 Campaigns
        $campaignIds = $settings->lastFewCampaigns('CoverageData', $settings::NUM_CAMP_CHARTS);
        $tenCampAdminData = $charts->chartData('CoverageData', 'campaignsStatistics', $campaignIds);
        $tenCampVacChildChart = $charts->chartData1Category($category[1], ['TotalVac'=>'Vaccinated Children'], $tenCampAdminData);
        $tenCampVacChildChart['title'] = 'Vaccinated Children During Last 10 Campaigns';

        $tenCampMissedChildChart = $charts->chartData1Category($category[1], ['TotalRemaining'=>'Missed Children'], $tenCampAdminData);
        $tenCampMissedChildChart['title'] = 'Missed Children During Last 10 Campaigns';

        $tenCampMissedTypeChart = $charts->chartData1Category($category[1],
                                                                ['RemAbsent'=>'Absent', 'RemNSS'=>'NSS', 'RemRefusal'=>'Refusal'], $tenCampAdminData);
        $tenCampMissedTypeChart['title'] = 'Missed Children By Reason Last 10 Campaigns';

        // Last campaign missed by reason
        $lastCampMissedPieChart = $charts->pieData(['RemAbsent'=>'Absent', 'RemNSS'=>'NSS', 'RemRefusal'=>'Refusal'], $lastCampAdminData);
        $lastCampMissedPieChart['title'] = "Missed Children By Reason";

        // last campaign recovered all type by 3days, 4th day
        $lastCampRecovered = $charts->pieData(['Recovered3Days'=>'3Days', 'RecoveredDay4'=>'Day4', 'TotalRemaining'=>'Remaining'],
            $lastCampAdminData);
        $lastCampRecovered['title'] = "Missed Children Recovery Camp/Revisit";

        // last campaign Absent recovered by 3days and 4th day
        $lastCampAbsentRecovered = $charts->pieData(['VacAbsent3Days'=>'3Days', 'VacAbsentDay4'=>'Day4', 'RemAbsent'=>'Remaining'],
            $lastCampAdminData);
        $lastCampAbsentRecovered['title'] = "Absent Children Recovery Camp/Revisit";

        // last campaign NSS recovered by 3days and 4th day
        $lastCampNSSRecovered = $charts->pieData(['VacNSS3Days'=>'3Days', 'VacNSSDay4'=>'Day4', 'RemNSS'=>'Remaining'],
            $lastCampAdminData);
        $lastCampNSSRecovered['title'] = "NSS Children Recovery Camp/Revisit";

        // last campaign Refusal recovered by 3days and 4th day
        $lastCampRefusalRecovered = $charts->pieData(['VacRefusal3Days'=>'3Days', 'VacRefusalDay4'=>'Day4', 'RemRefusal'=>'Remaining'],
            $lastCampAdminData);
        $lastCampRefusalRecovered['title'] = "Refusal Children Recovery Camp/Revisit";

        // last campaign Refusal recovered by 3days and 4th day
        $last10CampRecovered = $charts->chartData1Category($category[1],['TotalRemaining'=>'Remaining',
                                                 'VacAbsent'=>'Recovered Absent',
                                                 'VacNSS'=>'Recovered NSS',
                                                 'VacRefusal'=>'Recovered Refusal'],
                                                $tenCampAdminData);
        $last10CampRecovered['title'] = "Recovering Missed Children By Reason During Last 10 Campaigns";

        // last campaign vaccine wastage by region
        $lastCampVaccineData = $charts->chartData1Category($category[0], ['VacWastage'=>'Wastage'], $lastCampRegionsData);
        $lastCampVaccineData['title'] = 'Regions Vaccine Wastage';
        return $this->render("pages/index.html.twig",
            [
//                'chart1data' => json_encode($missedChildChart),
//                'chartDataAbsent' => json_encode($chartDataAbsent),
//                'chartDataNss' => json_encode($chartDataNss),
//                'chartDataRefusal' => json_encode($chartDataRefusal),
//                'chartVaccineUsage' => json_encode($lastCampVaccUsageChart),
                'chartVacChild10Camp' => json_encode($tenCampVacChildChart),
                'chartMissed10Camp' => json_encode($tenCampMissedChildChart),
                'chartMissedType10camp' => json_encode($tenCampMissedTypeChart),
                'lastCampPieData' => json_encode($lastCampMissedPieChart),
                'lastCampVacData' => json_encode($lastCampVaccineData),
                'lastCampRegionData' => $lastCampRegionsData,
                'recoveredAll' => json_encode($lastCampRecovered),
                'recoveredAbsent' => json_encode($lastCampAbsentRecovered),
                'recoveredNSS' => json_encode($lastCampNSSRecovered),
                'recoveredRefusal' => json_encode($lastCampRefusalRecovered),
                'last10CampRecovered' => json_encode($last10CampRecovered),
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
     * @Route("/test", name="testing")
     * @param $var
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction(Request $request, Charts $charts, Settings $settings) {
//        $category = [['column'=>'Region'], ['column'=>'CID', 'substitute'=>['col1'=>'CMonth', 'col2'=>'CYear', 'short'=>'my']]];
        $campaignIds = $settings->lastFewCampaigns('CatchupData');
        $tenCampAdminData = $charts->chartData('CatchupData', 'clusterAgg', $campaignIds, 3301);
//
//        $otherFunction = $charts->chartData('CoverageData', 'campaignsStatistics', [$campaignIds[0]['id']]);
//
//        $data['single'] = $tenCampAdminData;
//        $data['plural'] = $otherFunction;
//        //$data = $charts->pieData(['RemAbsent'=>'Absent', 'RemNSS'=>'NSS', 'RemRefusal'=>'Refusal'], $tenCampAdminData);
//        //$data['title'] = 'Missed Children During Last 10 Campaigns';
////        $campaignIds = $settings->lastFewCampaigns('CoverageData', 10);
////        $data = $charts->chartData('CoverageData', 'clusterAgg', $campaignIds, 1510);
////        $data = $charts->clusterDataForHeatMap($data, 'RemAbsent', ['column'=>'CID', 'substitute' => 'CName']);
////        $data['title'] = 'Missed Children During Last 10 Campaigns';
////        $em = $this->getDoctrine()->getManager();
////        $data = $em->getRepository('AppBundle:District')
////            ->selectDistrictByProvince(6);
        return new Response(json_encode($tenCampAdminData));

//        return $this->render("pages/test.html.twig",
//            ['testData' => json_encode([])]);

    }


}