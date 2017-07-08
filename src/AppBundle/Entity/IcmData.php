<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IcmData
 *
 * @ORM\Table(name="icm_data", indexes={@ORM\Index(name="icm_campaign_idx", columns={"campaign"}), @ORM\Index(name="district_icm_idx", columns={"district"})})
 * @ORM\Entity
 */
class IcmData
{
    /**
     * @var integer
     *
     * @ORM\Column(name="no_team_monitored", type="integer", nullable=true)
     */
    private $noTeamMonitored;

    /**
     * @var integer
     *
     * @ORM\Column(name="team_resident_area", type="integer", nullable=true)
     */
    private $teamResidentArea;

    /**
     * @var integer
     *
     * @ORM\Column(name="vaccinator_trained", type="integer", nullable=true)
     */
    private $vaccinatorTrained;

    /**
     * @var integer
     *
     * @ORM\Column(name="vacc_stage_3", type="integer", nullable=true)
     */
    private $vaccStage3;

    /**
     * @var integer
     *
     * @ORM\Column(name="team_supervised", type="integer", nullable=true)
     */
    private $teamSupervised;

    /**
     * @var integer
     *
     * @ORM\Column(name="team_with_chw", type="integer", nullable=true)
     */
    private $teamWithChw;

    /**
     * @var integer
     *
     * @ORM\Column(name="team_with_female", type="integer", nullable=true)
     */
    private $teamWithFemale;

