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
     * @param $districts
     * @param $monthsYear
     * @return mixed
     */
    public function clustersByDistrictMonth($districts, $monthsYear) {
        $months = $this->extractMonth($monthsYear);
        $years = $this->extractYear($monthsYear);

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode,
                d.id as dcode, d.districtName, sm.cluster as clusterNo
                FROM AppBundle:OdkSmMonitoring sm JOIN sm.district d JOIN d.province p
                WHERE (YEAR(sm.monitoringDate) IN (:year))
                 AND (MONTH(sm.monitoringDate) IN (:month))
                 AND sm.district IN (:dist)
                 AND sm.cluster IS NOT NULL
                GROUP BY sm.cluster ORDER BY d.id, sm.cluster
                "
            ) ->setParameters(['year'=>$years, 'month'=>$months, 'dist'=> $districts])
            ->getResult(Query::HYDRATE_SCALAR);
    }
    /*
     * @param $region
     * @param int $startDate
     * @param int $endDate
     * @return array
     *
    public function aggByProvince($region, $startDate = 0, $endDate = 0) {
        // if end date and start date was not set
        $endDate = $endDate === 0 ? $this->getLastDate() : $endDate;
        $startDate = $startDate === 0  || $startDate === null ? $this->getStartDate($endDate) : $startDate;

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode, p.provinceRegion as region, p.provinceName as provinceName,
                ".self::$DQL.", Month(sm.monitoringDate) as mdate,
                CONCAT(MonthName(sm.monitoringDate),'-', YEAR(sm.monitoringDate)) as mName
                FROM AppBundle:OdkSmMonitoring sm JOIN sm.district d JOIN d.province p
                WHERE (sm.monitoringDate Between :startDate AND :endDate)
                 AND p.provinceRegion IN (:region)
                GROUP BY p.id, mdate ORDER BY p.id, sm.monitoringDate DESC
                "
            ) ->setParameters(['startDate'=>$startDate, 'endDate'=>$endDate, 'region'=>$region])
            ->getResult(Query::HYDRATE_SCALAR);
    }
    */

    /**
     * @param $monthsYear (Year-Month)
     * @return mixed
     */
    public function aggByMonthRegion($monthsYear) {

        $months = $this->extractMonth($monthsYear);
        $years = $this->extractYear($monthsYear);

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode, p.provinceRegion as region, p.provinceName as provinceName,
                ".self::$DQL.", Month(sm.monitoringDate) as mdate, 
                CONCAT(MonthName(sm.monitoringDate),'-', YEAR(sm.monitoringDate)) as mName
                FROM AppBundle:OdkSmMonitoring sm JOIN sm.district d JOIN d.province p
                WHERE (YEAR(sm.monitoringDate) IN (:year))
                 AND (MONTH(sm.monitoringDate) IN (:month))
                GROUP BY p.id ORDER BY p.id
                "
            ) ->setParameters(['year'=>$years, 'month'=>$months])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $region
     * @param $monthsYear (Year-Month)
     * @return mixed
     */
    public function aggByMonthProvince($region, $monthsYear) {

        $months = $this->extractMonth($monthsYear);
        $years = $this->extractYear($monthsYear);

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode, p.provinceRegion as region, p.provinceName as provinceName,
                ".self::$DQL.", Month(sm.monitoringDate) as mdate, 
                CONCAT(MonthName(sm.monitoringDate),'-', YEAR(sm.monitoringDate)) as mName
                FROM AppBundle:OdkSmMonitoring sm JOIN sm.district d JOIN d.province p
                WHERE (YEAR(sm.monitoringDate) IN (:year))
                 AND (MONTH(sm.monitoringDate) IN (:month))
                 AND p.provinceRegion IN (:region)
                GROUP BY p.id ORDER BY p.id
                "
            ) ->setParameters(['region'=>$region, 'year'=>$years, 'month'=>$months])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $province
     * @param $monthsYear (Year-Month)
     * @return mixed
     */
    public function aggByMonthDistrict($province, $monthsYear) {

        $months = $this->extractMonth($monthsYear);
        $years = $this->extractYear($monthsYear);

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode, p.provinceRegion as region, p.provinceName as provinceName,
                d.id as dcode, d.districtName as districtName, 
                ".self::$DQL.", Month(sm.monitoringDate) as mdate, 
                CONCAT(MonthName(sm.monitoringDate),'-', YEAR(sm.monitoringDate)) as mName
                FROM AppBundle:OdkSmMonitoring sm JOIN sm.district d JOIN d.province p
               WHERE (YEAR(sm.monitoringDate) IN (:year))
                 AND (MONTH(sm.monitoringDate) IN (:month))
                 AND d.province IN (:prov)
                GROUP BY d.id ORDER BY p.id, d.id
                "
            ) ->setParameters(['prov'=>$province,'year'=>$years, 'month'=>$months])
            ->getResult(Query::HYDRATE_SCALAR);
    }


    /**
     * @param $district
     * @param $monthsYear (Year-Month)
     * @return mixed
     */
    public function aggByMonthCluster($district, $monthsYear) {

        $months = $this->extractMonth($monthsYear);
        $years = $this->extractYear($monthsYear);

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode, p.provinceRegion as region, p.provinceName as provinceName,
                d.id as dcode, d.districtName as districtName, sm.cluster, sm.monitorName, sm.ccsName, sm.smName,
                sm.campaignPhase, 
                ".self::$DQL.", Month(sm.monitoringDate) as mdate, 
                CONCAT(MonthName(sm.monitoringDate),'-', YEAR(sm.monitoringDate)) as mName
                FROM AppBundle:OdkSmMonitoring sm JOIN sm.district d JOIN d.province p
                WHERE (YEAR(sm.monitoringDate) IN (:year))
                 AND (MONTH(sm.monitoringDate) IN (:month))
                 AND sm.district IN (:dist)
                 AND sm.cluster IS NOT NULL
                GROUP BY sm.cluster ORDER BY d.id, sm.cluster
                "
            ) ->setParameters(['dist'=>$district, 'year'=>$years, 'month'=>$months])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $distClusters ['cluster', 'district']
     * @param $monthsYear (Year-Month)
     * @return mixed
     */
    public function aggByMonthSm($distClusters, $monthsYear) {

        $clusters = $distClusters['cluster'];
        $dist = $distClusters['district'];

        $months = $this->extractMonth($monthsYear);
        $years = $this->extractYear($monthsYear);

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode, p.provinceRegion as region, p.provinceName as provinceName,
                d.id as dcode, d.districtName as districtName, sm.cluster, sm.monitorName, sm.ccsName, sm.smName,
                sm.campaignPhase, 
                ".self::$DQL.", Month(sm.monitoringDate) as mdate, 
                CONCAT(MonthName(sm.monitoringDate),'-', YEAR(sm.monitoringDate)) as mName
                FROM AppBundle:OdkSmMonitoring sm JOIN sm.district d JOIN d.province p
                WHERE (YEAR(sm.monitoringDate) IN (:year))
                 AND (MONTH(sm.monitoringDate) IN (:month))
                 AND sm.district IN (:dist)
                 AND sm.cluster IN (:cluster)
                GROUP BY sm.ccsName, sm.smName, sm.cluster ORDER BY d.id, sm.cluster, sm.ccsName
                "
            ) ->setParameters(['cluster'=>$clusters, 'year'=>$years, 'month'=>$months, 'dist'=>$dist])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $monthsYear (Year-Month)
     * @return mixed
     */
    public function aggByRegionMonth($monthsYear) {

        $months = $this->extractMonth($monthsYear);
        $years = $this->extractYear($monthsYear);

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode, p.provinceRegion as region, p.provinceName as provinceName,
                ".self::$DQL.", Month(sm.monitoringDate) as mdate, 
                CONCAT(MonthName(sm.monitoringDate),'-', YEAR(sm.monitoringDate)) as mName
                FROM AppBundle:OdkSmMonitoring sm JOIN sm.district d JOIN d.province p
                WHERE (YEAR(sm.monitoringDate) IN (:year))
                 AND (MONTH(sm.monitoringDate) IN (:month))
                GROUP BY p.id, mdate ORDER BY p.id, sm.monitoringDate DESC
                "
            ) ->setParameters(['year'=>$years, 'month'=>$months])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $region
     * @param $monthsYear (Year-Month)
     * @return mixed
     */
    public function aggByProvinceMonth($region, $monthsYear) {

        $months = $this->extractMonth($monthsYear);
        $years = $this->extractYear($monthsYear);

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode, p.provinceRegion as region, p.provinceName as provinceName,
                ".self::$DQL.", Month(sm.monitoringDate) as mdate, 
                CONCAT(MonthName(sm.monitoringDate),'-', YEAR(sm.monitoringDate)) as mName
                FROM AppBundle:OdkSmMonitoring sm JOIN sm.district d JOIN d.province p
                WHERE (YEAR(sm.monitoringDate) IN (:year))
                 AND (MONTH(sm.monitoringDate) IN (:month))
                 AND p.provinceRegion IN (:region)
                GROUP BY p.id, mdate ORDER BY p.id, sm.monitoringDate DESC
                "
            ) ->setParameters(['region'=>$region, 'year'=>$years, 'month'=>$months])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $province
     * @param $monthsYear (Year-Month)
     * @return mixed
     */
    public function aggByDistrictMonth($province, $monthsYear) {

        $months = $this->extractMonth($monthsYear);
        $years = $this->extractYear($monthsYear);

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode, p.provinceRegion as region, p.provinceName as provinceName,
                d.id as dcode, d.districtName as districtName, 
                ".self::$DQL.", Month(sm.monitoringDate) as mdate, 
                CONCAT(MonthName(sm.monitoringDate),'-', YEAR(sm.monitoringDate)) as mName
                FROM AppBundle:OdkSmMonitoring sm JOIN sm.district d JOIN d.province p
               WHERE (YEAR(sm.monitoringDate) IN (:year))
                 AND (MONTH(sm.monitoringDate) IN (:month))
                 AND d.province IN (:prov)
                GROUP BY d.id, mdate ORDER BY p.id, d.id, sm.monitoringDate DESC
                "
            ) ->setParameters(['prov'=>$province,'year'=>$years, 'month'=>$months])
            ->getResult(Query::HYDRATE_SCALAR);
    }


    /**
     * @param $district
     * @param $monthsYear (Year-Month)
     * @return mixed
     */
    public function aggByClusterMonth($district, $monthsYear) {

        $months = $this->extractMonth($monthsYear);
        $years = $this->extractYear($monthsYear);

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode, p.provinceRegion as region, p.provinceName as provinceName,
                d.id as dcode, d.districtName as districtName, sm.cluster, sm.monitorName, sm.ccsName, sm.smName,
                sm.campaignPhase, 
                ".self::$DQL.", Month(sm.monitoringDate) as mdate, 
                CONCAT(MonthName(sm.monitoringDate),'-', YEAR(sm.monitoringDate)) as mName
                FROM AppBundle:OdkSmMonitoring sm JOIN sm.district d JOIN d.province p
                WHERE (YEAR(sm.monitoringDate) IN (:year))
                 AND (MONTH(sm.monitoringDate) IN (:month))
                 AND sm.district IN (:dist)
                 AND sm.cluster IS NOT NULL
                GROUP BY sm.cluster, mdate ORDER BY d.id, sm.cluster, sm.monitoringDate DESC
                "
            ) ->setParameters(['dist'=>$district, 'year'=>$years, 'month'=>$months])
            ->getResult(Query::HYDRATE_SCALAR);
    }


    /**
     * @param $distClusters ['district', 'cluster']
     * @param $monthsYear (Year-Month)
     * @return mixed
     */
    public function aggBySmMonth($distClusters, $monthsYear) {

        $clusters = $distClusters['cluster'];
        $dist = $distClusters['district'];

        $months = $this->extractMonth($monthsYear);
        $years = $this->extractYear($monthsYear);

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode, p.provinceRegion as region, p.provinceName as provinceName,
                d.id as dcode, d.districtName as districtName, sm.cluster, sm.monitorName, sm.ccsName, sm.smName,
                sm.campaignPhase, 
                ".self::$DQL.", Month(sm.monitoringDate) as mdate, 
                CONCAT(MonthName(sm.monitoringDate),'-', YEAR(sm.monitoringDate)) as mName
                FROM AppBundle:OdkSmMonitoring sm JOIN sm.district d JOIN d.province p
                WHERE (YEAR(sm.monitoringDate) IN (:year))
                 AND (MONTH(sm.monitoringDate) IN (:month))
                 AND sm.district IN (:dist)
                 AND sm.cluster IN (:cluster)
                GROUP BY sm.ccsName, sm.smName, sm.cluster, mdate ORDER BY d.id, sm.cluster, sm.ccsName, sm.monitoringDate DESC
                "
            ) ->setParameters(['dist'=>$dist, 'cluster'=>$clusters, 'year'=>$years, 'month'=>$months])
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

    /**
     * @param $monthsYear (Year-month)
     * @return array months
     */
    private function extractMonth($monthsYear) {
        $months = array();
        foreach ($monthsYear as $item) {
            $m = explode("-", $item);
            $months[] = $m[1];
        }

        return $months;
    }

    /**
     * @param $monthsYear (Year-month)
     * @return array years
     */
    private function extractYear($monthsYear) {
        $years = array();
        foreach ($monthsYear as $item) {
            $y = explode("-", $item);
            $years[] = $y[0];
        }

        return $years;
    }

}
