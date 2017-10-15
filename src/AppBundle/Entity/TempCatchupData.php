<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TempCatchupData
 *
 * @ORM\Table(name="temp_catchup_data")
 * @ORM\Entity
 */
class TempCatchupData
{
    /**
     * @var string
     *
     * @ORM\Column(name="cluster_name", type="text", length=65535, nullable=true)
     */
    private $clusterName;

    /**
     * @var string
     *
     * @ORM\Column(name="cluster_no", type="text", length=65535, nullable=true)
     */
    private $clusterNo;

    /**
     * @var string
     *
     * @ORM\Column(name="sub_district_name", type="text", length=65535, nullable=true)
     */
    private $subDistrictName;

    /**
     * @var string
     *
     * @ORM\Column(name="area_name", type="text", length=65535, nullable=true)
     */
    private $areaName;

    /**
     * @var integer
     *
     * @ORM\Column(name="reg_absent", type="integer", nullable=true)
     */
    private $regAbsent;

    /**
     * @var integer
     *
     * @ORM\Column(name="vacc_absent", type="integer", nullable=true)
     */
    private $vaccAbsent;

    /**
     * @var integer
     *
     * @ORM\Column(name="reg_sleep", type="integer", nullable=true)
     */
    private $regSleep;

    /**
     * @var integer
     *
     * @ORM\Column(name="vacc_sleep", type="integer", nullable=true)
     */
    private $vaccSleep;

    /**
     * @var integer
     *
     * @ORM\Column(name="reg_refusal", type="integer", nullable=true)
     */
    private $regRefusal;

    /**
     * @var integer
     *
     * @ORM\Column(name="vacc_refusal", type="integer", nullable=true)
     */
    private $vaccRefusal;

    /**
     * @var integer
     *
     * @ORM\Column(name="new_missed", type="integer", nullable=true)
     */
    private $newMissed;

    /**
     * @var integer
     *
     * @ORM\Column(name="new_vaccinated", type="integer", nullable=true)
     */
    private $newVaccinated;

    /**
     * @var integer
     *
     * @ORM\Column(name="new_remaining", type="integer", nullable=true)
     */
    private $newRemaining;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Campaign
     *
     * @ORM\Column(name="campaign", type="integer")
     */
    private $campaign;

    /**
     * @var \AppBundle\Entity\District
     * @ORM\Column(name="district", type="integer")
     */
    private $district;

    /**
     * @var integer
     *
     * @ORM\Column(name="file", type="integer", nullable=true)
     */
    private $file;

    /**
     * @return string
     */
    public function getClusterName()
    {
        return $this->clusterName;
    }

    /**
     * @param string $clusterName
     */
    public function setClusterName($clusterName)
    {
        $this->clusterName = $clusterName;
    }

    /**
     * @return string
     */
    public function getClusterNo()
    {
        return $this->clusterNo;
    }

    /**
     * @param string $clusterNo
     */
    public function setClusterNo($clusterNo)
    {
        $this->clusterNo = $clusterNo;
    }

    /**
     * @return string
     */
    public function getSubDistrictName()
    {
        return $this->subDistrictName;
    }

    /**
     * @param string $subDistrictName
     */
    public function setSubDistrictName($subDistrictName)
    {
        $this->subDistrictName = $subDistrictName;
    }

    /**
     * @return string
     */
    public function getAreaName()
    {
        return $this->areaName;
    }

    /**
     * @param string $areaName
     */
    public function setAreaName($areaName)
    {
        $this->areaName = $areaName;
    }

    /**
     * @return int
     */
    public function getRegAbsent()
    {
        return $this->regAbsent;
    }

    /**
     * @param int $regAbsent
     */
    public function setRegAbsent($regAbsent)
    {
        $this->regAbsent = $regAbsent;
    }

    /**
     * @return int
     */
    public function getVaccAbsent()
    {
        return $this->vaccAbsent;
    }

    /**
     * @param int $vaccAbsent
     */
    public function setVaccAbsent($vaccAbsent)
    {
        $this->vaccAbsent = $vaccAbsent;
    }

    /**
     * @return int
     */
    public function getRegSleep()
    {
        return $this->regSleep;
    }

    /**
     * @param int $regSleep
     */
    public function setRegSleep($regSleep)
    {
        $this->regSleep = $regSleep;
    }

    /**
     * @return int
     */
    public function getVaccSleep()
    {
        return $this->vaccSleep;
    }

    /**
     * @param int $vaccSleep
     */
    public function setVaccSleep($vaccSleep)
    {
        $this->vaccSleep = $vaccSleep;
    }

    /**
     * @return int
     */
    public function getRegRefusal()
    {
        return $this->regRefusal;
    }

    /**
     * @param int $regRefusal
     */
    public function setRegRefusal($regRefusal)
    {
        $this->regRefusal = $regRefusal;
    }

    /**
     * @return int
     */
    public function getVaccRefusal()
    {
        return $this->vaccRefusal;
    }

    /**
     * @param int $vaccRefusal
     */
    public function setVaccRefusal($vaccRefusal)
    {
        $this->vaccRefusal = $vaccRefusal;
    }

    /**
     * @return int
     */
    public function getNewMissed()
    {
        return $this->newMissed;
    }

    /**
     * @param int $newMissed
     */
    public function setNewMissed($newMissed)
    {
        $this->newMissed = $newMissed;
    }

    /**
     * @return int
     */
    public function getNewVaccinated()
    {
        return $this->newVaccinated;
    }

    /**
     * @param int $newVaccinated
     */
    public function setNewVaccinated($newVaccinated)
    {
        $this->newVaccinated = $newVaccinated;
    }

    /**
     * @return int
     */
    public function getNewRemaining()
    {
        return $this->newRemaining;
    }

    /**
     * @param int $newRemaining
     */
    public function setNewRemaining($newRemaining)
    {
        $this->newRemaining = $newRemaining;
    }

    /**
     * @return Campaign
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * @param Campaign $campaign
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * @return District
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * @param District $district
     */
    public function setDistrict($district)
    {
        $this->district = $district;
    }

    /**
     * @return int
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param int $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


}
