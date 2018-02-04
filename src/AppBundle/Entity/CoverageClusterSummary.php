<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoverageClusterSummary
 * @ORM\Table(name="coverage_cluster_summary")
 * @ORM\Entity(readOnly=true)
 */
class CoverageClusterSummary
{
    /**
     * @var string
     *
     * @ORM\Column(name="sub_district", type="text", length=100, nullable=true)
     */
    private $subDistrict;

    /**
     * @var string
     *
     * @ORM\Column(name="cluster_no", type="text", length=50, nullable=true)
     */
    private $clusterNo;


    /**
     * @var integer
     *
     * @ORM\Column(name="no_teams", type="integer", nullable=true)
     */
    private $noTeams;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_houses", type="integer", nullable=true)
     */
    private $noHouses;

    /**
     * @var integer
     *
     * @ORM\Column(name="target_children", type="integer", nullable=true)
     */
    private $targetChildren;

    /**
     * @var integer
     *
     * @ORM\Column(name="vials_received", type="integer", nullable=true)
     */
    private $vialsReceived;

    /**
     * @var integer
     *
     * @ORM\Column(name="vials_used", type="integer", nullable=true)
     */
    private $vialsUsed;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_houses_visited", type="integer", nullable=true)
     */
    private $noHousesVisited;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_resident_children", type="integer", nullable=true)
     */
    private $noResidentChildren;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_guest_children", type="integer", nullable=true)
     */
    private $noGuestChildren;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_child_in_house_vac", type="integer", nullable=true)
     */
    private $noChildInHouseVac;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_child_outside_vac", type="integer", nullable=true)
     */
    private $noChildOutsideVac;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_absent_same_day", type="integer", nullable=true)
     */
    private $noAbsentSameDay;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_absent_same_day_found_vac", type="integer", nullable=true)
     */
    private $noAbsentSameDayFoundVac;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_absent_same_day_vac_by_team", type="integer", nullable=true)
     */
    private $noAbsentSameDayVacByTeam;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_absent_not_same_day", type="integer", nullable=true)
     */
    private $noAbsentNotSameDay;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_absent_not_same_day_found_vac", type="integer", nullable=true)
     */
    private $noAbsentNotSameDayFoundVac;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_absent_not_same_day_vac_by_team", type="integer", nullable=true)
     */
    private $noAbsentNotSameDayVacByTeam;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_nss", type="integer", nullable=true)
     */
    private $noNSS;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_nss_found_vac", type="integer", nullable=true)
     */
    private $noNSSFoundVac;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_nss_vac_by_team", type="integer", nullable=true)
     */
    private $noNSSVacByTeam;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_refusal", type="integer", nullable=true)
     */
    private $noRefusal;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_refusal_found_vac", type="integer", nullable=true)
     */
    private $noRefusalFoundVac;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_refusal_vac_by_team", type="integer", nullable=true)
     */
    private $noRefusalVacByTeam;

    /**
     * @var integer
     *
     * @ORM\Column(name="afp_case", type="integer", nullable=true)
     */
    private $afpCase;



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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @return string
     */
    public function getSubDistrict()
    {
        return $this->subDistrict;
    }

