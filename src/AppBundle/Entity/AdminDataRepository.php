<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 10/13/2017
 * Time: 10:05 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class AdminDataRepository extends EntityRepository {
    /**
     * @param $function the function must be pre-defined
     * @param $parameters the parameters must be defined in the function
     * @param null $secondParam
     * @return mixed
     */
    public function callMe($function, $parameters, $secondParam = null) {
        return call_user_func(array($this, $function), $parameters, $secondParam);
    }


    public function dqlSummary($cid, $did, $cluster, $subd, $col)
    {
        return $this->getEntityManager()->createQuery("
            SELECT SUM(CASE WHEN (vaccDay=1 OR vaccDay=2 OR vaccDay= 3) THEN $col ELSE 0) as val
            WHERE district =:did AND campaign =:cid AND cluster=:cluster AND 
            (subDistrictName IS NULL OR subDistrictName =:sub)
        ")->setParameters(['did'=>$did, 'cid'=>$cid, 'cluster'=>$cluster, 'sub'=>$subd])->getDQL();
    }
    /**
     * @param $campaign
     * @return array
     */
    public function campaignStatistics($campaign) {
        return $this->getEntityManager()
            ->createQuery("SELECT cmp.id as CID, cmp.campaignStartDate as CDate,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
                  ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
                  sum(adm.targetPopulation)/4 as TargetPopulation,
                  sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
                  sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
                  sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regAbsent ELSE 0
                    END
                  ) as RegAbsent,
                  sum(adm.vaccAbsent) as VaccAbsent,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regAbsent-adm.vaccAbsent ELSE 0 END ) as RemainingAbsent,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regSleep ELSE 0
                    END
                  ) as RegNSS,
                  sum(adm.vaccSleep) as VaccNSS,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regSleep-adm.vaccSleep ELSE 0 END ) as RemainingNSS,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regRefusal ELSE 0
                    END
                  ) as RegRefusal,
                  sum(adm.vaccRefusal) as VaccRefusal,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as RemainingRefusal,

                  sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
                  FROM AppBundle:AdminData adm JOIN adm.campaign cmp WHERE(adm.campaign=:camp)") ->setParameter('camp', $campaign)
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaign
     * @return array
     */
    public function districtAgg($campaign) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.provinceRegion, p.provinceName, d.districtName,
                  sum(adm.targetPopulation) as TargetPopulation,
                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regRefusal ELSE 0
                    END
                  ) as RegRefusal,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as Refusal,

                  cmp.campaignType
                  FROM AppBundle:AdminData adm JOIN adm.campaign cmp
                  JOIN adm.district d JOIN d.province p WHERE(adm.campaign = :camp)
                  GROUP BY p.provinceRegion, p.provinceName, d.id"
            )-> setParameters(['camp'=>$campaign])
            ->getResult(Query::HYDRATE_SCALAR);
    }
    /**
     * @param $campaign
     * @return array
     */
    public function provinceAgg($campaign) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.provinceRegion, p.provinceName, p.id,
                  sum(adm.targetPopulation) as TargetPopulation,
                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regRefusal ELSE 0
                    END
                  ) as RegRefusal,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as Refusal,

                  cmp.campaignType
                  FROM AppBundle:AdminData adm JOIN adm.campaign cmp
                  JOIN adm.district d JOIN d.province p WHERE(adm.campaign = :camp)
                  GROUP BY p.provinceRegion, p.provinceName"
            )-> setParameters(['camp'=>$campaign])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @return array
     */
    public function clusterAgg() {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  adm.subDistrictName as Subdistrict, adm.clusterNo as Cluster,
                  sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
                  ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
                  sum(adm.targetPopulation)/4 as TargetPopulation,
                  sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
                  sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
                  sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regAbsent ELSE 0
                    END
                  ) as RegAbsent,
                  sum(adm.vaccAbsent) as VaccAbsent,
                  (sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regAbsent ELSE 0
                    END
                  ) - sum(adm.vaccAbsent)) as RemainingAbsent,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regSleep ELSE 0
                    END
                  ) as RegNSS,
                  sum(adm.vaccSleep) as VaccNSS,
                  (sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regSleep ELSE 0
                    END
                  )-sum(adm.vaccSleep)) as RemainingNSS,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regRefusal ELSE 0
                    END
                  ) as RegRefusal,
                  sum(adm.vaccRefusal) as VaccRefusal,
                  (sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regRefusal ELSE 0
                    END
                  )-sum(adm.vaccRefusal)) as RemainingRefusal

                  FROM AppBundle:AdminData adm JOIN adm.campaign cmp
                  JOIN adm.district d JOIN d.province p
                  GROUP BY adm.campaign, adm.district, adm.subDistrictName, adm.clusterNo"
            )
            ->getResult(Query::HYDRATE_SCALAR);
    }

//sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
    /**
     * @param $campaign
     * @return array
     */
    public function districtAggByCampaign($campaign) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
                  ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
                  sum(adm.targetPopulation)/4 as TargetPopulation,
                  sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
                  sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
                  sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regAbsent ELSE 0
                    END
                  ) as RegAbsent,
                  sum(adm.vaccAbsent) as VaccAbsent,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regAbsent-adm.vaccAbsent ELSE 0 END ) as RemainingAbsent,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regSleep ELSE 0
                    END
                  ) as RegNSS,
                  sum(adm.vaccSleep) as VaccNSS,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regSleep-adm.vaccSleep ELSE 0 END ) as RemainingNSS,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regRefusal ELSE 0
                    END
                  ) as RegRefusal,
                  sum(adm.vaccRefusal) as VaccRefusal,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as RemainingRefusal,

                  sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
                  FROM AppBundle:AdminData adm JOIN adm.campaign cmp
                  JOIN adm.district d JOIN d.province p WHERE(adm.campaign in (:camp))
                  GROUP BY p.id, adm.district, adm.campaign"
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
                "SELECT p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
                  ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
                  sum(adm.targetPopulation)/4 as TargetPopulation,
                  sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
                  sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
                  sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regAbsent ELSE 0
                    END
                  ) as RegAbsent,
                  sum(adm.vaccAbsent) as VaccAbsent,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regAbsent-adm.vaccAbsent ELSE 0 END ) as RemainingAbsent,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regSleep ELSE 0
                    END
                  ) as RegNSS,
                  sum(adm.vaccSleep) as VaccNSS,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regSleep-adm.vaccSleep ELSE 0 END ) as RemainingNSS,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regRefusal ELSE 0
                    END
                  ) as RegRefusal,
                  sum(adm.vaccRefusal) as VaccRefusal,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as RemainingRefusal,

                  sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
                  FROM AppBundle:AdminData adm JOIN adm.campaign cmp
                  JOIN adm.district d JOIN d.province p WHERE(adm.campaign in (:camp) AND adm.district in (:dist))
                  GROUP BY p.id, adm.district, adm.campaign"
            )-> setParameters(['camp'=>$campaign, 'dist'=>$district])
            ->getResult(Query::HYDRATE_SCALAR);
    }
    public function districtAggByCampaignDistrictRisk($campaign, $risk)  {
        $prov = $risk['province'];
        $risk = $risk['risk'];
        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
                  ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
                  sum(adm.targetPopulation)/4 as TargetPopulation,
                  sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
                  sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
                  sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regAbsent ELSE 0
                    END
                  ) as RegAbsent,
                  sum(adm.vaccAbsent) as VaccAbsent,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regAbsent-adm.vaccAbsent ELSE 0 END ) as RemainingAbsent,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regSleep ELSE 0
                    END
                  ) as RegNSS,
                  sum(adm.vaccSleep) as VaccNSS,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regSleep-adm.vaccSleep ELSE 0 END ) as RemainingNSS,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regRefusal ELSE 0
                    END
                  ) as RegRefusal,
                  sum(adm.vaccRefusal) as VaccRefusal,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as RemainingRefusal,

                  sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
                  FROM AppBundle:AdminData adm JOIN adm.campaign cmp
                  JOIN adm.district d JOIN d.province p WHERE(adm.campaign in (:camp) AND p.id in (:prov) AND (d.districtRiskStatus in (:risk)))
                  GROUP BY p.id, adm.district, adm.campaign"
            )-> setParameters(['camp'=>$campaign, 'risk'=>$risk, 'prov'=>$prov])
            ->getResult(Query::HYDRATE_SCALAR);
    }
    public function districtAggByCampaignDistrictRiskNull($campaign, $risk)  {
        $prov = $risk['province'];
        $risk = $risk['risk'];
        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
                  ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
                  sum(adm.targetPopulation)/4 as TargetPopulation,
                  sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
                  sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
                  sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regAbsent ELSE 0
                    END
                  ) as RegAbsent,
                  sum(adm.vaccAbsent) as VaccAbsent,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regAbsent-adm.vaccAbsent ELSE 0 END ) as RemainingAbsent,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regSleep ELSE 0
                    END
                  ) as RegNSS,
                  sum(adm.vaccSleep) as VaccNSS,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regSleep-adm.vaccSleep ELSE 0 END ) as RemainingNSS,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regRefusal ELSE 0
                    END
                  ) as RegRefusal,
                  sum(adm.vaccRefusal) as VaccRefusal,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as RemainingRefusal,

                  sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
                  FROM AppBundle:AdminData adm JOIN adm.campaign cmp
                  JOIN adm.district d JOIN d.province p WHERE(adm.campaign in (:camp) AND p.id in (:prov)
                  AND (d.districtRiskStatus in (:risk) OR d.districtRiskStatus IS NULL))
                  GROUP BY p.id, adm.district, adm.campaign"
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
                "SELECT p.provinceRegion as Region, p.id as PCODE, p.provinceName as Province, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
                  ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
                  sum(adm.targetPopulation)/4 as TargetPopulation,
                  sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
                  sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
                  sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regAbsent ELSE 0
                    END
                  ) as RegAbsent,
                  sum(adm.vaccAbsent) as VaccAbsent,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regAbsent-adm.vaccAbsent ELSE 0 END ) as RemainingAbsent,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regSleep ELSE 0
                    END
                  ) as RegNSS,
                  sum(adm.vaccSleep) as VaccNSS,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regSleep-adm.vaccSleep ELSE 0 END ) as RemainingNSS,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regRefusal ELSE 0
                    END
                  ) as RegRefusal,
                  sum(adm.vaccRefusal) as VaccRefusal,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as RemainingRefusal,

                  sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
                  FROM AppBundle:AdminData adm JOIN adm.campaign cmp
                  JOIN adm.district d JOIN d.province p WHERE(adm.campaign in (:camp))
                  GROUP BY p.id, adm.campaign"
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
                "SELECT p.provinceRegion as Region, p.id as PCODE, p.provinceName as Province, cmp.campaignStartDate as CDate, cmp.id as CID,
                  cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth,
                  sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
                  ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
                  sum(adm.targetPopulation)/4 as TargetPopulation,
                  sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
                  sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
                  sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regAbsent ELSE 0
                    END
                  ) as RegAbsent,
                  sum(adm.vaccAbsent) as VaccAbsent,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regAbsent-adm.vaccAbsent ELSE 0 END ) as RemainingAbsent,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regSleep ELSE 0
                    END
                  ) as RegNSS,
                  sum(adm.vaccSleep) as VaccNSS,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regSleep-adm.vaccSleep ELSE 0 END ) as RemainingNSS,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regRefusal ELSE 0
                    END
                  ) as RegRefusal,
                  sum(adm.vaccRefusal) as VaccRefusal,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as RemainingRefusal,

                  sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
                  FROM AppBundle:AdminData adm JOIN adm.campaign cmp
                  JOIN adm.district d JOIN d.province p WHERE(adm.campaign in (:camp) AND p.id in (:prov))
                  GROUP BY p.id, adm.campaign"
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
                  sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
                  ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
                  sum(adm.targetPopulation)/4 as TargetPopulation,
                  sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
                  sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
                  sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regAbsent ELSE 0
                    END
                  ) as RegAbsent,
                  sum(adm.vaccAbsent) as VaccAbsent,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regAbsent-adm.vaccAbsent ELSE 0 END ) as RemainingAbsent,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regSleep ELSE 0
                    END
                  ) as RegNSS,
                  sum(adm.vaccSleep) as VaccNSS,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regSleep-adm.vaccSleep ELSE 0 END ) as RemainingNSS,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regRefusal ELSE 0
                    END
                  ) as RegRefusal,
                  sum(adm.vaccRefusal) as VaccRefusal,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as RemainingRefusal,

                  sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
                  FROM AppBundle:AdminData adm JOIN adm.campaign cmp
                  JOIN adm.district d JOIN d.province p WHERE(adm.campaign in (:camp))
                  GROUP BY p.provinceRegion, adm.campaign"
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
                  sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
                  ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
                  sum(adm.targetPopulation)/4 as TargetPopulation,
                  sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
                  sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
                  sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regAbsent ELSE 0
                    END
                  ) as RegAbsent,
                  sum(adm.vaccAbsent) as VaccAbsent,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regAbsent-adm.vaccAbsent ELSE 0 END ) as RemainingAbsent,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regSleep ELSE 0
                    END
                  ) as RegNSS,
                  sum(adm.vaccSleep) as VaccNSS,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regSleep-adm.vaccSleep ELSE 0 END ) as RemainingNSS,

                  sum(
                    CASE
                      WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                      THEN adm.regRefusal ELSE 0
                    END
                  ) as RegRefusal,
                  sum(adm.vaccRefusal) as VaccRefusal,
                  sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as RemainingRefusal,

                  sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
                  FROM AppBundle:AdminData adm JOIN adm.campaign cmp
                  JOIN adm.district d JOIN d.province p WHERE(adm.campaign in (:camp) AND p.provinceRegion in (:region))
                  GROUP BY p.provinceRegion, adm.campaign"
            )-> setParameters(['camp'=>$campaign, 'region'=>$region])
            ->getResult(Query::HYDRATE_SCALAR);

    }

    /**
     * @param $region
     * @return array
     */
    public function selectRegion($region) {

        return $this->getEntityManager()
            ->createQuery(
                "SELECT adm.id as ID, p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, adm.subDistrictName as SDistrict, adm.cluster as Cluster,
            c.campaignName as CName, c.campaignType as CType, c.campaignMonth as CMonth, c.campaignYear as CYear, adm.receivedVials as ReceivedVials
            , adm.usedVials as UsedVials, adm.child011 as Child011, adm.child1259 as Child1259, adm.regAbsent as RegAbsent, adm.vaccAbsent as VaccAbsent, adm.regAbsent-adm.vaccAbsent as RemainingAbsent,
            adm.regSleep as RegSleep
            , adm.vaccSleep as VaccSleep, adm.regSleep-adm.vaccSleep as RemainingSleep, adm.regRefusal as RegRefusal, adm.vaccRefusal as VaccRefusal, adm.regRefusal-adm.vaccRefusal as RemainingRefusal, adm.vaccDay as VaccDay
                FROM AppBundle:AdminData adm JOIN adm.campaign c JOIN adm.district d JOIN d.province p WHERE p.provinceRegion in (:regio) ORDER BY p.provinceRegion, p.provinceName, d.districtName, adm.cluster, adm.vaccDay"
            )-> setParameters(['regio'=>$region])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $province
     * @return array
     */
    public function selectProvince($province) {

        return $this->getEntityManager()
            ->createQuery(
                "SELECT adm.id as ID, p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, adm.subDistrictName as SDistrict, adm.cluster as Cluster,
            c.campaignName as CName, c.campaignType as CType, c.campaignMonth as CMonth, c.campaignYear as CYear, adm.receivedVials as ReceivedVials
            , adm.usedVials as UsedVials, adm.child011 as Child011, adm.child1259 as Child1259, adm.regAbsent as RegAbsent, adm.vaccAbsent as VaccAbsent, adm.regAbsent-adm.vaccAbsent as RemainingAbsent,
            adm.regSleep as RegSleep
            , adm.vaccSleep as VaccSleep, adm.regSleep-adm.vaccSleep as RemainingSleep, adm.regRefusal as RegRefusal, adm.vaccRefusal as VaccRefusal, adm.regRefusal-adm.vaccRefusal as RemainingRefusal, adm.vaccDay as VaccDay
                FROM AppBundle:AdminData adm JOIN adm.campaign c JOIN adm.district d JOIN d.province p WHERE p.id in (:prov) ORDER BY p.provinceRegion, p.provinceName, d.districtName, adm.cluster, adm.vaccDay"
            )-> setParameters(['prov'=>$province])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $district
     * @return array
     */
    public function selectDistrict($district) {

        return $this->getEntityManager()
            ->createQuery(
                "SELECT adm.id as ID, p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, adm.subDistrictName as SDistrict, adm.cluster as Cluster,
            c.campaignName as CName, c.campaignType as CType, c.campaignMonth as CMonth, c.campaignYear as CYear, adm.receivedVials as ReceivedVials
            , adm.usedVials as UsedVials, adm.child011 as Child011, adm.child1259 as Child1259, adm.regAbsent as RegAbsent, adm.vaccAbsent as VaccAbsent, adm.regAbsent-adm.vaccAbsent as RemainingAbsent,
            adm.regSleep as RegSleep
            , adm.vaccSleep as VaccSleep, adm.regSleep-adm.vaccSleep as RemainingSleep, adm.regRefusal as RegRefusal, adm.vaccRefusal as VaccRefusal, adm.regRefusal-adm.vaccRefusal as RemainingRefusal, adm.vaccDay as VaccDay
                FROM AppBundle:AdminData adm JOIN adm.campaign c JOIN adm.district d JOIN d.province p WHERE d.id in (:dist) ORDER BY p.provinceRegion, p.provinceName, d.districtName, adm.cluster, adm.vaccDay"
            )-> setParameters(['dist'=>$district])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaign
     * @return array
     */
    public function selectCampaign($campaign) {

        return $this->getEntityManager()
            ->createQuery(
                "SELECT adm.id as ID, p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, adm.subDistrictName as SDistrict, adm.cluster as Cluster,
            c.campaignName as CName, c.campaignType as CType, c.campaignMonth as CMonth, c.campaignYear as CYear, adm.receivedVials as ReceivedVials
            , adm.usedVials as UsedVials, adm.child011 as Child011, adm.child1259 as Child1259, adm.regAbsent as RegAbsent, adm.vaccAbsent as VaccAbsent, adm.regAbsent-adm.vaccAbsent as RemainingAbsent,
            adm.regSleep as RegSleep
            , adm.vaccSleep as VaccSleep, adm.regSleep-adm.vaccSleep as RemainingSleep, adm.regRefusal as RegRefusal, adm.vaccRefusal as VaccRefusal, adm.regRefusal-adm.vaccRefusal as RemainingRefusal, adm.vaccDay as VaccDay
                FROM AppBundle:AdminData adm JOIN adm.campaign c JOIN adm.district d JOIN d.province p WHERE c.id in (:camp) ORDER BY p.provinceRegion, p.provinceName, d.districtName, adm.cluster, adm.vaccDay"
            )-> setParameters(['camp'=>$campaign])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $region
     * @param $campaign
     * @return array
     */

    public function regionByCampaigns($region, $campaign) {

        return $this->getEntityManager()
            ->createQuery(
                "SELECT adm.id as ID, p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, adm.subDistrictName as SDistrict, adm.cluster as Cluster,
            c.campaignName as CName, c.campaignType as CType, c.campaignMonth as CMonth, c.campaignYear as CYear, adm.receivedVials as ReceivedVials
            , adm.usedVials as UsedVials, adm.child011 as Child011, adm.child1259 as Child1259, adm.regAbsent as RegAbsent, adm.vaccAbsent as VaccAbsent, adm.regAbsent-adm.vaccAbsent as RemainingAbsent,
            adm.regSleep as RegSleep
            , adm.vaccSleep as VaccSleep, adm.regSleep-adm.vaccSleep as RemainingSleep, adm.regRefusal as RegRefusal, adm.vaccRefusal as VaccRefusal, adm.regRefusal-adm.vaccRefusal as RemainingRefusal, adm.vaccDay as VaccDay
                FROM AppBundle:AdminData adm JOIN adm.district d JOIN d.province p JOIN adm.campaign c WHERE c.id in (:camp) AND p.provinceRegion in (:regio) ORDER BY p.provinceRegion, p.provinceName, d.districtName, adm.cluster, adm.vaccDay"
            )-> setParameters(['camp'=>$campaign, 'regio'=>$region])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $province
     * @param $campaign
     * @return array
     */
    public function provinceByCampaigns($province, $campaign) {

        return $this->getEntityManager()
            ->createQuery(
                "SELECT adm.id as ID, p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, adm.subDistrictName as SDistrict, adm.cluster as Cluster,
            c.campaignName as CName, c.campaignType as CType, c.campaignMonth as CMonth, c.campaignYear as CYear, adm.receivedVials as ReceivedVials
            , adm.usedVials as UsedVials, adm.child011 as Child011, adm.child1259 as Child1259, adm.regAbsent as RegAbsent, adm.vaccAbsent as VaccAbsent, adm.regAbsent-adm.vaccAbsent as RemainingAbsent,
            adm.regSleep as RegSleep
            , adm.vaccSleep as VaccSleep, adm.regSleep-adm.vaccSleep as RemainingSleep, adm.regRefusal as RegRefusal, adm.vaccRefusal as VaccRefusal, adm.regRefusal-adm.vaccRefusal as RemainingRefusal, adm.vaccDay as VaccDay
                FROM AppBundle:AdminData adm JOIN adm.district d JOIN d.province p JOIN adm.campaign c WHERE c.id in (:camp) AND p.id in (:prov) ORDER BY p.provinceRegion, p.provinceName, d.districtName, adm.cluster, adm.vaccDay"
            )-> setParameters(['camp'=>$campaign, 'prov'=>$province])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $district
     * @param $campaign
     * @return array
     */
    public function districtByCampaigns($district, $campaign) {

        return $this->getEntityManager()
            ->createQuery(
                "SELECT adm.id as ID, p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, adm.subDistrictName as SDistrict, adm.cluster as Cluster,
            c.campaignName as CName, c.campaignType as CType, c.campaignMonth as CMonth, c.campaignYear as CYear, adm.receivedVials as ReceivedVials
            , adm.usedVials as UsedVials, adm.child011 as Child011, adm.child1259 as Child1259, adm.regAbsent as RegAbsent, adm.vaccAbsent as VaccAbsent, adm.regAbsent-adm.vaccAbsent as RemainingAbsent,
            adm.regSleep as RegSleep
            , adm.vaccSleep as VaccSleep, adm.regSleep-adm.vaccSleep as RemainingSleep, adm.regRefusal as RegRefusal, adm.vaccRefusal as VaccRefusal, adm.regRefusal-adm.vaccRefusal as RemainingRefusal, adm.vaccDay as VaccDay
                FROM AppBundle:AdminData adm JOIN adm.campaign c JOIN adm.district d JOIN d.province p WHERE c.id in (:camp) AND d.id IN (:dist) ORDER BY p.provinceRegion, p.provinceName, d.districtName, adm.cluster, adm.vaccDay "
            )-> setParameters(['camp'=>$campaign, 'dist' => $district])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $region
     * @return array
     */
    public function selectAggRegion($region) {

        return $this->getEntityManager()
            ->createQuery(
                "SELECT adm.id as ID, p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
              cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, adm.clusterName as ClusterName, adm.clusterNo as ClusterNo,
              sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
              ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
              sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
              sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
              sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regAbsent ELSE 0
                END
              ) as RegAbsent,
              sum(adm.vaccAbsent) as VaccAbsent,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regAbsent-adm.vaccAbsent ELSE 0 END ) as RemainingAbsent,

              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regSleep ELSE 0
                END
              ) as RegNSS,
              sum(adm.vaccSleep) as VaccNSS,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regSleep-adm.vaccSleep ELSE 0 END ) as RemainingNSS,

              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regRefusal ELSE 0
                END
              ) as RegRefusal,
              sum(adm.vaccRefusal) as VaccRefusal,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as RemainingRefusal,

              sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
              FROM AppBundle:AdminData adm JOIN adm.campaign cmp
              JOIN adm.district d JOIN d.province p WHERE p.provinceRegion in (:regio)
              GROUP BY adm.cluster, p.provinceRegion"
            )-> setParameters(['regio'=>$region])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $province
     * @return array
     */
    public function selectAggProvince($province) {

        return $this->getEntityManager()
            ->createQuery(
                "SELECT adm.id as ID, p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
              cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, adm.clusterName as ClusterName, adm.clusterNo as ClusterNo,
              sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
              ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
              sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
              sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
              sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regAbsent ELSE 0
                END
              ) as RegAbsent,
              sum(adm.vaccAbsent) as VaccAbsent,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regAbsent-adm.vaccAbsent ELSE 0 END ) as RemainingAbsent,

              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regSleep ELSE 0
                END
              ) as RegNSS,
              sum(adm.vaccSleep) as VaccNSS,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regSleep-adm.vaccSleep ELSE 0 END ) as RemainingNSS,

              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regRefusal ELSE 0
                END
              ) as RegRefusal,
              sum(adm.vaccRefusal) as VaccRefusal,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as RemainingRefusal,

              sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
              FROM AppBundle:AdminData adm JOIN adm.campaign cmp
              JOIN adm.district d JOIN d.province p WHERE p.id in (:prov)
              GROUP BY adm.cluster, p.id"
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
                "SELECT adm.id as ID, p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
              cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, adm.clusterName as ClusterName, adm.clusterNo as ClusterNo,
              sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
              ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
              sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
              sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
              sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regAbsent ELSE 0
                END
              ) as RegAbsent,
              sum(adm.vaccAbsent) as VaccAbsent,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regAbsent-adm.vaccAbsent ELSE 0 END ) as RemainingAbsent,

              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regSleep ELSE 0
                END
              ) as RegNSS,
              sum(adm.vaccSleep) as VaccNSS,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regSleep-adm.vaccSleep ELSE 0 END ) as RemainingNSS,

              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regRefusal ELSE 0
                END
              ) as RegRefusal,
              sum(adm.vaccRefusal) as VaccRefusal,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as RemainingRefusal,

              sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
              FROM AppBundle:AdminData adm JOIN adm.campaign cmp
              JOIN adm.district d JOIN d.province p WHERE d.id in (:dist)
              GROUP BY adm.cluster, d.id"
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
                "SELECT adm.id as ID, p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
              cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, adm.clusterName as ClusterName, adm.clusterNo as ClusterNo,
              sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
              ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
              sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
              sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
              sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regAbsent ELSE 0
                END
              ) as RegAbsent,
              sum(adm.vaccAbsent) as VaccAbsent,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regAbsent-adm.vaccAbsent ELSE 0 END ) as RemainingAbsent,

              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regSleep ELSE 0
                END
              ) as RegNSS,
              sum(adm.vaccSleep) as VaccNSS,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regSleep-adm.vaccSleep ELSE 0 END ) as RemainingNSS,

              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regRefusal ELSE 0
                END
              ) as RegRefusal,
              sum(adm.vaccRefusal) as VaccRefusal,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as RemainingRefusal,

              sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
              FROM AppBundle:AdminData adm JOIN adm.campaign cmp
              JOIN adm.district d JOIN d.province p WHERE cmp.id in (:camp)
              GROUP BY adm.cluster, cmp.id"
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
                "SELECT adm.id as ID, p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
              cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, adm.clusterName as ClusterName, adm.clusterNo as ClusterNo,
              sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
              ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
              sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
              sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
              sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regAbsent ELSE 0
                END
              ) as RegAbsent,
              sum(adm.vaccAbsent) as VaccAbsent,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regAbsent-adm.vaccAbsent ELSE 0 END ) as RemainingAbsent,

              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regSleep ELSE 0
                END
              ) as RegNSS,
              sum(adm.vaccSleep) as VaccNSS,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regSleep-adm.vaccSleep ELSE 0 END ) as RemainingNSS,

              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regRefusal ELSE 0
                END
              ) as RegRefusal,
              sum(adm.vaccRefusal) as VaccRefusal,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as RemainingRefusal,

              sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
              FROM AppBundle:AdminData adm JOIN adm.campaign cmp
              JOIN adm.district d JOIN d.province p WHERE(adm.campaign in (:camp) AND p.provinceRegion in (:region))
              GROUP BY adm.cluster, p.provinceRegion, cmp.id"
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
                "SELECT adm.id as ID, p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
              cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, adm.clusterName as ClusterName, adm.clusterNo as ClusterNo,
              sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
              ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
              sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
              sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
              sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regAbsent ELSE 0
                END
              ) as RegAbsent,
              sum(adm.vaccAbsent) as VaccAbsent,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regAbsent-adm.vaccAbsent ELSE 0 END ) as RemainingAbsent,

              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regSleep ELSE 0
                END
              ) as RegNSS,
              sum(adm.vaccSleep) as VaccNSS,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regSleep-adm.vaccSleep ELSE 0 END ) as RemainingNSS,

              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regRefusal ELSE 0
                END
              ) as RegRefusal,
              sum(adm.vaccRefusal) as VaccRefusal,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as RemainingRefusal,

              sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
              FROM AppBundle:AdminData adm JOIN adm.campaign cmp
              JOIN adm.district d JOIN d.province p WHERE(adm.campaign in (:camp) AND p.id in (:prov))
              GROUP BY adm.cluster, p.id, cmp.id"
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
                "SELECT adm.id as ID, p.provinceRegion as Region, p.provinceName as Province, d.districtName as District, d.id as DCODE, cmp.campaignStartDate as CDate, cmp.id as CID,
              cmp.campaignType as CType, cmp.campaignYear as CYear, cmp.campaignMonth as CMonth, adm.clusterName as ClusterName, adm.clusterNo as ClusterNo,
              sum(adm.receivedVials) as RVials, sum(adm.usedVials) as UVials,
              ((sum(adm.usedVials)*20 - (sum(adm.child011)+sum(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal)))/(sum(adm.usedVials)*20) * 100) as VaccWastage,
              sum(adm.child011)+SUM(adm.child1259)+sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as VaccChild,
              sum(adm.child011) as Child011, sum(adm.child1259) as Child1259,
              sum(adm.vaccAbsent)+sum(adm.vaccSleep)+sum(adm.vaccRefusal) as MissedVaccinated,
              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regAbsent ELSE 0
                END
              ) as RegAbsent,
              sum(adm.vaccAbsent) as VaccAbsent,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regAbsent-adm.vaccAbsent ELSE 0 END ) as RemainingAbsent,

              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regSleep ELSE 0
                END
              ) as RegNSS,
              sum(adm.vaccSleep) as VaccNSS,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regSleep-adm.vaccSleep ELSE 0 END ) as RemainingNSS,

              sum(
                CASE
                  WHEN (adm.vaccDay = 1 OR adm.vaccDay = 2 OR adm.vaccDay = 3)
                  THEN adm.regRefusal ELSE 0
                END
              ) as RegRefusal,
              sum(adm.vaccRefusal) as VaccRefusal,
              sum(CASE WHEN adm.vaccDay = 4 THEN adm.regRefusal-adm.vaccRefusal ELSE 0 END ) as RemainingRefusal,

              sum(adm.regAbsent-adm.vaccAbsent + adm.regSleep-adm.vaccSleep + adm.regRefusal-adm.vaccRefusal) as TotalRemaining
              FROM AppBundle:AdminData adm JOIN adm.campaign cmp
              JOIN adm.district d JOIN d.province p WHERE(adm.campaign in (:camp) AND d.id in (:dist))
              GROUP BY adm.cluster, d.id, cmp.id"
            )-> setParameters(['camp'=>$campaign, 'dist' => $district])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $district
     * @return array
     */
    public function selectClustereByDistrict($district) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT DISTINCT a, d.id, d.districtName FROM AppBundle:AdminData a JOIN a.districtCode d WHERE a.districtCode IN (:dis) Group BY a.cluster"
            ) ->setParameter('dis', $district)
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $cluster, $vaccday
     * @return array
     */
    public function selectCampaignsByClusterVaccDay($cluster, $vaccday) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT c.id, c.campaignName FROM AppBundle:AdminData a JOIN a.campaign c WHERE a.cluster IN (:clus) AND a.vaccDay IN (:vacc) GROUP BY c.id"
            ) -> setParameters(['clus'=>$cluster, 'vacc'=>$vaccday])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $campaign
     * @return array
     */
    public function checkIfCampaignExist($campaign) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT c.id FROM AppBundle:Campaign c WHERE c.id IN (:camp)"
            ) ->setParameter('camp', $campaign)
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $districts, $campaign
     * @return array
     */
    public function checkDistrictsData($districts, $campaign) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT DISTINCT IDENTITY(adm.district) FROM AppBundle:AdminData adm WHERE (adm.district IN (:dis) AND adm.campaign IN (:camp))"
            ) -> setParameters(['dis'=>$districts, 'camp'=>$campaign])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param  $campaign
     * @return array
     */
    public function checkThreeDayCampaign($campaignday)
    {
        $em = $this->getEntityManager();
        $repository = $em->getRepository('AppBundle:AdminData');

        // createQueryBuilder() automatically selects FROM AppBundle:Product
        // and aliases it to "p"
        $query = $repository->createQueryBuilder('p')
            ->where('p.campaign= :campid')
            ->andWhere('p.vaccDay = 1 OR p.vaccDay = 2 OR p.vaccDay = 3')
            ->setParameter('campid', $campaignday)
            ->getQuery();


        return $query->getResult();
    }

    /**
     * @param  $campaign
     * @return array
     */
    public function checkDayFourCampaign($campaignday)
    {
        $em = $this->getEntityManager();
        $repository = $em->getRepository('AppBundle:AdminData');

        // createQueryBuilder() automatically selects FROM AppBundle:Product
        // and aliases it to "p"
        $query = $repository->createQueryBuilder('p')
            ->where('p.campaign= :campid')
            ->andWhere('p.vaccDay = 4')
            ->setParameter('campid', $campaignday)
            ->getQuery();


        return $query->getResult();
    }
}
