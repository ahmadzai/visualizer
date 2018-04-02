<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 1/23/2018
 * Time: 7:50 PM
 */

namespace AppBundle\Controller\Ajax;

use AppBundle\Service\Settings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;


class AjaxFilterController extends Controller
{

    /**
     * @param Request $request
     * @param string $source
     * @param Settings $settings
     * @Route("/smallFilter/{source}", name="small_filter")
     * @Method("GET")selectProvinceByRegion
     * @return Response
     */
    public function smallFilterAction(Request $request, $source="CoverageData", Settings $settings)
    {


        $em = $this->getDoctrine()->getManager();

        $selectedCampaigns = $settings->lastFewCampaigns($source, 1);

        $campaigns = $em->getRepository('AppBundle:Campaign')->selectCampaignBySource($source);

        $regions = $em->getRepository('AppBundle:Province')->selectAllRegions();

        return $this->render("shared/filter-small.html.twig", ['campaigns' => $campaigns, 'regions' => $regions, 'selectedCampaign' => $selectedCampaigns]);
    }

    /**
     * @Route("/clusterFilter/{source}/{district}", name="small_filter_cluster")
     * @param $source
     * @param $district
     * @param $request
     * @param $settings
     * @return Response
     * @Method("GET")selectProvinceByRegion
     */
    public function clusterFilterAction(Request $request, $source="CoverageData", $district = 0, Settings $settings)
    {


        $em = $this->getDoctrine()->getManager();

        $latestCampaign = $settings->lastFewCampaigns($source, 1);

        $campaigns = $em->getRepository('AppBundle:Campaign')->selectCampaignBySource($source);

        $provinces = $em->getRepository("AppBundle:Province")->findAll();
        $data = [
            'campaigns' => $campaigns,
            'provinces' => $provinces,
            'selectedCampaign' => $latestCampaign,
            'source' => $source
        ];

        if($district !== 0) {
            $province = $em->getRepository('AppBundle:District')->findOneBy(['id' => $district]);
            $data['selectedProvince'] = $province->getProvince()->getId();
            $data['districts'] = $em->getRepository("AppBundle:District")->findBy(['province'=>$province->getProvince()->getId()]);
            $data['selectedDistrict'] = $province->getId();
            $campaignIds = $settings->lastFewCampaigns($source, $settings::NUM_CAMP_CLUSTERS);
            $clusterRaw = $em->getRepository("AppBundle:".$source)->clustersByDistrictCampaign([$province->getId()], $campaignIds);
            $data['clusters'] = $this->clustersJSON($clusterRaw,  true);
        }



        return $this->render("shared/filter-small-cluster.html.twig",
            $data
        );
    }

    /**
     * @param Request $request
     * @param $source
     * @param Settings $settings
     * @return Response
     */
    public function odkFilterAction(Request $request, $source, Settings $settings) {


        $months = $settings->getMonths($source);

        $selectedMonth = date('Y-m');
        if(count($months) > 0)
            $selectedMonth = $months[0]['monthYear']."-".$months[0]['monthNo'];

        $provinces = $settings->selectProvinceBySource($source);

        return $this->render("shared/filter-odk.html.twig", ['months' => $months, 'provinces' => $provinces, 'selectedMonth' => [$selectedMonth]]);

    }

    /**
     * @Route("filter/odk_district", name="filter_district_odk")
     * @param Request $request
     * @return Response
     */
    public function odkFilterDistrictAction(Request $request, Settings $settings) {

        $province = $request->get('province');
        $source = $request->get('source');

        $district = $settings->selectDistrictBySource($source, $province);

        $response = array();
        foreach ($province as $prov) {
            $temp = array();
            $pname = '';
            foreach ($district as $option) {

                if($option['pid'] == $prov[0]) {
                    $pname = $option['provinceName'];
                    $temp[] = array('label' => $option['districtName'], 'value'=>$option['id']);
                }
            }

            $response[] = array('label'=>$pname, 'children'=>$temp);
        }

        return new Response(json_encode($response));

    }

    /**
     * @Route("filter/province", name="filter_province")
     * @param Request $request
     * @return Response
     */
    public function filterProvinceAction(Request $request) {

        $region = $request->get('region');
        //$requestData = json_decode($content);


        //return new Response(var_dump($content));

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('AppBundle:Province')
            ->selectProvinceByRegion($region);

        $response = array();

        foreach ($region as $reg) {
            $temp = array();
            foreach ($data as $option) {
                if($option['p_provinceRegion'] == $reg[0]) {
                    $temp[] = array('label' => $option['p_provinceName'], 'value' => $option['p_id']);
                    //$response .= "<option value='".$option['p_provinceCode']."'>".$option['p_provinceName']."</option>";
                }
            }

            $response[] = array('label'=>$reg[0], 'children'=>$temp);
        }



        return new Response(
            json_encode($response)
        );

    }

