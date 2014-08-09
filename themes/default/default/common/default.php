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
	
	// - top
	$HTML->AddElement(new HtmlElement('div', 'id="top"'), 'main', 'top');
	//$HTML->SetPointer('top');
	$HTML->AddElement(new HtmlElement('span', 'id="logo"', '&nbsp;'));
	$HTML->AddElement(new HtmlElement('span', 'id="sitetitle"', 'iensenfirippu.dk'));
	
	// - left
	$HTML->AddElement(new HtmlElement('div', 'id="left"'), 'main', 'left');
	//$HTML->SetPointer('left');
	$HTML->AddElement(
		new HtmlElement('div', 'id="left-fixed"', '',
			array(
				new HtmlElement('a', 'class="toplink-left" href="#top"', 'To top'),
				$menu->GetHtml()
			)
		)
	);
	
	// - content
	$HTML->AddElement(new HtmlElement('div', 'id="content"'), 'main', 'content');
	//$HTML->SetPointer('content');
	$HTML->AddElement(new HtmlElement('div', 'class="breadcrumbs"', '', $breadcrumbs->GetHtml()));
	
	// - bottom
	$HTML->AddElement(new HtmlElement('div', 'id="bottom"'), 'main', 'bottom');
	//$HTML->SetPointer('bottom');
	$HTML->AddElement(new HtmlElement('span', 'class="left"', 'Copyright Philip Jensen'));
	$HTML->AddElement(new HtmlElement('span', 'class="right"', '<!--{EXECUTIONTIME}--!> <!--{QUERYCOUNT}--!>'));
}
?>
