<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	include_once($THEME->GetDirectory().'/'.FOLDERCOMMON.'/common.php');
	
	$menu = Module::Load('menu');
	$breadcrumbs = Module::Load('breadcrumbs');
	
	//foreach ($menu->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
	//foreach ($breadcrumbs->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
	
	// BODY
	$HTML->AddElement(new HtmlElement('div', 'id="wrapper"'), 'BODY', 'wrapper');
	$HTML->AddElement(new HtmlElement('div', 'id="main"'), 'wrapper', 'main');
	
	// - top
	$HTML->AddElement(new HtmlElement('div', 'id="top"'), 'main', 'top');
	//$HTML->SetPointer('top');
	$HTML->AddElement(new HtmlElement('span', 'id="logo"', '&nbsp;'));
	$HTML->AddElement(new HtmlElement('span', 'id="sitetitle"', WEBSITETITLE));
	
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
	$HTML->AddElement(new HtmlElement('div', 'class="breadcrumbs"', EMPTYSTRING, $breadcrumbs->GetHtml()));
	
	// - bottom
	$HTML->AddElement(new HtmlElement('div', 'id="bottom"'), 'main', 'bottom');
	//$HTML->SetPointer('bottom');
	$HTML->AddElement(new HtmlElement('span', 'class="left"', 'Valid and beautiful <a href="http://validator.w3.org/check?uri='.URLROOT.$_SERVER['REQUEST_URI'].'">HTML5</a> provided by <a href="https://github.com/iensenfirippu/reiZ">reiZ</a>'));
	$HTML->AddElement(new HtmlElement('span', 'class="right"', '<!--{EXECUTIONTIME}--!> <!--{QUERYCOUNT}--!>'));
}
?>
