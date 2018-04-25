<?php
/**
 * Created by PhpStorm.
 * User: Awesome
 * Date: 4/6/2018
 * Time: 12:10 PM
 */

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FieldbookRepository")
 * @ORM\Table(name="fieldbook")
 * @package AppBundle\Entity
 */
class Fieldbook
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FieldbookEntry",
     *     mappedBy="fieldbook", cascade={"all"}, orphanRemoval=true)
     */
    protected $fieldbookEntries;

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
     * @ORM\Column(type="string", name="sm_name", nullable=true)
     */
    private $smName;

    /**
     * @ORM\Column(type="string", name="area_name", nullable=true)
     */
    private $areaName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $cluster;

    /**
     * @ORM\Column(type="string", name="year_from_to", nullable=true)
     */
    private $yearFromTo;

    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", fetch="EXTRA_LAZY")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * Fieldbook constructor.
     */
    public function __construct()
    {
        $this->fieldbookEntries = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getFieldbookEntries(): Collection
    {
        return $this->fieldbookEntries;
    }



    /**
     * @return District
     */
    public function getDistrict(): District
    {
        return $this->district;
    }

    /**
     * @param District $district
     */
    public function setDistrict(District $district): void
    {
        $this->district = $district;
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
    public function setSmName($smName): void
    {
        $this->smName = $smName;
    }

    /**
     * @return mixed
     */
    public function getAreaName()
    {
        return $this->areaName;
    }

    /**
     * @param mixed $areaName
     */
    public function setAreaName($areaName): void
    {
        $this->areaName = $areaName;
    }

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
    public function setCluster($cluster): void
    {
        $this->cluster = $cluster;
    }

    /**
     * @return mixed
     */
    public function getYearFromTo()
    {
        return $this->yearFromTo;
    }

    /**
     * @param mixed $yearFromTo
     */
    public function setYearFromTo($yearFromTo): void
    {
        $this->yearFromTo = $yearFromTo;
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param FieldbookEntry $entry
     */
    public function addFieldbookEntry(FieldbookEntry $entry) {

        if($this->fieldbookEntries->contains($entry))
            return;
        $this->fieldbookEntries->add($entry);
        $entry->addFieldbook($this);

    }

    public function setFieldbookEntries($entry) {
        foreach ($entry as $item) {
            $this->addFieldbookEntry($item);
        }
    }

    public function removeFieldbookEntries(FieldbookEntry $entry) {
        $this->fieldbookEntries->removeElement($entry);
        return $this;
    }




}