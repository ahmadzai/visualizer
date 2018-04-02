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

class IntOdkCcsRepository extends EntityRepository {

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
                                avg(ccs.fieldbook) as fieldbook,
                                avg(ccs.mobilization) as mobilization, 
                                avg(ccs.campPerform) as campPerform, 
                                avg(ccs.catchupPerform) as catchupPerform, 
                                avg(ccs.iecMaterial) as iecMaterial,
                                avg(ccs.refusalChallenge) as refusalChallenge,
                                avg(ccs.higherSupv) as higherSup,
                                avg(ccs.comSupport) as comSupport,
                                avg(ccs.coldchain) as coldchain,
                                avg(ccs.accessChallenge) as accessChallenge,
                                avg(ccs.overallPerform) as overallPerform ";

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
                d.id as dcode, d.districtName, ccs.cluster as clusterNo
                FROM AppBundle:IntOdkCcsMonitoring ccs JOIN ccs.district d JOIN d.province p
                WHERE (YEAR(ccs.monitoringDate) IN (:year))
                 AND (MONTH(ccs.monitoringDate) IN (:month))
                 AND ccs.district IN (:dist)
                 AND ccs.cluster IS NOT NULL
                GROUP BY ccs.cluster ORDER BY d.id, ccs.cluster
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
                ".self::$DQL.", Month(ccs.monitoringDate) as mdate, 
                CONCAT(MonthName(ccs.monitoringDate),'-', YEAR(ccs.monitoringDate)) as mName
                FROM AppBundle:IntOdkCcsMonitoring ccs JOIN ccs.district d JOIN d.province p
                WHERE (ccs.monitoringDate Between :startDate AND :endDate)
                 AND p.provinceRegion IN (:region)
                GROUP BY p.id, mdate ORDER BY p.id, ccs.monitoringDate DESC
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
                ".self::$DQL.", Month(ccs.monitoringDate) as mdate, 
                CONCAT(MonthName(ccs.monitoringDate),'-', YEAR(ccs.monitoringDate)) as mName
                FROM AppBundle:IntOdkCcsMonitoring ccs JOIN ccs.district d JOIN d.province p
                WHERE (YEAR(ccs.monitoringDate) IN (:year))
                 AND (MONTH(ccs.monitoringDate) IN (:month))
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
                ".self::$DQL.", Month(ccs.monitoringDate) as mdate, 
                CONCAT(MonthName(ccs.monitoringDate),'-', YEAR(ccs.monitoringDate)) as mName
                FROM AppBundle:IntOdkCcsMonitoring ccs JOIN ccs.district d JOIN d.province p
                WHERE (YEAR(ccs.monitoringDate) IN (:year))
                 AND (MONTH(ccs.monitoringDate) IN (:month))
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
                ".self::$DQL.", Month(ccs.monitoringDate) as mdate, 
                CONCAT(MonthName(ccs.monitoringDate),'-', YEAR(ccs.monitoringDate)) as mName
                FROM AppBundle:IntOdkCcsMonitoring ccs JOIN ccs.district d JOIN d.province p
               WHERE (YEAR(ccs.monitoringDate) IN (:year))
                 AND (MONTH(ccs.monitoringDate) IN (:month))
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
                d.id as dcode, d.districtName as districtName, ccs.cluster, ccs.monitorName, ccs.dcoName, ccs.ccsName,
                ccs.campaignPhase, 
                ".self::$DQL.", Month(ccs.monitoringDate) as mdate, 
                CONCAT(MonthName(ccs.monitoringDate),'-', YEAR(ccs.monitoringDate)) as mName
                FROM AppBundle:IntOdkCcsMonitoring ccs JOIN ccs.district d JOIN d.province p
                WHERE (YEAR(ccs.monitoringDate) IN (:year))
                 AND (MONTH(ccs.monitoringDate) IN (:month))
                 AND ccs.district IN (:dist)
                 AND ccs.cluster IS NOT NULL
                GROUP BY ccs.cluster ORDER BY d.id, ccs.cluster
                "
            ) ->setParameters(['dist'=>$district, 'year'=>$years, 'month'=>$months])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $distClusters ['cluster', 'district']
     * @param $monthsYear (Year-Month)
     * @return mixed
     */
    public function aggByMonthCcs($distClusters, $monthsYear) {

        $clusters = $distClusters['cluster'];
        $dist = $distClusters['district'];
        $months = $this->extractMonth($monthsYear);
        $years = $this->extractYear($monthsYear);

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode, p.provinceRegion as region, p.provinceName as provinceName,
                d.id as dcode, d.districtName as districtName, ccs.cluster, ccs.monitorName, ccs.dcoName, ccs.ccsName,
                ccs.campaignPhase, 
                ".self::$DQL.", Month(ccs.monitoringDate) as mdate, 
                CONCAT(MonthName(ccs.monitoringDate),'-', YEAR(ccs.monitoringDate)) as mName
                FROM AppBundle:IntOdkCcsMonitoring ccs JOIN ccs.district d JOIN d.province p
                WHERE (YEAR(ccs.monitoringDate) IN (:year))
                 AND (MONTH(ccs.monitoringDate) IN (:month))
                 AND ccs.district IN (:dist)
                 AND ccs.cluster IN (:cluster)
                GROUP BY ccs.cluster, ccs.ccsName ORDER BY d.id, ccs.cluster, ccs.dcoName
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
                ".self::$DQL.", Month(ccs.monitoringDate) as mdate, 
                CONCAT(MonthName(ccs.monitoringDate),'-', YEAR(ccs.monitoringDate)) as mName
                FROM AppBundle:IntOdkCcsMonitoring ccs JOIN ccs.district d JOIN d.province p
                WHERE (YEAR(ccs.monitoringDate) IN (:year))
                 AND (MONTH(ccs.monitoringDate) IN (:month))
                GROUP BY p.id, mdate ORDER BY p.id, ccs.monitoringDate DESC
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
                ".self::$DQL.", Month(ccs.monitoringDate) as mdate, 
                CONCAT(MonthName(ccs.monitoringDate),'-', YEAR(ccs.monitoringDate)) as mName
                FROM AppBundle:IntOdkCcsMonitoring ccs JOIN ccs.district d JOIN d.province p
                WHERE (YEAR(ccs.monitoringDate) IN (:year))
                 AND (MONTH(ccs.monitoringDate) IN (:month))
                 AND p.provinceRegion IN (:region)
                GROUP BY p.id, mdate ORDER BY p.id, ccs.monitoringDate DESC
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
                ".self::$DQL.", Month(ccs.monitoringDate) as mdate, 
                CONCAT(MonthName(ccs.monitoringDate),'-', YEAR(ccs.monitoringDate)) as mName
                FROM AppBundle:IntOdkCcsMonitoring ccs JOIN ccs.district d JOIN d.province p
               WHERE (YEAR(ccs.monitoringDate) IN (:year))
                 AND (MONTH(ccs.monitoringDate) IN (:month))
                 AND d.province IN (:prov)
                GROUP BY d.id, mdate ORDER BY p.id, d.id, ccs.monitoringDate DESC
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
                d.id as dcode, d.districtName as districtName, ccs.cluster, ccs.monitorName, ccs.dcoName, ccs.ccsName,
                ccs.campaignPhase, 
                ".self::$DQL.", Month(ccs.monitoringDate) as mdate, 
                CONCAT(MonthName(ccs.monitoringDate),'-', YEAR(ccs.monitoringDate)) as mName
                FROM AppBundle:IntOdkCcsMonitoring ccs JOIN ccs.district d JOIN d.province p
                WHERE (YEAR(ccs.monitoringDate) IN (:year))
                 AND (MONTH(ccs.monitoringDate) IN (:month))
                 AND ccs.district IN (:dist)
                 AND ccs.cluster IS NOT NULL
                GROUP BY ccs.cluster, mdate ORDER BY d.id, ccs.cluster, ccs.monitoringDate DESC
                "
            ) ->setParameters(['dist'=>$district, 'year'=>$years, 'month'=>$months])
            ->getResult(Query::HYDRATE_SCALAR);
    }

    /**
     * @param $distClusters ['district', 'cluster']
     * @param $monthsYear (Year-Month)
     * @return mixed
     */
    public function aggByCcsMonth($distClusters, $monthsYear) {

        $clusters = $distClusters['cluster'];
        $dist = $distClusters['district'];
        $months = $this->extractMonth($monthsYear);
        $years = $this->extractYear($monthsYear);

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p.id as pcode, p.provinceRegion as region, p.provinceName as provinceName,
                d.id as dcode, d.districtName as districtName, ccs.cluster, ccs.monitorName, ccs.dcoName, ccs.ccsName,
                ccs.campaignPhase, 
                ".self::$DQL.", Month(ccs.monitoringDate) as mdate, 
                CONCAT(MonthName(ccs.monitoringDate),'-', YEAR(ccs.monitoringDate)) as mName
                FROM AppBundle:IntOdkCcsMonitoring ccs JOIN ccs.district d JOIN d.province p
                WHERE (YEAR(ccs.monitoringDate) IN (:year))
                 AND (MONTH(ccs.monitoringDate) IN (:month))
                 AND ccs.district IN (:dist)
                 AND ccs.cluster IN (:cluster)
                GROUP BY ccs.ccsName, ccs.cluster, mdate ORDER BY d.id, ccs.cluster, ccs.dcoName
                "
            ) ->setParameters(['dist'=>$dist, 'cluster'=>$clusters, 'year'=>$years, 'month'=>$months])
            ->getResult(Query::HYDRATE_SCALAR);
    }



    /**
     * @return mixed
     */
    private function getLastDate() {

        $data = $this->getEntityManager()->createQuery(
            "SELECT max(tbl.monitoringDate) as lastDate FROM AppBundle:IntOdkCcsMonitoring tbl"
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
