<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Trexima\EuropeanCvBundle\Entity\Listing\DrivingLicense;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EuropeanCV practice
 *
 * @ORM\Table(
 *   name="european_cv_driving_license",
 *   uniqueConstraints = {
 *      @ORM\UniqueConstraint(columns = {"european_cv_id", "driving_license"})
 *  },
 *  indexes={
 *      @ORM\Index(name="driving_license_idx", columns={"driving_license"})
 *  }
 * )
 * @ORM\Entity
 */
class EuropeanCVDrivingLicense
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
     * @ORM\ManyToOne(targetEntity="Trexima\EuropeanCvBundle\Entity\EuropeanCV", inversedBy="drivingLicenses")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $europeanCV;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $drivingLicense;

    /**
     * @var string|null
     *
     * @ORM\Column(type="integer", nullable=true, options={
     *  "unsigned"=true,
     *  "comment": "Distance traveled in kilometers"
     * })
     */
    private $distanceTraveled;

    /**
     * @var boolean|null
     *
     * @ORM\Column(type="boolean")
     */
    private $activeDriver;

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
    public function getDrivingLicense(): ?string
    {
        return $this->drivingLicense;
    }

    /**
     * @param null|string $drivingLicense
     */
    public function setDrivingLicense(?string $drivingLicense): void
    {
        $this->drivingLicense = $drivingLicense;
    }

    /**
     * @return null|string
     */
    public function getDistanceTraveled(): ?string
    {
        return $this->distanceTraveled;
    }

    /**
     * @param null|string $distanceTraveled
     */
    public function setDistanceTraveled(?string $distanceTraveled): void
    {
        $this->distanceTraveled = $distanceTraveled;
    }

    /**
     * @return bool|null
     */
    public function getActiveDriver(): ?bool
    {
        return $this->activeDriver;
    }

    /**
     * @param bool|null $activeDriver
     */
    public function setActiveDriver(?bool $activeDriver): void
    {
        $this->activeDriver = $activeDriver;
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