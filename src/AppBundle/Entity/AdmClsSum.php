<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdmClsSum
 *
 * @ORM\Table(name="adm_cls_sum")
 * @ORM\Entity(readOnly=true)
 */
class AdmClsSum
{
    /**
     * @var integer
     *
     * @ORM\Column(name="district", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $district;

    /**
     * @var string
     *
     * @ORM\Column(name="district_name", type="text", length=65535, precision=0, scale=0, nullable=true, unique=false)
     */
    private $districtName;

    /**
     * @var string
     *
     * @ORM\Column(name="district_risk_status", type="text", length=65535, precision=0, scale=0, nullable=true, unique=false)
     */
    private $districtRiskStatus;

    /**
     * @var integer
     *
     * @ORM\Column(name="province", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="province_name", type="text", length=65535, precision=0, scale=0, nullable=true, unique=false)
     */
    private $provinceName;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="text", length=65535, precision=0, scale=0, nullable=true, unique=false)
     */
    private $region;

    /**
     * @var integer
     *
     * @ORM\Column(name="campaign", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $campaign;

    /**
     * @var string
     *
     * @ORM\Column(name="campaign_name", type="text", length=65535, precision=0, scale=0, nullable=true, unique=false)
     */
    private $campaignName;

    /**
     * @var string
     *
     * @ORM\Column(name="campaign_type", type="text", length=65535, precision=0, scale=0, nullable=true, unique=false)
     */
    private $campaignType;

    /**
     * @var string
     *
     * @ORM\Column(name="campaign_date", type="text", length=65535, precision=0, scale=0, nullable=true, unique=false)
     */
    private $campaignDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="campaign_year", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $campaignYear;

    /**
     * @var string
     *
     * @ORM\Column(name="campaign_month", type="string", length=20, precision=0, scale=0, nullable=true, unique=false)
     */
    private $campaignMonth;

    /**
     * @var string
     *
     * @ORM\Column(name="cluster", type="string", length=30, precision=0, scale=0, nullable=true, unique=false)
     */
    private $cluster;

    /**
     * @var string
     *
     * @ORM\Column(name="sub_district", type="text", length=65535, precision=0, scale=0, nullable=true, unique=false)
     */
    private $subDistrict;

    /**
     * @var integer
     *
     * @ORM\Column(name="target_population", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $targetPopulation;

    /**
     * @var integer
     *
     * @ORM\Column(name="received_vials", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $receivedVials;

    /**
     * @var integer
     *
     * @ORM\Column(name="used_vials", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $usedVials;

    /**
     * @var integer
     *
     * @ORM\Column(name="child_0_11", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $child011;

    /**
     * @var integer
     *
     * @ORM\Column(name="child_12_59", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $child1259;

    /**
     * @var integer
     *
     * @ORM\Column(name="reg_absent", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $regAbsent;

    /**
     * @var integer
     *
     * @ORM\Column(name="vacc_absent_3days", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $vaccAbsent3Days;

    /**
     * @var integer
     *
     * @ORM\Column(name="vacc_absent_5day", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $vaccAbsent5Day;

    /**
     * @var integer
     *
     * @ORM\Column(name="reg_sleep", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $regSleep;

    /**
     * @var integer
     *
     * @ORM\Column(name="vacc_sleep_3days", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $vaccSleep3Days;

    /**
     * @var integer
     *
     * @ORM\Column(name="vacc_sleep_5day", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $vaccSleep5Day;

    /**
     * @var integer
     *
     * @ORM\Column(name="reg_refusal", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $regRefusal;

    /**
     * @var integer
     *
     * @ORM\Column(name="vacc_refusal_3days", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $vaccRefusal3Days;

    /**
     * @var integer
     *
     * @ORM\Column(name="vacc_refusal_5day", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $vaccRefusal5Day;

    /**
     * @var integer
     *
     * @ORM\Column(name="new_polio_case", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $newPolioCase;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
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
     * @return string
     */
    public function getDistrictName()
    {
        return $this->districtName;
    }

    /**
     * @return string
     */
    public function getDistrictRiskStatus()
    {
        return $this->districtRiskStatus;
    }

    /**
     * @return int
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @return string
     */
    public function getProvinceName()
    {
        return $this->provinceName;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return int
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * @return string
     */
    public function getCampaignName()
    {
        return $this->campaignName;
    }

    /**
     * @return string
     */
    public function getCampaignType()
    {
        return $this->campaignType;
    }

    /**
     * @return string
     */
    public function getCampaignDate()
    {
        return $this->campaignDate;
    }

    /**
     * @return int
     */
    public function getCampaignYear()
    {
        return $this->campaignYear;
    }

    /**
     * @return string
     */
    public function getCampaignMonth()
    {
        return $this->campaignMonth;
    }

    /**
     * @return string
     */
    public function getCluster()
    {
        return $this->cluster;
    }

    /**
     * @return string
     */
    public function getSubDistrict()
    {
        return $this->subDistrict;
    }

    /**
     * @return int
     */
    public function getTargetPopulation()
    {
        return $this->targetPopulation;
    }

    /**
     * @return int
     */
    public function getReceivedVials()
    {
        return $this->receivedVials;
    }

    /**
     * @return int
     */
    public function getUsedVials()
    {
        return $this->usedVials;
    }

    /**
     * @return int
     */
    public function getChild011()
    {
        return $this->child011;
    }

    /**
     * @return int
     */
    public function getChild1259()
    {
        return $this->child1259;
    }

    /**
     * @return int
     */
    public function getRegAbsent()
    {
        return $this->regAbsent;
    }

    /**
     * @return int
     */
    public function getVaccAbsent3Days()
    {
        return $this->vaccAbsent3Days;
    }

    /**
     * @return int
     */
    public function getVaccAbsent5Day()
    {
        return $this->vaccAbsent5Day;
    }

    /**
     * @return int
     */
    public function getRegSleep()
    {
        return $this->regSleep;
    }

    /**
     * @return int
     */
    public function getVaccSleep3Days()
    {
        return $this->vaccSleep3Days;
    }

    /**
     * @return int
     */
    public function getVaccSleep5Day()
    {
        return $this->vaccSleep5Day;
    }

    /**
     * @return int
     */
    public function getRegRefusal()
    {
        return $this->regRefusal;
    }

    /**
     * @return int
     */
    public function getVaccRefusal3Days()
    {
        return $this->vaccRefusal3Days;
    }

    /**
     * @return int
     */
    public function getVaccRefusal5Day()
    {
        return $this->vaccRefusal5Day;
    }

    /**
     * @return int
     */
    public function getNewPolioCase()
    {
        return $this->newPolioCase;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }




}
