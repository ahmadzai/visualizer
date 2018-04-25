<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 1/23/2018
 * Time: 8:22 PM
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Fieldbook;
use AppBundle\Form\FieldbookType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class FieldbookRepository extends EntityRepository {


    public function getMaxId() {
        return $this->_em->createQuery("
                  Select Max(fb.id) FROM AppBundle:Fieldbook fb
                ")->getSingleResult();

    }


}
