<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	include_once(FOLDERMODULES.'/menu/module.php');
	include_once(FOLDERMODULES.'/breadcrumbs/module.php');
	$menu = new MenuModule();
	$breadcrumbs = new BreadcrumbsModule();
	
	// HEAD
	$HTML->SetPointer('HEAD');
	$HTML->AddElement(new HtmlElement('meta', 'charset="UTF-8"'));
	$HTML->AddElement(new HtmlElement('link',
		'rel="icon" type="image/png" href="'.URLROOT.'/'.FOLDERCOMMON.'/'.FOLDERIMAGES.'/favicon.png"'));
		//'rel="icon" type="image/png" href="'.URLROOT.'/'.$THEME->GetDirectory().'/'.FOLDERIMAGES.'/favicon.png"'));
	$HTML->AddStylesheet(FOLDERCOMMON.'/'.FOLDERSTYLES.'/common.css');
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/common.css');
	foreach ($menu->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
	foreach ($breadcrumbs->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
	
	// BODY
	$HTML->AddElement(new HtmlElement('div', 'id="wrapper"'), 'BODY', 'wrapper');
	$HTML->AddElement(new HtmlElement('div', 'id="main"'), 'wrapper', 'main');
	
	// - bigbox
	$HTML->AddElement(new HtmlElement('div', 'id="bigbox"'), 'main', 'bigbox');
	$HTML->AddElement(new HtmlElement('span', 'id="bigbox-logo"', '&nbsp;'), 'bigbox', 'logo');
	$HTML->AddElement(new HtmlElement('span', 'id="bigbox-title"', 'iensenfirippu.dk'), 'bigbox', 'title');
	$HTML->AddElement(new HtmlElement('div', 'id="bigbox-content"'), 'bigbox', 'content');
}
?>