    /**
     * @param string $subDistrict
     */
    public function setSubDistrict($subDistrict)
    {
        $this->subDistrict = $subDistrict;
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
     * @return int
     */
    public function getNoTeams()
    {
        return $this->noTeams;
    }

    /**
     * @param int $noTeams
     */
    public function setNoTeams($noTeams)
    {
        $this->noTeams = $noTeams;
    }

    /**
     * @return int
     */
    public function getNoHouses()
    {
        return $this->noHouses;
    }

    /**
     * @param int $noHouses
     */
    public function setNoHouses($noHouses)
    {
        $this->noHouses = $noHouses;
    }

    /**
     * @return int
     */
    public function getTargetChildren()
    {
        return $this->targetChildren;
    }

    /**
     * @param int $targetChildren
     */
    public function setTargetChildren($targetChildren)
    {
        $this->targetChildren = $targetChildren;
    }

    /**
     * @return int
     */
    public function getVialsReceived()
    {
        return $this->vialsReceived;
    }

    /**
     * @param int $vialsReceived
     */
    public function setVialsReceived($vialsReceived)
    {
        $this->vialsReceived = $vialsReceived;
    }

    /**
     * @return int
     */
    public function getVialsUsed()
    {
        return $this->vialsUsed;
    }

    /**
     * @param int $vialsUsed
     */
    public function setVialsUsed($vialsUsed)
    {
        $this->vialsUsed = $vialsUsed;
    }

    /**
     * @return int
     */
    public function getNoHousesVisited()
    {
        return $this->noHousesVisited;
    }

    /**
     * @param int $noHousesVisited
     */
    public function setNoHousesVisited($noHousesVisited)
    {
        $this->noHousesVisited = $noHousesVisited;
    }

    /**
     * @return int
     */
    public function getNoResidentChildren()
    {
        return $this->noResidentChildren;
    }

    /**
     * @param int $noResidentChildren
     */
    public function setNoResidentChildren($noResidentChildren)
    {
        $this->noResidentChildren = $noResidentChildren;
    }

    /**
     * @return int
     */
    public function getNoGuestChildren()
    {
        return $this->noGuestChildren;
    }

    /**
     * @param int $noGuestChildren
     */
    public function setNoGuestChildren($noGuestChildren)
    {
        $this->noGuestChildren = $noGuestChildren;
    }

    /**
     * @return int
     */
    public function getNoChildInHouseVac()
    {
        return $this->noChildInHouseVac;
    }

    /**
     * @param int $noChildInHouseVac
     */
    public function setNoChildInHouseVac($noChildInHouseVac)
    {
        $this->noChildInHouseVac = $noChildInHouseVac;
    }

    /**
     * @return int
     */
    public function getNoChildOutsideVac()
    {
        return $this->noChildOutsideVac;
    }

    /**
     * @param int $noChildOutsideVac
     */
    public function setNoChildOutsideVac($noChildOutsideVac)
    {
        $this->noChildOutsideVac = $noChildOutsideVac;
    }

    /**
     * @return int
     */
    public function getNoAbsentSameDay()
    {
        return $this->noAbsentSameDay;
    }

    /**
     * @param int $noAbsentSameDay
     */
    public function setNoAbsentSameDay($noAbsentSameDay)
    {
        $this->noAbsentSameDay = $noAbsentSameDay;
    }

    /**
     * @return int
     */
    public function getNoAbsentSameDayFoundVac()
    {
        return $this->noAbsentSameDayFoundVac;
    }

    /**
     * @param int $noAbsentSameDayFoundVac
     */
    public function setNoAbsentSameDayFoundVac($noAbsentSameDayFoundVac)
    {
        $this->noAbsentSameDayFoundVac = $noAbsentSameDayFoundVac;
    }

    /**
     * @return int
     */
    public function getNoAbsentSameDayVacByTeam()
    {
        return $this->noAbsentSameDayVacByTeam;
    }

    /**
     * @param int $noAbsentSameDayVacByTeam
     */
    public function setNoAbsentSameDayVacByTeam($noAbsentSameDayVacByTeam)
    {
        $this->noAbsentSameDayVacByTeam = $noAbsentSameDayVacByTeam;
    }

    /**
     * @return int
     */
    public function getNoAbsentNotSameDay()
    {
        return $this->noAbsentNotSameDay;
    }

    /**
     * @param int $noAbsentNotSameDay
     */
    public function setNoAbsentNotSameDay($noAbsentNotSameDay)
    {
        $this->noAbsentNotSameDay = $noAbsentNotSameDay;
    }

    /**
     * @return int
     */
    public function getNoAbsentNotSameDayFoundVac()
    {
        return $this->noAbsentNotSameDayFoundVac;
    }

    /**
     * @param int $noAbsentNotSameDayFoundVac
     */
    public function setNoAbsentNotSameDayFoundVac($noAbsentNotSameDayFoundVac)
    {
        $this->noAbsentNotSameDayFoundVac = $noAbsentNotSameDayFoundVac;
    }

    /**
     * @return int
     */
    public function getNoAbsentNotSameDayVacByTeam()
    {
        return $this->noAbsentNotSameDayVacByTeam;
    }

    /**
     * @param int $noAbsentNotSameDayVacByTeam
     */
    public function setNoAbsentNotSameDayVacByTeam($noAbsentNotSameDayVacByTeam)
    {
        $this->noAbsentNotSameDayVacByTeam = $noAbsentNotSameDayVacByTeam;
    }

    /**
     * @return int
     */
    public function getNoNSS()
    {
        return $this->noNSS;
    }

    /**
     * @param int $noNSS
     */
    public function setNoNSS($noNSS)
    {
        $this->noNSS = $noNSS;
    }

    /**
     * @return int
     */
    public function getNoNSSFoundVac()
    {
        return $this->noNSSFoundVac;
    }

    /**
     * @param int $noNSSFoundVac
     */
    public function setNoNSSFoundVac($noNSSFoundVac)
    {
        $this->noNSSFoundVac = $noNSSFoundVac;
    }

    /**
     * @return int
     */
    public function getNoNSSVacByTeam()
    {
        return $this->noNSSVacByTeam;
    }

    /**
     * @param int $noNSSVacByTeam
     */
    public function setNoNSSVacByTeam($noNSSVacByTeam)
    {
        $this->noNSSVacByTeam = $noNSSVacByTeam;
    }

    /**
     * @return int
     */
    public function getNoRefusal()
    {
        return $this->noRefusal;
    }

    /**
     * @param int $noRefusal
     */
    public function setNoRefusal($noRefusal)
    {
        $this->noRefusal = $noRefusal;
    }

    /**
     * @return int
     */
    public function getNoRefusalFoundVac()
    {
        return $this->noRefusalFoundVac;
    }

    /**
     * @param int $noRefusalFoundVac
     */
    public function setNoRefusalFoundVac($noRefusalFoundVac)
    {
        $this->noRefusalFoundVac = $noRefusalFoundVac;
    }

    /**
     * @return int
     */
    public function getNoRefusalVacByTeam()
    {
        return $this->noRefusalVacByTeam;
    }

    /**
     * @param int $noRefusalVacByTeam
     */
    public function setNoRefusalVacByTeam($noRefusalVacByTeam)
    {
        $this->noRefusalVacByTeam = $noRefusalVacByTeam;
    }

    /**
     * @return int
     */
    public function getAfpCase()
    {
        return $this->afpCase;
    }

    /**
     * @param int $afpCase
     */
    public function setAfpCase($afpCase)
    {
        $this->afpCase = $afpCase;
    }

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
    public function getDistrictName()
    {
        return $this->districtName;
    }

    /**
     * @param string $districtName
     */
    public function setDistrictName($districtName)
    {
        $this->districtName = $districtName;
    }

    /**
     * @return string
     */
    public function getDistrictRiskStatus()
    {
        return $this->districtRiskStatus;
    }

    /**
     * @param string $districtRiskStatus
     */
    public function setDistrictRiskStatus($districtRiskStatus)
    {
        $this->districtRiskStatus = $districtRiskStatus;
    }

    /**
     * @return int
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param int $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }

    /**
     * @return string
     */
    public function getProvinceName()
    {
        return $this->provinceName;
    }

    /**
     * @param string $provinceName
     */
    public function setProvinceName($provinceName)
    {
        $this->provinceName = $provinceName;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
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
     * @return string
     */
    public function getCampaignName()
    {
        return $this->campaignName;
    }

    /**
     * @param string $campaignName
     */
    public function setCampaignName($campaignName)
    {
        $this->campaignName = $campaignName;
    }

    /**
     * @return string
     */
    public function getCampaignType()
    {
        return $this->campaignType;
    }

    /**
     * @param string $campaignType
     */
    public function setCampaignType($campaignType)
    {
        $this->campaignType = $campaignType;
    }

    /**
     * @return string
     */
    public function getCampaignDate()
    {
        return $this->campaignDate;
    }

    /**
     * @param string $campaignDate
     */
    public function setCampaignDate($campaignDate)
    {
        $this->campaignDate = $campaignDate;
    }

    /**
     * @return int
     */
    public function getCampaignYear()
    {
        return $this->campaignYear;
    }

    /**
     * @param int $campaignYear
     */
    public function setCampaignYear($campaignYear)
    {
        $this->campaignYear = $campaignYear;
    }

    /**
     * @return string
     */
    public function getCampaignMonth()
    {
        return $this->campaignMonth;
    }

    /**
     * @param string $campaignMonth
     */
    public function setCampaignMonth($campaignMonth)
    {
        $this->campaignMonth = $campaignMonth;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }




}
