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
     * @ORM\Column(name="data_source", type="text", length=20, nullable=true)
     */
    private $dataSource;

    /**
     * @var string
     *
     * @ORM\Column(name="cluster_name", type="text", length=50, nullable=true)
     */
    private $clusterName;

    /**
     * @var string
     *
     * @ORM\Column(name="cluster_no", type="text", length=20, nullable=true)
     */
    private $clusterNo;

    /**
     * @var string
     *
     * @ORM\Column(name="sub_district_name", type="text", length=50, nullable=true)
     */
    private $subDistrictName;

    /**
     * @var string
     *
     * @ORM\Column(name="area_name", type="text", length=100, nullable=true)
     */
    private $areaName;


    /**
     * @var integer
     *
     * @ORM\Column(name="fb_no_sms", type="integer", nullable=true)
     */
    private $fbNoSMs;

    /**
     * @var integer
     *
     * @ORM\Column(name="fb_no_hhs", type="integer", nullable=true)
     */
    private $fbNoHHs;

    /**
     * @var integer
     *
     * @ORM\Column(name="fb_no_u5", type="integer", nullable=true)
     */
    private $fbNoU5;

    /**
     * @var integer
     *
     * @ORM\Column(name="fb_no_u5irr", type="integer", nullable=true)
     */
    private $fbNoU5IRR;

    /**
     * @var integer
     *
     * @ORM\Column(name="fb_guest_vac", type="integer", nullable=true)
     */
    private $fbGuestVac;

    /**
     * @var integer
     *
     * @ORM\Column(name="fb_during_camp_vac", type="integer", nullable=true)
     */
    private $fbDuringCampVac;

    /**
     * @var integer
     *
     * @ORM\Column(name="fb_refusal_not_vac", type="integer", nullable=true)
     */
    private $fbRefusalNotVac;

    /**
     * @var integer
     *
     * @ORM\Column(name="fb_refusal_vac_during_camp", type="integer", nullable=true)
     */
    private $fbRefusalVacDuringCamp;

    /**
     * @var integer
     *
     * @ORM\Column(name="fb_refusal_vac_after_camp", type="integer", nullable=true)
     */
    private $fbRefusalVacAfterCamp;

    /**
     * @var integer
     *
     * @ORM\Column(name="fb_child_vac_by_smafter_camp", type="integer", nullable=true)
     */
    private $fbChildVacBySMAfterCamp;

    /**
     * @var integer
     *
     * @ORM\Column(name="fb_child_not_vacc_after_camp", type="integer", nullable=true)
     */
    private $fbChildNotVaccAfterCamp;

    /**
     * @var integer
     *
     * @ORM\Column(name="fb_child_missed_inaccessiblity", type="integer", nullable=true)
     */
    private $fbChildMissedInaccessiblity;

    /**
     * @var integer
     *
     * @ORM\Column(name="fb_child_refer_ri", type="integer", nullable=true)
     */
    private $fbChildReferRI;

    /**
     * @var integer
     *
     * @ORM\Column(name="fb_newborn_rec", type="integer", nullable=true)
     */
    private $fbNewbornRec;

    /**
     * @var integer
     *
     * @ORM\Column(name="fb_newborn_opv0", type="integer", nullable=true)
     */
    private $fbNewbornOPV0;

    /**
     * @var integer
     *
     * @ORM\Column(name="fb_pregnant_rec", type="integer", nullable=true)
     */
    private $fbPregnantRec;

    /**
     * @var integer
     *
     * @ORM\Column(name="fb_pregnant_refer_anc", type="integer", nullable=true)
     */
    private $fbPregnantReferANC;

    /**
     * @var integer
     *
     * @ORM\Column(name="ch_reg_absent", type="integer", nullable=true)
     */
    private $chRegAbsent;

    /**
     * @var integer
     *
     * @ORM\Column(name="ch_vac_absent", type="integer", nullable=true)
     */
    private $chVacAbsent;

    /**
     * @var integer
     *
     * @ORM\Column(name="ch_reg_sleep", type="integer", nullable=true)
     */
    private $chRegSleep;

    /**
     * @var integer
     *
     * @ORM\Column(name="ch_vac_sleep", type="integer", nullable=true)
     */
    private $chVacSleep;

    /**
     * @var integer
     *
     * @ORM\Column(name="ch_reg_refusal", type="integer", nullable=true)
     */
    private $chRegRefusal;

    /**
     * @var integer
     *
     * @ORM\Column(name="ch_vac_refusal", type="integer", nullable=true)
     */
    private $chVacRefusal;

    /**
     * @var integer
     *
     * @ORM\Column(name="ch_unrecorded", type="integer", nullable=true)
     */
    private $chUnrecorded;

    /**
     * @var integer
     *
     * @ORM\Column(name="ch_unrecorded_vac", type="integer", nullable=true)
     */
    private $chUnrecordedVac;


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(name="campaign", type="integer", nullable=true)
     */
    private $campaign;

    /**
     * @var integer
     * @ORM\Column(name="district", type="integer", nullable=true)
     */
    private $district;

    /**
     * @var integer
     * @ORM\Column(name="file", type="integer", nullable=true)
     */
    private $file;

    /**
     * @return string
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * @param string $dataSource
     */
    public function setDataSource($dataSource)
    {
        $this->dataSource = $dataSource;
    }

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
    public function getFbNoSMs()
    {
        return $this->fbNoSMs;
    }

    /**
     * @param int $fbNoSMs
     */
    public function setFbNoSMs($fbNoSMs)
    {
        $this->fbNoSMs = $fbNoSMs;
    }

    /**
     * @return int
     */
    public function getFbNoHHs()
    {
        return $this->fbNoHHs;
    }

    /**
     * @param int $fbNoHHs
     */
    public function setFbNoHHs($fbNoHHs)
    {
        $this->fbNoHHs = $fbNoHHs;
    }

    /**
     * @return int
     */
    public function getFbNoU5()
    {
        return $this->fbNoU5;
    }

    /**
     * @param int $fbNoU5
     */
    public function setFbNoU5($fbNoU5)
    {
        $this->fbNoU5 = $fbNoU5;
    }

    /**
     * @return int
     */
    public function getFbNoU5IRR()
    {
        return $this->fbNoU5IRR;
    }

    /**
     * @param int $fbNoU5IRR
     */
    public function setFbNoU5IRR($fbNoU5IRR)
    {
        $this->fbNoU5IRR = $fbNoU5IRR;
    }

    /**
     * @return int
     */
    public function getFbGuestVac()
    {
        return $this->fbGuestVac;
    }

    /**
     * @param int $fbGuestVac
     */
    public function setFbGuestVac($fbGuestVac)
    {
        $this->fbGuestVac = $fbGuestVac;
    }

    /**
     * @return int
     */
    public function getFbDuringCampVac()
    {
        return $this->fbDuringCampVac;
    }

    /**
     * @param int $fbDuringCampVac
     */
    public function setFbDuringCampVac($fbDuringCampVac)
    {
        $this->fbDuringCampVac = $fbDuringCampVac;
    }

    /**
     * @return int
     */
    public function getFbRefusalNotVac()
    {
        return $this->fbRefusalNotVac;
    }

    /**
     * @param int $fbRefusalNotVac
     */
    public function setFbRefusalNotVac($fbRefusalNotVac)
    {
        $this->fbRefusalNotVac = $fbRefusalNotVac;
    }

    /**
     * @return int
     */
    public function getFbRefusalVacDuringCamp()
    {
        return $this->fbRefusalVacDuringCamp;
    }

    /**
     * @param int $fbRefusalVacDuringCamp
     */
    public function setFbRefusalVacDuringCamp($fbRefusalVacDuringCamp)
    {
        $this->fbRefusalVacDuringCamp = $fbRefusalVacDuringCamp;
    }

    /**
     * @return int
     */
    public function getFbRefusalVacAfterCamp()
    {
        return $this->fbRefusalVacAfterCamp;
    }

    /**
     * @param int $fbRefusalVacAfterCamp
     */
    public function setFbRefusalVacAfterCamp($fbRefusalVacAfterCamp)
    {
        $this->fbRefusalVacAfterCamp = $fbRefusalVacAfterCamp;
    }

    /**
     * @return int
     */
    public function getFbChildVacBySMAfterCamp()
    {
        return $this->fbChildVacBySMAfterCamp;
    }

    /**
     * @param int $fbChildVacBySMAfterCamp
     */
    public function setFbChildVacBySMAfterCamp($fbChildVacBySMAfterCamp)
    {
        $this->fbChildVacBySMAfterCamp = $fbChildVacBySMAfterCamp;
    }

    /**
     * @return int
     */
    public function getFbChildNotVaccAfterCamp()
    {
        return $this->fbChildNotVaccAfterCamp;
    }

    /**
     * @param int $fbChildNotVaccAfterCamp
     */
    public function setFbChildNotVaccAfterCamp($fbChildNotVaccAfterCamp)
    {
        $this->fbChildNotVaccAfterCamp = $fbChildNotVaccAfterCamp;
    }

    /**
     * @return int
     */
    public function getFbChildMissedInaccessiblity()
    {
        return $this->fbChildMissedInaccessiblity;
    }

    /**
     * @param int $fbChildMissedInaccessiblity
     */
    public function setFbChildMissedInaccessiblity($fbChildMissedInaccessiblity)
    {
        $this->fbChildMissedInaccessiblity = $fbChildMissedInaccessiblity;
    }

    /**
     * @return int
     */
    public function getFbChildReferRI()
    {
        return $this->fbChildReferRI;
    }

    /**
     * @param int $fbChildReferRI
     */
    public function setFbChildReferRI($fbChildReferRI)
    {
        $this->fbChildReferRI = $fbChildReferRI;
    }

    /**
     * @return int
     */
    public function getFbNewbornRec()
    {
        return $this->fbNewbornRec;
    }

    /**
     * @param int $fbNewbornRec
     */
    public function setFbNewbornRec($fbNewbornRec)
    {
        $this->fbNewbornRec = $fbNewbornRec;
    }

    /**
     * @return int
     */
    public function getFbNewbornOPV0()
    {
        return $this->fbNewbornOPV0;
    }

    /**
     * @param int $fbNewbornOPV0
     */
    public function setFbNewbornOPV0($fbNewbornOPV0)
    {
        $this->fbNewbornOPV0 = $fbNewbornOPV0;
    }

    /**
     * @return int
     */
    public function getFbPregnantRec()
    {
        return $this->fbPregnantRec;
    }

    /**
     * @param int $fbPregnantRec
     */
    public function setFbPregnantRec($fbPregnantRec)
    {
        $this->fbPregnantRec = $fbPregnantRec;
    }

    /**
     * @return int
     */
    public function getFbPregnantReferANC()
    {
        return $this->fbPregnantReferANC;
    }

    /**
     * @param int $fbPregnantReferANC
     */
    public function setFbPregnantReferANC($fbPregnantReferANC)
    {
        $this->fbPregnantReferANC = $fbPregnantReferANC;
    }

    /**
     * @return int
     */
    public function getChRegAbsent()
    {
        return $this->chRegAbsent;
    }

    /**
     * @param int $chRegAbsent
     */
    public function setChRegAbsent($chRegAbsent)
    {
        $this->chRegAbsent = $chRegAbsent;
    }

    /**
     * @return int
     */
    public function getChVacAbsent()
    {
        return $this->chVacAbsent;
    }

    /**
     * @param int $chVacAbsent
     */
    public function setChVacAbsent($chVacAbsent)
    {
        $this->chVacAbsent = $chVacAbsent;
    }

    /**
     * @return int
     */
    public function getChRegSleep()
    {
        return $this->chRegSleep;
    }

    /**
     * @param int $chRegSleep
     */
    public function setChRegSleep($chRegSleep)
    {
        $this->chRegSleep = $chRegSleep;
    }

    /**
     * @return int
     */
    public function getChVacSleep()
    {
        return $this->chVacSleep;
    }

    /**
     * @param int $chVacSleep
     */
    public function setChVacSleep($chVacSleep)
    {
        $this->chVacSleep = $chVacSleep;
    }

    /**
     * @return int
     */
    public function getChRegRefusal()
    {
        return $this->chRegRefusal;
    }

    /**
     * @param int $chRegRefusal
     */
    public function setChRegRefusal($chRegRefusal)
    {
        $this->chRegRefusal = $chRegRefusal;
    }

    /**
     * @return int
     */
    public function getChVacRefusal()
    {
        return $this->chVacRefusal;
    }

    /**
     * @param int $chVacRefusal
     */
    public function setChVacRefusal($chVacRefusal)
    {
        $this->chVacRefusal = $chVacRefusal;
    }

    /**
     * @return int
     */
    public function getChUnrecorded()
    {
        return $this->chUnrecorded;
    }

    /**
     * @param int $chUnrecorded
     */
    public function setChUnrecorded($chUnrecorded)
    {
        $this->chUnrecorded = $chUnrecorded;
    }

    /**
     * @return int
     */
    public function getChUnrecordedVac()
    {
        return $this->chUnrecordedVac;
    }

    /**
     * @param int $chUnrecordedVac
     */
    public function setChUnrecordedVac($chUnrecordedVac)
    {
        $this->chUnrecordedVac = $chUnrecordedVac;
    }




    /**
     * @return int
     */
    public function getUnrecordedVac()
    {
        return $this->unrecordedVac;
    }

    /**
     * @param int $unrecordedVac
     */
    public function setUnrecordedVac($unrecordedVac)
    {
        $this->unrecordedVac = $unrecordedVac;
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

    /**
     * Set campaign
     *
     * @param integer $campaign
     *
     * @return CatchupData
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;

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
     * Set district
     *
     * @param integer $district
     *
     * @return CatchupData
     */
    public function setDistrict($district)
    {
        $this->district = $district;

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




}
