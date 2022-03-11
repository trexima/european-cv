<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Trexima\EuropeanCvBundle\Entity\Embeddable\DateRange;

/**
 * EuropeanCV practice
 *
 * @ORM\Table(
 *     name="european_cv_practice"
 * )
 * @ORM\Entity
 */
class EuropeanCVPractice
{
    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", options={"unsigned"=true}))
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var EuropeanCV
     *
     * @ORM\ManyToOne(targetEntity="Trexima\EuropeanCvBundle\Entity\EuropeanCV", inversedBy="practices")
     * @ORM\JoinColumn(name="european_cv_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $europeanCV;

    /**
     * @var DateRange
     *
     * @ORM\Embedded(class="Trexima\EuropeanCvBundle\Entity\Embeddable\DateRange")
     */
    private $dateRange;

    /**
     * @var string|null Max length is 256 because this field can be filled from Harvey where field max length is 256
     *
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    private $job;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $mainActivities;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $jobAddress;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $industry;

    /**
     * @var string|null
     *
     * @ORM\Column(type="integer", options={"unsigned"=true}))
     */
    private $position;

    public function __construct()
    {
        $this->dateRange = new DateRange();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return EuropeanCV
     */
    public function getEuropeanCV(): EuropeanCV
    {
        return $this->europeanCV;
    }

    /**
     * @param EuropeanCV $europeanCV
     */
    public function setEuropeanCV(EuropeanCV $europeanCV): void
    {
        $this->europeanCV = $europeanCV;
    }

    /**
     * @return DateRange
     */
    public function getDateRange(): DateRange
    {
        return $this->dateRange;
    }

    /**
     * @param DateRange $dateRange
     */
    public function setDateRange(DateRange $dateRange): void
    {
        $this->dateRange = $dateRange;
    }

    /**
     * @return null|string
     */
    public function getJob(): ?string
    {
        return $this->job;
    }

    /**
     * @param null|string $job
     */
    public function setJob(?string $job): void
    {
        $this->job = $job;
    }

    /**
     * @return null|string
     */
    public function getMainActivities(): ?string
    {
        return $this->mainActivities;
    }

    /**
     * @param null|string $mainActivities
     */
    public function setMainActivities(?string $mainActivities): void
    {
        $this->mainActivities = $mainActivities;
    }

    /**
     * @return null|string
     */
    public function getJobAddress(): ?string
    {
        return $this->jobAddress;
    }

    /**
     * @param null|string $jobAddress
     */
    public function setJobAddress(?string $jobAddress): void
    {
        $this->jobAddress = $jobAddress;
    }

    /**
     * @return null|string
     */
    public function getIndustry(): ?string
    {
        return $this->industry;
    }

    /**
     * @param null|string $industry
     */
    public function setIndustry(?string $industry): void
    {
        $this->industry = $industry;
    }

    /**
     * @return null|string
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @param null|string $position
     */
    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    public function __clone()
    {
        /*
         * If the entity has an identity, proceed as normal.
         * https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/cookbook/implementing-wakeup-or-clone.html
         */
        if ($this->id) {
            $this->setId(null);
        }
    }
}