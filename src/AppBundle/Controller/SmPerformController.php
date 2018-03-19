<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/26/2017
 * Time: 11:20 AM
 */

namespace AppBundle\Controller;


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
 * Class SmPerformController
 * @package AppBundle\Controller
 * @Security("has_role('ROLE_USER')")
 */
class SmPerformController extends Controller
{

    /**
     * @return Response
     * @Route("/icn_monitoring/sm", name="icn_monitoring_sm")
     */
    public function indexAction(Charts $charts) {

        //$source = $charts->chartData('OdkSmMonitoring', 'aggByProvince', ['SR', 'CR', 'ER', 'SER', 'WR']);
        //$source = $charts->chartData('OdkSmMonitoring', 'aggByDistrict', [33, 1, 6, 13]);
        $source = $charts->chartData('OdkSmMonitoring', 'aggByCluster', [101, 601, 3301]);
        $xAxises = [
            ['col'=>'provinceName', 'label'=>'Province', 'calc'=>'none'],
            ['col'=>'attendance', 'label'=>'Attendance', 'calc'=>'normal'],
            ['col'=>'profile', 'label'=>'Profile', 'calc'=>'normal'],
            ['col'=>'preparedness', 'label'=>'Prepared', 'calc'=>'normal'],
            ['col'=>'trackingMissed', 'label'=>'Tracking Missed', 'calc'=>'normal'],
            ['col'=>'tallying', 'label'=>'Tallying', 'calc'=>'normal'],
            ['col'=>'mobilization', 'label'=>'Mobiliz', 'calc'=>'normal'],
            ['col'=>'advocacy', 'label'=>'Advocacy', 'calc'=>'normal'],
            ['col'=>'iecMaterial', 'label'=>'IEC Mat', 'calc'=>'normal'],
            ['col'=>'higherSup', 'label'=>'Supervision', 'calc'=>'none'],
            ['col'=>'refusalChallenge', 'label'=>'Refusal', 'calc'=>'rev'],
            ['col'=>'accessChallenge', 'label'=>'Access', 'calc'=>'rev'],
         ];

        $xAxises2 = [
            ['col'=>'provinceName', 'label'=>'Province', 'calc'=>'none'],
            ['col'=>'districtName', 'label'=>'District', 'calc'=>'none'],
            ['col'=>'attendance', 'label'=>'Attendance', 'calc'=>'normal'],
            ['col'=>'profile', 'label'=>'Profile', 'calc'=>'normal'],
            ['col'=>'preparedness', 'label'=>'Prepared', 'calc'=>'normal'],
            ['col'=>'trackingMissed', 'label'=>'Tracking Missed', 'calc'=>'normal'],
            ['col'=>'tallying', 'label'=>'Tallying', 'calc'=>'normal'],
            ['col'=>'mobilization', 'label'=>'Mobiliz', 'calc'=>'normal'],
            ['col'=>'advocacy', 'label'=>'Advocacy', 'calc'=>'normal'],
            ['col'=>'iecMaterial', 'label'=>'IEC Mat', 'calc'=>'normal'],
            ['col'=>'higherSup', 'label'=>'Supervision', 'calc'=>'none'],
            ['col'=>'refusalChallenge', 'label'=>'Refusal', 'calc'=>'rev'],
            ['col'=>'accessChallenge', 'label'=>'Access', 'calc'=>'rev'],
        ];

        $xAxises3 = [
            ['col'=>'provinceName', 'label'=>'Province', 'calc'=>'none'],
            ['col'=>'districtName', 'label'=>'District', 'calc'=>'none'],
            ['col'=>'cluster', 'label'=>'Cluster', 'calc'=>'none'],
            ['col'=>'attendance', 'label'=>'Attendance', 'calc'=>'normal'],
            ['col'=>'profile', 'label'=>'Profile', 'calc'=>'normal'],
            ['col'=>'preparedness', 'label'=>'Prepared', 'calc'=>'normal'],
            ['col'=>'trackingMissed', 'label'=>'Tracking Missed', 'calc'=>'normal'],
            ['col'=>'tallying', 'label'=>'Tallying', 'calc'=>'normal'],
            ['col'=>'mobilization', 'label'=>'Mobiliz', 'calc'=>'normal'],
            ['col'=>'advocacy', 'label'=>'Advocacy', 'calc'=>'normal'],
            ['col'=>'iecMaterial', 'label'=>'IEC Mat', 'calc'=>'normal'],
            ['col'=>'higherSup', 'label'=>'Supervision', 'calc'=>'none'],
            ['col'=>'refusalChallenge', 'label'=>'Refusal', 'calc'=>'rev'],
            ['col'=>'accessChallenge', 'label'=>'Access', 'calc'=>'rev'],
        ];

        $table = HtmlTable::tableODK($source, $xAxises3);
        return $this->render("pages/icn/clusters.html.twig", ['table'=>$table]);
    }

    /**
     * @param null $district
     * @return Response
     * @Route("/icn_monitoring/sm/clusters/{district}", name="cluster_icn_monitoring_sm", options={"expose"=true})
     */
    public  function clusterLevelAction($district = null)
    {
        $data = ['district' => $district === null ? 0 : $district];
        return $this->render("pages/icn/clusters.html.twig",
            $data
        );

    }


}