<?php
require("./include/config.php");
$temp->header($lng->projectInfo);
$temp->menu();
?>
<h1 id="toc-uvod">Úvod</h1>

<p>Projekt <a href="http://ctenari.cz">internetového čtenářské deníku</a>
vznikl díky diskusi o&nbsp;hledání informací o&nbsp;knihách. Při množství
knížek není moc jednoduché dostat se kté, která by se mohla člověku
zalíbit. Je tu sice možnost udržovat si přehled pomocí různých
literárních novin, případně žánrově zaměřených časopisů, ale to se
neobejde bez obětování značného množství&nbsp;času.</p>

<p>Pokud by se ale povedlo přimět lidi vést jednoduchý záznam o&nbsp;tom, co
četli, dalo by se informací využít k&nbsp;tomu, aby člověk mohl najít knihu,
která je podobná nějaké, se kterou už má zkušenost. Bylo by potom mnohem
jednodušší dostat se knihám opdovídajícím osobnímu&nbsp;vkusu.</p>

<p>Tento projekt se snaží vytvořit systém, jenž by shraňování informací
tohoto typu umožňoval. Uživatel se k~němu připojí, přidá knihu a
klíčová slova, která ji charakterizují. Na základě těchto slov se pak
rozhoduje o&nbsp;podobnosti s&nbsp;ostatními knihami. Nic také nebrání uživateli
najít si člověka s&nbsp;podobným vkusem a podívat se do jeho čtenářského
deníku.</p>

<h1 id="toc-uzivatelska-prirucka">Uživatelská příručka</h1>

<p>Jelikož může nastat situace, kdy si nebudete úplně jistí, jak se na
stránkách Internetového čtenářského deníku orientovat a jak využívat
jeho služeb. Doporučuji alespoň letmo projít následující&nbsp;text.</p>

<h2 id="toc-prvni-navsteva">První návštěva</h2>

<p>Když poprvé navštívíte stránku <a
href="http://ctenari.cz">http://ctenari.cz</a>, pravděpodobně budete trochu
rozladěni z toho, že nemůžete žádným způsobem měnit obsah stránky.
Avšak není důvod k&nbsp;panice, vaše práva jakkoliv pracovat s&nbsp;již
vytvořeným obsahem nejsou omezena. Můžete bez potíží pročítat názory
registrovaných uživatelů, diskuse, hledat podobné knihy&nbsp;atd.</p>

<p>Pokud ale pocítíte touhu vést si prostřednictvým našich stránek svůj
čtenářský deník (Pomáhat tedy vytvářet data potřebná k&nbsp;běhu
stránek.), budete muset projít jednoduchým procesem registrace.</p>

<h2 id="toc-registrace">Registrace</h2>

<p>Klknutím na tlačítko <a
href="http://www.ctenari.cz/user.php?action=regForm">Registrovat</a> v&nbsp;horní
navigaci se přesunete na stránku s&nbsp;registračním formulářem.</p>

<p>Do tohoto formuláře prosím vyplňte jméno, pod kterým chcete vystupovat,
heslo, e-mail a stručný popis, který Vás charakterizuje. E-mail nebude nikde
zveřejněn a slouží pouze jako nástroj komunikace mezi Vámi a
administrátorem.</p>

<p>Po odeslání formuláře se můžete ihned přihlásit a přidat první
knihu do Vašeho čtenářského deníku.</p>

<h1 id="toc-kniha">Kniha</h1>

<p>Základním stavebním kamenem našeho projektu je kniha. Uživatelé
přidávají ke knize své hodnocení, klíčová slova a názor, a ty se poté
shromažďují.</p>

<h2 id="toc-pridat-knihu">Přidat knihu</h2>

<p>Poklepáním na tlačítko „Přidat knihu“ v&nbsp;horní navigaci přejdete
na stránku s&nbsp;formulářem pro přidání knihy do Vašeho čtenářského
deníku. Vyplňte prosím pozorně uvedené položky a odešlete formulář.Je
velmi důležité zkontrolovat název knihy a jméno autora, jelikož podle
těchto položek se Váš názor přidá k&nbsp;určité&nbsp;knize.</p>

<h2 id="toc-klicova-slova">Klíčová slova</h2>

