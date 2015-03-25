<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') && class_exists('Administration') or exit(1))
{
	$HTML = new HtmlDocument('Iensenfirippu.dk');
	
	// HEAD
	$HTML->SetPointer('HEAD');
	$HTML->AddElement(new HtmlElement('meta', 'charset="UTF-8"'));
	$HTML->AddElement(new HtmlElement('link',
		'rel="icon" type="image/png" href="'.URLROOT.'/'.FOLDERCOMMON.'/'.FOLDERIMAGES.'/favicon.png"'));
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/default.css');
	foreach ($PAGE->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
	foreach ($PAGE->GetJavascripts() as $js) { $HTML->AddJavascript($js); }
	
	// BODY
	$HTML->AddElement(new HtmlElement('div', 'id="fixedtop"'), 'BODY', 'fixedtop');
	$HTML->AddElement(new HtmlElement('div', 'id="menubar"'), 'fixedtop', 'menubar');
	$HTML->AddElement(new HtmlElement('a', ' href="/'.ADMINPAGE.'"', EMPTYSTRING, new HtmlElement('span', 'id="logo"', '&nbsp;')),'menubar', 'logo');
	$HTML->AddElement($PAGE->GetMenu(),'menubar', 'menu');
	
	$HTML->AddElement(new HtmlElement('div', 'id="wrapper"'), 'BODY', 'wrapper');
	$HTML->AddElement(new HtmlElement('div', 'id="optionsbar"', EMPTYSTRING, $PAGE->GetOptions()), 'wrapper', 'optionsbar');
	//$HTML->AddElement(new HtmlElement('ul', 'class="options"', EMPTYSTRING, Administration::GetOptions()), 'optionsbar', 'options');
	
	// content
	$HTML->AddElement($PAGE->GetContent(), 'wrapper');
}
?>
