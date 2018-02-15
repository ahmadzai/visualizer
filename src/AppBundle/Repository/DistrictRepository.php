<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 1/23/2018
 * Time: 9:01 PM
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class DistrictRepository extends EntityRepository {

    public function selectDistrictByProvince($province) {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT d, p.id, p.provinceName FROM AppBundle:District d JOIN d.province p WHERE d.province IN (:province) ORDER BY d.province"
            ) ->setParameter('province', $province)
            ->getResult(Query::HYDRATE_SCALAR);
    }


//    public function selectProvinceByDistrict(district) {
//        return $this->getEntityManager()
//            ->createQuery(
//                "SELECT d, p.id, p.provinceName FROM AppBundle:District d JOIN d.province p WHERE d.id IN (:district) ORDER BY d.province"
//            ) ->setParameter('province', $province)
//            ->getResult(Query::HYDRATE_SCALAR);
//    }

}