<?php
/**
 * Created by PhpStorm.
 * User: Awesome
 * Date: 8/12/2018
 * Time: 9:08 PM
 */

namespace AppBundle\Controller\Ajax;


use AppBundle\Service\Settings;
use Symfony\Component\HttpFoundation\Request;

abstract class CommonDashboardController extends DashController
{

    /**
     * @param Request $request
     * @param Settings $settings
     * @return mixed
     */
    public function mainAction(Request $request, Settings $settings) {

        $campaignIds = $request->get('campaign');
        $regions = $request->get('region');
        $provinces = $request->get('province');
        $districts = $request->get('district');
        $entity = $request->get('entity');
        $loadWhat = $request->get('loadWhat');


        /*
        $campaignIds = [24];
        $regions = [];
        $provinces = [];
        $districts = ['VHR'];
        $entity = "CoverageData";
        */

        $aggType = "Region";   // this just for the column header of the table info
        $subTitle = "";        // set subtitle
        $during = "Last 10 Campaigns";        // set mid title

        if(count($campaignIds)>1) {            // if some campaigns were selected, reset the mid title
            $subTitle = "for selected campaigns";
            $during = "Selected Campaigns";
        }


        // set district param
        $params['district'] = $districts;
        // default by campaign
        $params['by'] = 'campaign';

        // flag to know the requested filter is for districts
        $byDistrict = (count($districts)>0 &&
                       !in_array("None", $districts) &&
                       !in_array("HR", $districts) &&
                       !in_array("VHR", $districts)
                       );
        // if the filter was for district
        if($byDistrict) {
            // set the by and subtitle
            $params['by'] = 'district';
            $subTitle = 'for selected districts';

        } elseif(count($provinces) > 0) {               // if district was not set, check for provinces
            $subTitle = 'for selected provinces';
            $aggType = "Province";
            $params['by'] = 'province';
            $params['value'] = $provinces;              // set the value index as well
        } elseif (count($regions) > 0) {                // if provinces were not set, check for regions
            $subTitle = 'for selected regions';
            $params['by'] = 'region';
            $params['value'] = $regions;
        }

        // Just for changing the subtitle in case districts' group was selected
        if(count($districts) > 0) {
            $aggType = "District";
            // just to set subtitle as per filter
            if (in_array("None", $districts))
                $subTitle .= "' None-HR/VHRDs";
            else if (in_array("HR", $districts))
                $subTitle .= "' HRDs";
            else if (in_array("VHR", $districts))
                $subTitle .= "' VHRDs";
        }

        // set params to null if this was first request
        if($params['by'] === 'campaign' && count($districts) < 1)
            $params = null;

        // if this request was for info but if the campaignIds>1, 
        // in this case the info will never be called, to solve this 
        // we put latest campaign id in
        if($loadWhat === "info") {
            $campaignIds = count($campaignIds) > 1 ? [$this->lastCamp($entity)] : $campaignIds;
        }

        // if multiple campaigns were selected, means only update trends
        if(count($campaignIds) > 1 || $loadWhat === 'trend') {
            $campaignIds = count($campaignIds) > 1 ? $campaignIds :
                $settings->lastFewCampaigns($entity, Settings::NUM_CAMP_CHARTS);
            return $this->trendAction($entity, $campaignIds, $params,
                ['midTitle' => $during, 'subTitle' => $subTitle]);
        }
        // if one campaign was selected, means update one campaign information
        if(count($campaignIds) === 1 || $loadWhat === 'info') {
            // just set one latest campaign id in-case this was the first load
            // in this case campaign will have more keys
            return $this->latestInfoAction($entity, $campaignIds, $params,
                ['midTitle' => $during, 'subTitle' => $subTitle, 'aggType' => $aggType]);
        }


    }

    /**
     * @param Request $request
     * @param Settings $settings
     * @return mixed
     */
    public function clusterAction(Request $request, Settings $settings) {

        $campaigns = $request->get('campaign');
        $clusters = $request->get('cluster');
        $districts = $request->get('district');
        $districts = is_array($districts)?$districts:[$districts];
        $selectType = $request->get('selectType');
        $selectType = $selectType === null || $selectType === "" ? "TotalRemaining" : $selectType;

        $calcType = $request->get('calcType');

        $entity = $request->get('entity');

        if($calcType === "info") {
            return $this->clustersInfoAction($entity, $campaigns,
                ['district'=>$districts, 'cluster'=>$clusters]);
        } else {
            $campaigns = count($campaigns) > 1 ? $campaigns :
                         $settings->lastFewCampaigns($entity, Settings::NUM_CAMP_CLUSTERS);
            return $this->clustersTrendAction($entity, $campaigns,
                         ['district'=>$districts, 'cluster'=>$clusters],
                         ['calcType'=>$calcType, 'selectType'=>$selectType]);
        }

    }

    /**
     * @param $entity
     * @param $campaigns
     * @param $params
     * @param $titles
     * @return mixed
     */
    protected abstract function trendAction($entity, $campaigns, $params, $titles);

    /**
     * @param $entity
     * @param $campaigns
     * @param $params
     * @param $titles
     * @return mixed
     */
    protected abstract function latestInfoAction($entity, $campaigns, $params, $titles);

    protected abstract function clustersTrendAction($entity, $campaigns, $params, $controlParams);

    protected abstract function clustersInfoAction($entity, $campaigns, $params, $controlParams = null);


}