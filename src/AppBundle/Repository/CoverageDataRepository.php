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

class CoverageDataRepository extends EntityRepository {


    protected static $DQL = " sum(cvr.vialsReceived) as RVials, sum(cvr.vialsUsed) as UVials,
                   ((COALESCE(sum(cvr.vialsUsed), 0)*20 -
                   (COALESCE(sum(cvr.noChildInHouseVac), 0) +COALESCE(sum(cvr.noChildOutsideVac), 0)+
                    COALESCE(sum(cvr.noAbsentSameDayFoundVac), 0)+ COALESCE(sum(cvr.noAbsentSameDayVacByTeam), 0) +
                    COALESCE(sum(cvr.noAbsentNotSameDayFoundVac), 0) + COALESCE(sum(cvr.noAbsentNotSameDayVacByTeam), 0) +
                    COALESCE(sum(cvr.noNSSFoundVac), 0) + COALESCE(sum(cvr.noNSSVacByTeam),0) +
                    COALESCE(sum(cvr.noRefusalFoundVac),0) + COALESCE(sum(cvr.noRefusalVacByTeam),0))) /
                    (COALESCE(sum(cvr.vialsUsed), 0) *20) * 100)
                    as VacWastage,
                  (COALESCE(sum(cvr.targetChildren),0))/4 as Target,
                  (COALESCE(sum(cvr.noChildInHouseVac),0)+
                    COALESCE(sum(cvr.noChildOutsideVac),0)+
                    sum( case WHEN (cvr.vacDay = 1 OR cvr.vacDay = 2 OR cvr.vacDay = 3)
                        THEN 
                        (COALESCE(cvr.noAbsentSameDay,0) + 
                        COALESCE(cvr.noAbsentNotSameDay,0) + 
                        COALESCE(cvr.noNSS,0) + 
                        COALESCE(cvr.noRefusal,0))
                        ELSE 0
                    end)
                    ) as CalcTarget,
                  (COALESCE(sum(cvr.noChildInHouseVac),0)+COALESCE(sum(cvr.noChildOutsideVac),0)+
                    COALESCE(sum(cvr.noAbsentSameDayFoundVac),0) +
                    COALESCE(sum(cvr.noAbsentSameDayVacByTeam),0)+ COALESCE(sum(cvr.noAbsentNotSameDayFoundVac),0) + 
                    COALESCE(sum(cvr.noAbsentNotSameDayVacByTeam),0) + COALESCE(sum(cvr.noNSSFoundVac),0) + 
                    COALESCE(sum(cvr.noNSSVacByTeam),0) + COALESCE(sum(cvr.noRefusalFoundVac),0) + 
                    COALESCE(sum(cvr.noRefusalVacByTeam),0)) as TotalVac,  
                  sum(cvr.noChildInHouseVac) as InHouseVac, sum(cvr.noChildOutsideVac) as OutsideVac,
                  (COALESCE(sum(cvr.noAbsentSameDayFoundVac),0) + COALESCE(sum(cvr.noAbsentSameDayVacByTeam),0)+
                   COALESCE(sum(cvr.noAbsentNotSameDayFoundVac),0) + COALESCE(sum(cvr.noAbsentNotSameDayVacByTeam),0) + 
                   COALESCE(sum(cvr.noNSSFoundVac),0) + COALESCE(sum(cvr.noNSSVacByTeam),0) +
                   COALESCE(sum(cvr.noRefusalFoundVac),0) + COALESCE(sum(cvr.noRefusalVacByTeam),0)) as MissedVaccinated,
                  sum(
                    CASE
                      WHEN (cvr.vacDay = 1 OR cvr.vacDay = 2 OR cvr.vacDay = 3)
                      THEN (COALESCE(cvr.noAbsentSameDay,0) + COALESCE(cvr.noAbsentNotSameDay,0)) ELSE 0
                    END
                  ) as RegAbsent,
                  sum(COALESCE(cvr.noAbsentSameDayFoundVac,0) +
                      COALESCE(cvr.noAbsentSameDayVacByTeam,0) +
                      COALESCE(cvr.noAbsentNotSameDayFoundVac,0) +
                      COALESCE(cvr.noAbsentNotSameDayVacByTeam,0)) as VacAbsent,
                  sum(
                    CASE
                      WHEN (cvr.vacDay = 1 OR cvr.vacDay = 2 OR cvr.vacDay = 3)
                      THEN
                      (COALESCE(cvr.noAbsentSameDayFoundVac,0) +
                       COALESCE(cvr.noAbsentSameDayVacByTeam,0) +
                       COALESCE(cvr.noAbsentNotSameDayFoundVac,0) +
                       COALESCE(cvr.noAbsentNotSameDayVacByTeam,0))
                      ELSE
                       0
                    END   
                       ) as VacAbsent3Days,    
                  sum(
                    CASE
                      WHEN (cvr.vacDay = 4)
                      THEN
                      (COALESCE(cvr.noAbsentSameDayFoundVac,0) +
                       COALESCE(cvr.noAbsentSameDayVacByTeam,0) +
                       COALESCE(cvr.noAbsentNotSameDayFoundVac,0) +
                       COALESCE(cvr.noAbsentNotSameDayVacByTeam,0))
                      ELSE
                       0
                    END   
                       ) as VacAbsentDay4,     
                  sum(
                    CASE
                      WHEN (cvr.vacDay = 1 OR cvr.vacDay = 2 OR cvr.vacDay = 3)
                      THEN (COALESCE(cvr.noAbsentSameDay,0) + COALESCE(cvr.noAbsentNotSameDay,0)) ELSE 0
                    END
                  ) -
                  sum(COALESCE(cvr.noAbsentSameDayFoundVac,0) +
                      COALESCE(cvr.noAbsentSameDayVacByTeam,0) +
                      COALESCE(cvr.noAbsentNotSameDayFoundVac,0) +
                      COALESCE(cvr.noAbsentNotSameDayVacByTeam,0)) as RemAbsent,    
                      
