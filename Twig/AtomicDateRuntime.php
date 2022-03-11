<?php
namespace Trexima\EuropeanCvBundle\Twig;

use Twig\Extension\RuntimeExtensionInterface;

class AtomicDateRuntime implements RuntimeExtensionInterface
{
    public function format(array $dateParts, string $glue = '.')
    {
        return implode($glue, array_filter($dateParts));
    }
}
