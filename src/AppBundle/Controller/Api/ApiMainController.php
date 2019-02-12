<?php
/**
 * Created by PhpStorm.
 * User: Awesome
 * Date: 1/29/2019
 * Time: 11:16 AM
 */

namespace AppBundle\Controller\Api;


use AppBundle\Service\Settings;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiMainController
 * @package AppBundle\Controller\Api
 * @Route("api")
 * @Security("has_role('ROLE_USER')")
 */
class ApiMainController extends Controller
{
    /**
     * @Route("/admindata/by_district/{campaign}")
     * @param Settings $settings
     * @param int $campaign
     * @return Response
     * @Method("GET")
     */
    public function getAdminDataByDistricts(Settings $settings, $campaign = null) {

        if($campaign === null) {
            $campaign = $campaign ?? $settings->latestCampaign("CoverageData");
            $campaign = $campaign[0]['id'];
        }
        $data = $this->getDoctrine()
            ->getRepository("AppBundle:CoverageData")
            ->aggByCampaign([$campaign], ['by'=>'district', 'district'=>['all']]);
        $data = $this->jsonEncode([
            'campaign'=>$campaign,
            'districts'=>$data
        ]);
        return new Response($data, 200);
    }

    /**
     * @param Settings $settings
     * @param string $campaign
     * @return Response
     * @Route("/campaign/{campaign}")
     * @Method("GET")
     */
    public function getCampaign(Settings $settings, $campaign = "latest") {

        if($campaign === "all") {
            $campaigns = $settings->campaignMenu("CoverageData");
            $campaigns = $this->jsonEncode(['campaigns'=>$campaigns]);

            return new Response($campaigns, 200);
        } else if($campaign === "latest") {
            $campaign = $settings->latestCampaign("CoverageData");
            $campaign = $this->jsonEncode(['campaign'=>$campaign]);

            return new Response($campaign, 200);
        }

    }

    /**
     * @param Settings $settings
     * @param null $campaign
     * @return Response
     * @Route("/catchup/by_district/{campaign}")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getCatchupDataByDistrict(Settings $settings, $campaign = null) {
        if($campaign === null) {
            $campaign = $campaign ?? $settings->latestCampaign("CatchupData");
            $campaign = $campaign[0]['id'];
        }
        $data = $this->getDoctrine()
            ->getRepository("AppBundle:CatchupData")
            ->aggByCampaign([$campaign], ['by'=>'district', 'district'=>['all']]);
        $data = $this->jsonEncode([
            'campaign'=>$campaign,
            'districts'=>$data
        ]);
        return new Response($data, 200);
    }




    private function jsonEncode($data) {
        return $this->get('jms_serializer')->serialize($data, "json");
    }


}