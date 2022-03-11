<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Trexima\EuropeanCvBundle\Entity\Embeddable\DateRange;

/**
 * EuropeanCV education
 *
 * @ORM\Table(
 *  name="european_cv_education",
 *  indexes={
 *      @ORM\Index(name="education_level_idx", columns={"education_level"})
 *  }
 * )
 * @ORM\Entity
 */
class EuropeanCVEducation
{
    public const EKR_LEVEL_1 = 1,
        EKR_LEVEL_2 = 2,
        EKR_LEVEL_3 = 3,
        EKR_LEVEL_4 = 4,
        EKR_LEVEL_5 = 5,
        EKR_LEVEL_6 = 6,
        EKR_LEVEL_7 = 7,
        EKR_LEVEL_8 = 8;

    public const EUROPEAN_QUALIFICATION_LIST = [
        self::EKR_LEVEL_1 => 'EKR úroveň 1',
        self::EKR_LEVEL_2 => 'EKR úroveň 2',
        self::EKR_LEVEL_3 => 'EKR úroveň 3',
        self::EKR_LEVEL_4 => 'EKR úroveň 4',
        self::EKR_LEVEL_5 => 'EKR úroveň 5',
        self::EKR_LEVEL_6 => 'EKR úroveň 6',
        self::EKR_LEVEL_7 => 'EKR úroveň 7',
        self::EKR_LEVEL_8 => 'EKR úroveň 8'
    ];

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
     * @ORM\ManyToOne(targetEntity="Trexima\EuropeanCvBundle\Entity\EuropeanCV", inversedBy="educations")
     * @ORM\JoinColumn(name="european_cv_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $europeanCV;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $title;

    /**
     * @var DateRange
     *
     * @ORM\Embedded(class="Trexima\EuropeanCvBundle\Entity\Embeddable\DateRange")
     */
    private $dateRange;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     */
    private $educationLevel;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $europeanQualification;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $organizationAddress;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $subject;

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
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
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
     * @return int|null
     */
    public function getEducationLevel(): ?int
    {
        return $this->educationLevel;
    }

    /**
     * @param int|null $educationLevel
     */
    public function setEducationLevel(?int $educationLevel): void
    {
        $this->educationLevel = $educationLevel;
    }

    /**
     * @return null|string
     */
    public function getEuropeanQualification(): ?string
    {
        return $this->europeanQualification;
    }

    /**
     * @param null|string $europeanQualification
     */
    public function setEuropeanQualification(?string $europeanQualification): void
    {
        $this->europeanQualification = $europeanQualification;
    }

    /**
     * @return null|string
     */
    public function getOrganizationAddress(): ?string
    {
        return $this->organizationAddress;
    }

    /**
     * @param null|string $organizationAddress
     */
    public function setOrganizationAddress(?string $organizationAddress): void
    {
        $this->organizationAddress = $organizationAddress;
    }

    /**
     * @return null|string
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param null|string $subject
     */
    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
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