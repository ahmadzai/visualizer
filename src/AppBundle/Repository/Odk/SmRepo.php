<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 1/23/2018
 * Time: 8:22 PM
 */

namespace AppBundle\Repository\Odk;

use Doctrine\ORM\Mapping;
use Doctrine\ORM\Query;

class SmRepo extends CcsSMRepo {

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
                d.id as dcode, d.districtName as districtName, dt.cluster,
                ".$this->DQL."
                FROM AppBundle:".$this->entity." dt JOIN dt.district d JOIN d.province p
                WHERE (YEAR(dt.monitoringDate) IN (:year))
                 AND (MONTH(dt.monitoringDate) IN (:month))
                 AND dt.district IN (:dist)
                 AND dt.cluster IS NOT NULL
                 GROUP BY p.id, d.id, dt.cluster ORDER BY p.id ASC, d.id ASC, dt.cluster ASC
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
                d.id as dcode, d.districtName as districtName, dt.cluster, dt.monitorName, dt.ccsName, dt.smName,
                ".$this->DQL."
                FROM AppBundle:".$this->entity." dt JOIN dt.district d JOIN d.province p
                WHERE (YEAR(dt.monitoringDate) IN (:year))
                 AND (MONTH(dt.monitoringDate) IN (:month))
                 AND dt.district IN (:dist)
                 AND dt.cluster IN (:cluster)
                GROUP BY p.id, d.id, dt.cluster, dt.monitorName, dt.ccsName, dt.smName 
                ORDER BY p.id, d.id, dt.cluster, dt.monitorName, dt.ccsName, dt.smName
                "
            ) ->setParameters(['cluster'=>$clusters, 'year'=>$years, 'month'=>$months, 'dist'=>$dist])
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
                d.id as dcode, d.districtName as districtName, dt.cluster, 
                ".$this->DQL.", Month(dt.monitoringDate) as mdate, 
                CONCAT(MonthName(dt.monitoringDate),'-', YEAR(dt.monitoringDate)) as mName
                FROM AppBundle:".$this->entity." dt JOIN dt.district d JOIN d.province p
                WHERE (YEAR(dt.monitoringDate) IN (:year))
                 AND (MONTH(dt.monitoringDate) IN (:month))
                 AND dt.district IN (:dist)
                 AND dt.cluster IS NOT NULL
                GROUP BY mName, mdate, p.id, d.id, dt.cluster
                ORDER BY mdate Desc, p.id, d.id, dt.cluster
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
                d.id as dcode, d.districtName as districtName, dt.cluster, dt.monitorName, dt.ccsName, dt.smName,
                ".$this->DQL.", Month(dt.monitoringDate) as mdate, 
                CONCAT(MonthName(dt.monitoringDate),'-', YEAR(dt.monitoringDate)) as mName
                FROM AppBundle:".$this->entity." dt JOIN dt.district d JOIN d.province p
                WHERE (YEAR(dt.monitoringDate) IN (:year))
                 AND (MONTH(dt.monitoringDate) IN (:month))
                 AND dt.district IN (:dist)
                 AND dt.cluster IN (:cluster)
                GROUP BY mName, mdate, p.id, d.id, dt.cluster, dt.monitorName, dt.ccsName, dt.smName
                ORDER BY mdate DESC, p.id, d.id, dt.cluster, dt.monitorName, dt.ccsName, dt.smName
                "
            ) ->setParameters(['dist'=>$dist, 'cluster'=>$clusters, 'year'=>$years, 'month'=>$months])
            ->getResult(Query::HYDRATE_SCALAR);
    }

}