<p>Klíčová slova, jimiž jsou označeny knihy jsou jednou
z&nbsp;nejdůležitějších částí systému. Charakterizují knihu a usnadňují
tím navigaci a vyhledávání knih. Pomocí těchto slov se také určuje
podobnost knih, která symbolizuje podíl stejných štítků ku všem
štítků, kterými je daná dvojice knihy označena.</p>

<p>Používejte štítky jednoslovné a co nejvýstižnější.</p>

<h2 id="toc-hodnoceni">Hodnocení</h2>

<p>Hodnocení je to první, co by mělo vystihovat Váš postoj ke knize.
Narozdíl od názoru samotného se dá porovnat s&nbsp;ostatními uživateli a jeho
průměrná hodnota bude také použita při~zobrazení&nbsp;kni&shy;hy.</p>

<p>Mějte prosím na paměti, že méně je někdy více, což v~tomto
případě znamená chválou spíše šetřit. Nehchte si to nejlepší
hodnocení na~opravdové literární skvosty.</p>

<h2 id="toc-stranka-knihy">Stránka knihy</h2>

<p>Pokud si vyhledáte knihu a následně poklepáte na její název ve
výsledcích hledání, přesunete se na stránku dané knihy. Najdete zde její
průměrné hodnocení, štítky, seznam uživatelů, kteří ji četli, diskusi
a to nejdůležitější&nbsp;&ndash; odkaz na nalezení podobné&nbsp;knihy.</p>

<h2 id="toc-diskuse-o-knize">Diskuse o&nbsp;knize</h2>

<p>Pokud se u&nbsp;knihy nalezne téma, o&nbsp;kterém byste se rádi pobavili
s&nbsp;ostatními, toto je místo, kde tak můžete učinit. Pokud nejste
přihlášeni, můžete diskusi pouze&nbsp;číst.</p>

<h2 id="toc-formatovani-textu">Formátování textu</h2>

<p>Formátování veškerého textu zajišťuje systém ze stránek <a
href="http://texy.info">http://texy.info</a>, kde také naleznete návod, jak
Texy! používat. Nemusíte se ale bát. Pokud budete chtít psát pouze
jednoduchý text, znalost Texy! nebudete potřebovat.</p>

<h1 id="toc-uzivatel">Uživatel</h1>

<p>Hlavní a vlastně i&nbsp;jedinou silou, která tvoří obsah, jsou uživatelé.
Ti zároveň ale nepředstavují jen tvůrce, ale i&nbsp;konzumenty těchto dat.
Charakteristikou každého uživatele jsou knihy, které přečetl, a karma
reprezentující jeho hodnocení v&nbsp;rámci systému.</p>

<h2 id="toc-stranka-uzivatele">Stránka uživatele</h2>

<p>Každý uživatel vlastní tzv. stránku uživatele. Zde můžete najít
knihy, které přidal do svého čtenářského deníku naposled či které si
nejvíce oblíbil. Není však problém si zobrazit všechny uživatelovi knihy
a ty si následně seřadit podle některého ze sloupců.</p>

<p>Určitě si v&nbsp;levé části stránky všimnete sloupce, kde je zobrazena
čtenářova karma, charakteristika a lidé, které si oblíbil. Na tomto
místě také naleznete RSS (Description Framework nebo také
Rich Site Summary.) zdroje, máte na výběr mezi odebíráním nejnovějších
knih daného uživatele či jeho oblíbenců.</p>

<h2 id="toc-oblibeni-uzivatele">Oblíbení uživatelé</h2>

<p>Pro snazší orientaci mezi uživateli mají jednotliví čtenáři možnost
označovat ostatní za oblíbené (Tuto možnost naleznete po přihlášení na
stránce uživatele, kterího si chcete oblíbit. Na stejném místě jej ze
skupiny oblíbených můžete odstranit.). Následně vytvořenou skupinu
oblíbených mají možnost lépe sledovat a oceňují tím kvalitu daných
čtenářských deníků.</p>

<h2 id="toc-karma">Karma</h2>

<p>Dalším prvkem systému, který se snaží ulehčit orientaci, je karma.
Tato položka je číslem hodnotícím přínos čtenáře pro ostatní. Roste
s&nbsp;počtem přidaných knih a počtem lidí, kteří si Vás přidají do
oblíbených.</p>

<!-- by Texy2! -->
<?php
$temp->middle();
$temp->footer();
?>