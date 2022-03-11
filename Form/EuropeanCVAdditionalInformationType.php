<?php

namespace Trexima\EuropeanCvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVAdditionalInformation;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EuropeanCVAdditionalInformationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $now = new \DateTime();
        $builder->add('position', HiddenType::class)
            ->add('type', ChoiceType::class, array(
                'label' => 'Typ',
                'placeholder' => 'Prosím vyberte',
                'required' => false,
                'choices'  => array_flip(EuropeanCVAdditionalInformation::TYPES),
            ))->add('content', TextareaType::class, array(
                'label' => 'Opis',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Vyplňte'
                )
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'data_class' => EuropeanCVAdditionalInformation::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'european_cv_additional_information';
    }
}