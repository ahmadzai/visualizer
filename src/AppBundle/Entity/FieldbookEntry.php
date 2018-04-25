<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FieldbookEntry
 * @ORM\Entity()
 * @ORM\Table(name="fieldbook_entry")
 *
 */
class FieldbookEntry
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="house_no", nullable=true)
     */
    private $houseNo;

    /**
     * @ORM\Column(type="string", name="house_head", nullable=true)
     */
    private $houseHead;

    /**
     * @ORM\Column(type="string", name="child_name", nullable=true)
     */
    private $childName;


    /**
     * @ORM\Column(type="date", name="child_dob", nullable=true)
     */
    private $childDob;

    /**
     * @ORM\Column(type="boolean", name="child_gender", nullable=true)
     */
    private $childGender;

    /**
     * @ORM\Column(type="string", name="month_jun", nullable=true)
     */
    private $monthJun;

    /**
     * @ORM\Column(type="string", name="month_jul", nullable=true)
     */
    private $monthJul;

    /**
     * @ORM\Column(type="string", nullable=true, name="month_aug")
     */
    private $monthAug;

    /**
     * @ORM\Column(type="string", nullable=true, name="month_sep")
     */
    private $monthSep;

    /**
     * @ORM\Column(type="string", nullable=true, name="month_oct")
     */
    private $monthOct;

    /**
     * @ORM\Column(type="string", nullable=true, name="month_nov")
     */
    private $monthNov;

    /**
     * @ORM\Column(type="string", nullable=true, name="month_dec")
     */
    private $monthDec;

    /**
     * @ORM\Column(type="string", nullable=true, name="month_jan")
     */
    private $monthJan;

    /**
     * @ORM\Column(type="string", nullable=true, name="month_feb")
     */
    private $monthFeb;

    /**
     * @ORM\Column(type="string", nullable=true, name="month_mar")
     */
    private $monthMar;

    /**
     * @ORM\Column(type="string", nullable=true, name="month_apr")
     */
    private $monthApr;

    /**
     * @ORM\Column(type="string", nullable=true, name="month_may")
     */
    private $monthMay;

    /**
     * @ORM\Column(type="boolean", name="ri_book", nullable=true)
     */
    private $riBook;

    /**
     * @ORM\Column(type="boolean", name="bcg_hep_b", nullable=true)
     */
    private $bcgHepB;

    /**
     * @ORM\Column(type="boolean", name="penta1_pvc1_opv1", nullable=true)
     */
    private $penta1Pvc1Opv1;

    /**
     * @ORM\Column(type="boolean", name="penta2_pvc2_opv2", nullable=true)
     */
    private $penta2Pvc2Opv2;

    /**
     * @ORM\Column(type="boolean", name="penta3_pvc3_opv3", nullable=true)
     */
    private $penta3Pvc3Opv3;

    /**
     * @ORM\Column(type="boolean", name="measles_opv4", nullable=true)
     */
    private $measlesOpv4;

    /**
     * @ORM\Column(type="string", name="preg_woman_name", nullable=true)
     */
    private $pregWomanName;

    /**
     * @ORM\Column(type="date", name="date_of_record", nullable=true)
     */
    private $dateOfRecord;

    /**
     * @ORM\Column(type="integer", name="no_months", nullable=true)
     */
    private $noMonths;


    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     */
    private $updatedAt;

    /**
     * @var Fieldbook
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Fieldbook", inversedBy="fieldbookEntries")
     * @ORM\JoinColumn(name="fieldbook_id", referencedColumnName="id")
     */
    private $fieldbook;

    /**
     * @return Fieldbook
     */
    public function getFieldbook(): Fieldbook
    {
        return $this->fieldbook;
    }

    /**
     * @param Fieldbook $fieldbook
     */
    public function setFieldbook(Fieldbook $fieldbook): void
    {
        $this->fieldbook = $fieldbook;
    }


    /**
     * @return mixed
     */
    public function getHouseNo()
    {
        return $this->houseNo;
    }

    /**
     * @param mixed $houseNo
     */
    public function setHouseNo($houseNo): void
    {
        $this->houseNo = $houseNo;
    }

    /**
     * @return mixed
     */
    public function getHouseHead()
    {
        return $this->houseHead;
    }

    /**
     * @param mixed $houseHead
     */
    public function setHouseHead($houseHead): void
    {
        $this->houseHead = $houseHead;
    }

    /**
     * @return mixed
     */
    public function getChildName()
    {
        return $this->childName;
    }

    /**
     * @param mixed $childName
     */
    public function setChildName($childName): void
    {
        $this->childName = $childName;
    }

    /**
     * @return mixed
     */
    public function getChildDob()
    {
        return $this->childDob;
    }

    /**
     * @param mixed $childDob
     */
    public function setChildDob($childDob): void
    {
        $this->childDob = $childDob;
    }

    /**
     * @return mixed
     */
    public function getChildGender()
    {
        return $this->childGender;
    }

    /**
     * @param mixed $childGender
     */
    public function setChildGender($childGender): void
    {
        $this->childGender = $childGender;
    }

    /**
     * @return mixed
     */
    public function getMonthJun()
    {
        return $this->monthJun;
    }

    /**
     * @param mixed $monthJun
     */
    public function setMonthJun($monthJun): void
    {
        $this->monthJun = $monthJun;
    }

    /**
     * @return mixed
     */
    public function getMonthJul()
    {
        return $this->monthJul;
    }

    /**
     * @param mixed $monthJul
     */
    public function setMonthJul($monthJul): void
    {
        $this->monthJul = $monthJul;
    }

    /**
     * @return mixed
     */
    public function getMonthAug()
    {
        return $this->monthAug;
    }

    /**
     * @param mixed $monthAug
     */
    public function setMonthAug($monthAug): void
    {
        $this->monthAug = $monthAug;
    }

    /**
     * @return mixed
     */
    public function getMonthSep()
    {
        return $this->monthSep;
    }

    /**
     * @param mixed $monthSep
     */
    public function setMonthSep($monthSep): void
    {
        $this->monthSep = $monthSep;
    }

    /**
     * @return mixed
     */
    public function getMonthOct()
    {
        return $this->monthOct;
    }

    /**
     * @param mixed $monthOct
     */
    public function setMonthOct($monthOct): void
    {
        $this->monthOct = $monthOct;
    }

    /**
     * @return mixed
     */
    public function getMonthNov()
    {
        return $this->monthNov;
    }

    /**
     * @param mixed $monthNov
     */
    public function setMonthNov($monthNov): void
    {
        $this->monthNov = $monthNov;
    }

    /**
     * @return mixed
     */
    public function getMonthDec()
    {
        return $this->monthDec;
    }

    /**
     * @param mixed $monthDec
     */
    public function setMonthDec($monthDec): void
    {
        $this->monthDec = $monthDec;
    }

    /**
     * @return mixed
     */
    public function getMonthJan()
    {
        return $this->monthJan;
    }

    /**
     * @param mixed $monthJan
     */
    public function setMonthJan($monthJan): void
    {
        $this->monthJan = $monthJan;
    }

    /**
     * @return mixed
     */
    public function getMonthFeb()
    {
        return $this->monthFeb;
    }

    /**
     * @param mixed $monthFeb
     */
    public function setMonthFeb($monthFeb): void
    {
        $this->monthFeb = $monthFeb;
    }

    /**
     * @return mixed
     */
    public function getMonthMar()
    {
        return $this->monthMar;
    }

    /**
     * @param mixed $monthMar
     */
    public function setMonthMar($monthMar): void
    {
        $this->monthMar = $monthMar;
    }

    /**
     * @return mixed
     */
    public function getMonthApr()
    {
        return $this->monthApr;
    }

    /**
     * @param mixed $monthApr
     */
    public function setMonthApr($monthApr): void
    {
        $this->monthApr = $monthApr;
    }

    /**
     * @return mixed
     */
    public function getMonthMay()
    {
        return $this->monthMay;
    }

    /**
     * @param mixed $monthMay
     */
    public function setMonthMay($monthMay): void
    {
        $this->monthMay = $monthMay;
    }



    /**
     * @return mixed
     */
    public function getRiBook()
    {
        return $this->riBook;
    }

    /**
     * @param mixed $riBook
     */
    public function setRiBook($riBook): void
    {
        $this->riBook = $riBook;
    }

    /**
     * @return mixed
     */
    public function getBcgHepB()
    {
        return $this->bcgHepB;
    }

    /**
     * @param mixed $bcgHepB
     */
    public function setBcgHepB($bcgHepB): void
    {
        $this->bcgHepB = $bcgHepB;
    }

    /**
     * @return mixed
     */
    public function getPenta1Pvc1Opv1()
    {
        return $this->penta1Pvc1Opv1;
    }

    /**
     * @param mixed $penta1Pvc1Opv1
     */
    public function setPenta1Pvc1Opv1($penta1Pvc1Opv1): void
    {
        $this->penta1Pvc1Opv1 = $penta1Pvc1Opv1;
    }

    /**
     * @return mixed
     */
    public function getPenta2Pvc2Opv2()
    {
        return $this->penta2Pvc2Opv2;
    }

    /**
     * @param mixed $penta2Pvc2Opv2
     */
    public function setPenta2Pvc2Opv2($penta2Pvc2Opv2): void
    {
        $this->penta2Pvc2Opv2 = $penta2Pvc2Opv2;
    }

    /**
     * @return mixed
     */
    public function getPenta3Pvc3Opv3()
    {
        return $this->penta3Pvc3Opv3;
    }

    /**
     * @param mixed $penta3Pvc3Opv3
     */
    public function setPenta3Pvc3Opv3($penta3Pvc3Opv3): void
    {
        $this->penta3Pvc3Opv3 = $penta3Pvc3Opv3;
    }

    /**
     * @return mixed
     */
    public function getMeaslesOpv4()
    {
        return $this->measlesOpv4;
    }

    /**
     * @param mixed $measlesOpv4
     */
    public function setMeaslesOpv4($measlesOpv4): void
    {
        $this->measlesOpv4 = $measlesOpv4;
    }

    /**
     * @return mixed
     */
    public function getPregWomanName()
    {
        return $this->pregWomanName;
    }

    /**
     * @param mixed $pregWomanName
     */
    public function setPregWomanName($pregWomanName): void
    {
        $this->pregWomanName = $pregWomanName;
    }

    /**
     * @return mixed
     */
    public function getDateOfRecord()
    {
        return $this->dateOfRecord;
    }

    /**
     * @param mixed $dateOfRecord
     */
    public function setDateOfRecord($dateOfRecord): void
    {
        $this->dateOfRecord = $dateOfRecord;
    }

    /**
     * @return mixed
     */
    public function getNoMonths()
    {
        return $this->noMonths;
    }

    /**
     * @param mixed $noMonths
     */
    public function setNoMonths($noMonths): void
    {
        $this->noMonths = $noMonths;
    }


    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function addFieldbook(Fieldbook $fieldbook){
        $this->setFieldbook($fieldbook);
    }



}
