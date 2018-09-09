<?php
/**
 * Created by PhpStorm.
 * User: Awesome
 * Date: 8/10/2018
 * Time: 9:37 AM
 */

namespace AppBundle\Repository\CoverageCatchup;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ChartRepo extends EntityRepository
{
    protected $DQL;
    protected $entity;

    public function setDQL($DQL) {
        $this->DQL = $DQL;
    }

    public function setEntity($entity) {
        $this->entity = $entity;
    }

    /**
     * @param $function the function must be pre-defined
     * @param $parameters the parameters must be defined in the function
     * @param null $secondParam
     * @return mixed
     */
    public function callMe($function, $parameters, $secondParam = null) {
        return call_user_func(array($this, $function), $parameters, $secondParam);
    }

    /**
     * @param $districts
     * @param $campaigns
     * @return array
     */
    public function clustersByDistrictCampaign($districts, $campaigns) {
        return $this->getEntityManager()
            ->createQuery("SELECT DISTINCT cvr.clusterNo, cvr.subDistrict,
                                CASE 
                                WHEN cvr.subDistrict IS NULL 
                                THEN cvr.clusterNo 
                                ELSE CONCAT(cvr.subDistrict, '|', cvr.clusterNo)
                                END as cluster,
                                dist.id, dist.districtName 
                                FROM AppBundle:".$this->entity." cvr JOIN cvr.district dist 
                                WHERE (cvr.district IN (:districts) AND cvr.campaign IN (:campaigns))
                                ORDER BY cvr.subDistrict DESC ")
            ->setParameters(['districts'=> $districts, 'campaigns' => $campaigns])
            ->getResult(Query::HYDRATE_SCALAR);
    }


    /**
     * @param $districts
     * @return array
     */
    public function clustersByDistrict($districts) {
        return $this->getEntityManager()
            ->createQuery("SELECT DISTINCT cvr.clusterNo, cvr.subDistrict, dist.id, dist.districtName,
                                CASE 
                                WHEN cvr.subDistrict IS NULL 
                                THEN cvr.clusterNo 
                                ELSE CONCAT(cvr.subDistrict, '|', cvr.clusterNo)
                                END as cluster
                                FROM AppBundle:".$this->entity." cvr JOIN cvr.district dist 
                                WHERE (cvr.district IN (:districts))
                                ORDER BY cvr.subDistrict DESC")
            ->setParameters(['districts'=> $districts])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $district
     * @param $campaigns
     * @return array
     */
    public function subDistrictByDistrict($district, $campaigns) {
        return $this->getEntityManager()
            ->createQuery("SELECT DISTINCT cvr.subDistrict, dist.id, dist.districtName
                                FROM AppBundle:".$this->entity." cvr JOIN cvr.district dist 
                                WHERE (cvr.district IN (:district) AND cvr.campaign IN (:campaigns))
                                ORDER BY cvr.subDistrict DESC ")
            ->setParameters(['district'=> $district, 'campaigns' => $campaigns])
            ->getResult(Query::HYDRATE_SCALAR);
    }


    /**
     * @param $campaigns
     * @param $settings
     * @return array of the campaign range statistics
     */
    public function campaignsStatistics($campaigns, $settings = null) {
        $condition = "";

        if(isset($settings['entity']) && $settings['entity'] == "district") {
            //$filter = $settings['filter'];
            $condition = " AND d.id IN (:filter)";
        } else if(isset($settings['entity']) && $settings['entity'] == "province") {
            //$filter = $settings['filter'];
            $condition = " AND p.id IN (:filter)";
        } else if(isset($settings['entity']) && $settings['entity'] == "region") {
            //$filter = $settings['filter'];
            $condition = " AND p.provinceRegion IN (:filter)";
        }

        $em = $this->getEntityManager();
        $dq = $em->createQuery("SELECT cmp.id as joinkey, cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp 
                  JOIN cvr.district d JOIN d.province p
                  WHERE(cvr.campaign IN (:camps) $condition)
                  GROUP BY cvr.campaign");
        $dq->setParameter('camps', $campaigns);

        if(isset($settings['filter'])) {
            $filter = $settings['filter'];
            $dq->setParameter("filter", $filter);
        }

        return $dq->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaigns
     * @param $regions
     * @return array of the campaign range statistics
     */
    public function campaignsStatisticsByRegion($campaigns, $regions) {
        return $this->getEntityManager()
            ->createQuery("SELECT cmp.id as joinkey, 
                  cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName, p.provinceRegion as Region, 
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp 
                  JOIN cvr.district d JOIN d.province p  
                  WHERE(cvr.campaign IN (:camps) AND p.provinceRegion IN (:regions))
                  GROUP BY cvr.campaign") ->setParameters(['camps'=>$campaigns, 'regions'=>$regions])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaigns
     * @param $province
     * @return array of the campaign range statistics
     */
    public function campaignsStatisticsByProvince($campaigns, $province) {
        return $this->getEntityManager()
            ->createQuery("SELECT cmp.id as joinkey,
                  cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp 
                  JOIN cvr.district d JOIN d.province p  
                  WHERE(cvr.campaign IN (:camps) AND p.id in (:province))
                  GROUP BY cvr.campaign") ->setParameters(['camps'=>$campaigns, 'province'=>$province])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaigns
     * @param $district
     * @return array of the campaign range statistics
     */
    public function campaignsStatisticsByDistrict($campaigns, $district) {
        return $this->getEntityManager()
            ->createQuery("SELECT cmp.id as joinkey,
                  cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp JOIN cvr.district d
                  WHERE(cvr.campaign IN (:camps) AND cvr.district IN (:district))
                  GROUP BY cvr.campaign") ->setParameters(['camps'=>$campaigns, 'district'=>$district])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param array $campaigns
     * @param array $risk
     * @return array of the campaign range statistics
     */
    public function campaignsStatisticsByDistrictRisk($campaigns, $risk) {
        $prov = $risk['province'];
        $risk = $risk['risk'];
        return $this->getEntityManager()
            ->createQuery("SELECT cmp.id as joinkey,
                  cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp 
                  JOIN cvr.district d JOIN d.province p 
                  WHERE(cvr.campaign IN (:camps) AND p.id in (:province) AND d.districtRiskStatus in (:risk) )
                  GROUP BY cvr.campaign") ->setParameters(['camps'=>$campaigns, 'province'=>$prov, 'risk' => $risk])
            ->getResult(Query::HYDRATE_SCALAR);
    }


    /**
     * @param array $campaigns
     * @param array $risk
     * @return array of the campaign range statistics
     */
    public function campaignsStatisticsByDistrictRiskNull($campaigns, $risk) {
        $prov = $risk['province'];
        $risk = $risk['risk'];
        return $this->getEntityManager()
            ->createQuery("SELECT cmp.id as joinkey,
                  cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp 
                  JOIN cvr.district d JOIN d.province p 
                  WHERE(cvr.campaign IN (:camps) AND p.id in (:province) AND d.districtRiskStatus IS NULL )
                  GROUP BY cvr.campaign") ->setParameters(['camps'=>$campaigns, 'province'=>$prov])
            ->getResult(Query::HYDRATE_SCALAR);
    }


    /**
     * @param $campaigns
     * @param array $settings
     * @return array of campaign statistics with the range of the campaigns which matches the campaign type
     */
    public function campaignsStatisticsByType($campaigns, $settings) {
        $campaignType = $settings['type'];
        $condition = "";

        if(isset($settings['entity']) && $settings['entity'] == "district") {
            //$filter = $settings['filter'];
            $condition = " AND d.id IN (:filter)";
        } else if(isset($settings['entity']) && $settings['entity'] == "province") {
            //$filter = $settings['filter'];
            $condition = " AND p.id IN (:filter)";
        } else if(isset($settings['entity']) && $settings['entity'] == "region") {
            //$filter = $settings['filter'];
            $condition = " AND p.provinceRegion IN (:filter)";
        }

        $em = $this->getEntityManager();
        $dq = $em->createQuery("SELECT cmp.id as joinkey,
                  cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp 
                  JOIN cvr.district d JOIN d.province p
                  WHERE(cvr.campaign IN (:camps) AND cmp.campaignType IN (:campType) $condition)
                  GROUP BY cvr.campaign");
        $dq->setParameters(['camps'=> $campaigns, 'campType' => $campaignType]);

        if(isset($settings['filter'])) {
            $filter = $settings['filter'];
            $dq->setParameter("filter", $filter);
        }

        return $dq->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $limit
     * @param $settings
     * @return array of campaign which length is equal to $limit
     */
    public function campaignsStatisticsByLimit($limit, $settings) {
        $condition = "";

        if(isset($settings['entity']) && $settings['entity'] == "district") {
            //$filter = $settings['filter'];
            $condition = "WHERE (d.id IN (:filter))";
        } else if(isset($settings['entity']) && $settings['entity'] == "province") {
            //$filter = $settings['filter'];
            $condition = "WHERE (p.id IN (:filter))";
        } else if(isset($settings['entity']) && $settings['entity'] == "region") {
            //$filter = $settings['filter'];
            $condition = "WHERE (p.provinceRegion IN (:filter))";
        }

        $em = $this->getEntityManager();
        $dq = $em->createQuery("SELECT cmp.id as joinkey,
                  cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp 
                  JOIN cvr.district d JOIN d.province p
                  $condition
                  GROUP BY cvr.campaign");
        $dq->setMaxResults($limit);

        if(isset($settings['filter'])) {
            $filter = $settings['filter'];
            //$condition = " AND d.id IN (".implode(",", $filter).")";
            //$dq->getEntityManager()->createQueryBuilder()->andWhere("d.id in (:districts)");
            $dq->setParameter("filter", $filter);
        }

        return $dq->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $limit
     * @param array(type, entity, filter) $settings
     * @return array of campaign which length is equal to $limit and matches the campaign type
     */
    public function campaignsStatisticsByTypeLimit($limit, $settings) {
        $campaignType = $settings['type'];
        $condition = "";

        if(isset($settings['entity']) && $settings['entity'] == "district") {
            //$filter = $settings['filter'];
            $condition = " AND d.id IN (:filter)";
        } else if(isset($settings['entity']) && $settings['entity'] == "province") {
            //$filter = $settings['filter'];
            $condition = " AND p.id IN (:filter)";
        } else if(isset($settings['entity']) && $settings['entity'] == "region") {
            //$filter = $settings['filter'];
            $condition = " AND p.provinceRegion IN (:filter)";
        }

        $em = $this->getEntityManager();
        $dq = $em->createQuery("SELECT cmp.id as joinkey,
                  cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp 
                  JOIN cvr.district d JOIN d.province p
                  WHERE(cmp.campaignType IN (:campType) $condition)
                  GROUP BY cvr.campaign");
        $dq->setParameter('campType', $campaignType);
        $dq->setMaxResults($limit);

        if(isset($settings['filter'])) {
            $filter = $settings['filter'];
            //$condition = " AND d.id IN (".implode(",", $filter).")";
            //$dq->getEntityManager()->createQueryBuilder()->andWhere("d.id in (:districts)");
            $dq->setParameter("filter", $filter);
        }

        return $dq->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param array $campaigns
     * @param int $district
     * @return array
     */
    public function clusterAgg($campaigns, $district) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT CASE 
                                WHEN cvr.subDistrict IS NULL 
                                THEN Concat(cmp.id, cvr.clusterNo, d.id) 
                                ELSE CONCAT(cmp.id, cvr.subDistrict, cvr.clusterNo, d.id)
                             END as joinkey,
                  p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, 
                  d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, 
                  cmp.campaignName as CName,
                  cvr.subDistrict as Subdistrict, cvr.clusterNo as ClusterNo, cvr.clusterName as ClusterName, 
                  CASE 
                    WHEN cvr.subDistrict IS NULL 
                    THEN cvr.clusterNo 
                    ELSE CONCAT(cvr.subDistrict, '-', cvr.clusterNo)
                  END as Cluster,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
                  JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp) AND cvr.district in (:dist))
                  GROUP BY cvr.campaign, cvr.district, cvr.subDistrict, cvr.clusterNo
                  ORDER BY cvr.subDistrict, cvr.clusterNo"
            ) -> setParameters(['camp'=>$campaigns, 'dist'=>$district])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param array $campaigns
     * @param int $district
     * @param string $subDistrict
     * @param array $clusters
     * @return array
     */
    public function clusterAggBySubDistrictCluster($campaigns, $district, $clusters, $subDistrict = '' ) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT CASE 
                                WHEN cvr.subDistrict IS NULL 
                                THEN Concat(cmp.id, cvr.clusterNo, d.id) 
                                ELSE CONCAT(cmp.id, cvr.subDistrict, cvr.clusterNo, d.id)
                             END as joinkey,
                  p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, 
                  d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, 
                  cmp.campaignName as CName,
                  cvr.subDistrict as Subdistrict, cvr.clusterNo as ClusterNo, cvr.clusterName as ClusterName,
                  CASE 
                    WHEN cvr.subDistrict IS NULL 
                    THEN cvr.clusterNo 
                    ELSE CONCAT(cvr.subDistrict, '-', cvr.clusterNo)
                  END as Cluster, 
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
                  JOIN cvr.district d JOIN d.province p 
                  WHERE(cvr.campaign in (:camp) 
                  AND cvr.district in (:dist)
                  AND (cvr.subDistrict IS NULL OR cvr.subDistrict = :subDist)
                  AND cvr.clusterNo IN (:clusters))
                  GROUP BY cvr.campaign, cvr.district, cvr.subDistrict, cvr.clusterNo
                  ORDER BY cvr.subDistrict, cvr.clusterNo"
            ) -> setParameters(['camp'=>$campaigns, 'dist'=>$district, 'subDist' => $subDistrict, 'clusters' => $clusters])
            ->getResult(Query::HYDRATE_SCALAR);
    }


    /**
     * @param $campaign
     * @return array
     */
    public function districtAggByCampaign($campaign) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT CONCAT(cmp.id, d.id) as joinkey, 
                  p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, 
                  d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
                  JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp))
                  GROUP BY p.id, cvr.district, cvr.campaign"
            )-> setParameters(['camp'=>$campaign])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaign
     * @param $province
     * @return array
     */
    public function districtAggByCampaignProvince($campaign, $province) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT CONCAT(cmp.id, d.id) as joinkey, 
                  p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, 
                  d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
                  JOIN cvr.district d JOIN d.province p 
                  WHERE(cvr.campaign in (:camp) AND p.id IN (:prov))
                  GROUP BY p.id, cvr.district, cvr.campaign"
            )-> setParameters(['camp'=>$campaign, 'prov'=>$province])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaign
     * @param $district
     * @return array
     */
    public function districtAggByCampaignDistrict($campaign, $district) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT CONCAT(cmp.id, d.id) as joinkey,
                  p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, 
                  d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
                  JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp) AND cvr.district in (:dist))
                  GROUP BY p.id, cvr.district, cvr.campaign"
            )-> setParameters(['camp'=>$campaign, 'dist'=>$district])
            ->getResult(Query::HYDRATE_SCALAR);
    }
    public function districtAggByCampaignDistrictRisk($campaign, $risk)  {
        $prov = $risk['province'];
        $risk = $risk['risk'];
        return $this->getEntityManager()
            ->createQuery(
                "SELECT CONCAT(cmp.id, d.id) as joinkey,
                  p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, 
                  d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
                  JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp) AND p.id in (:prov) 
                  AND (d.districtRiskStatus in (:risk)))
                  GROUP BY p.id, cvr.district, cvr.campaign"
            )-> setParameters(['camp'=>$campaign, 'risk'=>$risk, 'prov'=>$prov])
            ->getResult(Query::HYDRATE_SCALAR);
    }
    public function districtAggByCampaignDistrictRiskNull($campaign, $risk)  {
        $prov = $risk['province'];
        $risk = $risk['risk'];
        return $this->getEntityManager()
            ->createQuery(
                "SELECT CONCAT(cmp.id, d.id) as joinkey,
                  p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, 
                  d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
                  JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp) AND p.id in (:prov)
                  AND (d.districtRiskStatus in (:risk) OR d.districtRiskStatus IS NULL))
                  GROUP BY p.id, cvr.district, cvr.campaign"
            )-> setParameters(['camp'=>$campaign, 'risk'=>$risk, 'prov'=>$prov])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaign
     * @return array
     */
    public function provinceAggByCampaign($campaign) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT CONCAT(cmp.id, p.id) as joinkey,
                  p.provinceRegion as Region, p.id as PCODE, p.provinceName as Province, 
                  cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
                  JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp))
                  GROUP BY p.id, cvr.campaign"
            )-> setParameters(['camp'=>$campaign])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaign
     * @param $region
     * @return array
     */
    public function provinceAggByCampaignRegion($campaign, $region) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT CONCAT(cmp.id, p.id) as joinkey,
                  p.provinceRegion as Region, p.id as PCODE, p.provinceName as Province, 
                  cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
                  JOIN cvr.district d JOIN d.province p 
                  WHERE(cvr.campaign in (:camp) AND p.provinceRegion IN (:region))
                  GROUP BY p.id, cvr.campaign"
            )-> setParameters(['camp'=>$campaign, 'region'=>$region])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaign
     * @param $province
     * @return array
     */
    public function provinceAggByCampaignProvince($campaign, $province) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT CONCAT(cmp.id, p.id) as joinkey,
                  p.provinceRegion as Region, p.id as PCODE, p.provinceName as Province, 
                  cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
                  JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp) AND p.id in (:prov))
                  GROUP BY p.id, cvr.campaign"
            )-> setParameters(['camp'=>$campaign, 'prov'=>$province])
            ->getResult(Query::HYDRATE_SCALAR);
    }
    /**
     * @param $campaign
     * @return array
     */
    public function regionAgg($campaign) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT CONCAT(cmp.id, p.provinceRegion) as joinkey,
                  p.provinceRegion as Region, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
                  JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp))
                  GROUP BY p.provinceRegion, cvr.campaign"
            )-> setParameters(['camp'=>$campaign])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaign
     * @param $region
     * @return array
     */
    public function regionAggByCampaignRegion($campaign, $region) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT CONCAT(cmp.id, p.provinceRegion) as joinkey,
                  p.provinceRegion as Region, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
                  JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp) AND p.provinceRegion in (:region))
                  GROUP BY p.provinceRegion, cvr.campaign"
            )-> setParameters(['camp'=>$campaign, 'region'=>$region])
            ->getResult(Query::HYDRATE_SCALAR);

    }

    /**
     * @param $region
     * @param $campaign
     * @return array
     */

    public function regionAggByCampaigns($region, $campaign) {

        return $this->getEntityManager()
            ->createQuery(
                "SELECT CONCAT(cmp.id, p.provinceRegion) as joinkey,
                cvr.id as ID, p.provinceRegion as Region, p.provinceName as Province, 
                d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
              cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, 
              cmp.campaignName as CName,
              cvr.clusterName as ClusterName, cvr.clusterNo as ClusterNo,
              ".$this->DQL."
              FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
              JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp) AND p.provinceRegion in (:region))
              GROUP BY cvr.clusterNo, p.provinceRegion, cmp.id"
            )-> setParameters(['camp'=>$campaign, 'region' => $region])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $province
     * @param $campaign
     * @return array
     */
    public function provinceAggByCampaigns($province, $campaign) {

        return $this->getEntityManager()
            ->createQuery(
                "SELECT CONCAT(cmp.id, p.id) as joinkey,
                cvr.id as ID, p.provinceRegion as Region, p.provinceName as Province, 
                d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
              cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, 
              cmp.campaignName as CName,
              cvr.clusterName as ClusterName, cvr.clusterNo as ClusterNo,
              ".$this->DQL."
              FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
              JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp) AND p.id in (:prov))
              GROUP BY cvr.clusterNo, p.id, cmp.id"
            )-> setParameters(['camp'=>$campaign, 'prov' => $province])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $district
     * @param $campaign
     * @return array
     */
    public function districtAggByCampaigns($district, $campaign) {

        return $this->getEntityManager()
            ->createQuery(
                "SELECT CONCAT(cmp.id, d.id) as joinkey,
                cvr.id as ID, p.provinceRegion as Region, p.provinceName as Province, 
                d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
              cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, 
              cmp.campaignName as CName,
              cvr.clusterName as ClusterName, cvr.clusterNo as ClusterNo,
              ".$this->DQL."
              FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
              JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp) AND d.id in (:dist))
              GROUP BY cvr.clusterNo, d.id, cmp.id"
            )-> setParameters(['camp'=>$campaign, 'dist' => $district])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /*
     * New Methods, Once they are in used the old methods would be deleted
     */

    /**
     * @param $campaignIds
     * @param $params ['by' => 'region', 'value' => [regions], 'district'=>[]]
     * @return array
     */
    public function campaignStatistics($campaignIds, $params = ['by'=>'campaign', 'district'=>null]) {

        $condition = $this->createCondition($params);

        if ($params['by'] === "region") {
            $condition .= " AND p.provinceRegion IN (:param2) ";
        } elseif ($params['by'] === "province") {
            $condition .= " AND p.id IN (:param2) ";
        }

        $em = $this->getEntityManager();
        $dq = $em->createQuery("SELECT cmp.id as joinkey, cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp 
                  JOIN cvr.district d JOIN d.province p
                  WHERE(cvr.campaign IN (:camps) $condition)
                  GROUP BY cvr.campaign");
        $dq->setParameter('camps', $campaignIds);

        if(strpos($condition, "param1") !== false)
            $dq->setParameter("param1", $params['district']);
        if(strpos($condition, "param2") !== false)
            $dq->setParameter("param2", $params['value']);


        return $dq->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaignIds
     * @param $params ['by'=>'campaign', 'district'=>null, 'value'=>[]]
     * @return mixed
     */
    public function aggByCampaign($campaignIds, $params = ['by'=>'campaign', 'district'=>null]) {

        $joinKey = "cmp.id, p.provinceRegion";
        $select = ""; // default select has region
        $groupBy = ", p.provinceRegion";
        // set the condition
        $condition = $this->createCondition($params);

        if ($params['by'] === "region") {
            $condition .= " AND p.provinceRegion IN (:param2)";
        } elseif ($params['by'] === "province") {
            $joinKey = "cmp.id, p.id";
            $select = "p.id as PCODE, p.provinceName as Province, ";
            $groupBy = " ,p.id";
            $condition .= " AND p.id IN (:param2)";
        }

        // check if the district was not null
        if(isset($params['district'])) {
            $joinKey = "cmp.id, d.id";
            $groupBy = " ,p.id, cvr.district";
            $select = "p.provinceName as Province, d.districtName as District, d.id as DCODE, ";
        }

        $em = $this->getEntityManager();
        $dq = $em->createQuery(
            "SELECT CONCAT(".$joinKey.") as joinkey, 
                  p.provinceRegion as Region, ".$select."
                  cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
                  JOIN cvr.district d JOIN d.province p 
                  WHERE(cvr.campaign in (:campaign) ".$condition.")
                  GROUP BY cvr.campaign".$groupBy);
        $dq->setParameter('campaign', $campaignIds);

        if(strpos($condition, "param1") !== false)
            $dq->setParameter("param1", $params['district']);
        if(strpos($condition, "param2") !== false)
            $dq->setParameter("param2", $params['value']);

        return $dq->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaignIds
     * @param $params ['by'=>'campaign', 'district'=>null, 'value'=>[]]
     * @return mixed
     */
    public function aggByLocation($campaignIds, $params = ['by'=>'campaign', 'district'=>null]) {

        $joinKey = "p.provinceRegion";
        $select = ""; // default select has region
        $groupBy = "p.provinceRegion";
        $condition = "";

        if ($params['by'] === "region") {
            $condition .= " AND p.provinceRegion IN (:param2)";
        } elseif ($params['by'] === "province") {
            $joinKey = "p.id";
            $select = "p.id as PCODE, p.provinceName as Province, ";
            $groupBy = "p.id";
            $condition .= " AND p.id IN (:param2)";
        }

        // check if the district was not null
        if(isset($params['district'])) {
            $joinKey = "d.id";
            $groupBy = "cvr.district";
            $select = "p.provinceName as Province, d.districtName as District, d.id as DCODE, ";
        }

        $em = $this->getEntityManager();
        $dq = $em->createQuery(
            "SELECT ".$joinKey." as joinkey, 
                  p.provinceRegion as Region, ".$select." 
                  ".$this->DQL."
                  FROM AppBundle:".$this->entity." cvr
                  JOIN cvr.district d JOIN d.province p 
                  WHERE(cvr.campaign in (:campaign) ".$condition.")
                  GROUP BY ".$groupBy);
        $dq->setParameter('campaign', $campaignIds);

        if(strpos($condition, "param1") !== false)
            $dq->setParameter("param1", $params['district']);
        if(strpos($condition, "param2") !== false)
            $dq->setParameter("param2", $params['value']);

        return $dq->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaigns
     * @param $params ['district', 'param' => ['subdist', 'cluster']]
     * @return mixed
     */
    public function clusterAggByCampaign($campaigns, $params) {
        // considering that district key should always be set in $params
        $condition = " AND cvr.district in (:district) ";
        /*
         The district key is required the others are optional
         $params = ['district' => [], 'param' => ['subdist', 'cluster']]
         */
        if(isset($params['param'])) {
            $param = $params['param'];
            if(isset($param['subdist']))
                $condition .= "AND (cvr.subDistrict IS NULL OR cvr.subDistrict = :subdist) ";
            if(isset($param['cluster']))
                $condition .= "AND cvr.clusterNo IN (:cluster) ";
        }

        $em = $this->getEntityManager();
        $dq = $em->createQuery(
            "SELECT CASE 
                            WHEN cvr.subDistrict IS NULL 
                            THEN Concat(cmp.id, d.id, cvr.clusterNo) 
                            ELSE CONCAT(cmp.id, d.id, cvr.subDistrict, cvr.clusterNo)
                         END as joinkey,
              p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, 
              d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
              cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, 
              cmp.campaignName as CName,
              cvr.subDistrict as Subdistrict, cvr.clusterNo as ClusterNo,
              CASE 
                WHEN cvr.subDistrict IS NULL 
                THEN cvr.clusterNo 
                ELSE CONCAT(cvr.subDistrict, '-', cvr.clusterNo)
              END as Cluster, 
              ".$this->DQL."
              FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
              JOIN cvr.district d JOIN d.province p 
              WHERE(cvr.campaign in (:campaign) 
              ".$condition.")
              GROUP BY cvr.campaign, cvr.district, cvr.subDistrict, cvr.clusterNo
              ORDER BY cvr.subDistrict, cvr.clusterNo"
        );
        // setting the campaigns
        $dq -> setParameter('campaign', $campaigns);
        // setting the district
        $dq -> setParameter('district', $params['district']);
        // check if additional key was there
        if(isset($params['param'])) {
            $param = $params['param'];
            // check and set the subdistrict
            if(isset($param['subdist']))
                $dq -> setParameter('subdist', $param['subdist']);
            // check and set the cluster
            if(isset($param['cluster']))
                $dq -> setParameter('cluster', $param['cluster']);
        }

        return $dq->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaigns
     * @param $params ['district', 'param' => ['subdist', 'cluster']]
     * @return mixed
     */
    public function clusterAggByLocation($campaigns, $params) {
        // considering that district key should always be set in $params
        $condition = " AND cvr.district in (:district) ";
        /*
         The district key is required the others are optional
         $params = ['district' => [], 'params' => ['subdist', 'cluster']]
         */
        if(isset($params['param'])) {
            $param = $params['param'];
            if(isset($param['subdist']))
                $condition .= "AND (cvr.subDistrict IS NULL OR cvr.subDistrict = :subdist) ";
            if(isset($param['cluster']))
                $condition .= "AND cvr.clusterNo IN (:cluster)) ";
        }

        $em = $this->getEntityManager();
        $dq = $em->createQuery(
            "SELECT CASE 
                            WHEN cvr.subDistrict IS NULL 
                            THEN Concat(d.id, cvr.clusterNo) 
                            ELSE CONCAT(d.id, cvr.subDistrict, cvr.clusterNo)
                         END as joinkey,
              p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, 
              d.id as DCODE, cvr.subDistrict as Subdistrict, cvr.clusterNo as ClusterNo,
              CASE 
                WHEN cvr.subDistrict IS NULL 
                THEN cvr.clusterNo 
                ELSE CONCAT(cvr.subDistrict, '-', cvr.clusterNo)
              END as Cluster, 
              ".$this->DQL."
              FROM AppBundle:".$this->entity." cvr JOIN cvr.campaign cmp
              JOIN cvr.district d JOIN d.province p 
              WHERE(cvr.campaign in (:campaign) 
              ".$condition.")
              GROUP BY cvr.district, cvr.subDistrict, cvr.clusterNo
              ORDER BY cvr.subDistrict, cvr.clusterNo"
        );
        // setting the campaigns
        $dq -> setParameter('campaign', $campaigns);
        // setting the district
        $dq -> setParameter('district', $params['district']);
        // check if additional key was there
        if(isset($params['param'])) {
            $param = $params['param'];
            // check and set the subdistrict
            if(isset($param['subdist']))
                $dq -> setParameter('subdist', $param['subdist']);
            // check and set the cluster
            if(isset($param['cluster']))
                $dq -> setParameter('cluster', $param['cluster']);
        }

        return $dq->getResult(Query::HYDRATE_SCALAR);
    }

    private function createCondition($param) {
        $cond = "";
        $district = isset($param['district']) ? $param['district'] : [];
        if(count($district)>0) {
            $distType = $param['district'];
            if (in_array("None", $distType)) {
                $cond = "AND d.districtRiskStatus IS NULL";
            } elseif(in_array("HR", $distType) || in_array("VHR", $distType)) {
                $cond = "AND d.districtRiskStatus IN (:param1) ";
            } else
                $cond = "AND d.id IN (:param1)";
        }
        return $cond;
    }




}