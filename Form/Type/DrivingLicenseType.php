<?php

namespace Trexima\EuropeanCvBundle\Form\Type;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVDrivingLicense;
use Trexima\EuropeanCvBundle\Entity\Listing\DrivingLicense;
use Trexima\EuropeanCvBundle\Listing\EuropeanCvListingInterface;

/**
 * Specialized checkboxes for selecting driving licenses.
 */
class DrivingLicenseType extends AbstractType
{
    /**
     * @var EuropeanCvListingInterface
     */
    private $europeanCvListing;

    public function __construct(EuropeanCvListingInterface $europeanCvListing)
    {
        $this->europeanCvListing = $europeanCvListing;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $drivingLicenses = $this->europeanCvListing->getDrivingLicenseList();
        /**
         * Prefill form with all available driving licenses
         */
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($drivingLicenses, $options) {
            $event->stopPropagation(); // Prevent CollectionType default behaviour

            $form = $event->getForm();
            $data = $event->getData();

            if (null === $data) {
                $data = array();
            }

            if (!is_array($data) && !($data instanceof Collection)) {
                throw new UnexpectedTypeException($data, 'Collection');
            }

            // First remove all rows
            foreach ($form as $name => $child) {
                $form->remove($name);
            }

            // Then add all rows again in the correct order
            foreach ($drivingLicenses as $drivingLicenseCode => $drivingLicense) {
                $actualDrivingLicenseData = null;
                foreach ($data as $drivingLicenseData) {
                    if ($drivingLicenseData->getDrivingLicense() === $drivingLicenseCode) {
                        $actualDrivingLicenseData = $drivingLicenseData;
                        break;
                    }
                }

                $name = 'driving_license_'.$drivingLicenseCode;
                $form->add(
                    $name,
                    $options['entry_type'],
                    array_replace([
                        'label' => $drivingLicense['label'],
                        'property_path' => '['.$name.']',
                        'driving_license' => $drivingLicense,
                        'driving_license_code' => $drivingLicenseCode,
                        'data' => $actualDrivingLicenseData,
                        'help' => $drivingLicense['tooltip'],
                    ], $options['entry_options'])
                );
            }
        }, 1); // We need greater priority than in CollectionType

        /**
         * Delete empty collections. Option 'delete_empty' with callback is allowed from Symfony 3.
         */
        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){
            $data = $event->getData();

            $toDelete = [];
            /**
             * @var string $name
             * @var EuropeanCVDrivingLicense $child
             */
            foreach ($data as $name => $child) {
                /**
                 * Field driving_license_id is primary key. Rows without primary keys aren't allowed.
                 * And we don't want row without checked driving license!
                 */
                if (!$child->getDrivingLicense()) {
                    $toDelete[] = $name;
                }
            }

            foreach ($toDelete as $name) {
                unset($data[$name]);
            }

            $event->setData($data);
        });
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars['driving_licenses'] = $this->europeanCvListing->getDrivingLicenseList();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'allow_add' => false,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_options' => [
                'required' => false,
            ],
        ));
    }

    public function getParent()
    {
        return CollectionType::class;
    }
}