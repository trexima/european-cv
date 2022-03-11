<?php

namespace Trexima\EuropeanCvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVPractice;
use Trexima\EuropeanCvBundle\Form\Type\AtomicDateRangeType;
use Trexima\EuropeanCvBundle\Form\Type\Select2Type;

class EuropeanCVPracticeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('position', HiddenType::class)
            ->add('dateRange', AtomicDateRangeType::class, [
                'required' => false,
            ])->add('job', null, array(
                'label' => 'Zamestnanie alebo pracovné zaradenie',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Kuchár',
                ),
            ))->add('mainActivities', null, array(
                'label' => 'Hlavné činnosti a zodpovednosť',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Napr. príprava jedál národných kuchýň a ďalších špecialít.',
                ),
            ))->add('jobAddress', null, array(
                'label' => 'Názov a adresa zamestnávateľa',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'VZOR, spol. s r.o., Vzorová 1, 844 07 Bratislava IV, Slovenská republika',
                ),
            ))
            ->add('industry', null, array(
                'label' => 'Odvetvie hospodárstva',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Gastronómia',
                ),
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'data_class' => EuropeanCVPractice::class,
        ));
    }
}