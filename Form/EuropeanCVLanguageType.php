<?php

namespace Trexima\EuropeanCvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Trexima\EuropeanCvBundle\Entity\EuropeanCVLanguage;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trexima\EuropeanCvBundle\Form\Type\Select2Type;
use Trexima\EuropeanCvBundle\Listing\EuropeanCvListingInterface;

class EuropeanCVLanguageType extends AbstractType
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
        $now = new \DateTime();
        $builder->add('position', HiddenType::class)
            ->add('language', Select2Type::class, array(
                'label' => 'Cudzí jazyk',
                'placeholder' => 'Prosím, vyberte možnosť',
                'required' => false,
                'choices'  => array_flip($this->europeanCvListing->getLanguageList()),
                'preferred_choices'  => function ($value, $key) {
                    return in_array($value, ['en', 'fr', 'de', 'ru', 'es', 'it']);
                }
            ))
            ->add('listeningLevel', Select2Type::class, array(
                'label' => 'Počúvanie',
                'placeholder' => 'Prosím, vyberte možnosť',
                'required' => false,
                'choices_description_callback' => array($this, 'getDescriptionFromLanguageLevelOption'),
                'choices'  => array_flip(EuropeanCVLanguage::LANGUAGE_LEVEL_LIST)
            ))
            ->add('readingLevel', Select2Type::class, array(
                'label' => 'Čítanie',
                'placeholder' => 'Prosím, vyberte možnosť',
                'required' => false,
                'choices_description_callback' => array($this, 'getDescriptionFromLanguageLevelOption'),
                'choices'  => array_flip(EuropeanCVLanguage::LANGUAGE_LEVEL_LIST)
            ))
            ->add('talkingLevel', Select2Type::class, array(
                'label' => 'Ústna interakcia',
                'placeholder' => 'Prosím, vyberte možnosť',
                'required' => false,
                'choices_description_callback' => array($this, 'getDescriptionFromLanguageLevelOption'),
                'choices'  => array_flip(EuropeanCVLanguage::LANGUAGE_LEVEL_LIST)
            ))
            ->add('oralSpeechLevel', Select2Type::class, array(
                'label' => 'Samostatný ústny prejav',
                'placeholder' => 'Prosím, vyberte možnosť',
                'required' => false,
                'choices_description_callback' => array($this, 'getDescriptionFromLanguageLevelOption'),
                'choices'  => array_flip(EuropeanCVLanguage::LANGUAGE_LEVEL_LIST)
            ))
            ->add('writingLevel', Select2Type::class, array(
                'label' => 'Písanie',
                'placeholder' => 'Prosím, vyberte možnosť',
                'required' => false,
                'choices_description_callback' => array($this, 'getDescriptionFromLanguageLevelOption'),
                'choices'  => array_flip(EuropeanCVLanguage::LANGUAGE_LEVEL_LIST)
            ))
            ->add('certificate', null, array(
                'label' => 'Diplom(y), vysvedčenie(ia), osvedčenie(ia)',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'First Certificate in English'
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
            'data_class' => EuropeanCVLanguage::class,
        ));
    }

    /**
     * @param string $parentName
     * @return string|null
     */
    public function getDescriptionFromLanguageLevelOption($parentName)
    {
        $descriptions = array(
            'listeningLevel' => array(
                EuropeanCVLanguage::LANGUAGE_LEVEL_A1 => '
                    Dokážem rozoznať známe slová a veľmi základné frázy týkajúce sa mňa a mojej rodiny a bezprostredného konkrétneho okolia, keď ľudia hovoria pomaly a jasne.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_A2 => '
                   Dokážem porozumieť frázam a najbežnejšej slovnej zásobe vo vzťahu k oblastiam, ktoré sa ma bezprostredne týkajú (napríklad základné informácie
                   o mne a rodine, nakupovaní, miestnej oblasti, zamestnaní). Dokážem pochopiť zmysel v krátkych, jasných a jednoduchých správach a oznámeniach.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_B1 => '
                    Dokážem pochopiť hlavné body jasnej štandardnej reči o známych veciach, s ktorými sa pravidelne stretávam v škole, práci, vo voľnom čase atď. 
                    Rozumiem zmyslu mnohých rozhlasových alebo televíznych programov o aktuálnych udalostiach a témach osobného či odborného záujmu, keď je prejav relatívne pomalý a jasný.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_B2 => '
                    Dokážem porozumieť dlhšej reči a prednáškam a sledovať aj zložitú argumentačnú líniu za predpokladu, že téma mi je dostatočne známa. Dokážem pochopiť väčšinu televíznych správ a programov o aktuálnych udalostiach. Dokážem porozumieť väčšine filmov v spisovnom jazyku.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_C1 => '
                    Rozumiem dlhšej reči aj keď nie je jasne štruktúrovaná a keď vzťahy sú iba naznačené, nie explicitne signalizované. 
                    Dokážem bez väčšej námahy porozumieť televíznym programom a filmom.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_C2 => '
                    Nemám žiadne ťažkosti pri pochopení akéhokoľvek druhu hovoreného jazyka, či už je to naživo alebo z vysielania, dokonca aj keď je prejav hovoriaceho veľmi rýchly za predpokladu,
                    že mám dosť času na to, aby som si zvykol na jeho výslovnosť.
                '
            ),
            'readingLevel' => array(
                EuropeanCVLanguage::LANGUAGE_LEVEL_A1 => '
                    Rozumiem známym menám, slovám a veľmi jednoduchým vetám, napríklad na oznámeniach a plagátoch alebo v katalógoch.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_A2 => '
                   Dokážem čítať veľmi krátke jednoduché texty. Dokážem nájsť konkrétne predvídateľné informácie v jednoduchom každodennom materiáli, 
                   ako sú napríklad inzeráty, prospekty, jedálne lístky a časové harmonogramy a dokážem porozumieť krátkym jednoduchým osobným listom.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_B1 => '
                    Rozumiem textom, ktoré pozostávajú zo slovnej zásoby často používanej v každodennom živote alebo ktoré sa vzťahujú na moju prácu. 
                    Dokážem porozumieť opisom udalostí, pocitov a prianí v osobných listoch.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_B2 => '
                   Dokážem prečítať články a správy týkajúce sa aktuálnych problémov, v ktorých pisatelia alebo autori adoptujú konkrétne postoje alebo názory. 
                   Rozumiem súčasnej literárnej próze.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_C1 => '
                    Rozumiem dlhým a zložitým faktickým a literárnym textom, pričom rozoznávam rozdiely v štýle. 
                    Rozumiem odborným článkom a dlhším návodom, dokonca aj keď sa nevzťahujú na moju oblasť.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_C2 => '
                    Ľahko čítam všetky formy písaného jazyka vrátane abstraktných textov náročných svojou stavbou a jazykom, 
                    ako sú napríklad príručky, odborné články a literárne diela.
                '
            ),
            'talkingLevel' => array(
                EuropeanCVLanguage::LANGUAGE_LEVEL_A1 => '
                    Dokážem komunikovať jednoduchým spôsobom za predpokladu, že môj partner v komunikácii 
                    je pripravený zopakovať alebo preformulovať svoju výpoveď pri pomalšej rýchlosti reči a že mi pomôže sformulovať, 
                    čo ja sa pokúšam povedať. Dokážem klásť a odpovedať na jednoduché otázky v oblasti mojich základných potrieb alebo na veľmi známe témy.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_A2 => '
                   Dokážem komunikovať v jednoduchých a bežných situáciách vyžadujúcich jednoduchú a priamu výmenu informácií o známych témach a činnostiach. 
                   Dokážem zvládnuť veľmi krátke spoločenské kontakty, dokonca aj keď zvyčajne nerozumiem dostatočne na to, aby som sám udržiaval konverzáciu.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_B1 => '
                    Dokážem zvládnuť väčšinu situácií, ktoré sa môžu vyskytnúť počas cestovania v oblasti, kde sa hovorí týmto jazykom.
                    Môžem nepripravený vstúpiť do konverzácie na témy, ktoré sú známe, ktoré ma osobne zaujímajú, alebo ktoré sa týkajú osobného
                    každodenného života (napríklad rodina, koníčky, práca, cestovanie, súčasné udalosti).
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_B2 => '
                    Dokážem komunikovať na takej úrovni plynulosti a spontánnosti, ktorá mi umožňuje viesť bežný rozhovor s rodenými hovoriacimi. 
                    Dokážem sa aktívne zúčastniť na diskusii na známe témy, pričom vyjadrujem a presadzujem svoje názory.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_C1 => '
                    Dokážem sa vyjadrovať plynulo a spontánne bez zjavného hľadania výrazov. Dokážem využívať jazyk pružne
                    a účinne na spoločenské a profesijné účely. Dokážem presne sformulovať svoje myšlienky a názory a dokážem vhodne nadviazať na príspevky ostatných hovoriacich.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_C2 => '
                    Bez námahy sa dokážem zúčastniť na akejkoľvek konverzácii alebo diskusii a dobre ovládam idiomatické a hovorové výrazy. Dokážem sa vyjadrovať plynulo. 
                    Ak pri vyjadrovaní sa narazím na problém, dokážem sa vrátiť a preformulovať danú pasáž tak ľahko, že ostatní to ani nepostrehnú.
                '
            ),
            'oralSpeechLevel' => array(
                EuropeanCVLanguage::LANGUAGE_LEVEL_A1 => '
                    Dokážem využívať jednoduché frázy a vetami opísať miesto, kde žijem a ľudí, ktorých poznám.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_A2 => '
                   Dokážem použiť sériu fráz a viet na jednoduchý opis mojej rodiny a ostatných ľudí, 
                   životných podmienok, môjho vzdelania a mojej terajšej alebo nedávnej práce.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_B1 => '
                    Dokážem spájať frázy jednoduchým spôsobom, aby som opísal skúsenosti a udalosti, svoje sny, nádeje a ambície. 
                    Stručne dokážem uviesť dôvody a vysvetlenia názorov a plánov. Dokážem vyrozprávať príbeh alebo zápletku knihy či filmu a opísať svoje reakcie.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_B2 => '
                    Dokážem prezentovať jasné podrobné opisy celého radu predmetov vzťahujúcich sa na moju oblasť záujmu. 
                    Dokážem vysvetliť svoje stanovisko na aktuálne otázky s uvedením výhod a nevýhod rozličných možností.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_C1 => '
                    Dokážem jasne a podrobne opísať zložité témy, rozširovať ich o vedľajšie témy,
                     rozvíjať konkrétne body a zakončiť reč vhodným záverom.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_C2 => '
                    Dokážem podať jasný a plynulý opis alebo zdôvodnenie v štýle, ktorý sa hodí pre daný kontext
                    a s efektívnou logickou štruktúrou, ktorá pomôže príjemcovi všimnúť si dôležité body a zapamätať si ich.
                '
            ),
            'writingLevel' => array(
                EuropeanCVLanguage::LANGUAGE_LEVEL_A1 => '
                    Dokážem napísať krátku jednoduchú pohľadnicu, napríklad dokážem poslať pozdrav z dovolenky. Dokážem vyplniť formuláre s osobnými údajmi,
                     napríklad uviesť svoje meno, štátnu príslušnosť a adresu na registračnom formulári v hoteli.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_A2 => '
                   Dokážem napísať krátke jednoduché oznámenia a správy vzťahujúce sa na moje bezprostredné potreby. 
                   Dokážem napísať veľmi jednoduchý osobný list, napríklad poďakovanie.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_B1 => '
                    Dokážem napísať jednoduchý súvislý text na témy, ktoré sú mi známe alebo ma osobne zaujímajú.
                    Dokážem napísať osobné listy opisujúce skúsenosti a dojmy.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_B2 => '
                    Dokážem napísať podrobný text o širokom rozsahu tém vzťahujúcich sa na moje záujmy. 
                    Dokážem napísať referát alebo správu, odovzdávať informácie alebo poskytnúť dôkazy na podporu konkrétneho názoru. 
                    Dokážem napísať listy, ktoré objasňujú, prečo sú niektoré udalosti a skúsenosti pre mňa osobne dôležité.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_C1 => '
                    Dokážem sa jasne vyjadriť, dobre usporiadať text a odborne vyjadriť svoje stanoviská. 
                    Dokážem písať o zložitých predmetoch v liste, referáte, či správe a zdôrazniť, čo pokladám za najdôležitejšie. 
                    Dokážem zvoliť štýl podľa čitateľa, ktorému je text určený.
                ',
                EuropeanCVLanguage::LANGUAGE_LEVEL_C2 => '
                    Dokážem napísať hladko plynúci text v príslušnom štýle. 
                    Dokážem napísať zložité listy, správy alebo články, ktoré prezentujú prípad s efektívnou logickou štruktúrou, 
                    ktorá pomôže príjemcovi všimnúť si dôležité body a zapamätať si ich. Dokážem napísať zhrnutia
                    a recenzie o odborných prácach alebo literárnych dielach.
                '
            )
        );

        return isset($descriptions[$parentName]) ? $descriptions[$parentName] : null;
    }
}