<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 1/23/2018
 * Time: 8:22 PM
 */

namespace AppBundle\Repository\Odk;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\Query;

class CcsSMRepo extends EntityRepository {

    protected $DQL;

    protected $entity;

    public function setDQL($DQL) {
        $this->DQL = $DQL;
    }

    public function setEntity($entity) {
        $this->entity = $entity;
    }
    
    /**
     * @param $function
     * @param $parameters
     * @param null $secondParam
     * @return mixed
     */
    public function callMe($function, $parameters, $secondParam = null) {
        return call_user_func(array($this, $function), $parameters, $secondParam);
    }

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
                d.id as dcode, d.districtName, dt.cluster as clusterNo
                FROM AppBundle:".$this->entity." dt JOIN dt.district d JOIN d.province p
                WHERE (YEAR(dt.monitoringDate) IN (:year))
                 AND (MONTH(dt.monitoringDate) IN (:month))
                 AND dt.district IN (:dist)
                 AND dt.cluster IS NOT NULL
                GROUP BY d.id, dt.cluster ORDER BY d.id, dt.cluster
                "
            ) ->setParameters(['year'=>$years, 'month'=>$months, 'dist'=> $districts])
            ->getResult(Query::HYDRATE_SCALAR);
    }

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
                ".$this->DQL."
                FROM AppBundle:".$this->entity." dt JOIN dt.district d JOIN d.province p
                WHERE (YEAR(dt.monitoringDate) IN (:year))
                 AND (MONTH(dt.monitoringDate) IN (:month))
                GROUP BY p.id ORDER BY  p.id ASC
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
                ".$this->DQL."
                FROM AppBundle:".$this->entity." dt JOIN dt.district d JOIN d.province p
                WHERE (YEAR(dt.monitoringDate) IN (:year))
                 AND (MONTH(dt.monitoringDate) IN (:month))
                 AND p.provinceRegion IN (:region)
                GROUP BY p.id ORDER BY p.id ASC
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
                ".$this->DQL."
                FROM AppBundle:".$this->entity." dt JOIN dt.district d JOIN d.province p
               WHERE (YEAR(dt.monitoringDate) IN (:year))
                 AND (MONTH(dt.monitoringDate) IN (:month))
                 AND d.province IN (:prov)
                GROUP BY p.id, d.id ORDER BY p.id, d.id
                "
            ) ->setParameters(['prov'=>$province,'year'=>$years, 'month'=>$months])
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
                ".$this->DQL.", Month(dt.monitoringDate) as mdate, 
                CONCAT(MonthName(dt.monitoringDate),'-', YEAR(dt.monitoringDate)) as mName
                FROM AppBundle:".$this->entity." dt JOIN dt.district d JOIN d.province p
                WHERE (YEAR(dt.monitoringDate) IN (:year))
                 AND (MONTH(dt.monitoringDate) IN (:month))
                GROUP BY mName, mdate, p.id ORDER BY mdate DESC, p.id ASC
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
                ".$this->DQL.", Month(dt.monitoringDate) as mdate, 
                CONCAT(MonthName(dt.monitoringDate),'-', YEAR(dt.monitoringDate)) as mName
                FROM AppBundle:".$this->entity." dt JOIN dt.district d JOIN d.province p
                WHERE (YEAR(dt.monitoringDate) IN (:year))
                 AND (MONTH(dt.monitoringDate) IN (:month))
                 AND p.provinceRegion IN (:region)
                GROUP BY mName, mDate, p.id ORDER BY mDate DESC, p.id ASC
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
                ".$this->DQL.", Month(dt.monitoringDate) as mdate, 
                CONCAT(MonthName(dt.monitoringDate),'-', YEAR(dt.monitoringDate)) as mName
                FROM AppBundle:".$this->entity." dt JOIN dt.district d JOIN d.province p
               WHERE (YEAR(dt.monitoringDate) IN (:year))
                 AND (MONTH(dt.monitoringDate) IN (:month))
                 AND d.province IN (:prov)
                GROUP BY mName, mdate, p.id, d.id ORDER BY mdate DESC, p.id, d.id ASC 
                "
            ) ->setParameters(['prov'=>$province,'year'=>$years, 'month'=>$months])
            ->getResult(Query::HYDRATE_SCALAR);
    }
    



    /**
     * @return mixed
     */
    protected function getLastDate() {

        $data = $this->getEntityManager()->createQuery(
            "SELECT max(tbl.monitoringDate) as lastDate FROM AppBundle:".$this->entity." tbl"
        )
            ->getResult(Query::HYDRATE_SCALAR);

        return $data[0]['lastDate'];
    }

    protected function getStartDate($endDate) {
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
    protected function extractMonth($monthsYear) {
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
    protected function extractYear($monthsYear) {
        $years = array();
        foreach ($monthsYear as $item) {
            $y = explode("-", $item);
            $years[] = $y[0];
        }

        return $years;
    }

}
