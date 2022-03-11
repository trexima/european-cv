<?php

namespace Trexima\EuropeanCvBundle\Twig\Extension;

use Trexima\EuropeanCvBundle\Twig\AtomicDateRuntime;
use Trexima\EuropeanCvBundle\Twig\EmogrifierRuntime;
use Trexima\EuropeanCvBundle\Twig\EuropeanCvListingRuntime;
use Trexima\EuropeanCvBundle\Twig\ImageTagRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class EuropeanCvExtension extends AbstractExtension
{


    public function getFunctions()
    {
        return [
            new TwigFunction('european_cv_image_tag', [ImageTagRuntime::class, 'imageTag'], ['is_safe' => ['html']]),
            new TwigFunction('european_cv_emogrify', [EmogrifierRuntime::class, 'emogrify'], ['is_safe' => ['html']])
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('european_cv_listing', [EuropeanCvListingRuntime::class, 'getListingValue']),
            new TwigFilter('european_cv_atomic_date', [AtomicDateRuntime::class, 'format']),
        ];
    }
}