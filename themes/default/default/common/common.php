<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	$HTML = new HtmlDocument('Iensenfirippu.dk');
	
	/*include_once(FOLDERMODULES.'/menu/module.php');
	include_once(FOLDERMODULES.'/breadcrumbs/module.php');
	$menu = new MenuModule();
	$breadcrumbs = new BreadcrumbsModule();*/
	
	// HEAD
	$HTML->SetPointer('HEAD');
	$HTML->AddElement(new HtmlElement('meta', 'charset="UTF-8"'));
	$HTML->AddElement(new HtmlElement('link',
		'rel="icon" type="image/png" href="'.URLROOT.'/'.FOLDERCOMMON.'/'.FOLDERIMAGES.'/favicon.png"'));
		//'rel="icon" type="image/png" href="'.URLROOT.'/'.$THEME->GetDirectory().'/'.FOLDERIMAGES.'/favicon.png"'));
	$HTML->AddStylesheet(FOLDERCOMMON.'/'.FOLDERSTYLES.'/common.css');
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/common.css');
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/bigbox.css');
	//foreach ($menu->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
	//foreach ($breadcrumbs->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
}
?>
