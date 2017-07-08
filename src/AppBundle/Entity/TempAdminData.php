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
     * @ORM\Column(name="sub_dist_name", type="text", length=65535, nullable=true)
     */
    private $subDistName;

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
     * @ORM\Column(name="cluster", type="string", length=45, nullable=true)
     */
    private $cluster;

    /**
     * @var string
     *
     * @ORM\Column(name="target_pop", type="text", length=65535, nullable=true)
     */
    private $targetPop;

    /**
     * @var integer
     *
     * @ORM\Column(name="given_vials", type="integer", nullable=true)
     */
    private $givenVials;

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
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set district
     *
     * @param integer $district
     *
     * @return TempAdminData
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return integer
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set subDistName
     *
     * @param string $subDistName
     *
     * @return TempAdminData
     */
    public function setSubDistName($subDistName)
    {
        $this->subDistName = $subDistName;

        return $this;
    }

    /**
     * Get subDistName
     *
     * @return string
     */
    public function getSubDistName()
    {
        return $this->subDistName;
    }

    /**
     * Set clusterName
     *
     * @param string $clusterName
     *
     * @return TempAdminData
     */
    public function setClusterName($clusterName)
    {
        $this->clusterName = $clusterName;

        return $this;
    }

    /**
     * Get clusterName
     *
     * @return string
     */
    public function getClusterName()
    {
        return $this->clusterName;
    }

    /**
     * Set clusterNo
     *
     * @param string $clusterNo
     *
     * @return TempAdminData
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
     * @return TempAdminData
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
     * Set targetPop
     *
     * @param string $targetPop
     *
     * @return TempAdminData
     */
    public function setTargetPop($targetPop)
    {
        $this->targetPop = $targetPop;

        return $this;
    }

    /**
     * Get targetPop
     *
     * @return string
     */
    public function getTargetPop()
    {
        return $this->targetPop;
    }

    /**
     * Set givenVials
     *
     * @param integer $givenVials
     *
     * @return TempAdminData
     */
    public function setGivenVials($givenVials)
    {
        $this->givenVials = $givenVials;

        return $this;
    }

    /**
     * Get givenVials
     *
     * @return integer
     */
    public function getGivenVials()
    {
        return $this->givenVials;
    }

    /**
     * Set usedVials
     *
     * @param integer $usedVials
     *
     * @return TempAdminData
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
     * @return TempAdminData
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
     * @return TempAdminData
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
     * @return TempAdminData
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
     * @return TempAdminData
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
     * @return TempAdminData
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
     * @return TempAdminData
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
     * @return TempAdminData
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
     * @return TempAdminData
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
     * @return TempAdminData
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
     * @return TempAdminData
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
     * Set campaign
     *
     * @param integer $campaign
     *
     * @return TempAdminData
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return integer
     */
    public function getCampaign()
    {
        return $this->campaign;
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
