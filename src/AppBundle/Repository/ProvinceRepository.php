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

class ProvinceRepository extends EntityRepository {
    /***
     * @return array
     */
    public function selectAllDistricts() {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT d FROM AppBundle:DistrictData d"
            )
            ->getResult(Query::HYDRATE_SCALAR);
    }

    public function selectAllRegions() {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT DISTINCT p.provinceRegion as provinceRegion FROM AppBundle:Province p ORDER BY p.provinceRegion"
            )
            ->getResult(Query::HYDRATE_SCALAR);
    }

    public function selectProvinceByRegion($region) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT DISTINCT p FROM AppBundle:Province p WHERE p.provinceRegion IN (:region) ORDER BY p.provinceRegion"
            ) ->setParameter('region', $region)
            ->getResult(Query::HYDRATE_SCALAR);
    }

}
