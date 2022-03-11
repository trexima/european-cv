<?php

namespace Trexima\EuropeanCvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVEducation;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Form\Type\AtomicDateRangeType;
use Trexima\EuropeanCvBundle\Form\Type\Select2Type;
use Trexima\EuropeanCvBundle\Listing\EuropeanCvListingInterface;

class EuropeanCVEducationType extends AbstractType
{
    /**
     * @var EuropeanCvListingInterface
     */
    private $europeanCvListing;

    public function __construct(EuropeanCvListingInterface $europeanCvListing)
    {
        $this->europeanCvListing = $europeanCvListing;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $ekrAutocompleteData = array();
        foreach (EuropeanCVEducation::EUROPEAN_QUALIFICATION_LIST as $ekr) {
            $ekrAutocompleteData[] = array('value' => $ekr);
        }

        $builder->add('position', HiddenType::class)
            ->add('dateRange', AtomicDateRangeType::class, [
                'required' => false
            ])->add('title', null, array(
                'label' => 'Názov získanej kvalifikácie',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Úplné stredné odborné vzdelanie v odbore obchodná akadémia'
                )
            ))->add('educationLevel', Select2Type::class, array(
                'label' => 'Úroveň vzdelania',
                'placeholder' => 'Prosím, vyberte možnosť',
                'required' => false,
                'choices'  => array_flip($this->europeanCvListing->getEducationList()),
            ))->add('europeanQualification', null, array(
                'label' => 'Úroveň v Európskom kvalifikačnom rámci alebo národnej klasifikácii',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Vyberte úroveň alebo vyplňte text',
                    'data-trexima-european-cv-autocomplete' => true,
                    'data-trexima-european-cv-autocomplete-min-length' => 0,
                    'data-trexima-european-cv-autocomplete-data' => json_encode($ekrAutocompleteData)
                )
            ))->add('organizationAddress', null, array(
                'label' => 'Názov a adresa organizácie poskytujúcej vzdelávanie a prípravu',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Obchodná akadémia, Vzorová 1, 841 01 Bratislava IV, Slovenská republika'
                )
            ))->add('subject', null, array(
                'label' => 'Hlavné predmety/profesijné zručnosti',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Napr. slovenský jazyk a literatúra, anglický jazyk, matematika, fyzika, informatika'
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
            'data_class' => EuropeanCVEducation::class
        ));
    }
}