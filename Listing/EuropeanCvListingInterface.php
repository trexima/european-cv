<?php

namespace Trexima\EuropeanCvBundle\Listing;


interface EuropeanCvListingInterface
{
    public function getEducationList(): array;
    public function getLanguageList(): array;
    public function getDrivingLicenseList(): array;
}