                  sum(
                    CASE
                      WHEN (cvr.vacDay = 1 OR cvr.vacDay = 2 OR cvr.vacDay = 3)
                      THEN COALESCE(cvr.noNSS,0) ELSE 0
                    END
                  ) as RegNSS,
                  sum(COALESCE(cvr.noNSSFoundVac,0) + COALESCE(cvr.noNSSVacByTeam,0)) as VacNSS,
                  sum(
                    CASE
                      WHEN (cvr.vacDay = 1 OR cvr.vacDay = 2 OR cvr.vacDay = 3)
                      THEN (COALESCE(cvr.noNSSFoundVac,0) + COALESCE(cvr.noNSSVacByTeam,0))
                      ELSE 0
                    END     
                   ) as VacNSS3Days,
                   sum(
                    CASE
                      WHEN (cvr.vacDay = 4)
                      THEN (COALESCE(cvr.noNSSFoundVac,0) + COALESCE(cvr.noNSSVacByTeam,0))
                      ELSE 0
                    END     
                   ) as VacNSSDay4,
                  sum(
                    CASE
                      WHEN (cvr.vacDay = 1 OR cvr.vacDay = 2 OR cvr.vacDay = 3)
                      THEN COALESCE(cvr.noNSS,0) ELSE 0
                    END
                  ) -
                  sum(COALESCE(cvr.noNSSFoundVac,0) + COALESCE(cvr.noNSSVacByTeam,0)) as RemNSS,

