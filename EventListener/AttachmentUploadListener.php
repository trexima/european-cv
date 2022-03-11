<?php

namespace Trexima\EuropeanCvBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVAttachment;
use Trexima\EuropeanCvBundle\Manager\EuropeanCvManager;

class AttachmentUploadListener
{
    /**
     * @var EuropeanCvManager
     */
    private $europeanCvManager;

    public function __construct(EuropeanCvManager $europeanCvManager)
    {
        $this->europeanCvManager = $europeanCvManager;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        // upload only works for EuropeanCVAttachment entities
        if (!$entity instanceof EuropeanCVAttachment) {
            return;
        }

        $file = $entity->getFile();
        // only upload new files
        if ($file instanceof UploadedFile) {
            $entity->setName($file->getClientOriginalName());
            $entity->setFile($this->europeanCvManager->uploadAttachment($file));
        } elseif ($file instanceof File) {
            // prevents the full file path being saved on updates
            // as the path is set on the postLoad listener
            $entity->setFile($file->getFilename());
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof EuropeanCVAttachment) {
            return;
        }

        if ($fileName = $entity->getFile()) {
            $entity->setFile(new File($this->europeanCvManager->getAttachmentsDir().$fileName));
        }
    }
}