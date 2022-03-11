<?php

namespace Trexima\EuropeanCvBundle\Export;

use Mpdf\Mpdf;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Twig\Environment;

class EuropeanCvExporter
{
    public const TYPE_PDF = 'pdf',
        TYPE_DOC = 'doc';

    /**
     * @var string
     */
    private $uploadUrl;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(string $uploadUrl, Environment $twig)
    {
        $this->uploadUrl = $uploadUrl;
        $this->twig = $twig;
    }

    /**
     * @return Mpdf
     * @throws \Mpdf\MpdfException
     */
    private function createMpdf()
    {
        $mpdf = new Mpdf([
            'format' => 'A4',
            'tempDir' => sys_get_temp_dir().'/mpdf'
        ]);
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->showImageErrors = true;

        return $mpdf;
    }

    /**
     * Generate HTML content for DOC or PDF.
     *
     * @param EuropeanCV $europeanCV
     * @param string $exportType self::TYPE_PDF|self::TYPE_DOC
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function generateContent(EuropeanCV $europeanCV, string $exportType)
    {
        return $this->twig->render('@TreximaEuropeanCv/export/european_cv.html.twig', [
            'exportType' => $exportType,
            'european_cv' => $europeanCV,
            'image_upload_url' => ltrim($this->uploadUrl, '\\/').'/images/',
            // PDF require absolute paths because mPDF needs to download images and connecting to foreign hosts(Cloudflare) is disabled
            'img_use_absolute_path' => $exportType === self::TYPE_PDF
        ]);
    }

    /**
     * Generate PDF or DOC content.
     *
     * @param EuropeanCV $europeanCV
     * @param string $exportType self::TYPE_PDF|self::TYPE_DOC
     * @return string
     * @throws \Mpdf\MpdfException
     */
    public function generate(EuropeanCV $europeanCV, string $exportType)
    {
        $html = $this->generateContent($europeanCV, $exportType);
        switch ($exportType) {
            case self::TYPE_PDF:
                $mpdf = $this->createMpdf();
                $mpdf->WriteHTML($html);
                return $mpdf->Output('', 'S');
                break;
            case self::TYPE_DOC:
                return $html;
                break;
            default:
                trigger_error('This output type isn\'t supported');
        }
    }
}