<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 1/23/2018
 * Time: 8:22 PM
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\Query;

class OdkSmRepository extends EntityRepository {

    /**
     * @param $function
     * @param $parameters
     * @param null $secondParam
     * @return mixed
     */
    public function callMe($function, $parameters, $secondParam = null) {
        return call_user_func(array($this, $function), $parameters, $secondParam);
    }

    protected static $DQL = "   count(sm.id) as times,
                                avg(sm.attendance) as attendance, 
                                avg(sm.profile) as profile, 
                                avg(sm.preparedness) as preparedness, 
                                avg(sm.trackingMissed) as trackingMissed, 
                                avg(sm.tallying) as tallying, 
                                avg(sm.mobilization) as mobilization, 
                                avg(sm.advocacy) as advocacy, 
                                avg(sm.iecMaterial) as iecMaterial,
                                avg(sm.higherSupv) as higherSup,
                                avg(sm.refusalChallenge) as refusalChallenge,
                                avg(sm.accessChallenge) as accessChallenge ";

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
                ".self::$DQL.", Month(sm.monitoringDate) as mdate
                FROM AppBundle:OdkSmMonitoring sm JOIN sm.district d JOIN d.province p
                WHERE (sm.monitoringDate Between :startDate AND :endDate)
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
                ".self::$DQL.", Month(sm.monitoringDate) as mdate
                FROM AppBundle:OdkSmMonitoring sm JOIN sm.district d JOIN d.province p
                WHERE (sm.monitoringDate Between :startDate AND :endDate)
                 AND d.province IN (:prov)
                GROUP BY d.id, mdate ORDER BY d.id
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
                d.id as dcode, d.districtName as districtName, sm.cluster, sm.monitorName, sm.ccsName, sm.smName,
                sm.campaignPhase, 
                ".self::$DQL.", Month(sm.monitoringDate) as mdate
                FROM AppBundle:OdkSmMonitoring sm JOIN sm.district d JOIN d.province p
                WHERE (sm.monitoringDate Between :startDate AND :endDate)
                 AND sm.district IN (:dist)
                GROUP BY sm.cluster, mdate ORDER BY d.id, sm.cluster
                "
            ) ->setParameters(['startDate'=>$startDate, 'endDate'=>$endDate, 'dist'=>$district])
            ->getResult(Query::HYDRATE_SCALAR);
    }



    /**
     * @return mixed
     */
    private function getLastDate() {

        $data = $this->getEntityManager()->createQuery(
            "SELECT max(tbl.monitoringDate) as lastDate FROM AppBundle:OdkSmMonitoring tbl"
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
