<?php


namespace AppBundle\Repository\CoverageCatchup;

use Doctrine\ORM\Mapping;
use Doctrine\ORM\Query;

class RefusalCommRepository extends ChartRepo
{

    protected $DQL = " sum(COALESCE(cvr.regRefusal)) as refusalAfterDay5, 
                       sum(COALESCE(cvr.refusalVacInCatchup)) as refusalVacInCatchup,
                       sum(COALESCE(cvr.refusalVacByCRC)) as refusalVacByCRC, 
                       sum(COALESCE(cvr.refusalVacByRC)) as refusalVacByRC,
                       sum(COALESCE(cvr.refusalVacByCIP)) as refusalVacByCIP, 
                       sum(COALESCE(cvr.refusalVacBySeniorStaff)) as refusalVacBySenior,
                       (sum(COALESCE(cvr.refusalVacByCRC)) + 
                        sum(COALESCE(cvr.refusalVacByRC)) + 
                        sum(COALESCE(cvr.refusalVacByCIP)) + 
                        sum(COALESCE(cvr.refusalVacBySeniorStaff))
                        ) as totalRefusalVacByRefComm
                    ";



    public function __construct($em, Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setDQL($this->DQL);
        $this->setEntity("RefusalComm");
    }

}