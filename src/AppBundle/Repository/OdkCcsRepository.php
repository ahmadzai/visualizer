<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 1/23/2018
 * Time: 8:22 PM
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class OdkCcsRepository extends EntityRepository {

    /**
     * @param $function
     * @param $parameters
     * @param null $secondParam
     * @return mixed
     */
    public function callMe($function, $parameters, $secondParam = null) {
        return call_user_func(array($this, $function), $parameters, $secondParam);
    }

    protected static $DQL = "   count(ccs.id) as times,
                                avg(ccs.attendance) as attendance, 
                                avg(ccs.profile) as profile, 
                                avg(ccs.preparedness) as preparedness, 
                                avg(ccs.mentoring) as mentoring, 
                                avg(ccs.trackingMissed) as trackingMissed, 
                                avg(ccs.planningReview) as planningReview, 
                                avg(ccs.mobilization) as mobilization, 
                                avg(ccs.advocacy) as advocacy, 
                                avg(ccs.iecMaterial) as iecMaterial,
                                avg(ccs.higherSupv) as higherSup,
                                avg(ccs.refusalChallenge) as refusalChallenge,
                                avg(ccs.accessChallenge) as accessChallenge ";

    /**
     * @param $region
     * @param int $startDate
     * @param int $endDate
     * @return array
     */
    public function aggByProvince($region, $startDate = 0, $endDate = 0) {
        // if end date and start date was not set
        $endDate = $endDate === 0 ? $this->getLastDate() : $endDate;
        $startDate = $startDate === 0  || $startDate === null ? $this->getStartDate($endDate) : $startDate;

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode, p.provinceRegion as region, p.provinceName as provinceName,
                ".self::$DQL.", Month(ccs.monitoringDate) as mdate
                FROM AppBundle:OdkCcsMonitoring ccs JOIN ccs.district d JOIN d.province p
                WHERE (ccs.monitoringDate Between :startDate AND :endDate)
                 AND p.provinceRegion IN (:region)
                GROUP BY p.id, mdate ORDER BY p.id
                "
            ) ->setParameters(['startDate'=>$startDate, 'endDate'=>$endDate, 'region'=>$region])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $province
     * @param int $startDate
     * @param int $endDate
     * @return array
     */
    public function aggByDistrict($province, $startDate = 0, $endDate = 0) {
        // if end date and start date was not set
        $endDate = $endDate === 0 ? $this->getLastDate() : $endDate;
        $startDate = $startDate === 0  || $startDate === null ? $this->getStartDate($endDate) : $startDate;

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode, p.provinceRegion as region, p.provinceName as provinceName,
                d.id as dcode, d.districtName as districtName, 
                ".self::$DQL.", Month(ccs.monitoringDate) as mdate
                FROM AppBundle:OdkCcsMonitoring ccs JOIN ccs.district d JOIN d.province p
                WHERE (ccs.monitoringDate Between :startDate AND :endDate)
                 AND d.province IN (:prov)
                GROUP BY d.id, mdate ORDER BY p.id, d.id
                "
            ) ->setParameters(['startDate'=>$startDate, 'endDate'=>$endDate, 'prov'=>$province])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $district
     * @param int $startDate
     * @param int $endDate
     * @return array
     */
    public function aggByCluster($district, $startDate = 0, $endDate = 0) {
        // if end date and start date was not set
        $endDate = $endDate === 0 ? $this->getLastDate() : $endDate;
        $startDate = $startDate === 0  || $startDate === null ? $this->getStartDate($endDate) : $startDate;

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode, p.provinceRegion as region, p.provinceName as provinceName,
                d.id as dcode, d.districtName as districtName, ccs.cluster, ccs.monitorName, ccs.dcoName, ccs.ccsName,
                ccs.campaignPhase, 
                ".self::$DQL.", Month(ccs.monitoringDate) as mdate
                FROM AppBundle:OdkCcsMonitoring ccs JOIN ccs.district d JOIN d.province p
                WHERE (ccs.monitoringDate Between :startDate AND :endDate)
                 AND ccs.district IN (:dist)
                GROUP BY ccs.cluster, mdate ORDER BY d.id, ccs.cluster
                "
            ) ->setParameters(['startDate'=>$startDate, 'endDate'=>$endDate, 'dist'=>$district])
            ->getResult(Query::HYDRATE_SCALAR);
    }



    /**
     * @return mixed
     */
    private function getLastDate() {

        $data = $this->getEntityManager()->createQuery(
            "SELECT max(tbl.monitoringDate) as lastDate FROM AppBundle:OdkCcsMonitoring tbl"
        )
            ->getResult(Query::HYDRATE_SCALAR);

        return $data[0]['lastDate'];
    }

    private function getStartDate($endDate) {
        //return \Dat('Y-m-01', strtotime($endDate));
        $year = date('Y', strtotime($endDate));
        $month = date('m', strtotime($endDate));

        $startDate = $year."-".$month."-"."01";

        return $startDate;
        //return \DateTimeImmutable::createFromFormat('Y-m-01',$endDate);
    }


}
