<?php
namespace Trexima\EuropeanCvBundle\Twig;

use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Trexima\EuropeanCvBundle\Listing\EuropeanCvListingInterface;
use Twig\Extension\RuntimeExtensionInterface;

class EuropeanCvListingRuntime implements RuntimeExtensionInterface
{
    /**
     * @var EuropeanCvListingInterface
     */
    private $europeanCvListing;

    /**
     * @var PropertyAccessor
     */
    private $accessor;

    public function __construct(EuropeanCvListingInterface $europeanCvListing)
    {
        $this->europeanCvListing = $europeanCvListing;
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    public function getListingValue($key, $listingName)
    {
        $listing = $this->accessor->getValue($this->europeanCvListing, $listingName);
        if (!isset($listing[$key])) {
            return null;
        }

        return $listing[$key];
    }
}