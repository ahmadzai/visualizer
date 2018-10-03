<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;

/**
 * AdminData
 *
 * @ORM\Table(name="int_odk_ccs_monitoring")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Odk\IntCcsRepo")
 */
class IntOdkCcsMonitoring
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var \AppBundle\Entity\District
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\District", fetch="EXTRA_LAZY")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="district", referencedColumnName="id")
     * })
     */
    private $district;

    /**
     * @ORM\Column(type="string", name="cluster")
     */
    private $cluster;

    /**
     * @ORM\Column(type="text", name="monitor_name")
     */
    private $monitorName;

    /**
     * @ORM\Column(type="text", name="dco_name")
     */
    private $dcoName;

    /**
     * @ORM\Column(type="text", name="ccs_name")
     */
    private $ccsName;

    /**
     * @ORM\Column(type="string", name="campaign_phase")
     */
    private $campaignPhase;

    /**
     * @ORM\Column(type="integer", name="attendance")
     */
    private $attendance;

    /**
     * @ORM\Column(type="integer", name="profile", length=2)
     */
    private $profile;

    /**
     * @ORM\Column(type="integer", name="preparedness")
     */
    private $preparedness;

    /**
     * @ORM\Column(type="integer", name="mentoring")
     */
    private $mentoring;

    /**
     * @ORM\Column(type="integer", name="fieldbook")
     */
    private $fieldbook;

    /**
     * @ORM\Column(type="integer", name="mobilization")
     */
    private $mobilization;

    /**
     * @ORM\Column(type="integer", name="camp_perform")
     */
    private $campPerform;

    /**
     * @ORM\Column(type="integer", name="catchup_perform")
     */
    private $catchupPerform;

    /**
     * @ORM\Column(type="integer", name="iec_material")
     */
    private $iecMaterial;

    /**
     * @ORM\Column(type="integer", name="refusal_challenge")
     */
    private $refusalChallenge;

    /**
     * @ORM\Column(type="integer", name="higher_supv")
     */
    private $higherSupv;

    /**
     * @ORM\Column(type="integer", name="com_support")
     */
    private $comSupport;

    /**
     * @ORM\Column(type="integer", name="coldchain")
     */
    private $coldchain;

    /**
     * @ORM\Column(type="integer", name="access_challenge")
     */
    private $accessChallenge;

    /**
     * @ORM\Column(type="integer", name="overall_perform")
     */
    private $overallPerform;

    /**
     * @ORM\Column(name="monitoring_date", type="date")
     */
    private $monitoringDate;

    /**
     * @ORM\Column(name="submission_date", type="date")
     */
    private $submissionDate;

    /**
     * @return mixed
     */
    public function getCluster()
    {
        return $this->cluster;
    }

    /**
     * @param mixed $cluster
     */
    public function setCluster($cluster)
    {
        $this->cluster = $cluster;
    }

    /**
     * @return mixed
     */
    public function getMonitorName()
    {
        return $this->monitorName;
    }

    /**
     * @param mixed $monitorName
     */
    public function setMonitorName($monitorName)
    {
        $this->monitorName = $monitorName;
    }

    /**
     * @return mixed
     */
    public function getDcoName()
    {
        return $this->dcoName;
    }

    /**
     * @param mixed $dcoName
     */
    public function setDcoName($dcoName)
    {
        $this->dcoName = $dcoName;
    }

    /**
     * @return mixed
     */
    public function getCcsName()
    {
        return $this->ccsName;
    }

    /**
     * @param mixed $ccsName
     */
    public function setCcsName($ccsName)
    {
        $this->ccsName = $ccsName;
    }

    /**
     * @return mixed
     */
    public function getCampaignPhase()
    {
        return $this->campaignPhase;
    }

    /**
     * @param mixed $campaignPhase
     */
    public function setCampaignPhase($campaignPhase)
    {
        $this->campaignPhase = $campaignPhase;
    }

    /**
     * @return mixed
     */
    public function getAttendance()
    {
        return $this->attendance;
    }

    /**
     * @param mixed $attendance
     */
    public function setAttendance($attendance)
    {
        $this->attendance = $attendance;
    }

    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param mixed $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return mixed
     */
    public function getPreparedness()
    {
        return $this->preparedness;
    }

    /**
     * @param mixed $preparedness
     */
    public function setPreparedness($preparedness)
    {
        $this->preparedness = $preparedness;
    }

    /**
     * @return mixed
     */
    public function getMentoring()
    {
        return $this->mentoring;
    }

    /**
     * @param mixed $mentoring
     */
    public function setMentoring($mentoring)
    {
        $this->mentoring = $mentoring;
    }

    /**
     * @return mixed
     */
    public function getFieldbook()
    {
        return $this->fieldbook;
    }

    /**
     * @param mixed $fieldbook
     */
    public function setFieldbook($fieldbook)
    {
        $this->fieldbook = $fieldbook;
    }

    /**
     * @return mixed
     */
    public function getMobilization()
    {
        return $this->mobilization;
    }

    /**
     * @param mixed $mobilization
     */
    public function setMobilization($mobilization)
    {
        $this->mobilization = $mobilization;
    }

    /**
     * @return mixed
     */
    public function getCampPerform()
    {
        return $this->campPerform;
    }

    /**
     * @param mixed $campPerform
     */
    public function setCampPerform($campPerform)
    {
        $this->campPerform = $campPerform;
    }

    /**
     * @return mixed
     */
    public function getCatchupPerform()
    {
        return $this->catchupPerform;
    }

    /**
     * @param mixed $catchupPerform
     */
    public function setCatchupPerform($catchupPerform)
    {
        $this->catchupPerform = $catchupPerform;
    }

    /**
     * @return mixed
     */
    public function getIecMaterial()
    {
        return $this->iecMaterial;
    }

    /**
     * @param mixed $iecMaterial
     */
    public function setIecMaterial($iecMaterial)
    {
        $this->iecMaterial = $iecMaterial;
    }

    /**
     * @return mixed
     */
    public function getRefusalChallenge()
    {
        return $this->refusalChallenge;
    }

    /**
     * @param mixed $refusalChallenge
     */
    public function setRefusalChallenge($refusalChallenge)
    {
        $this->refusalChallenge = $refusalChallenge;
    }

    /**
     * @return mixed
     */
    public function getHigherSupv()
    {
        return $this->higherSupv;
    }

    /**
     * @param mixed $higherSupv
     */
    public function setHigherSupv($higherSupv)
    {
        $this->higherSupv = $higherSupv;
    }

    /**
     * @return mixed
     */
    public function getComSupport()
    {
        return $this->comSupport;
    }

    /**
     * @param mixed $comSupport
     */
    public function setComSupport($comSupport)
    {
        $this->comSupport = $comSupport;
    }

    /**
     * @return mixed
     */
    public function getColdchain()
    {
        return $this->coldchain;
    }

    /**
     * @param mixed $coldchain
     */
    public function setColdchain($coldchain)
    {
        $this->coldchain = $coldchain;
    }

    /**
     * @return mixed
     */
    public function getAccessChallenge()
    {
        return $this->accessChallenge;
    }

    /**
     * @param mixed $accessChallenge
     */
    public function setAccessChallenge($accessChallenge)
    {
        $this->accessChallenge = $accessChallenge;
    }

    /**
     * @return mixed
     */
    public function getOverallPerform()
    {
        return $this->overallPerform;
    }

    /**
     * @param mixed $overallPerform
     */
    public function setOverallPerform($overallPerform)
    {
        $this->overallPerform = $overallPerform;
    }


    /**
     * @return mixed
     */
    public function getMonitoringDate()
    {
        return \DateTimeImmutable::createFromFormat('Y-m-d',$this->monitoringDate);
    }

    /**
     * @param mixed $monitoringDate
     */
    public function setMonitoringDate($monitoringDate)
    {
        $this->monitoringDate = \DateTimeImmutable::createFromFormat('Y-m-d',$monitoringDate);
    }

    /**
     * @return mixed
     */
    public function getSubmissionDate()
    {
        return \DateTimeImmutable::createFromFormat('Y-m-d',$this->submissionDate);
    }

    /**
     * @param mixed $submissionDate
     */
    public function setSubmissionDate($submissionDate)
    {
        $this->monitoringDate = \DateTimeImmutable::createFromFormat('Y-m-d',$submissionDate);
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set district
     *
     * @param \AppBundle\Entity\District $district
     * @return $this
     */
    public function setDistrict(\AppBundle\Entity\District $district = null)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return \AppBundle\Entity\District
     */
    public function getDistrict()
    {
        return $this->district;
    }
}
