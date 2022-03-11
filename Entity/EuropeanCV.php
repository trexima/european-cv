<?php

namespace Trexima\EuropeanCvBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Trexima\EuropeanCvBundle\Model\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * WARNING: Don't forget to prepare newly created entity relations for clonning in magic __clone method.
 *
 * @ORM\Table(
 *  name="european_cv",
 *  indexes={
 *      @ORM\Index(name="language_mother_idx", columns={"language_mother"})
 *  }
 * )
 * @ORM\Entity
 */
class EuropeanCV
{
    public const SEX_MALE = 1,
        SEX_FEMALE = 2;

    public const DIGITAL_SKILL_LEVEL_BASIC = 1,
        DIGITAL_SKILL_LEVEL_ADVANCED = 2,
        DIGITAL_SKILL_LEVEL_EXPERT = 3;

    public const SEX_LIST = [
        self::SEX_MALE => 'Muž',
        self::SEX_FEMALE => 'Žena'
    ];

    public const DIGITAL_SKILL_LEVEL_LIST = [
        self::DIGITAL_SKILL_LEVEL_BASIC => 'Používateľ so základnými zručnosťami',
        self::DIGITAL_SKILL_LEVEL_ADVANCED => 'Samostatný používateľ',
        self::DIGITAL_SKILL_LEVEL_EXPERT => 'Skúsený používateľ'
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
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="Trexima\EuropeanCvBundle\Model\UserInterface")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     * @var EuropeanCVPractice[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Trexima\EuropeanCvBundle\Entity\EuropeanCVPractice", mappedBy="europeanCV", orphanRemoval=true, cascade={"all"})
     * @ORM\OrderBy({"position" = "ASC"})
     *
     * @Assert\Valid
     */
    private $practices;

    /**
     * @var EuropeanCVEducation[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Trexima\EuropeanCvBundle\Entity\EuropeanCVEducation", mappedBy="europeanCV", orphanRemoval=true, cascade={"all"})
     * @ORM\OrderBy({"position" = "ASC"})
     *
     * @Assert\Valid
     */
    private $educations;

    /**
     * @var EuropeanCVLanguage[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Trexima\EuropeanCvBundle\Entity\EuropeanCVLanguage", mappedBy="europeanCV", orphanRemoval=true, cascade={"all"})
     * @ORM\OrderBy({"position" = "ASC"})
     *
     * @Assert\Valid
     */
    private $languages;

    /**
     * @var EuropeanCVDrivingLicense[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Trexima\EuropeanCvBundle\Entity\EuropeanCVDrivingLicense", mappedBy="europeanCV", orphanRemoval=true, cascade={"all"})
     *
     * @Assert\Valid
     */
    private $drivingLicenses;

    /**
     * @var EuropeanCVAdditionalInformation[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Trexima\EuropeanCvBundle\Entity\EuropeanCVAdditionalInformation", mappedBy="europeanCV", orphanRemoval=true, cascade={"all"})
     * @ORM\OrderBy({"position" = "ASC"})
     *
     * @Assert\Valid
     */
    private $additionalInformations;

    /**
     * @var EuropeanCVPhone[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Trexima\EuropeanCvBundle\Entity\EuropeanCVPhone", mappedBy="europeanCV", orphanRemoval=true, cascade={"all"})
     * @ORM\OrderBy({"position" = "ASC"})
     *
     * @Assert\Valid
     */
    private $phones;

    /**
     * @var EuropeanCVAttachment[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Trexima\EuropeanCvBundle\Entity\EuropeanCVAttachment", mappedBy="europeanCV", orphanRemoval=true, cascade={"all"})
     * @ORM\OrderBy({"position" = "ASC"})
     *
     * @Assert\Valid
     */
    private $attachments;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     *
     */
    private $photo;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=128)
     *
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $address;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=320, nullable=true)
     *
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Url()
     */
    private $personalWebsite;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $nationality;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     *
     * @Assert\Choice(callback="getSexList", message="Choose a valid sex.")
     */
    private $sex;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $jobInterest;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=5, options={"comment": "ISO 639-1"})
     */
    private $languageMother;

    /**
     * @var boolean|null
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $drivingLicenseOwner;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $skillCommunication;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $skillManagement;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $skillJob;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $skillOther;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Choice(callback="getDigitalSkillLevelList", message="Choose a valid digital skill level.")
     */
    private $skillDigitalInformationProcessing;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Choice(callback="getDigitalSkillLevelList", message="Choose a valid digital skill level.")
     */
    private $skillDigitalCommunication;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Choice(callback="getDigitalSkillLevelList", message="Choose a valid digital skill level.")
     */
    private $skillDigitalContentCreation;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Choice(callback="getDigitalSkillLevelList", message="Choose a valid digital skill level.")
     */
    private $skillDigitalSecurity;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Choice(callback="getDigitalSkillLevelList", message="Choose a valid digital skill level.")
     */
    private $skillDigitalTroubleshooting;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $skillDigitalCertificate;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $skillDigitalOther;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $attachmentList;

    /**
     * @var bool|null
     *
     * @ORM\Column(type="boolean")
     */
    private $invertPositionPracticeEducation = false;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->practices = new ArrayCollection();
        $this->educations = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->drivingLicenses = new ArrayCollection();
        $this->phones = new ArrayCollection();
        $this->additionalInformations = new ArrayCollection();
        $this->attachments = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return EuropeanCVPractice[]|Collection
     */
    public function getPractices()
    {
        return $this->practices;
    }

    /**
     * @param EuropeanCVPractice $practice
     */
    public function addPractice(EuropeanCVPractice $practice)
    {
        $this->practices[] = $practice;
        $practice->setEuropeanCV($this);
    }

    /**
     * @param EuropeanCVPractice $practice
     */
    public function removePractice(EuropeanCVPractice $practice)
    {
        $this->practices->removeElement($practice);
    }

    /**
     * @return EuropeanCVEducation[]|Collection
     */
    public function getEducations()
    {
        return $this->educations;
    }

    /**
     * @param EuropeanCVEducation $education
     */
    public function addEducation(EuropeanCVEducation $education)
    {
        $this->educations[] = $education;
        $education->setEuropeanCV($this);
    }

    /**
     * @param EuropeanCVEducation $practice
     */
    public function removeEducation(EuropeanCVEducation $education)
    {
        $this->educations->removeElement($education);
    }

    /**
     * @return EuropeanCVLanguage[]|Collection
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param EuropeanCVEducation $language
     */
    public function addLanguage(EuropeanCVLanguage $language)
    {
        $this->languages[] = $language;
        $language->setEuropeanCV($this);
    }

    /**
     * @param EuropeanCVLanguage $language
     */
    public function removeLanguage(EuropeanCVLanguage $language)
    {
        $this->languages->removeElement($language);
    }

    /**
     * @return EuropeanCVDrivingLicense[]|Collection
     */
    public function getDrivingLicenses()
    {
        return $this->drivingLicenses;
    }

    /**
     * @param EuropeanCVDrivingLicense $drivingLicense
     */
    public function addDrivingLicense(EuropeanCVDrivingLicense $drivingLicense)
    {
        $this->drivingLicenses[] = $drivingLicense;
        $drivingLicense->setEuropeanCV($this);
    }

    /**
     * @param EuropeanCVDrivingLicense $drivingLicense
     */
    public function removeDrivingLicense(EuropeanCVDrivingLicense $drivingLicense)
    {
        $this->drivingLicenses->removeElement($drivingLicense);
    }

    /**
     * @return EuropeanCVPhone[]|Collection
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * @param EuropeanCVPhone $phone
     */
    public function addPhone(EuropeanCVPhone $phone)
    {
        $this->phones[] = $phone;
        $phone->setEuropeanCV($this);
    }

    /**
     * @param EuropeanCVPhone $phone
     */
    public function removePhone(EuropeanCVPhone $phone)
    {
        $this->phones->removeElement($phone);
    }

    /**
     * @return EuropeanCVAttachment[]|Collection
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param EuropeanCVAttachment $attachment
     */
    public function addAttachment(EuropeanCVAttachment $attachment)
    {
        $this->attachments[] = $attachment;
        $attachment->setEuropeanCV($this);
    }

    /**
     * @param EuropeanCVAttachment $attachment
     */
    public function removeAttachment(EuropeanCVAttachment $attachment)
    {
        $this->attachments->removeElement($attachment);
    }

    /**
     * @return EuropeanCVAdditionalInformation[]|Collection
     */
    public function getAdditionalInformations()
    {
        return $this->additionalInformations;
    }

    /**
     * @param EuropeanCVAdditionalInformation $education
     */
    public function addAdditionalInformation(EuropeanCVAdditionalInformation $additionalInformation)
    {
        $this->additionalInformations[] = $additionalInformation;
        $additionalInformation->setEuropeanCV($this);
    }

    /**
     * @param EuropeanCVAdditionalInformation $practice
     */
    public function removeAdditionalInformation(EuropeanCVAdditionalInformation $additionalInformation)
    {
        $this->additionalInformations->removeElement($additionalInformation);
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
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

    /**
     * @return null|string
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param null|string $photo
     */
    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo;
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
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param null|string $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return null|string
     */
    public function getPersonalWebsite(): ?string
    {
        return $this->personalWebsite;
    }

    /**
     * @param null|string $personalWebsite
     */
    public function setPersonalWebsite(?string $personalWebsite): void
    {
        $this->personalWebsite = $personalWebsite;
    }

    /**
     * @return null|string
     */
    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    /**
     * @param null|string $nationality
     */
    public function setNationality(?string $nationality): void
    {
        $this->nationality = $nationality;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateOfBirth(): ?\DateTime
    {
        return $this->dateOfBirth;
    }

    /**
     * @param \DateTime|null $dateOfBirth
     */
    public function setDateOfBirth(?\DateTime $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return int|null
     */
    public function getSex(): ?int
    {
        return $this->sex;
    }

    /**
     * @param int|null $sex
     */
    public function setSex(?int $sex): void
    {
        $this->sex = $sex;
    }

    /**
     * @return null|string
     */
    public function getJobInterest(): ?string
    {
        return $this->jobInterest;
    }

    /**
     * @param null|string $jobInterest
     */
    public function setJobInterest(?string $jobInterest): void
    {
        $this->jobInterest = $jobInterest;
    }

    /**
     * @return null|string
     */
    public function getLanguageMother(): ?string
    {
        return $this->languageMother;
    }

    /**
     * @param null|string $languageMother
     */
    public function setLanguageMother(?string $languageMother): void
    {
        $this->languageMother = $languageMother;
    }

    /**
     * @return bool|null
     */
    public function isDrivingLicenseOwner(): ?bool
    {
        return $this->drivingLicenseOwner;
    }

    /**
     * @param bool|null $drivingLicenseOwner
     */
    public function setDrivingLicenseOwner(?bool $drivingLicenseOwner): void
    {
        $this->drivingLicenseOwner = $drivingLicenseOwner;
    }

    /**
     * @return null|string
     */
    public function getSkillCommunication(): ?string
    {
        return $this->skillCommunication;
    }

    /**
     * @param null|string $skillCommunication
     */
    public function setSkillCommunication(?string $skillCommunication): void
    {
        $this->skillCommunication = $skillCommunication;
    }

    /**
     * @return null|string
     */
    public function getSkillManagement(): ?string
    {
        return $this->skillManagement;
    }

    /**
     * @param null|string $skillManagement
     */
    public function setSkillManagement(?string $skillManagement): void
    {
        $this->skillManagement = $skillManagement;
    }

    /**
     * @return null|string
     */
    public function getSkillJob(): ?string
    {
        return $this->skillJob;
    }

    /**
     * @param null|string $skillJob
     */
    public function setSkillJob(?string $skillJob): void
    {
        $this->skillJob = $skillJob;
    }

    /**
     * @return null|string
     */
    public function getSkillOther(): ?string
    {
        return $this->skillOther;
    }

    /**
     * @param null|string $skillOther
     */
    public function setSkillOther(?string $skillOther): void
    {
        $this->skillOther = $skillOther;
    }

    /**
     * @return int|null
     */
    public function getSkillDigitalInformationProcessing(): ?int
    {
        return $this->skillDigitalInformationProcessing;
    }

    /**
     * @param int|null $skillDigitalInformationProcessing
     */
    public function setSkillDigitalInformationProcessing(?int $skillDigitalInformationProcessing): void
    {
        $this->skillDigitalInformationProcessing = $skillDigitalInformationProcessing;
    }

    /**
     * @return int|null
     */
    public function getSkillDigitalCommunication(): ?int
    {
        return $this->skillDigitalCommunication;
    }

    /**
     * @param int|null $skillDigitalCommunication
     */
    public function setSkillDigitalCommunication(?int $skillDigitalCommunication): void
    {
        $this->skillDigitalCommunication = $skillDigitalCommunication;
    }

    /**
     * @return int|null
     */
    public function getSkillDigitalContentCreation(): ?int
    {
        return $this->skillDigitalContentCreation;
    }

    /**
     * @param int|null $skillDigitalContentCreation
     */
    public function setSkillDigitalContentCreation(?int $skillDigitalContentCreation): void
    {
        $this->skillDigitalContentCreation = $skillDigitalContentCreation;
    }

    /**
     * @return int|null
     */
    public function getSkillDigitalSecurity(): ?int
    {
        return $this->skillDigitalSecurity;
    }

    /**
     * @param int|null $skillDigitalSecurity
     */
    public function setSkillDigitalSecurity(?int $skillDigitalSecurity): void
    {
        $this->skillDigitalSecurity = $skillDigitalSecurity;
    }

    /**
     * @return int|null
     */
    public function getSkillDigitalTroubleshooting(): ?int
    {
        return $this->skillDigitalTroubleshooting;
    }

    /**
     * @param int|null $skillDigitalTroubleshooting
     */
    public function setSkillDigitalTroubleshooting(?int $skillDigitalTroubleshooting): void
    {
        $this->skillDigitalTroubleshooting = $skillDigitalTroubleshooting;
    }

    /**
     * @return null|string
     */
    public function getSkillDigitalCertificate(): ?string
    {
        return $this->skillDigitalCertificate;
    }

    /**
     * @param null|string $skillDigitalCertificate
     */
    public function setSkillDigitalCertificate(?string $skillDigitalCertificate): void
    {
        $this->skillDigitalCertificate = $skillDigitalCertificate;
    }

    /**
     * @return null|string
     */
    public function getSkillDigitalOther(): ?string
    {
        return $this->skillDigitalOther;
    }

    /**
     * @param null|string $skillDigitalOther
     */
    public function setSkillDigitalOther(?string $skillDigitalOther): void
    {
        $this->skillDigitalOther = $skillDigitalOther;
    }

    /**
     * @return null|string
     */
    public function getAttachmentList(): ?string
    {
        return $this->attachmentList;
    }

    /**
     * @param null|string $attachmentList
     */
    public function setAttachmentList(?string $attachmentList): void
    {
        $this->attachmentList = $attachmentList;
    }

    /**
     * @return bool|null
     */
    public function getInvertPositionPracticeEducation(): ?bool
    {
        return $this->invertPositionPracticeEducation;
    }

    /**
     * @param bool|null $invertPositionPracticeEducation
     */
    public function setInvertPositionPracticeEducation(?bool $invertPositionPracticeEducation): void
    {
        $this->invertPositionPracticeEducation = $invertPositionPracticeEducation;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     */
    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public static function getSexList()
    {
        return array_keys(self::SEX_LIST);
    }

    public static function getDigitalSkillLevelList()
    {
        return array_keys(self::DIGITAL_SKILL_LEVEL_LIST);
    }

    public function __clone()
    {
        /*
         * If the entity has an identity, proceed as normal.
         * https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/cookbook/implementing-wakeup-or-clone.html
         */
        if ($this->id) {
            $this->setId(null);

            $this->practices = clone $this->practices;
            foreach ($this->practices as $key => $practice) {
                $practice = clone $practice;
                $practice->setEuropeanCV($this);
                $this->practices->set($key, $practice);
            }

            $this->educations = clone $this->educations;
            foreach ($this->educations as $key => $education) {
                $education = clone $education;
                $education->setEuropeanCV($this);
                $this->educations->set($key, $education);
            }

            $this->languages = clone $this->languages;
            foreach ($this->languages as $key => $language) {
                $language = clone $language;
                $language->setEuropeanCV($this);
                $this->languages->set($key, $language);
            }

            $this->drivingLicenses = clone $this->drivingLicenses;
            foreach ($this->drivingLicenses as $key => $drivingLicense) {
                $drivingLicense = clone $drivingLicense;
                $drivingLicense->setEuropeanCV($this);
                $this->drivingLicenses->set($key, $drivingLicense);
            }

            $this->additionalInformations = clone $this->additionalInformations;
            foreach ($this->additionalInformations as $key => $additionalInformation) {
                $additionalInformation = clone $additionalInformation;
                $additionalInformation->setEuropeanCV($this);
                $this->additionalInformations->set($key, $practice);
            }

            $this->phones = clone $this->phones;
            foreach ($this->phones as $phone) {
                $phone = clone $phone;
                $phone->setEuropeanCV($this);
                $this->phones->set($key, $phone);
            }

            $this->attachments = clone $this->attachments;
            foreach ($this->attachments as $attachment) {
                $attachment = clone $attachment;
                $attachment->setEuropeanCV($this);
                $this->attachments->set($key, $attachment);
            }
        }
    }
}