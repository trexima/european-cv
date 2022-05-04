<?php

namespace Trexima\EuropeanCvBundle\Twig;

use Pelago\Emogrifier\CssInliner;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Twig\Extension\RuntimeExtensionInterface;

class EmogrifierRuntime implements RuntimeExtensionInterface
{
    /**
     * @var FileLocator
     */
    private $fileLocator;

    public function __construct(FileLocator $fileLocator)
    {
        $this->fileLocator = $fileLocator;
    }

    public function emogrify(string $content, array $cssFilePaths, string $extraCssContent = '')
    {
        $cssContent = '';
        foreach ($cssFilePaths as $cssFilePath) {
            $cssContent .= file_get_contents($this->fileLocator->locate($cssFilePath));
        }

        return CssInliner::fromHtml($content)->inlineCss(strip_tags($cssContent).PHP_EOL.strip_tags($extraCssContent))->render();
    }
}