<?php
namespace Trexima\EuropeanCvBundle\Twig;

use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Twig\Extension\RuntimeExtensionInterface;

class ImageTagRuntime implements RuntimeExtensionInterface
{
    /**
     * @var string
     */
    private $projectDir;

    /**
     * @var Packages
     */
    private $assetsPackages;

    /**
     * @var FileLocator
     */
    private $fileLocator;

    /**
     * @var Request
     */
    private $requestStack;

    public function __construct(string $projectDir, Packages $assetsPackages, FileLocator $fileLocator, RequestStack $requestStack)
    {
        $this->projectDir = $projectDir;
        $this->assetsPackages = $assetsPackages;
        $this->fileLocator = $fileLocator;
        $this->requestStack = $requestStack;
    }

    public function imageTag(string $imagePath, int $width, bool $useAbsolutePath = false)
    {
        $absoluteFilePath = $this->projectDir.'/public/'.ltrim($imagePath, '\\/');
        if (substr($imagePath, 0, 1) === '@') {
            $absoluteFilePath = $this->fileLocator->locate($imagePath);
        }
        if (!file_exists($absoluteFilePath)) {
            return '';
        }

        list($originalWidth, $originalHeight) = getimagesize($absoluteFilePath);
        $height = round(($originalHeight/$originalWidth)*$width);


        $request = $this->requestStack->getCurrentRequest();
        // TODO: Can we obtain path to public dir? bundles/treximaeuropeancv/
        // Resolve public url
        $src = $request->getSchemeAndHttpHost().$this->assetsPackages->getUrl(preg_replace('/^@.*?\/Resources\/public\//', 'bundles/treximaeuropeancv/', $imagePath));
        if ($useAbsolutePath) {
            $src = $absoluteFilePath;
        }

        return '<img src="'.$src.'" width="'.$width.'" height="'.$height.'" />';
    }
}