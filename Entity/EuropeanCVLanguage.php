<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EuropeanCV language
 *
 * @ORM\Table(
 *  name="european_cv_language",
 *  indexes={
 *      @ORM\Index(name="language_idx", columns={"language"})
 *  }
 * )
 * @ORM\Entity
 */
class EuropeanCVLanguage
{
    public const LANGUAGE_LEVEL_A1 = 1,
        LANGUAGE_LEVEL_A2 = 2,
        LANGUAGE_LEVEL_B1 = 3,
        LANGUAGE_LEVEL_B2 = 4,
        LANGUAGE_LEVEL_C1 = 5,
        LANGUAGE_LEVEL_C2 = 6;

    public const LANGUAGE_LEVEL_LIST = [
        self::LANGUAGE_LEVEL_A1 => 'A1 Používateľ základov jazyka ',
        self::LANGUAGE_LEVEL_A2 => 'A2 Používateľ základov jazyka',
        self::LANGUAGE_LEVEL_B1 => 'B1 Samostatný používateľ',
        self::LANGUAGE_LEVEL_B2 => 'B2 Samostatný používateľ',
        self::LANGUAGE_LEVEL_C1 => 'C1 Skúsený používateľ',
        self::LANGUAGE_LEVEL_C2 => 'C2 Skúsený používateľ'
    ];

    public const LANGUAGE_LEVEL_TO_CODE = [
        self::LANGUAGE_LEVEL_A1 => 'A1',
        self::LANGUAGE_LEVEL_A2 => 'A2',
        self::LANGUAGE_LEVEL_B1 => 'B1',
        self::LANGUAGE_LEVEL_B2 => 'B2',
        self::LANGUAGE_LEVEL_C1 => 'C1',
        self::LANGUAGE_LEVEL_C2 => 'C2'
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
     * @ORM\ManyToOne(targetEntity="Trexima\EuropeanCvBundle\Entity\EuropeanCV", inversedBy="languages")
     * @ORM\JoinColumn(name="european_cv_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $europeanCV;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=5, options={"comment": "ISO 639-1"})
     */
    private $language;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true, options={"unsigned"=true})
     * @Assert\Choice(callback="getLanguageLevelList", message="Choose a valid language level.")
     */
    private $listeningLevel;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true, options={"unsigned"=true})
     * @Assert\Choice(callback="getLanguageLevelList", message="Choose a valid language level.")
     */
    private $readingLevel;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true, options={"unsigned"=true})
     * @Assert\Choice(callback="getLanguageLevelList", message="Choose a valid language level.")
     */
    private $talkingLevel;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true, options={"unsigned"=true})
     * @Assert\Choice(callback="getLanguageLevelList", message="Choose a valid language level.")
     */
    private $oralSpeechLevel;

    /**
    * @var int|null
    *
    * @ORM\Column(type="smallint", nullable=true, options={"unsigned"=true})
    * @Assert\Choice(callback="getLanguageLevelList", message="Choose a valid language level.")
    */
    private $writingLevel;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $certificate;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", options={"unsigned"=true})
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
     * @return null|string
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @param null|string $language
     */
    public function setLanguage(?string $language): void
    {
        $this->language = $language;
    }

    /**
     * @return int|null
     */
    public function getListeningLevel(): ?int
    {
        return $this->listeningLevel;
    }

    /**
     * @param int|null $listeningLevel
     */
    public function setListeningLevel(?int $listeningLevel): void
    {
        $this->listeningLevel = $listeningLevel;
    }

    /**
     * @return int|null
     */
    public function getReadingLevel(): ?int
    {
        return $this->readingLevel;
    }

    /**
     * @param int|null $readingLevel
     */
    public function setReadingLevel(?int $readingLevel): void
    {
        $this->readingLevel = $readingLevel;
    }

    /**
     * @return int|null
     */
    public function getTalkingLevel(): ?int
    {
        return $this->talkingLevel;
    }

    /**
     * @param int|null $talkingLevel
     */
    public function setTalkingLevel(?int $talkingLevel): void
    {
        $this->talkingLevel = $talkingLevel;
    }

    /**
     * @return int|null
     */
    public function getOralSpeechLevel(): ?int
    {
        return $this->oralSpeechLevel;
    }

    /**
     * @param int|null $oralSpeechLevel
     */
    public function setOralSpeechLevel(?int $oralSpeechLevel): void
    {
        $this->oralSpeechLevel = $oralSpeechLevel;
    }

    /**
     * @return int|null
     */
    public function getWritingLevel(): ?int
    {
        return $this->writingLevel;
    }

    /**
     * @param int|null $writingLevel
     */
    public function setWritingLevel(?int $writingLevel): void
    {
        $this->writingLevel = $writingLevel;
    }

    /**
     * @return null|string
     */
    public function getCertificate(): ?string
    {
        return $this->certificate;
    }

    /**
     * @param null|string $certificate
     */
    public function setCertificate(?string $certificate): void
    {
        $this->certificate = $certificate;
    }

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int|null $position
     */
    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public static function getLanguageLevelList()
    {
        return array_keys(self::LANGUAGE_LEVEL_LIST );
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
