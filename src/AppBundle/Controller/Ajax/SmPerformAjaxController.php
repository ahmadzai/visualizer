<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/26/2017
 * Time: 11:20 AM
 */

namespace AppBundle\Controller\Ajax;


use AppBundle\Datatables\CatchupDataDatatable;
use AppBundle\Service\Charts;
use AppBundle\Service\HtmlTable;
use AppBundle\Service\Settings;
use AppBundle\Service\Triangle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class CcsPerformController
 * @package AppBundle\Controller
 * @Security("has_role('ROLE_USER')")
 */
class SmPerformAjaxController extends Controller
{

    /**
     * @return Response
     * @Route("api/icn_monitoring/sm", name="ajax_icn_monitoring_sm")
     * @Method("GET")
     */
    public function indexAction(Request $request, Charts $charts) {

        $selectedMonths = $request->get('campaign');
        $regions = $request->get('region');
        $provinces = $request->get('province');
        $districts = $request->get('district');
        $clusters = $request->get('cluster');
        $cumulative = $request->get('cumulative');

        $xAxises = [
            ['col'=>'mName', 'label'=>'Month', 'calc'=>'none'],
            ['col'=>'provinceName', 'label'=>'Prov', 'calc'=>'none'],
            ['col'=>'attendance', 'label'=>'Attend', 'calc'=>'normal'],
            ['col'=>'profile', 'label'=>'Profile', 'calc'=>'normal'],
            ['col'=>'preparedness', 'label'=>'Prepared', 'calc'=>'normal'],
            ['col'=>'trackingMissed', 'label'=>'Missed', 'calc'=>'normal'],
            ['col'=>'tallying', 'label'=>'Tally', 'calc'=>'normal'],
            ['col'=>'mobilization', 'label'=>'Mobiliz', 'calc'=>'normal'],
            ['col'=>'advocacy', 'label'=>'Advoc', 'calc'=>'normal'],
            ['col'=>'iecMaterial', 'label'=>'IEC', 'calc'=>'normal'],
            ['col'=>'higherSup', 'label'=>'Super', 'calc'=>'none'],
            ['col'=>'refusalChallenge', 'label'=>'Refusal', 'calc'=>'rev'],
            ['col'=>'accessChallenge', 'label'=>'Access', 'calc'=>'rev'],
        ];

        //$function = "aggByRegionMonth";
        $funcPart1 = "aggBy";
        $funcPart2 = "Region";

        $firstArg = $selectedMonths;

        if($clusters !== null && count($clusters)>0) {
            $firstArg = ['district'=> $districts, 'cluster'=>$clusters];
            //$function = "aggBySmMonth";
            $funcPart2 = "Sm";
            array_splice($xAxises, 2, 0,
                [['col'=>'districtName', 'label'=>'Dist', 'calc'=>'none'],
                    ['col'=>'cluster', 'label'=>'Clstr', 'calc'=>'none'],
                    ['col'=>'ccsName', 'label'=>'CCS', 'calc'=>'none'],
                    ['col'=>'smName', 'label'=>'SM', 'calc'=>'none']]);

        } else if($districts !== null && count($districts)>0) {
            $firstArg = $districts;
            //$function = "aggByClusterMonth";
            $funcPart2 = "Cluster";
            array_splice($xAxises, 2, 0,
                   [['col'=>'districtName', 'label'=>'Dist', 'calc'=>'none'],
                    ['col'=>'cluster', 'label'=>'Clstr', 'calc'=>'none']]);
        } else if($provinces !== null && count($provinces)>0) {
            $firstArg = $provinces;
            //$function = "aggByDistrictMonth";
            $funcPart2 = "District";
            array_splice($xAxises, 2, 0,
                [['col'=>'districtName', 'label'=>'Dist', 'calc'=>'none']]);
        } else if($regions !== null && count($regions)>0) {
            //$function = "aggByProvinceMonth";
            $funcPart2 = "Province";
            $firstArg = $regions;
        }

        $function = $funcPart1;
        if($cumulative === true || $cumulative == "true") {
            unset($xAxises[0]); // remove month name
            $function .= "Month" . $funcPart2;
        }
        else
            $function .= $funcPart2."Month";

        $source = $charts->chartData('OdkSmMonitoring', $function, $firstArg, $selectedMonths);

        $table = HtmlTable::tableODK($source, $xAxises);

        return new Response($table);
        //return $this->render("pages/icn/clusters.html.twig", ['table'=>$table, 'source'=>'OdkCcsMonitoring']);
    }



}