    /**
     * @Route("filter/district", name="filter_district")
     * @param Request $request
     * @return Response
     */
    public function filterDistrictAction(Request $request) {

        $province = $request->get('province');
        $isRiskEnable = $request->get("risk");
        //$requestData = json_decode($content);


        //return new Response(var_dump($content));

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('AppBundle:District')
            ->selectDistrictByProvince($province);

        $response = array();
        $flag_vhr = false;
        $flag_hr = false;
        foreach ($province as $prov) {
            $temp = array();
            $pname = '';
            foreach ($data as $option) {
                if($option['d_districtRiskStatus'] == "VHR")
                    $flag_vhr = true;
                if($option['d_districtRiskStatus'] == "HR")
                    $flag_hr = true;
                if($option['id'] == $prov[0]) {
                    $pname = $option['provinceName'];
                    $temp[] = array('label' => $option['d_districtName'], 'value'=>$option['d_id']);
                    //$response .= "<option value='".$option['p_provinceCode']."'>".$option['p_provinceName']."</option>";
                }
            }

            $response[] = array('label'=>$pname, 'children'=>$temp);
        }

        $newResponse = array();
        $moreOptions = array();
        if($isRiskEnable === true || $isRiskEnable === null) {
            if ($flag_vhr)
                $moreOptions[] = array('label' => 'VHR districts', 'value' => 'VHR');
            if ($flag_hr) {
                $moreOptions[] = array('label' => 'HR districts', 'value' => 'HR');
            }
            if (count($response) > 0 && ($flag_hr || $flag_vhr)) {
                $moreOptions[] = array('label' => 'Non-V/HR districts', 'value' => null);

                $newResponse = array_merge($moreOptions, $response);
            }
        }


        return new Response(
            json_encode((count($newResponse)>count($response)?$newResponse:$response))
        );

    }


    /**
     * @Route("filter/district_norisk", name="filter_district_norisk")
     * @param Request $request
     * @return Response
     */
    public function filterDistrictWithoutRiskStatusAction(Request $request) {

        $province = $request->get('province');
        //$requestData = json_decode($content);


        //return new Response(var_dump($content));

        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('AppBundle:District')
            ->selectDistrictByProvince($province);

        $response = array();
        $flag_vhr = false;
        $flag_hr = false;
        foreach ($province as $prov) {
            $temp = array();
            $pname = '';
            foreach ($data as $option) {
                if($option['d_districtRiskStatus'] == "VHR")
                    $flag_vhr = true;
                if($option['d_districtRiskStatus'] == "HR")
                    $flag_hr = true;
                if($option['id'] == $prov[0]) {
                    $pname = $option['provinceName'];
                    $temp[] = array('label' => $option['d_districtName'], 'value'=>$option['d_id']);
                    //$response .= "<option value='".$option['p_provinceCode']."'>".$option['p_provinceName']."</option>";
                }
            }

            $response[] = array('label'=>$pname, 'children'=>$temp);
        }

        return new Response(
            json_encode($response)
        );

    }

    /**
     * @Route("filter/cluster", name="filter_cluster")
     * @param Request $request
     * @return Response
     */
    public function filterClusterAction(Request $request, Settings $settings) {

        $district = $request->get('district');
        $campaign = $request->get('campaign');
        $source = $request->get('source');


        $em = $this->getDoctrine()->getManager();

        $campaigns = array();
        if(count($campaign) > 1) {
            foreach($campaign as $item) {
                $campaigns[] = (int) $item;
            }
        } else {
            $campaigns = $settings->lastFewCampaigns($source, $settings::NUM_CAMP_CLUSTERS);

        }
        //$requestData = json_decode($content);


        //return new Response(var_dump($content));

        $data = $em->getRepository('AppBundle:'.$source)
            ->clustersByDistrictCampaign($district, $campaigns);

        return new Response($this->clustersJSON($data));

    }

    /**
     * @Route("filter/odk_cluster", name="filter_cluster_odk")
     * @param Request $request
     * @return Response
     */
    public function odkFilterClusterAction(Request $request, Settings $settings) {

        $district = $request->get('district');
        $campaign = $request->get('campaign');
        $source = $request->get('source');


        $em = $this->getDoctrine()->getManager();

        $campaigns = array();
        if(count($campaign) > 0) {
            foreach($campaign as $item) {
                $campaigns[] = $item;
            }
        }


        //return new Response(var_dump($content));

        $data = $em->getRepository('AppBundle:'.$source)
            ->clustersByDistrictMonth($district, $campaigns);

        return new Response($this->clustersJSON($data, false));

    }

    private function clustersJSON($clusters, $firstLoad = true) {
        $subDist = array();
        foreach($clusters as $cluster) {
            if(array_key_exists('subDistrict', $cluster))
                $subDist[] = ($cluster['subDistrict'] === null ? $cluster['districtName'] : $cluster['subDistrict']);
            else
                $subDist[] = $cluster['districtName'];

        }

        $subDist = array_unique($subDist);

        $response = array();

        foreach ($subDist as $sub) {
            $temp = array();
            foreach ($clusters as $option) {
                if(array_key_exists('subDistrict', $option) && $option['subDistrict'] == $sub) {
                    $temp[] = array('label' => $option['clusterNo'], 'value' => $sub."|".$option['clusterNo'], 'selected' => $firstLoad);
                } else if($option['districtName'] == $sub) {
                    $temp[] = array('label' => $option['clusterNo'], 'value' => $option['clusterNo'], 'selected' => $firstLoad);
                }
            }

            $response[] = array('label'=>$sub, 'children'=>$temp);
        }

        return json_encode($response);

    }





}