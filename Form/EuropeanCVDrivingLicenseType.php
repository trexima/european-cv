<?php

namespace Trexima\EuropeanCvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVDrivingLicense;
use Trexima\EuropeanCvBundle\Entity\Listing\DrivingLicense;

class EuropeanCVDrivingLicenseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $drivingLicense = $options['driving_license'];
        $drivingLicenseCode = $options['driving_license_code'];
        $builder->add('drivingLicense', CheckboxType::class, array(
            'label' => $drivingLicense ? $drivingLicense['label'] : false,
            'required' => false
        ))->add('distanceTraveled', null, array(
            'label' => 'Počet KM',
            'required' => false,
            'attr' => [
                'placeholder' => 'Počet KM'
            ]
        ))->add('activeDriver', CheckboxType::class, array(
            'label' => 'Som aktívny vodič',
            'required' => false
        ));

        /**
         * Return DrivingLicense entity if checkbox is checked instead bollean
         */
        $builder->get('drivingLicense')->addModelTransformer(new CallbackTransformer(
            function ($isChecked) {
                // Value is already filled if array with driver license data is recieved
                return $isChecked ? true : $isChecked;
            },
            function ($isChecked) use ($drivingLicenseCode) {
                if ($isChecked) {
                    // Field is checked, save entity to parent entity
                    return $drivingLicenseCode;
                }

                // Field isn't checked, return null to parent entity
                return null;
            }
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options); // TODO: Change the autogenerated stub

        $view->vars['driving_license'] = $options['driving_license'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'data_class' => EuropeanCVDrivingLicense::class
        ));

        $resolver->setRequired(array(
            'driving_license',
            'driving_license_code'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'european_cv_driving_license';
    }
}