    /**
     * @var integer
     *
     * @ORM\Column(name="team_accom_sm", type="integer", nullable=true)
     */
    private $teamAccomSm;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_missed_child_novisit", type="integer", nullable=true)
     */
    private $noMissedChildNovisit;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_child_seen", type="integer", nullable=true)
     */
    private $noChildSeen;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_child_with_fm", type="integer", nullable=true)
     */
    private $noChildWithFm;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_missed_child", type="integer", nullable=true)
     */
    private $noMissedChild;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_missed_10", type="integer", nullable=true)
     */
    private $noMissed10;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="entry_date", type="datetime", nullable=true)
     */
    private $entryDate = 'CURRENT_TIMESTAMP';

    /**
     * @var float
     *
     * @ORM\Column(name="per_resident_area", type="float", precision=10, scale=0, nullable=true)
     */
    private $perResidentArea;

    /**
     * @var float
     *
     * @ORM\Column(name="per_vacc_trained", type="float", precision=10, scale=0, nullable=true)
     */
    private $perVaccTrained;

    /**
     * @var float
     *
     * @ORM\Column(name="per_stage_3", type="float", precision=10, scale=0, nullable=true)
     */
    private $perStage3;

    /**
     * @var float
     *
     * @ORM\Column(name="per_team_supervised", type="float", precision=10, scale=0, nullable=true)
     */
    private $perTeamSupervised;

    /**
     * @var float
     *
     * @ORM\Column(name="per_team_with_chw", type="float", precision=10, scale=0, nullable=true)
     */
    private $perTeamWithChw;

    /**
     * @var float
     *
     * @ORM\Column(name="per_team_with_female", type="float", precision=10, scale=0, nullable=true)
     */
    private $perTeamWithFemale;

    /**
     * @var float
     *
     * @ORM\Column(name="per_team_accom_sm", type="float", precision=10, scale=0, nullable=true)
     */
    private $perTeamAccomSm;

    /**
     * @var float
     *
     * @ORM\Column(name="per_missed_10", type="float", precision=10, scale=0, nullable=true)
     */
    private $perMissed10;

    /**
     * @var float
     *
     * @ORM\Column(name="per_child_vaccinated", type="float", precision=10, scale=0, nullable=true)
     */
    private $perChildVaccinated;

    /**
     * @var float
     *
     * @ORM\Column(name="per_missed_child_fm", type="float", precision=10, scale=0, nullable=true)
     */
    private $perMissedChildFm;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Campaign")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="campaign", referencedColumnName="id")
     * })
     */
    private $campaign;

    /**
     * @var \AppBundle\Entity\District
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\District")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="district", referencedColumnName="id")
     * })
     */
    private $district;



    /**
     * Set noTeamMonitored
     *
     * @param integer $noTeamMonitored
     *
     * @return IcmData
     */
    public function setNoTeamMonitored($noTeamMonitored)
    {
        $this->noTeamMonitored = $noTeamMonitored;

        return $this;
    }

    /**
     * Get noTeamMonitored
     *
     * @return integer
     */
    public function getNoTeamMonitored()
    {
        return $this->noTeamMonitored;
    }

    /**
     * Set teamResidentArea
     *
     * @param integer $teamResidentArea
     *
     * @return IcmData
     */
    public function setTeamResidentArea($teamResidentArea)
    {
        $this->teamResidentArea = $teamResidentArea;

        return $this;
    }

    /**
     * Get teamResidentArea
     *
     * @return integer
     */
    public function getTeamResidentArea()
    {
        return $this->teamResidentArea;
    }

    /**
     * Set vaccinatorTrained
     *
     * @param integer $vaccinatorTrained
     *
     * @return IcmData
     */
    public function setVaccinatorTrained($vaccinatorTrained)
    {
        $this->vaccinatorTrained = $vaccinatorTrained;

        return $this;
    }

    /**
     * Get vaccinatorTrained
     *
     * @return integer
     */
    public function getVaccinatorTrained()
    {
        return $this->vaccinatorTrained;
    }

    /**
     * Set vaccStage3
     *
     * @param integer $vaccStage3
     *
     * @return IcmData
     */
    public function setVaccStage3($vaccStage3)
    {
        $this->vaccStage3 = $vaccStage3;

        return $this;
    }

    /**
     * Get vaccStage3
     *
     * @return integer
     */
    public function getVaccStage3()
    {
        return $this->vaccStage3;
    }

    /**
     * Set teamSupervised
     *
     * @param integer $teamSupervised
     *
     * @return IcmData
     */
    public function setTeamSupervised($teamSupervised)
    {
        $this->teamSupervised = $teamSupervised;

        return $this;
    }

    /**
     * Get teamSupervised
     *
     * @return integer
     */
    public function getTeamSupervised()
    {
        return $this->teamSupervised;
    }

    /**
     * Set teamWithChw
     *
     * @param integer $teamWithChw
     *
     * @return IcmData
     */
    public function setTeamWithChw($teamWithChw)
    {
        $this->teamWithChw = $teamWithChw;

        return $this;
    }

    /**
     * Get teamWithChw
     *
     * @return integer
     */
    public function getTeamWithChw()
    {
        return $this->teamWithChw;
    }

    /**
     * Set teamWithFemale
     *
     * @param integer $teamWithFemale
     *
     * @return IcmData
     */
    public function setTeamWithFemale($teamWithFemale)
    {
        $this->teamWithFemale = $teamWithFemale;

        return $this;
    }

    /**
     * Get teamWithFemale
     *
     * @return integer
     */
    public function getTeamWithFemale()
    {
        return $this->teamWithFemale;
    }

    /**
     * Set teamAccomSm
     *
     * @param integer $teamAccomSm
     *
     * @return IcmData
     */
    public function setTeamAccomSm($teamAccomSm)
    {
        $this->teamAccomSm = $teamAccomSm;

        return $this;
    }

    /**
     * Get teamAccomSm
     *
     * @return integer
     */
    public function getTeamAccomSm()
    {
        return $this->teamAccomSm;
    }

    /**
     * Set noMissedChildNovisit
     *
     * @param integer $noMissedChildNovisit
     *
     * @return IcmData
     */
    public function setNoMissedChildNovisit($noMissedChildNovisit)
    {
        $this->noMissedChildNovisit = $noMissedChildNovisit;

        return $this;
    }

    /**
     * Get noMissedChildNovisit
     *
     * @return integer
     */
    public function getNoMissedChildNovisit()
    {
        return $this->noMissedChildNovisit;
    }

    /**
     * Set noChildSeen
     *
     * @param integer $noChildSeen
     *
     * @return IcmData
     */
    public function setNoChildSeen($noChildSeen)
    {
        $this->noChildSeen = $noChildSeen;

        return $this;
    }

    /**
     * Get noChildSeen
     *
     * @return integer
     */
    public function getNoChildSeen()
    {
        return $this->noChildSeen;
    }

    /**
     * Set noChildWithFm
     *
     * @param integer $noChildWithFm
     *
     * @return IcmData
     */
    public function setNoChildWithFm($noChildWithFm)
    {
        $this->noChildWithFm = $noChildWithFm;

        return $this;
    }

    /**
     * Get noChildWithFm
     *
     * @return integer
     */
    public function getNoChildWithFm()
    {
        return $this->noChildWithFm;
    }

    /**
     * Set noMissedChild
     *
     * @param integer $noMissedChild
     *
     * @return IcmData
     */
    public function setNoMissedChild($noMissedChild)
    {
        $this->noMissedChild = $noMissedChild;

        return $this;
    }

    /**
     * Get noMissedChild
     *
     * @return integer
     */
    public function getNoMissedChild()
    {
        return $this->noMissedChild;
    }

    /**
     * Set noMissed10
     *
     * @param integer $noMissed10
     *
     * @return IcmData
     */
    public function setNoMissed10($noMissed10)
    {
        $this->noMissed10 = $noMissed10;

        return $this;
    }

    /**
     * Get noMissed10
     *
     * @return integer
     */
    public function getNoMissed10()
    {
        return $this->noMissed10;
    }

    /**
     * Set entryDate
     *
     * @param \DateTime $entryDate
     *
     * @return IcmData
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

    /**
     * Set perResidentArea
     *
     * @param float $perResidentArea
     *
     * @return IcmData
     */
    public function setPerResidentArea($perResidentArea)
    {
        $this->perResidentArea = $perResidentArea;

        return $this;
    }

    /**
     * Get perResidentArea
     *
     * @return float
     */
    public function getPerResidentArea()
    {
        return $this->perResidentArea;
    }

    /**
     * Set perVaccTrained
     *
     * @param float $perVaccTrained
     *
     * @return IcmData
     */
    public function setPerVaccTrained($perVaccTrained)
    {
        $this->perVaccTrained = $perVaccTrained;

        return $this;
    }

    /**
     * Get perVaccTrained
     *
     * @return float
     */
    public function getPerVaccTrained()
    {
        return $this->perVaccTrained;
    }

    /**
     * Set perStage3
     *
     * @param float $perStage3
     *
     * @return IcmData
     */
    public function setPerStage3($perStage3)
    {
        $this->perStage3 = $perStage3;

        return $this;
    }

    /**
     * Get perStage3
     *
     * @return float
     */
    public function getPerStage3()
    {
        return $this->perStage3;
    }

    /**
     * Set perTeamSupervised
     *
     * @param float $perTeamSupervised
     *
     * @return IcmData
     */
    public function setPerTeamSupervised($perTeamSupervised)
    {
        $this->perTeamSupervised = $perTeamSupervised;

        return $this;
    }

    /**
     * Get perTeamSupervised
     *
     * @return float
     */
    public function getPerTeamSupervised()
    {
        return $this->perTeamSupervised;
    }

    /**
     * Set perTeamWithChw
     *
     * @param float $perTeamWithChw
     *
     * @return IcmData
     */
    public function setPerTeamWithChw($perTeamWithChw)
    {
        $this->perTeamWithChw = $perTeamWithChw;

        return $this;
    }

    /**
     * Get perTeamWithChw
     *
     * @return float
     */
    public function getPerTeamWithChw()
    {
        return $this->perTeamWithChw;
    }

    /**
     * Set perTeamWithFemale
     *
     * @param float $perTeamWithFemale
     *
     * @return IcmData
     */
    public function setPerTeamWithFemale($perTeamWithFemale)
    {
        $this->perTeamWithFemale = $perTeamWithFemale;

        return $this;
    }

    /**
     * Get perTeamWithFemale
     *
     * @return float
     */
    public function getPerTeamWithFemale()
    {
        return $this->perTeamWithFemale;
    }

    /**
     * Set perTeamAccomSm
     *
     * @param float $perTeamAccomSm
     *
     * @return IcmData
     */
    public function setPerTeamAccomSm($perTeamAccomSm)
    {
        $this->perTeamAccomSm = $perTeamAccomSm;

        return $this;
    }

    /**
     * Get perTeamAccomSm
     *
     * @return float
     */
    public function getPerTeamAccomSm()
    {
        return $this->perTeamAccomSm;
    }

    /**
     * Set perMissed10
     *
     * @param float $perMissed10
     *
     * @return IcmData
     */
    public function setPerMissed10($perMissed10)
    {
        $this->perMissed10 = $perMissed10;

        return $this;
    }

    /**
     * Get perMissed10
     *
     * @return float
     */
    public function getPerMissed10()
    {
        return $this->perMissed10;
    }

    /**
     * Set perChildVaccinated
     *
     * @param float $perChildVaccinated
     *
     * @return IcmData
     */
    public function setPerChildVaccinated($perChildVaccinated)
    {
        $this->perChildVaccinated = $perChildVaccinated;

        return $this;
    }

    /**
     * Get perChildVaccinated
     *
     * @return float
     */
    public function getPerChildVaccinated()
    {
        return $this->perChildVaccinated;
    }

    /**
     * Set perMissedChildFm
     *
     * @param float $perMissedChildFm
     *
     * @return IcmData
     */
    public function setPerMissedChildFm($perMissedChildFm)
    {
        $this->perMissedChildFm = $perMissedChildFm;

        return $this;
    }

    /**
     * Get perMissedChildFm
     *
     * @return float
     */
    public function getPerMissedChildFm()
    {
        return $this->perMissedChildFm;
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
     * @param \AppBundle\Entity\Campaign $campaign
     *
     * @return IcmData
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
     * @return IcmData
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
