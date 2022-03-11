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
                'placeholder' => 'Vzorová 3, 841 01 Bratislava IV, Slovenská republika'
            )
        ))->add('email', null, array(
            'label' => 'E-mail',
            'attr' => array(
                'placeholder' => 'vzor@vzor.sk'
            )
        ))->add('personalWebsite', null, array(
            'label' => 'Osobná webová stránka',
            'required' => false,
            'default_protocol' => null,
            'attr' => array(
                'placeholder' => 'www.facebook.com/pali.vzor.10'
            )
        ))->add('nationality', null, array(
            'label' => 'Štátna príslušnosť',
            'attr' => array(
                'placeholder' => 'Slovensko'
            )
        ))->add('dateOfBirth', null, array(
            'widget' => 'single_text',
            'label' => 'Dátum narodenia',
            'attr' => array(
                'placeholder' => 'Prosím, vyplňte',
                'data-max-date' => $now->format('Y-m-d')
            )
        ))->add('sex', ChoiceType::class, array(
            'label' => 'Pohlavie',
            'required' => $options['sex_required'],
            'placeholder' => 'Prosím, vyberte možnosť',
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
                'data-parsley-trexima-european-cv-dynamic-collection-min-message' => 'Vyplňte aspoň %s telefónne čislo'
            ],
            'constraints' => [
                new Count([
                    'min' => $options['phones_min'],
                    'minMessage' => 'Vyplňte aspoň {{ limit }} telefónne čislo'
                ])
            ]
        ))->add('jobInterest', null, array(
            'label' => 'Zamestnanie, o ktoré sa uchádzate',
            'attr' => array(
                'placeholder' => 'Prosím, vyplňte'
            )
        ))->add('languageMother', Select2Type::class, array(
            'label' => 'Materinský jazyk',
            'placeholder' => 'Prosím, vyberte možnosť',
            'required' => false,
            'choices'  => array_flip($this->europeanCvListing->getLanguageList()),
            'preferred_choices'  => function ($value, $key) {
                return in_array($value, ['sk']);
            }
        ))->add('skillCommunication', null, array(
            'label' => 'Komunikačné zručnosti',
            'attr' => array(
                'placeholder' => 'Napr. poskytovanie spätnej väzby deťom počas futbalových tréningov.'
            )
        ))->add('skillManagement', null, array(
            'label' => 'Organizačné a riadiace zručnosti',
            'attr' => array(
                'placeholder' => 'Napr. vedenie a motivovanie tímu desiatich ľudí.'
            )
        ))->add('skillJob', null, array(
            'label' => 'Pracovné zručnosti',
            'attr' => array(
                'placeholder' => 'Napr. dobré ovládanie postupov kontroly kvality (zodpovednosť za audit kvality). '
            )
        ))->add('skillOther', null, array(
            'label' => 'Ďalšie zručnosti',
            'attr' => array(
                'placeholder' => 'Napr. certifikát v poskytovaní prvej pomoci, herecké aktivity - dobrovoľnícke divadlo.'
            )
        ))->add('skillDigitalInformationProcessing', Select2Type::class, array(
            'label' => 'Spracovanie informácií',
            'required' => false,
            'placeholder' => 'Prosím, vyberte možnosť',
            'choices_description_callback' => array($this, 'getDescriptionFromSkillDigitalLevelOption'),
            'choices'  => array_flip(EuropeanCV::DIGITAL_SKILL_LEVEL_LIST),
        ))->add('skillDigitalCommunication', Select2Type::class, array(
            'label' => 'Komunikácia',
            'required' => false,
            'placeholder' => 'Prosím, vyberte možnosť',
            'choices_description_callback' => array($this, 'getDescriptionFromSkillDigitalLevelOption'),
            'choices'  => array_flip(EuropeanCV::DIGITAL_SKILL_LEVEL_LIST),
        ))->add('skillDigitalContentCreation', Select2Type::class, array(
            'label' => 'Vytváranie obsahu',
            'required' => false,
            'placeholder' => 'Prosím, vyberte možnosť',
            'choices_description_callback' => array($this, 'getDescriptionFromSkillDigitalLevelOption'),
            'choices'  => array_flip(EuropeanCV::DIGITAL_SKILL_LEVEL_LIST),
        ))->add('skillDigitalSecurity', Select2Type::class, array(
            'label' => 'Bezpečnosť',
            'required' => false,
            'placeholder' => 'Prosím, vyberte možnosť',
            'choices_description_callback' => array($this, 'getDescriptionFromSkillDigitalLevelOption'),
            'choices'  => array_flip(EuropeanCV::DIGITAL_SKILL_LEVEL_LIST),
        ))->add('skillDigitalTroubleshooting', Select2Type::class, array(
            'label' => 'Riešenie problémov',
            'required' => false,
            'placeholder' => 'Prosím, vyberte možnosť',
            'choices_description_callback' => array($this, 'getDescriptionFromSkillDigitalLevelOption'),
            'choices'  => array_flip(EuropeanCV::DIGITAL_SKILL_LEVEL_LIST),
        ))->add('skillDigitalCertificate', null, array(
            'label' => 'Názov certifikátu',
            'attr' => array(
                'placeholder' => 'Balík MS Office - samostatný používateľ'
            )
        ))->add('skillDigitalOther', null, array(
            'label' => 'Ďalšie digitálne zručnosti',
            'attr' => array(
                'placeholder' => 'Napr. ovládanie softvéru na úpravu fotografií.'
            )
        ))->add('drivingLicenseOwner', ChoiceType::class, array(
            'required' => false,
            'label' => 'Vodičský preukaz',
            'placeholder' => false,
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Nemám vodičský preukaz' => false,
                'Mám vodičský preukaz' => true
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
                'data-parsley-trexima-european-cv-dynamic-collection-min-message' => 'Vyplňte aspoň %s doplňujúcu informáciu'
            ],
            'constraints' => [
                new Count([
                    'min' => $options['additional_informations_min'],
                    'minMessage' => 'Vyplňte aspoň {{ limit }} doplňujúcu informáciu'
                ])
            ]
        ))->add('attachmentList', null, array(
            'label' => 'Zoznam príloh životopisu',
            'attr' => array(
                'placeholder' => 'Napr. kópia inžinierskeho diplomu, potvrdenie o zamestnaní.'
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
                'data-parsley-trexima-european-cv-dynamic-collection-min-message' => 'Nahrajte aspoň %s prílohu'
            ],
            'constraints' => [
                new Count([
                    'min' => $options['attachments_min'],
                    'minMessage' => 'Nahrajte aspoň {{ limit }} prílohu'
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
                'data-parsley-trexima-european-cv-dynamic-collection-min-message' => 'Vyplňte aspoň %s prax'
            ],
            'constraints' => [
                new Count([
                    'min' => $options['practices_min'],
                    'minMessage' => 'Vyplňte aspoň {{ limit }} prax'
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
                'data-parsley-trexima-european-cv-dynamic-collection-min-message' => 'Vyplňte aspoň %s vzdelanie'
            ],
            'constraints' => [
                new Count([
                    'min' => $options['educations_min'],
                    'minMessage' => 'Vyplňte aspoň {{ limit }} vzdelanie'
                ])
            ]
        ))->add('invertPositionPracticeEducation', CheckboxType::class, array(
            'label' => 'Položku Vzdelávanie a príprava uviesť pred položkou Prax',
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
                'data-parsley-trexima-european-cv-dynamic-collection-min-message' => 'Vyplňte aspoň %s jazyk'
            ],
            'constraints' => [
                new Count([
                    'min' => $options['languages_min'],
                    'minMessage' => 'Vyplňte aspoň {{ limit }} jazyk'
                ])
            ]
        ))->add('outputLanguage', ChoiceType::class, array(
            'label' => 'Výstup',
            'mapped' => false,
            'required' => false,
            'placeholder' => false,
            'choices'  => array_flip([
                '' => 'Slovensky',
                'en' => 'Anglicky',
                'de' => 'Nemecky'
            ]),
        ))->add('outputEmailSender', EmailType::class, array(
            'label' => 'E-mail odosielateľa',
            'mapped' => false,
            'required' => false,
            'constraints' => array(
                new NotBlank(array('groups' => array('send_by_email')))
            ),
            'attr' => array(
                'placeholder' => 'Prosím, vyplňte',
                'data-parsley-group' => '["send_by_email"]',
                'data-parsley-required' => true
            )
        ))->add('outputEmailReceiver', EmailType::class, array(
            'label' => 'E-mail príjemcu',
            'mapped' => false,
            'required' => false,
            'constraints' => array(
                new NotBlank(array('groups' => array('send_by_email')))
            ),
            'attr' => array(
                'placeholder' => 'Prosím, vyplňte',
                'data-parsley-group' => '["send_by_email"]',
                'data-parsley-required' => true
            )
        ))->add('submitEmail', SubmitIconType::class, array(
            'label' => 'Odoslať',
            'validation_groups' => array('send_by_email'),
            'icon_left' => '<i class="far fa-envelope"></i>',
            'attr' => array(
                'data-parsley-trigger-group' => '["default", "send_by_email"]',
                'class' => 'btn btn-block btn-secondary',
            )
        ))->add('submitPreview', SubmitIconType::class, array(
            'label' => 'Zobraziť náhľad',
            'icon_left' => '<i class="fas fa-search"></i>',
            'attr' => array(
                'class' => 'btn btn-block btn-info',
                'formtarget' => '_blank'
            )
        ))->add('submitDoc', SubmitIconType::class, array(
            'label' => 'Uložiť ako Word',
            'icon_left' => '<i class="far fa-file-word"></i>',
            'attr' => array(
                'class' => 'btn btn-block btn-secondary'
            )
        ))->add('submitPdf', SubmitIconType::class, array(
            'label' => 'Uložiť ako PDF',
            'icon_left' => '<i class="far fa-file-pdf"></i>',
            'attr' => array(
                'class' => 'btn btn-block btn-secondary'
            )
        ));

        if ($options['is_user_logged_in']) {
            $builder->add('submit', SubmitIconType::class, array(
                'label' => 'Uložiť životopis',
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
                    Som schopný(á) vyhľadávať informácie online pomocou vyhľadávača. 
                    Viem, že nie všetky online informácie sú dôveryhodné. Som schopný(á) uložiť alebo
                    uchovať súbory alebo obsah (napr. texty, obrázky, hudbu, videá, web stránky) a uložené súbory opäť nájsť.
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_ADVANCED => '
                    Na vyhľadávanie informácií som schopný(á) používať rôzne vyhľadávače. Používam filtre pri vyhľadávaní (napr. vyhľadávanie v kategóriách obrázkov, videí, máp).
                    Porovnávam rôzne zdroje na posúdenie dôveryhodnosti nájdených informácií. Pre jednoduchšiu lokalizáciu informácie triedim metodicky pomocou súborov a priečinkov.
                    Uložené informácie a súbory zálohujem.
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_EXPERT => '
                    Som schopný(á) používať pokročilé vyhľadávanie (napr. pomocou operátorov vyhľadávania) na nájdenie spoľahlivých informácií na internete. 
                    Som schopný(á) používať webové kanály (ako RSS) na odoberanie aktuálneho obsahu, ktorý ma zaujíma. 
                    Používaním rôznych kritérií som schopný(á) posúdiť platnosť a spoľahlivosť informácií. Sledujem vývoj a pokrok vo vyhľadávaní, ukladaní a obnovovaní informácií. 
                    Som schopný(á) uložiť nájdené informácie v rôznych formátoch. Som schopný(á) používať služby zdieľaných úložísk (cloud).
                '
            ),
            'skillDigitalCommunication' => array(
                EuropeanCV::DIGITAL_SKILL_LEVEL_BASIC => '
                    Som schopný(á) komunikovať s ostatnými pomocou mobilného telefónu, technológie VoIP (napr. Skype), 
                    emailu alebo chatu – používaním základných funkcií (napr. hlasové správy, SMS, odosielanie a prijímanie emailov, výmena textov). 
                    Som schopný(á) zdieľať súbory a obsah pomocou jednoduchých nástrojov. Som si vedomý(á), že pri styku s inštitúciami poskytujúcimi služby (ako úrady, banky, nemocnice) 
                    môžem využiť digitálne technológie. Poznám sociálne siete a online nástroje na spoluprácu. Som si vedomý(á) toho, že pri používaní digitálnych nástrojov platia
                     určité pravidlá komunikácie (napr. pri komentovaní, zdieľaní osobných údajov). 
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_ADVANCED => '
                    Som schopný(á) používať pokročilé funkcie viacerých komunikačných nástrojov (napr. používať VoIP a zdieľanie súborov). 
                    Som schopný(á) používať nástroje na spoluprácu a prispievať napr. k zdieľaným dokumentom/súborom vytvoreným niekým iným. 
                    Som schopný(á) používať niektoré funkcie online služieb (napr. verejné služby, internet banking, online nakupovanie). 
                    Odovzdávam alebo zdieľam svoje vedomosti online (napr. pomocou nástrojov sociálnych sietí alebo v rámci online komunít). 
                    Poznám a dodržiavam pravidlá online komunikácie ("netiquette").
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_EXPERT => '
                    Aktívne používam širokú škálu komunikačných nástrojov (email, chat, SMS, instant messaging, blogy, micro-blogy, sociálne siete) 
                    na online komunikáciu. Som schopný(á) vytvárať a spravovať obsah pomocou nástrojov na spoluprácu (napr. elektronické kalendáre, 
                    systémy na správu projektov, online preverovanie, online tabuľkové procesory). Aktívne vystupujem v online priestoroch a používam
                    niekoľko online služieb (napr. verejné služby, internet banking, online nakupovanie). Používam pokročilé funkcie komunikačných
                    nástrojov (napr. videokonferencie, zdieľanie dát, zdieľanie aplikácií).
                '
            ),
            'skillDigitalContentCreation' => array(
                EuropeanCV::DIGITAL_SKILL_LEVEL_BASIC => '
                   Som schopný(á) vytvoriť jednoduchý digitálny obsah (napr. texty, tabuľky, obrázky, zvukové súbory) najmenej v jednom z formátov, 
                   použitím digitálnych nástrojov. Som schopný(á) urobiť základné úpravy obsahov vytvorených ostatnými.
                   Som si vedomý(á), že obsah môže byť chránený autorským právom. Som schopný(á) aplikovať a upravovať jednoduché funkcie a nastavenia softvéru a aplikácií, 
                   ktoré používam (napr. zmena predvolených nastavení). 
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_ADVANCED => '
                    Som schopný(á) vytvárať zložitý digitálny obsah v rôznych formátoch (napr. texty, tabuľky, obrázky, zvukové súbory).
                    Som schopný(á) používať nástroje/editory na vytváranie webovej stránky alebo blogu pomocou šablón (napr. WordPress). 
                    Som schopný(á) používať základné formátovanie (napr. vložiť poznámky pod čiarou, grafy, tabuľky) obsahov vytvorených mnou alebo ostatnými. 
                    Viem, ako uviesť zdroj a znovu použiť obsah chránený autorským právom. Poznám základy jedného programovacieho jazyka. 
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_EXPERT => '
                    Som schopný(á) vytvárať alebo upraviť zložitý, multimediálny obsah v rôznych formátoch, použitím rôznych digitálnych platforiem, nástrojov a prostredí. 
                    Som schopný(á) vytvoriť webovú stránku prostredníctvom programovacieho jazyka. Som schopný(á) používať pokročilé funkcie
                    formátovania rôznych nástrojov (napr. zlučovanie emailov, zlučovanie dokumentov odlišného formátu, použitie pokročilých vzorcov, makier). 
                    Som schopný(á) uplatňovať licencie a autorské práva. Poznám niekoľko programovacích jazykov. Som schopný(á) navrhovať, vytvárať a upravovať databázy počítačovým nástrojom.
                '
            ),
            'skillDigitalSecurity' => array(
                EuropeanCV::DIGITAL_SKILL_LEVEL_BASIC => '
                    Som schopný(á) robiť základné kroky k ochrane mojich zariadení (napr. používať antivírusový softvér a heslá). 
                    Som si vedomý(á), že nie všetky online informácie sú dôveryhodné. Som si vedomý(á) toho, že moje poverenia (používateľské meno a heslo) môžu byť odcudzené. 
                    Viem, že by som nemal(a) uvádzať súkromné informácie online. Viem, že nadmerné používanie digitálnych technológií môže ovplyvniť moje zdravie. 
                    Robím základné opatrenia na šetrenie energie. 
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_ADVANCED => '
                    Nainštaloval(a) som bezpečnostné programy na zariadenie(a), ktoré používam na prístup k internetu (napr. antivírus, firewall).
                    Pravidelne spúšťam a aktualizujem tieto programy. Používam rôzne heslá na prístup k zariadeniam, prístrojom a digitálnym službám 
                    a mením ich v pravidelných intervaloch. Rozpoznám webové stránky alebo emailové správy, ktoré mohli byť vytvorené na účel podvodu.
                    Rozpoznám podvodný email (phishing). Som schopný(á) utvárať svoju online digitálnu identitu a sledovať vlastnú digitálnu stopu.
                    Rozumiem zdravotným rizikám spojeným s používaním digitálnych technológií (napr. ergonómia, riziko závislosti). 
                    Rozumiem pozitívnym a negatívnym dôsledkom technológie na životné prostredie.
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_EXPERT => '
                    Často kontrolujem bezpečnostné nastavenia a systémy mojich zariadení a/alebo použitých aplikácií. 
                    Viem, ako reagovať v prípade, že môj počítač je napadnutý vírusom. Som schopný(á) nastaviť alebo upraviť nastavenia 
                    firewallu a zabezpečenia svojich digitálnych zariadení. Viem, ako šifrovať emaily alebo súbory. Som schopný(á) 
                    aplikovať filtre na nevyžiadané emaily (spam). Aby sa predišlo zdravotným problémom (fyzickým i psychickým), 
                    informačné a komunikačné technológie využívam s mierou. Som informovaný(á) 
                    o vplyve digitálnych technológií na každodenný život, online spotrebu a životné prostredie.
                '
            ),
            'skillDigitalTroubleshooting' => array(
                EuropeanCV::DIGITAL_SKILL_LEVEL_BASIC => '
                    Dokážem nájsť podporu a pomoc v prípade technického problému alebo pri použití nového zariadenia, 
                    programu alebo aplikácie. Som schopný(á) riešiť bežné problémy (napr. zavrieť program, reštartovať počítač, 
                    preinštalovať/aktualizovať program, skontrolovať pripojenie k internetu). Viem, že digitálne nástroje mi môžu pomôcť pri riešení problémov.
                    Som si tiež vedomý(á) toho, že majú svoje obmedzenia. V prípade technologických alebo netechnologických problémov dokážem na
                    ich riešenie používať digitálne nástroje, ktoré poznám. Som si vedomý(á), že musím neustále rozvíjať svoje digitálne zručnosti.
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_ADVANCED => '
                    Som schopný(á) vyriešiť väčšinu častejších problémov, ktoré vznikajú pri používaní digitálnych technológií. Som schopný(á) 
                    používať digitálne technológie na riešenie (netechnických) problémov. Dokážem zvoliť vhodný digitálny nástroj a posúdiť jeho 
                    efektívnosť. Som schopný(á) vyriešiť technologické problémy skúmaním nastavení a možností programov alebo nástrojov. 
                    Pravidelne aktualizujem svoje digitálne zručnosti. Poznám svoje hranice a snažím sa doplniť svoje vedomosti v oblastiach, kde mám medzery.
                ',
                EuropeanCV::DIGITAL_SKILL_LEVEL_EXPERT => '
                    Som schopný(á) vyriešiť takmer všetky problémy, ktoré vznikajú pri používaní digitálnych technológií. Som schopný(á) 
                    si vybrať správny nástroj, zariadenie, aplikáciu, softvér alebo službu na riešenie (netechnických) problémov.
                    Poznám vývoj nových technológií. Rozumiem fungovaniu nových nástrojov. Často aktualizujem svoje digitálne zručnosti.
                '
            ),
        );

        return isset($descriptions[$parentName]) ? $descriptions[$parentName] : null;
    }
}