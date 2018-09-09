<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 1/23/2018
 * Time: 8:22 PM
 */

namespace AppBundle\Repository\Odk;

use Doctrine\ORM\Mapping;

class IntCcsRepo extends CcsRepo {

    private $IntDQL = "   count(dt.id) as times,
                                avg(dt.attendance) as attendance, 
                                avg(dt.profile) as profile, 
                                avg(dt.preparedness) as preparedness, 
                                avg(dt.mentoring) as mentoring, 
                                avg(dt.fieldbook) as fieldbook,
                                avg(dt.mobilization) as mobilization, 
                                avg(dt.campPerform) as campPerform, 
                                avg(dt.catchupPerform) as catchupPerform, 
                                avg(dt.iecMaterial) as iecMaterial,
                                avg(dt.refusalChallenge) as refusalChallenge,
                                avg(dt.higherSupv) as higherSup,
                                avg(dt.comSupport) as comSupport,
                                avg(dt.coldchain) as coldchain,
                                avg(dt.accessChallenge) as accessChallenge,
                                avg(dt.overallPerform) as overallPerform ";

    public function __construct($em, Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->setEntity("IntOdkCcsMonitoring");
        $this->setDQL($this->IntDQL);
    }

}
