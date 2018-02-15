<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 10/13/2017
 * Time: 10:05 PM
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class CatchupDataRepository extends EntityRepository {


    protected static $DQL = " (SUM(COALESCE(cvr.regAbsent, 0)) + 
                               SUM(COALESCE(cvr.regNSS, 0)) + 
                               SUM(COALESCE(cvr.regRefusal,0))
                               ) AS RegMissed, 
                              SUM(COALESCE(cvr.regAbsent, 0)) as RegAbsent,
                              SUM(COALESCE(cvr.vacAbsent, 0)) as VacAbsent,
                              (SUM(COALESCE(cvr.regAbsent, 0)) - 
                               SUM(COALESCE(cvr.vacAbsent, 0))
                               ) as RemAbsent,
                              (SUM(COALESCE(cvr.vacAbsent, 0))/
                               SUM(COALESCE(cvr.regAbsent, 0))*100
                               ) as PerVacAbsent,
                              SUM(COALESCE(cvr.regNSS, 0)) as RegNSS,
                              SUM(COALESCE(cvr.vacNSS, 0)) as VacNSS,
                              (SUM(COALESCE(cvr.regNSS, 0)) -
                               SUM(COALESCE(cvr.vacNSS, 0))
                               )as RemNSS,
                              (SUM(COALESCE(cvr.vacNSS, 0))/
                               SUM(COALESCE(cvr.regNSS, 0))*100) as PerVacNSS,
                              SUM(COALESCE(cvr.regRefusal, 0)) as RegRefusal,
                              SUM(COALESCE(cvr.vacRefusal, 0)) as VacRefusal,
                              SUM(COALESCE(cvr.regRefusal, 0)) - SUM(COALESCE(cvr.vacRefusal, 0)) as RemRefusal,
                              (SUM(COALESCE(cvr.vacRefusal, 0))/SUM(COALESCE(cvr.regRefusal, 0))*100) as PerVacRefusal,
                              SUM(COALESCE(cvr.unRecorded, 0)) as UnRecorded,
                              SUM(COALESCE(cvr.vacUnRecorded, 0)) as VacUnRecorded,
                              SUM(COALESCE(cvr.unRecorded, 0)) - SUM(COALESCE(cvr.vacUnRecorded, 0)) as RemUnRecorded,
                              SUM(COALESCE(cvr.vacGuest, 0)) as VacGuest,
                              SUM(COALESCE(cvr.vacAbsent, 0)) + SUM(COALESCE(cvr.vacNSS, 0)) + SUM(COALESCE(cvr.vacRefusal, 0)) AS
                              TotalRecovered,
                              ((SUM(COALESCE(cvr.regAbsent, 0)) + 
                               SUM(COALESCE(cvr.regNSS, 0)) + 
                               SUM(COALESCE(cvr.regRefusal,0))
                               ) - (SUM(COALESCE(cvr.vacAbsent, 0)) + 
                                    SUM(COALESCE(cvr.vacNSS, 0)) + 
                                    SUM(COALESCE(cvr.vacRefusal, 0)))
                               ) As TotalRemaining,     
                              (SUM(COALESCE(cvr.vacAbsent, 0)) + 
                               SUM(COALESCE(cvr.vacNSS, 0)) + 
                               SUM(COALESCE(cvr.vacRefusal, 0)) +
                               SUM(COALESCE(cvr.vacUnRecorded, 0)) + 
                               SUM(COALESCE(cvr.vacGuest, 0))
                               ) AS TotalVac,
                              ((SUM(COALESCE(cvr.vacAbsent, 0)) + 
                                SUM(COALESCE(cvr.vacNSS, 0)) + 
                                SUM(COALESCE(cvr.vacRefusal, 0)))/
                                (SUM(COALESCE(cvr.regAbsent, 0)) + 
                                 SUM(COALESCE(cvr.regNSS, 0)) + 
                                 SUM(COALESCE(cvr.regRefusal, 0))) * 100)
                               AS PerRecovered
                            ";

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
                                FROM AppBundle:CatchupData cvr JOIN cvr.district dist 
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
                                FROM AppBundle:CatchupData cvr JOIN cvr.district dist 
                                WHERE (cvr.district IN (:districts))
                                ORDER BY cvr.subDistrict DESC ")
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
                                FROM AppBundle:CatchupData cvr JOIN cvr.district dist 
                                WHERE (cvr.district IN (:district) AND cvr.campaign IN (:campaigns))
                                ORDER BY cvr.subDistrict DESC ")
            ->setParameters(['district'=> $district, 'campaigns' => $campaigns])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaign
     * @return array
     */
    public function campaignStatistics($campaign) {
        return $this->getEntityManager()
            ->createQuery("SELECT cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp 
                  WHERE(cvr.campaign=:camp)") ->setParameter('camp', $campaign)
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
        $dq = $em->createQuery("SELECT cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp 
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
            ->createQuery("SELECT cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName, p.provinceRegion as Region, 
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp 
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
            ->createQuery("SELECT cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp 
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
            ->createQuery("SELECT cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
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
            ->createQuery("SELECT cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp 
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
            ->createQuery("SELECT cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp 
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
        $dq = $em->createQuery("SELECT cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp 
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
        $dq = $em->createQuery("SELECT cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp 
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
        $dq = $em->createQuery("SELECT cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp 
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
                "SELECT p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, 
                  d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, 
                  cmp.campaignName as CName,
                  cvr.subDistrict as Subdistrict, cvr.clusterNo as ClusterNo, cvr.clusterName as ClusterName, 
                  CASE 
                    WHEN cvr.subDistrict IS NULL 
                    THEN cvr.clusterNo 
                    ELSE CONCAT(cvr.subDistrict, '-', cvr.clusterNo)
                  END as Cluster,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
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
                "SELECT p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, 
                  d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, 
                  cmp.campaignName as CName,
                  cvr.subDistrict as Subdistrict, cvr.clusterNo as ClusterNo, cvr.clusterName as ClusterName,
                  CASE 
                    WHEN cvr.subDistrict IS NULL 
                    THEN cvr.clusterNo 
                    ELSE CONCAT(cvr.subDistrict, '-', cvr.clusterNo)
                  END as Cluster, 
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
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

//sum(cvr.regAbsent-cvr.vaccAbsent + cvr.regSleep-cvr.vaccSleep + cvr.regRefusal-cvr.vaccRefusal) as TotalRemaining
    /**
     * @param $campaign
     * @return array
     */
    public function districtAggByCampaign($campaign) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, 
                  d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
                  JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp))
                  GROUP BY p.id, cvr.district, cvr.campaign"
            )-> setParameters(['camp'=>$campaign])
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
                "SELECT p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, 
                  d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
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
                "SELECT p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, 
                  d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
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
                "SELECT p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, 
                  d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
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
                "SELECT p.provinceRegion as Region, p.id as PCODE, p.provinceName as Province, 
                   cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
                  JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp))
                  GROUP BY p.id, cvr.campaign"
            )-> setParameters(['camp'=>$campaign])
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
                "SELECT p.provinceRegion as Region, p.id as PCODE, p.provinceName as Province, 
                   cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
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
                "SELECT p.provinceRegion as Region, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
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
                "SELECT p.provinceRegion as Region, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  cmp.campaignName as CName,
                  ".self::$DQL."
                  FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
                  JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp) AND p.provinceRegion in (:region))
                  GROUP BY p.provinceRegion, cvr.campaign"
            )-> setParameters(['camp'=>$campaign, 'region'=>$region])
            ->getResult(Query::HYDRATE_SCALAR);

    }


    /**
     * @param $region
     * @return array
     */
    public function selectAggRegion($region) {

        return $this->getEntityManager()
            ->createQuery(
                "SELECT cvr.id as ID, p.provinceRegion as Region, p.provinceName as Province, 
                      d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                      cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, 
                      cmp.campaignName as CName,
                      cvr.clusterName as ClusterName, cvr.clusterNo as ClusterNo,
              ".self::$DQL."
              FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
              JOIN cvr.district d JOIN d.province p WHERE p.provinceRegion in (:region)
              GROUP BY cvr.clusterNo, p.provinceRegion"
            )-> setParameters(['region'=>$region])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $province
     * @return array
     */
    public function selectAggProvince($province) {

        return $this->getEntityManager()
            ->createQuery(
                "SELECT cvr.id as ID, p.provinceRegion as Region, p.provinceName as Province, 
                      d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                      cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, 
                      cmp.campaignName as CName,
                      cvr.clusterName as ClusterName, cvr.clusterNo as ClusterNo,
              ".self::$DQL."
              FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
              JOIN cvr.district d JOIN d.province p WHERE p.id in (:prov)
              GROUP BY cvr.cluster, p.id"
            )-> setParameters(['prov'=>$province])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $district
     * @return array
     */
    public function selectAggDistrict($district) {

        return $this->getEntityManager()
            ->createQuery(
                "SELECT cvr.id as ID, p.provinceRegion as Region, p.provinceName as Province, 
                      d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                      cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, 
                      cmp.campaignName as CName,
                      cvr.clusterName as ClusterName, cvr.clusterNo as ClusterNo,
                      ".self::$DQL."
                      FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
                      JOIN cvr.district d JOIN d.province p WHERE d.id in (:dist)
                      GROUP BY cvr.cluster, d.id"
            )-> setParameters(['dist'=>$district])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaign
     * @return array
     */
    public function selectAggCampaign($campaign) {

        return $this->getEntityManager()
            ->createQuery(
                "SELECT cvr.id as ID, p.provinceRegion as Region, p.provinceName as Province, 
                     d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                     cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, 
                     cmp.campaignName as CName,
                     cvr.clusterName as ClusterName, cvr.clusterNo as ClusterNo,
                      ".self::$DQL."
                      FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
                      JOIN cvr.district d JOIN d.province p WHERE cmp.id in (:camp)
                      GROUP BY cvr.cluster, cmp.id"
            )-> setParameters(['camp'=>$campaign])
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
                "SELECT cvr.id as ID, p.provinceRegion as Region, p.provinceName as Province, 
                d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
              cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, 
              cmp.campaignName as CName,
              cvr.clusterName as ClusterName, cvr.clusterNo as ClusterNo,
              ".self::$DQL."
              FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
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
                "SELECT cvr.id as ID, p.provinceRegion as Region, p.provinceName as Province, 
                d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
              cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, 
              cmp.campaignName as CName,
              cvr.clusterName as ClusterName, cvr.clusterNo as ClusterNo,
              ".self::$DQL."
              FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
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
                "SELECT cvr.id as ID, p.provinceRegion as Region, p.provinceName as Province, 
                d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
              cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, 
              cmp.campaignName as CName,
              cvr.clusterName as ClusterName, cvr.clusterNo as ClusterNo,
              ".self::$DQL."
              FROM AppBundle:CatchupData cvr JOIN cvr.campaign cmp
              JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp) AND d.id in (:dist))
              GROUP BY cvr.clusterNo, d.id, cmp.id"
            )-> setParameters(['camp'=>$campaign, 'dist' => $district])
            ->getResult(Query::HYDRATE_SCALAR);
    }

}
