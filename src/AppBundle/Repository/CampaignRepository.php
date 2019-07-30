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

class CampaignRepository extends EntityRepository {
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

    public function selectCampaignBySource($source) {
        $entity = "AppBundle:".$source;
        return $this->getEntityManager()
            ->createQuery(
                "SELECT DISTINCT cmp.id, cmp.campaignName FROM $entity s JOIN s.campaign cmp ORDER BY cmp.id DESC "
            )
            ->getResult();
    }

    public function searchCampaigns($term) {
        $term = $term ? $term : '';
        return $this->getEntityManager()
            ->getRepository('AppBundle:Campaign')
            ->createQueryBuilder('p')
            ->where('p.campaignName LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
