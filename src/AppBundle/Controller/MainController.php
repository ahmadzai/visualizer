<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/26/2017
 * Time: 11:20 AM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MainController
 * @package AppBundle\Controller
 * @Security("has_role('ROLE_NORMAL_USER') or has_role('ROLE_PARTNER')")
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
        $data['pageTitle'] = "Triangulated Data (Admin, Catchup and Refusals Committees' Data) Trends By Clusters";
        $data['source'] = 'CoverageData';
        $data['ajaxUrl'] = 'main_dashboard';
        return $this->render("pages/clusters-table.html.twig",
            $data
        );

    }


}