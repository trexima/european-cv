<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="european_cv_attachment"
 * )
 * @ORM\Entity
 */
class EuropeanCVAttachment
{
    public const TYPE_MOBILE = 1,
        TYPE_HOME = 2,
        TYPE_WORK = 3;

    public const TYPES = [
        self::TYPE_MOBILE => 'Mobil',
        self::TYPE_HOME => 'Doma',
        self::TYPE_WORK => 'Práca'
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
     * @ORM\ManyToOne(targetEntity="Trexima\EuropeanCvBundle\Entity\EuropeanCV", inversedBy="attachments")
     * @ORM\JoinColumn(name="european_cv_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $europeanCV;

    /**
     * @var string|File|null
     *
     * @ORM\Column(type="string", length=256, unique=true)
     * @Assert\NotBlank(message="Nahrajte prílohu prosím")
     * @Assert\File(
     *  maxSize = "2048k",
     *  mimeTypes = {
     *     "application/pdf",
     *     "application/msword",
     *     "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
     *     "application/rtf",
     *     "image/jpeg",
     *     "text/plain"
     *  },
     *  mimeTypesMessage = "Nahrajte prosím súbor v správnom formáte"
     * )
     */
    private $file;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=256)
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", options={"unsigned"=true}))
     */
    private $position;

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
     * @return null|string|File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param null|string|File $file
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
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