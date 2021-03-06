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

class HeatmapRepository extends EntityRepository {
    /***
     * @param string $source
     * @param string $indicator
     * @return array
     */
    public function findOne($source, $indicator) {
        $result = $this->getEntityManager()
            ->createQuery(
                "SELECT h.minValue as minValue, h.maxValue as maxValue,
                      h.midStop as midStop, h.minColor as minColor, h.midColor as midColor,
                      h.maxColor as maxColor
                      FROM AppBundle:HeatmapBenchmark h
                      WHERE h.dataSource = :source AND h.indicator = :ind "
            ) ->setParameters(['source'=>$source, 'ind'=>$indicator])
            ->getResult(Query::HYDRATE_OBJECT);

        if($result)
            return $result[0];
        else
            return null;
    }


}
