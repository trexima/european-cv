<?php

namespace Trexima\EuropeanCvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVAttachment;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Trexima\EuropeanCvBundle\Manager\EuropeanCvManager;

class EuropeanCVAttachmentType extends AbstractType
{
    /**
     * @var EuropeanCvManager
     */
    private $europeanCvManager;

    public function __construct(EuropeanCvManager $europeanCvManager)
    {
        $this->europeanCvManager = $europeanCvManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('position', HiddenType::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var EuropeanCVAttachment $attachment */
            $attachment = $event->getData();
            $form = $event->getForm();


            if ($attachment instanceof EuropeanCVAttachment) {
                $form->add('name', TextType::class, [
                        'disabled' => true
                    ]);
            } else {
                // Upload field is worthless for already uploaded files
                $form->add('file', null, [
                    'label' => 'Príloha',
                    'mapped' => true,
                    'required' => true,
                    'attr' => [
                        'accept' => implode(',', [
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/rtf',
                            'image/jpeg',
                            'text/plain'
                        ])
                    ]
                ]);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'data_class' => EuropeanCVAttachment::class,
        ));
    }
}