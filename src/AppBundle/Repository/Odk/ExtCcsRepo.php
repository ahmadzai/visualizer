<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 1/23/2018
 * Time: 8:22 PM
 */

namespace AppBundle\Repository\Odk;

use Doctrine\ORM\Mapping;

class ExtCcsRepo extends CcsRepo {

    private $extDQL = " count(dt.id) as times,
                                avg(dt.attendance) as attendance, 
                                avg(dt.profile) as profile, 
                                avg(dt.preparedness) as preparedness, 
                                avg(dt.mentoring) as mentoring, 
                                avg(dt.trackingMissed) as trackingMissed, 
                                avg(dt.planningReview) as planningReview, 
                                avg(dt.mobilization) as mobilization, 
                                avg(dt.advocacy) as advocacy, 
                                avg(dt.iecMaterial) as iecMaterial,
                                avg(dt.higherSupv) as higherSup,
                                avg(dt.refusalChallenge) as refusalChallenge,
                                avg(dt.accessChallenge) as accessChallenge ";


    public function __construct($em, Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntity("OdkCcsMonitoring");
        $this->setDQL($this->extDQL);
    }

}
