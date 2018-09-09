<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminData
 *
 * @ORM\Table(name="odk_sm_monitoring")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Odk\ExtSmRepo")
 */
class OdkSmMonitoring
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
     * @ORM\Column(type="text", name="ccs_name")
     */
    private $ccsName;

    /**
     * @ORM\Column(type="text", name="sm_name")
     */
    private $smName;

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
     * @ORM\Column(type="integer", name="tracking_missed")
     */
    private $trackingMissed;

    /**
     * @ORM\Column(type="integer", name="tallying")
     */
    private $tallying;

    /**
     * @ORM\Column(type="integer", name="mobilization")
     */
    private $mobilization;

    /**
     * @ORM\Column(type="integer", name="advocacy")
     */
    private $advocacy;

    /**
     * @ORM\Column(type="integer", name="iec_material")
     */
    private $iecMaterial;

    /**
     * @ORM\Column(type="integer", name="higher_supv")
     */
    private $higherSupv;

    /**
     * @ORM\Column(type="integer", name="refusal_challenge")
     */
    private $refusalChallenge;

    /**
     * @ORM\Column(type="integer", name="access_challenge")
     */
    private $accessChallenge;

    /**
     * @ORM\Column(type="date", name="monitoring_date")
     */
    private $monitoringDate;

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
    public function getSmName()
    {
        return $this->smName;
    }

    /**
     * @param mixed $smName
     */
    public function setSmName($smName)
    {
        $this->smName = $smName;
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
    public function getTrackingMissed()
    {
        return $this->trackingMissed;
    }

    /**
     * @param mixed $trackingMissed
     */
    public function setTrackingMissed($trackingMissed)
    {
        $this->trackingMissed = $trackingMissed;
    }

    /**
     * @return mixed
     */
    public function getTallying()
    {
        return $this->tallying;
    }

    /**
     * @param mixed $tallying
     */
    public function setTallying($tallying)
    {
        $this->tallying = $tallying;
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
    public function getAdvocacy()
    {
        return $this->advocacy;
    }

    /**
     * @param mixed $advocacy
     */
    public function setAdvocacy($advocacy)
    {
        $this->advocacy = $advocacy;
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
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set district
     *
     * @param \AppBundle\Entity\District $district
     *
     * @return OdkSmMonitoring
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
