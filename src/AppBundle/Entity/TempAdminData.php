<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TempAdminData
 *
 * @ORM\Table(name="temp_admin_data")
 * @ORM\Entity
 */
class TempAdminData
{
    /**
     * @var integer
     *
     * @ORM\Column(name="district", type="integer", nullable=true)
     */
    private $district;

    /**
     * @var string
     *
     * @ORM\Column(name="cluster_type", type="text", length=65535, nullable=true)
     */
    private $clusterType;

    /**
     * @var string
     *
     * @ORM\Column(name="cluster_no", type="text", length=65535, nullable=true)
     */
    private $clusterNo;

    /**
     * @var string
     *
     * @ORM\Column(name="cluster", type="string", length=30, nullable=true)
     */
    private $cluster;

    /**
     * @var string
     *
     * @ORM\Column(name="sub_district_name", type="text", length=65535, nullable=true)
     */
    private $subDistrictName;

    /**
     * @var integer
     *
     * @ORM\Column(name="target_population", type="integer", nullable=true)
     */
    private $targetPopulation;

    /**
     * @var integer
     *
     * @ORM\Column(name="received_vials", type="integer", nullable=true)
     */
    private $receivedVials;

    /**
     * @var integer
     *
     * @ORM\Column(name="used_vials", type="integer", nullable=true)
     */
    private $usedVials;

    /**
     * @var integer
     *
     * @ORM\Column(name="child_0_11", type="integer", nullable=true)
     */
    private $child011;

    /**
     * @var integer
     *
     * @ORM\Column(name="child_12_59", type="integer", nullable=true)
     */
    private $child1259;

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
     * @ORM\Column(name="new_polio_case", type="integer", nullable=true)
     */
    private $newPolioCase;

    /**
     * @var integer
     *
     * @ORM\Column(name="vacc_day", type="integer", nullable=true)
     */
    private $vaccDay;

    /**
     * @var integer
     *
     * @ORM\Column(name="campaign", type="integer", nullable=true)
     */
    private $campaign;

    /**
     * @var integer
     *
     * @ORM\Column(name="file", type="integer", nullable=true)
     */
    private $file;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @return int
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * @param int $district
     */
    public function setDistrict($district)
    {
        $this->district = $district;
    }

    /**
     * @return string
     */
    public function getClusterType()
    {
        return $this->clusterType;
    }

    /**
     * @param string $clusterType
     */
    public function setClusterType($clusterType)
    {
        $this->clusterType = $clusterType;
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
    public function getCluster()
    {
        return $this->cluster;
    }

    /**
     * @param string $cluster
     */
    public function setCluster($cluster)
    {
        $this->cluster = $cluster;
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
     * @return int
     */
    public function getTargetPopulation()
    {
        return $this->targetPopulation;
    }

    /**
     * @param int $targetPopulation
     */
    public function setTargetPopulation($targetPopulation)
    {
        $this->targetPopulation = $targetPopulation;
    }

    /**
     * @return int
     */
    public function getReceivedVials()
    {
        return $this->receivedVials;
    }

    /**
     * @param int $receivedVials
     */
    public function setReceivedVials($receivedVials)
    {
        $this->receivedVials = $receivedVials;
    }

    /**
     * @return int
     */
    public function getUsedVials()
    {
        return $this->usedVials;
    }

    /**
     * @param int $usedVials
     */
    public function setUsedVials($usedVials)
    {
        $this->usedVials = $usedVials;
    }

    /**
     * @return int
     */
    public function getChild011()
    {
        return $this->child011;
    }

    /**
     * @param int $child011
     */
    public function setChild011($child011)
    {
        $this->child011 = $child011;
    }

    /**
     * @return int
     */
    public function getChild1259()
    {
        return $this->child1259;
    }

    /**
     * @param int $child1259
     */
    public function setChild1259($child1259)
    {
        $this->child1259 = $child1259;
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
    public function getNewPolioCase()
    {
        return $this->newPolioCase;
    }

    /**
     * @param int $newPolioCase
     */
    public function setNewPolioCase($newPolioCase)
    {
        $this->newPolioCase = $newPolioCase;
    }

    /**
     * @return int
     */
    public function getVaccDay()
    {
        return $this->vaccDay;
    }

    /**
     * @param int $vaccDay
     */
    public function setVaccDay($vaccDay)
    {
        $this->vaccDay = $vaccDay;
    }

    /**
     * @return int
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * @param int $campaign
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
