<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 9/29/2017
 * Time: 4:23 PM
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\ORM\Query;

/**
 * @Security("has_role('ROLE_USER')")
 */
class AjaxController extends Controller
{
    /**
     * Get all Campaigns from Database to show in Select2-Filter.
     *
     * @param Request $request
     *
     * @Route("/campaigns", name="select2_campaign")
     *
     * @return JsonResponse|Response
     */
    public function select2AllCampaign(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            //$campaigns = $em->getRepository('AppBundle:Campaign')->findAll();
            $campaigns = $em->createQuery(
                "SELECT cmp.id, cmp.campaignName FROM AppBundle:Campaign cmp ORDER BY cmp.id DESC"
            )
                ->getResult(Query::HYDRATE_SCALAR);
            $result = array();

            foreach ($campaigns as $campaign) {
                $result[$campaign['id']] = $campaign['campaignName'];
            }

            //$result = array_reverse($result, true);

            return new JsonResponse($result);
        }

        return new Response('Bad request.', 400);
    }

    /**
     * Get all Districts from Database to show in Select2-Filter.
     *
     * @param Request $request
     *
     * @Route("/districts", name="select2_district")
     *
     * @return JsonResponse|Response
     */
    public function select2AllDistricts(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $districts = $em->getRepository('AppBundle:District')->findAll();

            $result = array();

            foreach ($districts as $district) {
                $result[$district->getId()] = $district->getDistrictName();
            }

            return new JsonResponse($result);
        }

        return new Response('Bad request.', 400);
    }

    /**
     * Get all Provinces from Database to show in Select2-Filter.
     *
     * @param Request $request
     *
     * @Route("/provinces", name="select2_province")
     *
     * @return JsonResponse|Response
     */
    public function select2AllProvinces(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $provinces = $em->getRepository('AppBundle:Province')->findAll();

            $result = array();

            foreach ($provinces as $province) {
                $result[$province->getId()] = $province->getProvinceName();
            }

            return new JsonResponse($result);
        }

        return new Response('Bad request.', 400);
    }


    /**
     * Get all Campaigns from Database to show in Select2-Filter.
     *
     * @param Request $request
     *
     * @Route("/campaigns_names", name="select2_campaign_names")
     *
     * @return JsonResponse|Response
     */
    public function select2AllCampaignNames(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            //$campaigns = $em->getRepository('AppBundle:Campaign')->findAll();
            $campaigns = $em->createQuery(
                "SELECT cmp.id, cmp.campaignName FROM AppBundle:Campaign cmp ORDER BY cmp.id DESC"
            )
                ->getResult(Query::HYDRATE_SCALAR);
            $result = array();

            foreach ($campaigns as $campaign) {
                $result[$campaign['campaignName']] = $campaign['campaignName'];
            }

            //$result = array_reverse($result, true);

            return new JsonResponse($result);
        }

        return new Response('Bad request.', 400);
    }

    /**
     * Get all Districts from Database to show in Select2-Filter.
     *
     * @param Request $request
     *
     * @Route("/districts_names", name="select2_district_names")
     *
     * @return JsonResponse|Response
     */
    public function select2AllDistrictsNames(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $districts = $em->getRepository('AppBundle:District')->findAll();

            $result = array();

            foreach ($districts as $district) {
                $result[$district->getDistrictName()] = $district->getDistrictName();
            }

            return new JsonResponse($result);
        }

        return new Response('Bad request.', 400);
    }

    /**
     * Get all Provinces from Database to show in Select2-Filter.
     *
     * @param Request $request
     *
     * @Route("/provinces_names", name="select2_province_names")
     *
     * @return JsonResponse|Response
     */
    public function select2AllProvincesNames(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $provinces = $em->getRepository('AppBundle:Province')->findAll();

            $result = array();

            foreach ($provinces as $province) {
                $result[$province->getProvinceName()] = $province->getProvinceName();
            }

            return new JsonResponse($result);
        }

        return new Response('Bad request.', 400);
    }


    /**
     * Get all Regions from Database to show in Select2-Filter.
     *
     * @param Request $request
     *
     * @Route("/regions", name="select2_region")
     *
     * @return JsonResponse|Response
     */
    public function select2AllRegions(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $regions = $em->createQuery(
                    "SELECT p.provinceRegion FROM AppBundle:Province p
                     GROUP BY p.provinceRegion ORDER BY p.provinceRegion"
                )
                ->getResult(Query::HYDRATE_SCALAR);
            $result = array();
            foreach ($regions as $region) {
                $result[$region['provinceRegion']] = $region['provinceRegion'];
            }

            return new JsonResponse($result);
        }

        return new Response('Bad request.', 400);
    }


}