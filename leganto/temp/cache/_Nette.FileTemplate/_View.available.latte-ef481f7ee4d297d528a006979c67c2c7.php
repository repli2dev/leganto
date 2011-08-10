<?php //netteCache[01]000405a:2:{s:4:"time";s:21:"0.46401800 1312988890";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:83:"/home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/available.latte";i:2;i:1312987818;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/available.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'k8khr0bv5h')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<available xmlns="http://leganto.com/api">

	<resource>
		<link /><?php echo Nette\Templating\DefaultHelpers::escapeHtml($control->link('//book'), ENT_NOQUOTES) ?></link>
		<schema></schema>
		<description>Náhled jedné knihy</description>
		<parameters>
			<parameter required="true">
				<name>id</name>
				<description>ID knihy</description>
			</parameter>			
		</parameters>
	</resource>

	<resource>
		<link /><?php echo Nette\Templating\DefaultHelpers::escapeHtml($control->link('//author'), ENT_NOQUOTES) ?></link>
		<schema></schema>
		<description>Náhled jednoho autora</description>
		<parameters>
			<parameter required="true">
				<name>id</name>
				<description>ID autora</description>
			</parameter>
		</parameters>
	</resource>

	<resource>
		<link /><?php echo Nette\Templating\DefaultHelpers::escapeHtml($control->link('//discussions'), ENT_NOQUOTES) ?></link>
		<schema></schema>
		<description>Výpis diskusních vláken seřazených sestupě podle času.</description>
		<parameters>
			<parameter required="false">
				<name>type</name>
				<!-- TODO: Specifikovat typ -->
				<description>Označuje typ vláken, která chceme zobrazit.</description>
			</parameter>
			<parameter required="false">
				<name>offset</name>
				<description>Číslo, které udává, odkud se má vypisovat.</description>
			</parameter>
			<parameter required="false">
				<name>limit</name>
				<description>Počet vypsaných položek. Maximálně 100.</description>
			</parameter>
		</parameters>
	</resource>

	<resource>
		<link /><?php echo Nette\Templating\DefaultHelpers::escapeHtml($control->link('//bookOpinions'), ENT_NOQUOTES) ?></link>
		<schema></schema>
		<description>Přehled názorů na knihu</description>
		<parameters>
			<parameter required="true">
				<name>book</name>
				<description>Kniha, ke které se názory vztahují.</description>
			</parameter>
			<parameter required="false">
				<name>user</name>
				<description>ID uživatele. Pokud je vyplněno, jsou názory seřazeny podle podobnosti s daným uživatelem.</description>
			</parameter>
			<parameter required="false">
				<name>offset</name>
				<description>Číslo, které udává, odkud se má vypisovat.</description>
			</parameter>
			<parameter required="false">
				<name>limit</name>
				<description>Počet vypsaných položek. Maximálně 100.</description>
			</parameter>
		</parameters>
	</resource>

	<resource>
		<link /><?php echo Nette\Templating\DefaultHelpers::escapeHtml($control->link('//userOpinions'), ENT_NOQUOTES) ?></link>
		<schema></schema>
		<description>Přehled názorů na knihu určitého uživatele</description>
		<parameters>
			<parameter required="true">
				<name>user</name>
				<description>Uživatel, jehož názory se mají zobrazit.</description>
			</parameter>
			<parameter required="false">
				<name>empty</name>
				<description>Zobrazovat i prázdné názory (0 - TRUE, 1 - FALSE). Výchozí stav 0.</description>
			</parameter>
			<parameter required="false">
				<name>offset</name>
				<description>Číslo, které udává, odkud se má vypisovat.</description>
			</parameter>
			<parameter required="false">
				<name>limit</name>
				<description>Počet vypsaných položek. Maximálně 100.</description>
			</parameter>
		</parameters>
	</resource>

	<resource>
		<link /><?php echo Nette\Templating\DefaultHelpers::escapeHtml($control->link('//posts'), ENT_NOQUOTES) ?></link>
		<schema></schema>
		<description>Výpis diskusních příspěvků k dané entitě.</description>
		<parameters>
			<parameter required="true">
				<name>id</name>
				<description>ID entity</description>
			</parameter>
			<parameter required="true">
				<name>type</name>
				<!-- TODO: Specifikovat -->
				<description>Typ entity</description>
			</parameter>
			<parameter required="false">
				<name>offset</name>
				<description>Číslo, které udává, odkud se má vypisovat.</description>
			</parameter>
			<parameter required="false">
				<name>limit</name>
				<description>Počet vypsaných položek. Maximálně 100.</description>
			</parameter>
		</parameters>
	</resource>

	<resource>
		<link /><?php echo Nette\Templating\DefaultHelpers::escapeHtml($control->link('//shelf'), ENT_NOQUOTES) ?></link>
		<schema></schema>
		<description>Výpis knih v poličce</description>
		<parameters>
			<parameter required="true">
				<name>id</name>
				<description>ID poličky</description>
			</parameter>
			<parameter required="false">
				<name>offset</name>
				<description>Číslo, které udává, odkud se má vypisovat.</description>
			</parameter>
			<parameter required="false">
				<name>limit</name>
				<description>Počet vypsaných položek. Maximálně 100.</description>
			</parameter>
		</parameters>
	</resource>

	<resource>
		<link /><?php echo Nette\Templating\DefaultHelpers::escapeHtml($control->link('//similarBooks'), ENT_NOQUOTES) ?></link>
		<schema></schema>
		<description>Výpis podobných knih k dané knize</description>
		<parameters>
			<parameter required="true">
				<name>book</name>
				<description>ID knihy</description>
			</parameter>
			<parameter required="false">
				<name>offset</name>
				<description>Číslo, které udává, odkud se má vypisovat.</description>
			</parameter>
			<parameter required="false">
				<name>limit</name>
				<description>Počet vypsaných položek. Maximálně 100.</description>
			</parameter>
		</parameters>
	</resource>

	<resource>
		<link /><?php echo Nette\Templating\DefaultHelpers::escapeHtml($control->link('//searchUsers'), ENT_NOQUOTES) ?></link>
		<schema></schema>
		<description>Vyhledávání v uživatelích</description>
		<parameters>
			<parameter required="true">
				<name>query</name>
				<description>Hledaná fráze</description>
			</parameter>
			<parameter required="false">
				<name>offset</name>
				<description>Číslo, které udává, odkud se má vypisovat.</description>
			</parameter>
			<parameter required="false">
				<name>limit</name>
				<description>Počet vypsaných položek. Maximálně 100.</description>
			</parameter>
		</parameters>
	</resource>

	<resource>
		<link /><?php echo Nette\Templating\DefaultHelpers::escapeHtml($control->link('//similarUsers'), ENT_NOQUOTES) ?></link>
		<schema></schema>
		<description>Výpis podobných uživatelů k danému uživateli</description>
		<parameters>
			<parameter required="true">
				<name>user</name>
				<description>ID uživatele</description>
			</parameter>
			<parameter required="false">
				<name>offset</name>
				<description>Číslo, které udává, odkud se má vypisovat.</description>
			</parameter>
			<parameter required="false">
				<name>limit</name>
				<description>Počet vypsaných položek. Maximálně 100.</description>
			</parameter>
		</parameters>
	</resource>

	<resource>
		<link /><?php echo Nette\Templating\DefaultHelpers::escapeHtml($control->link('//user'), ENT_NOQUOTES) ?></link>
		<schema></schema>
		<description>Náhled uživatele</description>
		<parameters>
			<parameter required="true">
				<name>id</name>
				<description>ID uživatele</description>
			</parameter>
		</parameters>
	</resource>


	<resource>
		<link /><?php echo Nette\Templating\DefaultHelpers::escapeHtml($control->link('//searchBooks'), ENT_NOQUOTES) ?></link>
		<schema></schema>
		<description>Vyhledávání v knihách</description>
		<parameters>
			<parameter required="true">
				<name>query</name>
				<description>Hledaná fráze</description>
			</parameter>
			<parameter required="false">
				<name>offset</name>
				<description>Číslo, které udává, odkud se má vypisovat.</description>
			</parameter>
			<parameter required="false">
				<name>limit</name>
				<description>Počet vypsaných položek. Maximálně 100.</description>
			</parameter>
		</parameters>
	</resource>

	<resource>
		<link /><?php echo Nette\Templating\DefaultHelpers::escapeHtml($control->link('//login'), ENT_NOQUOTES) ?></link>
		<schema></schema>
		<description>Zobrazeni aktualne prihlasene identity (dle cookies)</description>
	</resource>

	<resource>
		<link /><?php echo Nette\Templating\DefaultHelpers::escapeHtml($control->link('//logout'), ENT_NOQUOTES) ?></link>
		<schema></schema>
		<description>Odhlaseni aktualne prihlaseneho uzivatele</description>
	</resource>

</available>