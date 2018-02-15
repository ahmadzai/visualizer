<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminData
 *
 * @ORM\Table(name="admin_data", indexes={@ORM\Index(name="fk_camp_adm_idx", columns={"campaign"}), @ORM\Index(name="district_admindata_idx", columns={"district"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AdminDataRepository")
 */
class AdminData
{
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
     * @var \DateTime
     *
     * @ORM\Column(name="entry_date", type="datetime", nullable=true)
     */
    private $entryDate;

//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="missed", type="integer", nullable=true)
//     */
//    private $missed;
//
//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="sleep", type="integer", nullable=true)
//     */
//    private $sleep;
//
//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="refusal", type="integer", nullable=true)
//     */
//    private $refusal;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Campaign", fetch="EXTRA_LAZY")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="campaign", referencedColumnName="id")
     * })
     */
    private $campaign;

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
     * Set clusterType
     *
     * @param string $clusterType
     *
     * @return AdminData
     */
    public function setClusterType($clusterType)
    {
        $this->clusterType = $clusterType;

        return $this;
    }

    /**
     * Get clusterType
     *
     * @return string
     */
    public function getClusterType()
    {
        return $this->clusterType;
    }

    /**
     * Set clusterNo
     *
     * @param string $clusterNo
     *
     * @return AdminData
     */
    public function setClusterNo($clusterNo)
    {
        $this->clusterNo = $clusterNo;

        return $this;
    }

    /**
     * Get clusterNo
     *
     * @return string
     */
    public function getClusterNo()
    {
        return $this->clusterNo;
    }

    /**
     * Set cluster
     *
     * @param string $cluster
     *
     * @return AdminData
     */
    public function setCluster($cluster)
    {
        $this->cluster = $cluster;

        return $this;
    }

    /**
     * Get cluster
     *
     * @return string
     */
    public function getCluster()
    {
        return $this->cluster;
    }

    /**
     * Set subDistrictName
     *
     * @param string $subDistrictName
     *
     * @return AdminData
     */
    public function setSubDistrictName($subDistrictName)
    {
        $this->subDistrictName = $subDistrictName;

        return $this;
    }

    /**
     * Get subDistrictName
     *
     * @return string
     */
    public function getSubDistrictName()
    {
        return $this->subDistrictName;
    }

    /**
     * Set targetPopulation
     *
     * @param integer $targetPopulation
     *
     * @return AdminData
     */
    public function setTargetPopulation($targetPopulation)
    {
        $this->targetPopulation = $targetPopulation;

        return $this;
    }

    /**
     * Get targetPopulation
     *
     * @return integer
     */
    public function getTargetPopulation()
    {
        return $this->targetPopulation;
    }

    /**
     * Set receivedVials
     *
     * @param integer $receivedVials
     *
     * @return AdminData
     */
    public function setReceivedVials($receivedVials)
    {
        $this->receivedVials = $receivedVials;

        return $this;
    }

    /**
     * Get receivedVials
     *
     * @return integer
     */
    public function getReceivedVials()
    {
        return $this->receivedVials;
    }

    /**
     * Set usedVials
     *
     * @param integer $usedVials
     *
     * @return AdminData
     */
    public function setUsedVials($usedVials)
    {
        $this->usedVials = $usedVials;

        return $this;
    }

    /**
     * Get usedVials
     *
     * @return integer
     */
    public function getUsedVials()
    {
        return $this->usedVials;
    }

    /**
     * Set child011
     *
     * @param integer $child011
     *
     * @return AdminData
     */
    public function setChild011($child011)
    {
        $this->child011 = $child011;

        return $this;
    }

    /**
     * Get child011
     *
     * @return integer
     */
    public function getChild011()
    {
        return $this->child011;
    }

    /**
     * Set child1259
     *
     * @param integer $child1259
     *
     * @return AdminData
     */
    public function setChild1259($child1259)
    {
        $this->child1259 = $child1259;

        return $this;
    }

    /**
     * Get child1259
     *
     * @return integer
     */
    public function getChild1259()
    {
        return $this->child1259;
    }

    /**
     * Set regAbsent
     *
     * @param integer $regAbsent
     *
     * @return AdminData
     */
    public function setRegAbsent($regAbsent)
    {
        $this->regAbsent = $regAbsent;

        return $this;
    }

    /**
     * Get regAbsent
     *
     * @return integer
     */
    public function getRegAbsent()
    {
        return $this->regAbsent;
    }

    /**
     * Set vaccAbsent
     *
     * @param integer $vaccAbsent
     *
     * @return AdminData
     */
    public function setVaccAbsent($vaccAbsent)
    {
        $this->vaccAbsent = $vaccAbsent;

        return $this;
    }

    /**
     * Get vaccAbsent
     *
     * @return integer
     */
    public function getVaccAbsent()
    {
        return $this->vaccAbsent;
    }

    /**
     * Set regSleep
     *
     * @param integer $regSleep
     *
     * @return AdminData
     */
    public function setRegSleep($regSleep)
    {
        $this->regSleep = $regSleep;

        return $this;
    }

    /**
     * Get regSleep
     *
     * @return integer
     */
    public function getRegSleep()
    {
        return $this->regSleep;
    }

    /**
     * Set vaccSleep
     *
     * @param integer $vaccSleep
     *
     * @return AdminData
     */
    public function setVaccSleep($vaccSleep)
    {
        $this->vaccSleep = $vaccSleep;

        return $this;
    }

    /**
     * Get vaccSleep
     *
     * @return integer
     */
    public function getVaccSleep()
    {
        return $this->vaccSleep;
    }

    /**
     * Set regRefusal
     *
     * @param integer $regRefusal
     *
     * @return AdminData
     */
    public function setRegRefusal($regRefusal)
    {
        $this->regRefusal = $regRefusal;

        return $this;
    }

    /**
     * Get regRefusal
     *
     * @return integer
     */
    public function getRegRefusal()
    {
        return $this->regRefusal;
    }

    /**
     * Set vaccRefusal
     *
     * @param integer $vaccRefusal
     *
     * @return AdminData
     */
    public function setVaccRefusal($vaccRefusal)
    {
        $this->vaccRefusal = $vaccRefusal;

        return $this;
    }

    /**
     * Get vaccRefusal
     *
     * @return integer
     */
    public function getVaccRefusal()
    {
        return $this->vaccRefusal;
    }

    /**
     * Set newPolioCase
     *
     * @param integer $newPolioCase
     *
     * @return AdminData
     */
    public function setNewPolioCase($newPolioCase)
    {
        $this->newPolioCase = $newPolioCase;

        return $this;
    }

    /**
     * Get newPolioCase
     *
     * @return integer
     */
    public function getNewPolioCase()
    {
        return $this->newPolioCase;
    }

    /**
     * Set vaccDay
     *
     * @param integer $vaccDay
     *
     * @return AdminData
     */
    public function setVaccDay($vaccDay)
    {
        $this->vaccDay = $vaccDay;

        return $this;
    }

    /**
     * Get vaccDay
     *
     * @return integer
     */
    public function getVaccDay()
    {
        return $this->vaccDay;
    }

    /**
     * Set entryDate
     *
     * @param \DateTime $entryDate
     *
     * @return AdminData
     */
    public function setEntryDate($entryDate)
    {
        $this->entryDate = $entryDate;

        return $this;
    }

    /**
     * Get entryDate
     *
     * @return \DateTime
     */
    public function getEntryDate()
    {
        return $this->entryDate;
    }


//    /**
//     * Get missed
//     *
//     * @return integer
//     */
//    public function getMissed()
//    {
//        return $this->missed;
//    }
//
//
//    /**
//     * Get sleep
//     *
//     * @return integer
//     */
//    public function getSleep()
//    {
//        return $this->sleep;
//    }
//
//
//    /**
//     * Get refusal
//     *
//     * @return integer
//     */
//    public function getRefusal()
//    {
//        return $this->refusal;
//    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set campaign
     *
     * @param \AppBundle\Entity\Campaign $campaign
     *
     * @return AdminData
     */
    public function setCampaign(\AppBundle\Entity\Campaign $campaign = null)
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return \AppBundle\Entity\Campaign
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * Set district
     *
     * @param \AppBundle\Entity\District $district
     *
     * @return AdminData
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