                  sum(
                    CASE
                      WHEN (cvr.vacDay = 1 OR cvr.vacDay = 2 OR cvr.vacDay = 3)
                      THEN COALESCE(cvr.noRefusal, 0) ELSE 0
                    END
                  ) as RegRefusal,
                  sum(COALESCE(cvr.noRefusalFoundVac,0) + COALESCE(cvr.noRefusalVacByTeam,0)) as VacRefusal,
                  sum(
                    CASE
                      WHEN (cvr.vacDay = 1 OR cvr.vacDay = 2 OR cvr.vacDay = 3)
                      THEN (COALESCE(cvr.noRefusalFoundVac,0) + COALESCE(cvr.noRefusalVacByTeam,0))
                      ELSE 0
                    END     
                   ) as VacRefusal3Days,
                   sum(
                    CASE
                      WHEN (cvr.vacDay = 4)
                      THEN (COALESCE(cvr.noRefusalFoundVac,0) + COALESCE(cvr.noRefusalVacByTeam,0))
                      ELSE 0
                    END     
                   ) as VacRefusalDay4,
                  sum(
                    CASE
                      WHEN (cvr.vacDay = 1 OR cvr.vacDay = 2 OR cvr.vacDay = 3)
                      THEN COALESCE(cvr.noRefusal, 0) ELSE 0
                    END
                  ) -
                  sum(COALESCE(cvr.noRefusalFoundVac,0) + COALESCE(cvr.noRefusalVacByTeam,0)) as RemRefusal,
                  sum(
                    CASE
                      WHEN (cvr.vacDay = 1 OR cvr.vacDay = 2 OR cvr.vacDay = 3)
                      THEN (COALESCE(cvr.noAbsentSameDayFoundVac,0) +
                            COALESCE(cvr.noAbsentSameDayVacByTeam,0) +
                            COALESCE(cvr.noAbsentNotSameDayFoundVac,0) +
                            COALESCE(cvr.noAbsentNotSameDayVacByTeam,0) + 
                            COALESCE(cvr.noNSSFoundVac,0) + 
                            COALESCE(cvr.noNSSVacByTeam,0) + 
                            COALESCE(cvr.noRefusalFoundVac,0) +
                            COALESCE(cvr.noRefusalVacByTeam,0))
                      ELSE 0
                    END     
                   ) as Recovered3Days,
                   sum(
                    CASE
                      WHEN (cvr.vacDay = 4)
                      THEN (COALESCE(cvr.noAbsentSameDayFoundVac,0) +
                            COALESCE(cvr.noAbsentSameDayVacByTeam,0) +
                            COALESCE(cvr.noAbsentNotSameDayFoundVac,0) +
                            COALESCE(cvr.noAbsentNotSameDayVacByTeam,0) + 
                            COALESCE(cvr.noNSSFoundVac,0) + 
                            COALESCE(cvr.noNSSVacByTeam,0) + 
                            COALESCE(cvr.noRefusalFoundVac,0) +
                            COALESCE(cvr.noRefusalVacByTeam,0))
                      ELSE 0
                    END     
                   ) as RecoveredDay4,
                  sum( case WHEN (cvr.vacDay = 1 OR cvr.vacDay = 2 OR cvr.vacDay = 3)
                            THEN 
                            (COALESCE(cvr.noAbsentSameDay,0) + 
                            COALESCE(cvr.noAbsentNotSameDay,0) + 
                            COALESCE(cvr.noNSS,0) + 
                            COALESCE(cvr.noRefusal,0))
                            ELSE 0
                        end) - 
                   sum ( 
                         COALESCE(cvr.noAbsentSameDayFoundVac,0) +
                         COALESCE(cvr.noAbsentSameDayVacByTeam,0) +
                         COALESCE(cvr.noAbsentNotSameDayFoundVac,0) +
                         COALESCE(cvr.noAbsentNotSameDayVacByTeam,0) + 
                         COALESCE(cvr.noNSSFoundVac,0) + 
                         COALESCE(cvr.noNSSVacByTeam,0) + 
                         COALESCE(cvr.noRefusalFoundVac,0) +
                         COALESCE(cvr.noRefusalVacByTeam,0))
                    as TotalRemaining     
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
                                FROM AppBundle:CoverageData cvr JOIN cvr.district dist 
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
                                FROM AppBundle:CoverageData cvr JOIN cvr.district dist 
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
                                FROM AppBundle:CoverageData cvr JOIN cvr.district dist 
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp 
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp 
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp 
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp 
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp 
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp 
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp 
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp 
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp 
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
                  FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
              FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
              FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
                      FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
                      FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
              FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
              FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
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
              FROM AppBundle:CoverageData cvr JOIN cvr.campaign cmp
              JOIN cvr.district d JOIN d.province p WHERE(cvr.campaign in (:camp) AND d.id in (:dist))
              GROUP BY cvr.clusterNo, d.id, cmp.id"
            )-> setParameters(['camp'=>$campaign, 'dist' => $district])
            ->getResult(Query::HYDRATE_SCALAR);
    }

}
