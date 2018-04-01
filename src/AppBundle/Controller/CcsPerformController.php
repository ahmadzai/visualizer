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
 * Class CcsPerformController
 * @package AppBundle\Controller
 * @Security("has_role('ROLE_USER')")
 */
class CcsPerformController extends Controller
{

    /**
     * @param Charts $charts
     * @param Settings $settings
     * @return Response
     * @Route("/icn_monitoring/ccs", name="icn_monitoring_ccs")
     */
    public function indexAction(Charts $charts, Settings $settings) {

        $months = $settings->getMonths('OdkCcsMonitoring');
        $month = $months[0]['monthYear']."-".$months[0]['monthNo'];
        $source = $charts->chartData('OdkCcsMonitoring', 'aggByRegionMonth', [$month]);

        $xAxises = [
            ['col'=>'mName', 'label'=>'Month', 'calc'=>'none'],
            ['col'=>'provinceName', 'label'=>'Prov', 'calc'=>'none'],
            ['col'=>'attendance', 'label'=>'Attend', 'calc'=>'normal'],
            ['col'=>'profile', 'label'=>'Profile', 'calc'=>'normal'],
            ['col'=>'preparedness', 'label'=>'Prepared', 'calc'=>'normal'],
            ['col'=>'mentoring', 'label'=>'Mentor', 'calc'=>'normal'],
            ['col'=>'trackingMissed', 'label'=>'Missed', 'calc'=>'normal'],
            ['col'=>'planningReview', 'label'=>'Planning', 'calc'=>'normal'],
            ['col'=>'mobilization', 'label'=>'Mobiliz', 'calc'=>'normal'],
            ['col'=>'advocacy', 'label'=>'Advoc', 'calc'=>'normal'],
            ['col'=>'iecMaterial', 'label'=>'IEC', 'calc'=>'normal'],
            ['col'=>'higherSup', 'label'=>'Super', 'calc'=>'none'],
            ['col'=>'refusalChallenge', 'label'=>'Refusal', 'calc'=>'rev'],
            ['col'=>'accessChallenge', 'label'=>'Access', 'calc'=>'rev'],
        ];


        $table = HtmlTable::tableODK($source, $xAxises);
        return $this->render("pages/icn/index.html.twig",
                                 ['table'=>$table, 'url'=>'ajax_icn_monitoring_ccs',
                                     'source'=>'OdkCcsMonitoring']);
    }



}