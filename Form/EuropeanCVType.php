<?php

namespace Trexima\EuropeanCvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints\NotBlank;
use Trexima\EuropeanCvBundle\Entity\EuropeanCV;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Form\Type\DrivingLicenseType;
use Trexima\EuropeanCvBundle\Form\Type\JQueryFileUploadType;
use Trexima\EuropeanCvBundle\Form\Type\Select2Type;
use Trexima\EuropeanCvBundle\Form\Type\SubmitIconType;
use Trexima\EuropeanCvBundle\Listing\EuropeanCvListingInterface;
use Symfony\Component\Validator\Constraints\Count;

class EuropeanCVType extends AbstractType
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
        /** @var EuropeanCV|null $europeanCv */
        $europeanCv = $builder->getData();
        $now = new \DateTime();
        $builder->add('photo', JQueryFileUploadType::class, array(
            'required' => false,
            'label' => 'Fotografia',
            'upload_route' => $options['photo_upload_route']
        ))->add('name', null, array(
            'label' => 'Titul, meno a priezvisko',
            'attr' => array(
                'placeholder' => 'Ing. Pavol Vzor'
            )
        ))->add('address', null, array(
            'label' => 'Adresa',
            'attr' => array(
                'placeholder' => 'Vzorov?? 3, 841 01 Bratislava IV, Slovensk?? republika'
            )
        ))->add('email', null, array(
            'label' => 'E-mail',
            'attr' => array(
                'placeholder' => 'vzor@vzor.sk'
            )
        ))->add('personalWebsite', null, array(
            'label' => 'Osobn?? webov?? str??nka',
            'required' => false,
            'default_protocol' => null,
            'attr' => array(
                'placeholder' => 'www.facebook.com/pali.vzor.10'
            )
        ))->add('nationality', null, array(
            'label' => '??t??tna pr??slu??nos??',
            'attr' => array(
                'placeholder' => 'Slovensko'
            )
        ))->add('dateOfBirth', null, array(
            'widget' => 'single_text',
            'label' => 'D??tum narodenia',
            'attr' => array(
                'placeholder' => 'Pros??m, vypl??te',
                'data-max-date' => $now->format('Y-m-d')
            )
        ))->add('sex', ChoiceType::class, array(
            'label' => 'Pohlavie',
            'required' => $options['sex_required'],
            'placeholder' => 'Pros??m, vyberte mo??nos??',
            'choices'  => array_flip(EuropeanCV::SEX_LIST),
        ))->add('phones', CollectionType::class, array(
            'entry_type' => EuropeanCVPhoneType::class,
            'entry_options' => array(
                'label' => false
            ),
            'by_reference' => false,
            'label' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'attr' => [
                'data-parsley-trexima-european-cv-dynamic-collection-min' => $options['phones_min'],
                'data-parsley-trexima-european-cv-dynamic-collection-min-message' => 'Vypl??te aspo?? %s telef??nne ??islo'
            ],
            'constraints' => [
                new Count([
                    'min' => $options['phones_min'],
                    'minMessage' => 'Vypl??te aspo?? {{ limit }} telef??nne ??islo'
                ])
            ]
        ))->add('jobInterest', null, array(
            'label' => 'Zamestnanie, o ktor?? sa uch??dzate',
            'attr' => array(
                'placeholder' => 'Pros??m, vypl??te'
            )
        ))->add('languageMother', Select2Type::class, array(
            'label' => 'Materinsk?? jazyk',
            'placeholder' => 'Pros??m, vyberte mo??nos??',
            'required' => false,
            'choices'  => array_flip($this->europeanCvListing->getLanguageList()),
            'preferred_choices'  => function ($value, $key) {
                return in_array($value, ['sk']);
            }
        ))->add('skillCommunication', null, array(
            'label' => 'Komunika??n?? zru??nosti',
            'attr' => array(
                'placeholder' => 'Napr. poskytovanie sp??tnej v??zby de??om po??as futbalov??ch tr??ningov.'
            )
        ))->add('skillManagement', null, array(
            'label' => 'Organiza??n?? a riadiace zru??nosti',
            'attr' => array(
                'placeholder' => 'Napr. vedenie a motivovanie t??mu desiatich ??ud??.'
            )
        ))->add('skillJob', null, array(
            'label' => 'Pracovn?? zru??nosti',
            'attr' => array(
                'placeholder' => 'Napr. dobr?? ovl??danie postupov kontroly kvality (zodpovednos?? za audit kvality). '
            )
        ))->add('skillOther', null, array(
            'label' => '??al??ie zru??nosti',
            'attr' => array(
                'placeholder' => 'Napr. certifik??t v poskytovan?? prvej pomoci, hereck?? aktivity - dobrovo??n??cke divadlo.'
            )
        ))->add('skillDigitalInformationProcessing', Select2Type::class, array(
            'label' => 'Spracovanie inform??ci??',
            'required' => false,
            'placeholder' => 'Pros??m, vyberte mo??nos??',
            'choices_description_callback' => array($this, 'getDescriptionFromSkillDigitalLevelOption'),
            'choices'  => array_flip(EuropeanCV::DIGITAL_SKILL_LEVEL_LIST),
        ))->add('skillDigitalCommunication', Select2Type::class, array(
            'label' => 'Komunik??cia',
            'required' => false,
            'placeholder' => 'Pros??m, vyberte mo??nos??',
            'choices_description_callback' => array($this, 'getDescriptionFromSkillDigitalLevelOption'),
            'choices'  => array_flip(EuropeanCV::DIGITAL_SKILL_LEVEL_LIST),
        ))->add('skillDigitalContentCreation', Select2Type::class, array(
            'label' => 'Vytv??ranie obsahu',
            'required' => false,
            'placeholder' => 'Pros??m, vyberte mo??nos??',
            'choices_description_callback' => array($this, 'getDescriptionFromSkillDigitalLevelOption'),
            'choices'  => array_flip(EuropeanCV::DIGITAL_SKILL_LEVEL_LIST),
        ))->add('skillDigitalSecurity', Select2Type::class, array(
            'label' => 'Bezpe??nos??',
            'required' => false,
            'placeholder' => 'Pros??m, vyberte mo??nos??',
            'choices_description_callback' => array($this, 'getDescriptionFromSkillDigitalLevelOption'),
            'choices'  => array_flip(EuropeanCV::DIGITAL_SKILL_LEVEL_LIST),
        ))->add('skillDigitalTroubleshooting', Select2Type::class, array(
            'label' => 'Rie??enie probl??mov',
            'required' => false,
            'placeholder' => 'Pros??m, vyberte mo??nos??',
            'choices_description_callback' => array($this, 'getDescriptionFromSkillDigitalLevelOption'),
            'choices'  => array_flip(EuropeanCV::DIGITAL_SKILL_LEVEL_LIST),
        ))->add('skillDigitalCertificate', null, array(
            'label' => 'N??zov certifik??tu',
            'attr' => array(
                'placeholder' => 'Bal??k MS Office - samostatn?? pou????vate??'
            )
        ))->add('skillDigitalOther', null, array(
            'label' => '??al??ie digit??lne zru??nosti',
            'attr' => array(
                'placeholder' => 'Napr. ovl??danie softv??ru na ??pravu fotografi??.'
            )
        ))->add('drivingLicenseOwner', ChoiceType::class, array(
            'required' => false,
            'label' => 'Vodi??sk?? preukaz',
            'placeholder' => false,
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Nem??m vodi??sk?? preukaz' => false,
                'M??m vodi??sk?? preukaz' => true
            ),
            'choice_attr' => function ($choiceValue, $key, $value) {
                return array('data-trexima-european-cv-group-trigger' => 'europeancv-driving-license');
            }
        ))->add('drivingLicenses', DrivingLicenseType::class, array(
            'entry_type' => EuropeanCVDrivingLicenseType::class,
            'label' => false,
            'by_reference' => false,
            'entry_options' => array(
                'label' => false
            ),
            'required' => false
        ))->add('additionalInformations', CollectionType::class, array(
            'entry_type' => EuropeanCVAdditionalInformationType::class,
            'entry_options' => array(
                'label' => false
            ),
            'by_reference' => false,
            'label' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'attr' => [
                'data-parsley-trexima-european-cv-dynamic-collection-min' => $options['additional_informations_min'],
                'data-parsley-trexima-european-cv-dynamic-collection-min-message' => 'Vypl??te aspo?? %s dopl??uj??cu inform??ciu'
            ],
            'constraints' => [
                new Count([
                    'min' => $options['additional_informations_min'],
                    'minMessage' => 'Vypl??te aspo?? {{ limit }} dopl??uj??cu inform??ciu'
                ])
            ]
        ))->add('attachmentList', null, array(
            'label' => 'Zoznam pr??loh ??ivotopisu',
            'attr' => array(
                'placeholder' => 'Napr. k??pia in??inierskeho diplomu, potvrdenie o zamestnan??.'
            )
        ))->add('attachments', CollectionType::class, array(
            'entry_type' => EuropeanCVAttachmentType::class,
            'entry_options' => array(
                'label' => false
            ),
            'by_reference' => false,
            'label' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'attr' => [
                'data-parsley-trexima-european-cv-dynamic-collection-min' => $options['attachments_min'],
                'data-parsley-trexima-european-cv-dynamic-collection-min-message' => 'Nahrajte aspo?? %s pr??lohu'
            ],
            'constraints' => [
                new Count([
                    'min' => $options['attachments_min'],
                    'minMessage' => 'Nahrajte aspo?? {{ limit }} pr??lohu'
                ])
            ]
        ))->add('practices', CollectionType::class, array(
            'entry_type' => EuropeanCVPracticeType::class,
            'entry_options' => array(
                'label' => false
            ),
            'by_reference' => false,
            'label' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'attr' => [
                'data-parsley-trexima-european-cv-dynamic-collection-min' => $options['practices_min'],
                'data-parsley-trexima-european-cv-dynamic-collection-min-message' => 'Vypl??te aspo?? %s prax'
            ],
            'constraints' => [
                new Count([
                    'min' => $options['practices_min'],
                    'minMessage' => 'Vypl??te aspo?? {{ limit }} prax'
                ])
            ]
        ))->add('educations', CollectionType::class, array(
            'entry_type' => EuropeanCVEducationType::class,
            'entry_options' => array(
                'label' => false
            ),
            'by_reference' => false,
            'label' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'attr' => [
                'data-parsley-trexima-european-cv-dynamic-collection-min' => $options['educations_min'],
                'data-parsley-trexima-european-cv-dynamic-collection-min-message' => 'Vypl??te aspo?? %s vzdelanie'
            ],
            'constraints' => [
                new Count([
                    'min' => $options['educations_min'],
                    'minMessage' => 'Vypl??te aspo?? {{ limit }} vzdelanie'
                ])
            ]
        ))->add('invertPositionPracticeEducation', CheckboxType::class, array(
            'label' => 'Polo??ku Vzdel??vanie a pr??prava uvies?? pred polo??kou Prax',
            'required' => false
        ))->add('languages', CollectionType::class, array(
            'entry_type' => EuropeanCVLanguageType::class,
            'entry_options' => array(
                'label' => false
            ),
            'by_reference' => false,
            'label' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'attr' => [
                'data-parsley-trexima-european-cv-dynamic-collection-min' => $options['languages_min'],
                'data-parsley-trexima-european-cv-dynamic-collection-min-message' => 'Vypl??te aspo?? %s jazyk'
            ],
            'constraints' => [
                new Count([
                    'min' => $options['languages_min'],
                    'minMessage' => 'Vypl??te aspo?? {{ limit }} jazyk'
                ])
            ]
        ))->add('outputLanguage', ChoiceType::class, array(
            'label' => 'V??stup',
            'mapped' => false,
            'required' => false,
            'placeholder' => false,
            'choices'  => array_flip([
                '' => 'Slovensky',
                'en' => 'Anglicky',
                'de' => 'Nemecky'
            ]),
        ))->add('outputEmailSender', EmailType::class, array(
            'label' => 'E-mail odosielate??a',
            'mapped' => false,
            'required' => false,
            'constraints' => array(
                new NotBlank(array('groups' => array('send_by_email')))
            ),
            'attr' => array(
                'placeholder' => 'Pros??m, vypl??te',
                'data-parsley-group' => '["send_by_email"]',
                'data-parsley-required' => true
            )
        ))->add('outputEmailReceiver', EmailType::class, array(
            'label' => 'E-mail pr??jemcu',
            'mapped' => false,
            'required' => false,
            'constraints' => array(
                new NotBlank(array('groups' => array('send_by_email')))
            ),
            'attr' => array(
                'placeholder' => 'Pros??m, vypl??te',
                'data-parsley-group' => '["send_by_email"]',
                'data-parsley-required' => true
            )
        ))->add('submitEmail', SubmitIconType::class, array(
            'label' => 'Odosla??',
            'validation_groups' => array('send_by_email'),
            'icon_left' => '<i class="far fa-envelope"></i>',
            'attr' => array(
                'data-parsley-trigger-group' => '["default", "send_by_email"]',
                'class' => 'btn btn-block btn-secondary',
            )
        ))->add('submitPreview', SubmitIconType::class, array(
            'label' => 'Zobrazi?? n??h??ad',
            'icon_left' => '<i class="fas fa-search"></i>',
            'attr' => array(
                'class' => 'btn btn-block btn-info',
                'formtarget' => '_blank'
            )
        ))->add('submitDoc', SubmitIconType::class, array(
            'label' => 'Ulo??i?? ako Word',
            'icon_left' => '<i class="far fa-file-word"></i>',
            'attr' => array(
                'class' => 'btn btn-block btn-secondary'
            )
        ))->add('submitPdf', SubmitIconType::class, array(
            'label' => 'Ulo??i?? ako PDF',
            'icon_left' => '<i class="far fa-file-pdf"></i>',
            'attr' => array(
                'class' => 'btn btn-block btn-secondary'
            )
        ));

        if ($options['is_user_logged_in']) {
            $builder->add('submit', SubmitIconType::class, array(
                'label' => 'Ulo??i?? ??ivotopis',
                'icon_left' => '<i class="far fa-save"></i>',
                'attr' => array(
                    'class' => 'btn btn-block btn-primary'
                )
            ));
        }

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $submittedData = $event->getData();

            // Default scheme
            if ($submittedData['personalWebsite']) {
                $personalWebsiteScheme = parse_url($submittedData['personalWebsite'], PHP_URL_SCHEME);
                if (!in_array($personalWebsiteScheme, ['http', 'https'])) {
                    $submittedData['personalWebsite'] = 'http://'.$submittedData['personalWebsite'];
                }
            }

            $collectionsWithPosition = [
                'practices',
                'educations',
                'languages',
                'phones',
                'additionalInformations',
                'attachments'
            ];

            foreach ($collectionsWithPosition as $collectionName) {
                if (!array_key_exists($collectionName, $submittedData)) {
                    continue;
                }

                $i = 0;
                // Set position to ensure the entities stay in the submitted order
                foreach ($submittedData[$collectionName] as &$item) {
                    // Every sortable collection must contains position in entries
                    $item['position'] = $i;
                    $i++;
                }
            }

            $event->setData($submittedData);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => EuropeanCV::class,
            'is_user_logged_in' => false,
            'translation_domain' => 'trexima_european_cv',
            'sex_required' => false,
            'phones_min' => 0,
            'practices_min' => 0,
            'educations_min' => 0,
            'languages_min' => 0,
            'additional_informations_min' => 0,
            'attachments_min' => 0
         ]);

        $resolver->setRequired([
            'photo_upload_route'
        ]);
    }

    /**
     * @param string $parentName
     * @return string|null
     */
    public function getDescriptionFromSkillDigitalLevelOption($parentName)
    {
        $descriptions = array(
            'skillDigitalInformationProcessing' => array(
                EuropeanCV::DIGITAL_SKILL_LEVEL_BASIC => '
                    Som schopn??(??) vyh??ad??va?? inform??cie online pomocou vyh??ad??va??a. 
                    Viem, ??e nie v??etky online inform??cie s?? d??veryhodn??. Som schopn??(??) ulo??i?? alebo
                    uchova?? s??bory alebo obsah (napr. texty, obr??zky, hudbu, vide??, web str??nky) a ulo??en?? s??bory op???? n??js??.
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_ADVANCED => '
                    Na vyh??ad??vanie inform??ci?? som schopn??(??) pou????va?? r??zne vyh??ad??va??e. Pou????vam filtre pri vyh??ad??van?? (napr. vyh??ad??vanie v kateg??ri??ch obr??zkov, vide??, m??p).
                    Porovn??vam r??zne zdroje na pos??denie d??veryhodnosti n??jden??ch inform??ci??. Pre jednoduch??iu lokaliz??ciu inform??cie triedim metodicky pomocou s??borov a prie??inkov.
                    Ulo??en?? inform??cie a s??bory z??lohujem.
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_EXPERT => '
                    Som schopn??(??) pou????va?? pokro??il?? vyh??ad??vanie (napr. pomocou oper??torov vyh??ad??vania) na n??jdenie spo??ahliv??ch inform??ci?? na internete. 
                    Som schopn??(??) pou????va?? webov?? kan??ly (ako RSS) na odoberanie aktu??lneho obsahu, ktor?? ma zauj??ma. 
                    Pou????van??m r??znych krit??ri?? som schopn??(??) pos??di?? platnos?? a spo??ahlivos?? inform??ci??. Sledujem v??voj a pokrok vo vyh??ad??van??, ukladan?? a obnovovan?? inform??ci??. 
                    Som schopn??(??) ulo??i?? n??jden?? inform??cie v r??znych form??toch. Som schopn??(??) pou????va?? slu??by zdie??an??ch ??lo????sk (cloud).
                '
            ),
            'skillDigitalCommunication' => array(
                EuropeanCV::DIGITAL_SKILL_LEVEL_BASIC => '
                    Som schopn??(??) komunikova?? s ostatn??mi pomocou mobiln??ho telef??nu, technol??gie VoIP (napr. Skype), 
                    emailu alebo chatu ??? pou????van??m z??kladn??ch funkci?? (napr. hlasov?? spr??vy, SMS, odosielanie a prij??manie emailov, v??mena textov). 
                    Som schopn??(??) zdie??a?? s??bory a obsah pomocou jednoduch??ch n??strojov. Som si vedom??(??), ??e pri styku s in??tit??ciami poskytuj??cimi slu??by (ako ??rady, banky, nemocnice) 
                    m????em vyu??i?? digit??lne technol??gie. Pozn??m soci??lne siete a online n??stroje na spolupr??cu. Som si vedom??(??) toho, ??e pri pou????van?? digit??lnych n??strojov platia
                     ur??it?? pravidl?? komunik??cie (napr. pri komentovan??, zdie??an?? osobn??ch ??dajov).??
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_ADVANCED => '
                    Som schopn??(??) pou????va?? pokro??il?? funkcie viacer??ch komunika??n??ch n??strojov (napr. pou????va?? VoIP a zdie??anie s??borov). 
                    Som schopn??(??) pou????va?? n??stroje na spolupr??cu a prispieva?? napr. k zdie??an??m dokumentom/s??borom vytvoren??m niek??m in??m. 
                    Som schopn??(??) pou????va?? niektor?? funkcie online slu??ieb (napr. verejn?? slu??by, internet banking, online nakupovanie). 
                    Odovzd??vam alebo zdie??am svoje vedomosti online (napr. pomocou n??strojov soci??lnych siet?? alebo v r??mci online komun??t). 
                    Pozn??m a dodr??iavam pravidl?? online komunik??cie ("netiquette").
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_EXPERT => '
                    Akt??vne pou????vam ??irok?? ??k??lu komunika??n??ch n??strojov (email, chat, SMS, instant messaging, blogy, micro-blogy, soci??lne siete) 
                    na online komunik??ciu. Som schopn??(??) vytv??ra?? a spravova?? obsah pomocou n??strojov na spolupr??cu (napr. elektronick?? kalend??re, 
                    syst??my na spr??vu projektov, online preverovanie, online tabu??kov?? procesory). Akt??vne vystupujem v online priestoroch a pou????vam
                    nieko??ko online slu??ieb (napr. verejn?? slu??by, internet banking, online nakupovanie). Pou????vam pokro??il?? funkcie komunika??n??ch
                    n??strojov (napr. videokonferencie, zdie??anie d??t, zdie??anie aplik??ci??).
                '
            ),
            'skillDigitalContentCreation' => array(
                EuropeanCV::DIGITAL_SKILL_LEVEL_BASIC => '
                   Som schopn??(??) vytvori?? jednoduch?? digit??lny obsah (napr. texty, tabu??ky, obr??zky, zvukov?? s??bory) najmenej v jednom z form??tov, 
                   pou??it??m digit??lnych n??strojov. Som schopn??(??) urobi?? z??kladn?? ??pravy obsahov vytvoren??ch ostatn??mi.
                   Som si vedom??(??), ??e obsah m????e by?? chr??nen?? autorsk??m pr??vom. Som schopn??(??) aplikova?? a upravova?? jednoduch?? funkcie a nastavenia softv??ru a aplik??ci??, 
                   ktor?? pou????vam (napr. zmena predvolen??ch nastaven??).??
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_ADVANCED => '
                    Som schopn??(??) vytv??ra?? zlo??it?? digit??lny obsah v r??znych form??toch (napr. texty, tabu??ky, obr??zky, zvukov?? s??bory).
                    Som schopn??(??) pou????va?? n??stroje/editory na vytv??ranie webovej str??nky alebo blogu pomocou ??abl??n (napr. WordPress). 
                    Som schopn??(??) pou????va?? z??kladn?? form??tovanie (napr. vlo??i?? pozn??mky pod ??iarou, grafy, tabu??ky) obsahov vytvoren??ch mnou alebo ostatn??mi. 
                    Viem, ako uvies?? zdroj a znovu pou??i?? obsah chr??nen?? autorsk??m pr??vom. Pozn??m z??klady jedn??ho programovacieho jazyka.??
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_EXPERT => '
                    Som schopn??(??) vytv??ra?? alebo upravi?? zlo??it??, multimedi??lny obsah v r??znych form??toch, pou??it??m r??znych digit??lnych platforiem, n??strojov a prostred??. 
                    Som schopn??(??) vytvori?? webov?? str??nku prostredn??ctvom programovacieho jazyka. Som schopn??(??) pou????va?? pokro??il?? funkcie
                    form??tovania r??znych n??strojov (napr. zlu??ovanie emailov, zlu??ovanie dokumentov odli??n??ho form??tu, pou??itie pokro??il??ch vzorcov, makier). 
                    Som schopn??(??) uplat??ova?? licencie a autorsk?? pr??va. Pozn??m nieko??ko programovac??ch jazykov. Som schopn??(??) navrhova??, vytv??ra?? a upravova?? datab??zy po????ta??ov??m n??strojom.
                '
            ),
            'skillDigitalSecurity' => array(
                EuropeanCV::DIGITAL_SKILL_LEVEL_BASIC => '
                    Som schopn??(??) robi?? z??kladn?? kroky k ochrane mojich zariaden?? (napr. pou????va?? antiv??rusov?? softv??r a hesl??). 
                    Som si vedom??(??), ??e nie v??etky online inform??cie s?? d??veryhodn??. Som si vedom??(??) toho, ??e moje poverenia (pou????vate??sk?? meno a heslo) m????u by?? odcudzen??. 
                    Viem, ??e by som nemal(a) uv??dza?? s??kromn?? inform??cie online. Viem, ??e nadmern?? pou????vanie digit??lnych technol??gi?? m????e ovplyvni?? moje zdravie. 
                    Rob??m z??kladn?? opatrenia na ??etrenie energie.??
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_ADVANCED => '
                    Nain??taloval(a) som bezpe??nostn?? programy na zariadenie(a), ktor?? pou????vam na pr??stup k internetu (napr. antiv??rus, firewall).
                    Pravidelne sp??????am a aktualizujem tieto programy. Pou????vam r??zne hesl?? na pr??stup k zariadeniam, pr??strojom a digit??lnym slu??b??m 
                    a men??m ich v pravideln??ch intervaloch. Rozpozn??m webov?? str??nky alebo emailov?? spr??vy, ktor?? mohli by?? vytvoren?? na ????el podvodu.
                    Rozpozn??m podvodn?? email (phishing). Som schopn??(??) utv??ra?? svoju online digit??lnu identitu a sledova?? vlastn?? digit??lnu stopu.
                    Rozumiem zdravotn??m rizik??m spojen??m s pou????van??m digit??lnych technol??gi?? (napr. ergon??mia, riziko z??vislosti). 
                    Rozumiem pozit??vnym a negat??vnym d??sledkom technol??gie na ??ivotn?? prostredie.
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_EXPERT => '
                    ??asto kontrolujem bezpe??nostn?? nastavenia a syst??my mojich zariaden?? a/alebo pou??it??ch aplik??ci??. 
                    Viem, ako reagova?? v pr??pade, ??e m??j po????ta?? je napadnut?? v??rusom. Som schopn??(??) nastavi?? alebo upravi?? nastavenia 
                    firewallu a zabezpe??enia svojich digit??lnych zariaden??. Viem, ako ??ifrova?? emaily alebo s??bory. Som schopn??(??) 
                    aplikova?? filtre na nevy??iadan?? emaily (spam). Aby sa predi??lo zdravotn??m probl??mom (fyzick??m i psychick??m), 
                    informa??n?? a komunika??n?? technol??gie vyu????vam s mierou. Som informovan??(??) 
                    o vplyve digit??lnych technol??gi?? na ka??dodenn?? ??ivot, online spotrebu a ??ivotn?? prostredie.
                '
            ),
            'skillDigitalTroubleshooting' => array(
                EuropeanCV::DIGITAL_SKILL_LEVEL_BASIC => '
                    Dok????em n??js?? podporu a pomoc v pr??pade technick??ho probl??mu alebo pri pou??it?? nov??ho zariadenia, 
                    programu alebo aplik??cie. Som schopn??(??) rie??i?? be??n?? probl??my (napr. zavrie?? program, re??tartova?? po????ta??, 
                    prein??talova??/aktualizova?? program, skontrolova?? pripojenie k internetu). Viem, ??e digit??lne n??stroje mi m????u pom??c?? pri rie??en?? probl??mov.
                    Som si tie?? vedom??(??) toho, ??e maj?? svoje obmedzenia. V pr??pade technologick??ch alebo netechnologick??ch probl??mov dok????em na
                    ich rie??enie pou????va?? digit??lne n??stroje, ktor?? pozn??m. Som si vedom??(??), ??e mus??m neust??le rozv??ja?? svoje digit??lne zru??nosti.
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_ADVANCED => '
                    Som schopn??(??) vyrie??i?? v??????inu ??astej????ch probl??mov, ktor?? vznikaj?? pri pou????van?? digit??lnych technol??gi??. Som schopn??(??) 
                    pou????va?? digit??lne technol??gie na rie??enie (netechnick??ch) probl??mov. Dok????em zvoli?? vhodn?? digit??lny n??stroj a pos??di?? jeho 
                    efekt??vnos??. Som schopn??(??) vyrie??i?? technologick?? probl??my sk??man??m nastaven?? a mo??nost?? programov alebo n??strojov. 
                    Pravidelne aktualizujem svoje digit??lne zru??nosti. Pozn??m svoje hranice a sna????m sa doplni?? svoje vedomosti v oblastiach, kde m??m medzery.
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_EXPERT => '
                    Som schopn??(??) vyrie??i?? takmer v??etky probl??my, ktor?? vznikaj?? pri pou????van?? digit??lnych technol??gi??. Som schopn??(??) 
                    si vybra?? spr??vny n??stroj, zariadenie, aplik??ciu, softv??r alebo slu??bu na rie??enie (netechnick??ch) probl??mov.
                    Pozn??m v??voj nov??ch technol??gi??. Rozumiem fungovaniu nov??ch n??strojov. ??asto aktualizujem svoje digit??lne zru??nosti.
                '
            ),
        );

        return isset($descriptions[$parentName]) ? $descriptions[$parentName] : null;
    }
}