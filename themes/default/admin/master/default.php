<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	/*foreach ($PAGE->GetMasterPage()->GetModules() as $module)
	{
		include_once(FOLDERMODULES.'/'.$module.'/module.php');
	}*/
	
	$HTML = new HtmlPage('Iensenfirippu.dk');
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/default.css');
	
	$HTML->AddToBody(
		new HtmlElement('div', 'id="fixedtop"', EMPTYSTRING,
			new HtmlElement('div', 'id="menubar"', EMPTYSTRING,
				array(
					new HtmlElement('a', 'href="#"', EMPTYSTRING,
						new HtmlElement('span', 'id="menubar"', '&nbsp;')
					),
					AdminMenu::GetHtml()
				)
			)
		)
	);
	
	// LAST TIME ON reiZ development:
	//	I was about to fix the admin site
	//	...and remove the need for the layout classes
	//	...by adding their "containers" directly to the htmlpage class
	
	$HTML_optionsbar = new HtmlElement('div', 'id="optionsbar"', $PAGE->GetText());
	$HTML_options = new HtmlElement('ul', 'class="options"', $PAGE->GetText());
	$HTML->AddToBody(new HtmlElement('div', 'id="fixedtop"', $PAGE->GetText()));
	$HTML->AddToRight(new HtmlElement('div', 'id="right-fixed"', '',
		new HtmlElement('a', 'class="toplink-right" href="#top"', 'To top')));
		
	foreach ($PAGE->GetModules() as $module)
	{
		include_once(FOLDERMODULES.'/'.$module[0].'/module.php');
		$MODULE->Initialize();
		foreach ($MODULE->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
		foreach ($MODULE->GetJavascripts() as $js) { $HTML->AddJavascript($js); }
		
		$HTML->AddContent($MODULE->GetHtml($module[1]));
		$HTML->GetRight()->GetChildren()[0]->AddChild($MODULE->GetHtml_RightPane());
	}
}
?>
