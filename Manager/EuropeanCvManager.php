<?php

namespace Trexima\EuropeanCvBundle\Manager;

use claviska\SimpleImage;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVAttachment;
use Trexima\EuropeanCvBundle\Export\EuropeanCvExporter;

class EuropeanCvManager
{
    private const THUMB_WIDTH = 250,
        THUMB_HEIGHT = 250;

    /**
     * @var string
     */
    private $uploadDir;

    /**
     * @var EuropeanCvExporter
     */
    private $europeanCvExporter;

    public function __construct(string $uploadDir, EuropeanCvExporter $europeanCvExporter)
    {
        $this->uploadDir = $uploadDir;
        $this->europeanCvExporter = $europeanCvExporter;
    }

    /**
     * Save thumbnail to file.
     * Output is always jpeg file.
     *
     * @param File $file
     * @param string $dir Directory for saving thumbnail
     * @param string $filename Filename of thumbnail
     * @throws \Exception
     */
    public function saveThumbnail(File $file, string $dir, string $filename)
    {
        $image = new SimpleImage($file->getRealPath());
        $image->thumbnail(self::THUMB_WIDTH, self::THUMB_HEIGHT);

        // Always use jpg for resized images
        $image->toFile($dir.'/'.$filename, 'image/jpeg', 80);
    }

    public function uploadAttachment(UploadedFile $file)
    {
        $fileName = md5(uniqid('', true)).'.'.$file->guessExtension();

        $file->move($this->getAttachmentsDir(), $fileName);

        return $fileName;
    }

    /**
     * @return string Absolute path to attachemnts dir
     */
    public function getAttachmentsDir()
    {
        return rtrim($this->uploadDir, '\\/').'/attachments/';
    }
}