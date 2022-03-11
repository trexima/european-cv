<?php

namespace Trexima\EuropeanCvBundle\Listing;

use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

class EuropeanCvListing implements EuropeanCvListingInterface
{
    /**
     * @var FileLocator
     */
    private $fileLocator;

    /**
     * @var array
     */
    private $educationList;

    /**
     * @var array
     */
    private $languageList;

    /**
     * @var array
     */
    private $drivingLicenseList;

    public function __construct(FileLocator $fileLocator)
    {
        $this->fileLocator = $fileLocator;
    }

    public function getEducationList(): array
    {
        if (isset($this->educationList)) {
            return $this->educationList;
        }

        return $this->educationList = Yaml::parseFile($this->fileLocator->locate('@TreximaEuropeanCvBundle/Resources/listing/education.yaml'));
    }

    public function getLanguageList(): array
    {
        if (isset($this->languageList)) {
            return $this->languageList;
        }

        return $this->languageList = Yaml::parseFile($this->fileLocator->locate('@TreximaEuropeanCvBundle/Resources/listing/language.yaml'));
    }

    public function getDrivingLicenseList(): array
    {
        if (isset($this->drivingLicenseList)) {
            return $this->drivingLicenseList;
        }

        return $this->drivingLicenseList = Yaml::parseFile($this->fileLocator->locate('@TreximaEuropeanCvBundle/Resources/listing/driving_license.yaml'));
    }
}