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
	
	$HTML->AddToHead(new HtmlElement('meta', 'charset="UTF-8"'));
	$HTML->AddToHead(new HtmlElement('link',
		'rel="icon" type="image/png" href="'.URLROOT.'/'.FOLDERCOMMON.'/'.FOLDERIMAGES.'/favicon.png"'));
		//'rel="icon" type="image/png" href="'.URLROOT.'/'.$THEME->GetDirectory().'/'.FOLDERIMAGES.'/favicon.png"'));
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/common.css');
	foreach ($menu->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
	foreach ($breadcrumbs->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
	$HTML->AddToTop(new HtmlElement('span', 'id="logo"', '&nbsp;'));
	$HTML->AddToTop(new HtmlElement('span', 'id="sitetitle"', 'iensenfirippu.dk'));
	$HTML->AddToLeft(
		new HtmlElement('div', 'id="left-fixed"', '',
			array(
				new HtmlElement('a', 'class="toplink-left" href="#top"', 'To top'),
				$menu->GetHtml()
			)
		)
	);
	$HTML->AddToBottom(new HtmlElement('span', 'class="left"', 'Copyright Philip Jensen'));
	$HTML->AddToBottom(new HtmlElement('span', 'class="right"', '<!--{EXECUTIONTIME}--!> <!--{QUERYCOUNT}--!>'));
	$HTML->AddContent(new HtmlElement('div', 'class="breadcrumbs"', '', $breadcrumbs->GetHtml()));
}
?>
