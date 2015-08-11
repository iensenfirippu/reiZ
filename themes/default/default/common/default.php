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
	
	if (KEYBOARDSHORTCUTS)
	{
		$HTML->AddJavascript(FOLDERCOMMON.'/'.FOLDERSCRIPTS.'/mousetrap.js');
		$HTML->AddJavascript(FOLDERCOMMON.'/'.FOLDERSCRIPTS.'/keyboard-bindings.js');
	}
	
	if (RESPONSIVE)
	{
		$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/z_rwd1.css');
		$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/z_rwd2.css');
		$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/z_rwd3.css');
		$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/z_rwd4.css');
		$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/z_rwd5.css');
		$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/z_rwd6.css');
	}
	
	// BODY
	if (!ALLOWMOUSE || rand(0, 100) == 42)
	{
		$HTML->AddJavascript(FOLDERCOMMON.'/'.FOLDERSCRIPTS.'/hider.js');
		$HTML->AddElement(new HtmlElement('div', 'id="troll-overlay" onmouseover="showdiv(\'troll-box\')" onmouseout="hidediv(\'troll-box\')"'), 'BODY');
		$HTML->AddElement(new HtmlElement('div', 'id="troll-wrapper"', EMPTYSTRING,
			new HtmlElement('div', 'id="troll-box" class="hidden"', EMPTYSTRING,
				array(
					new HtmlElement('h1', EMPTYSTRING, 'PROBLEM? MOUSE-FAG'),
					new HtmlElement('span', EMPTYSTRING, 'Move your mouse cursor out of the browser window, and I&apos;ll make this (slightly annoying?) popup go away. ;)'),
					new HtmlElement('span', EMPTYSTRING, 'Please navigate the site with your keyboard by using TAB and SHIFT+TAB. (as the flying spaghetti monster always intented)'),
					new HtmlElement('span', EMPTYSTRING, '...you could also try the Konami code?')
				)
			)
		), 'BODY');
	}
	
	$HTML->AddElement(new HtmlElement('div', 'id="wrapper"'), 'BODY', 'wrapper');
	$HTML->AddElement(new HtmlElement('div', 'class="hiddenlink"', EMPTYSTRING,
		array(
			new HtmlElement('a', 'href="#kb_maincontent"', 'Skip navigation links'),
			new HtmlElement('a', 'id="kb_c" href="#kb_maincontent" tabindex="-1"', 'Go to main content'),
			new HtmlElement('a', 'id="kb_m" href="#kb_navigation" tabindex="-1"', 'Go to menu'),
			new HtmlElement('a', 'id="kb_r" href="#kb_rightpane" tabindex="-1"', 'Go to right pane'),
			new HtmlElement('a', 'id="kb_f" href="#kb_footer" tabindex="-1"', 'Go to footer')
		)
	));
	
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
		new HtmlElement('div', 'id="left-fixed"', EMPTYSTRING,
			array(
				new HtmlElement('a', 'class="hidden" id="kb_navigation"'),
				new HtmlElement('a', 'class="toplink-left" href="#top" tabindex="-1"', 'To top'),
				$menu->GetHtml()
			)
		)
	);
	
	// - content
	$HTML->AddElement(new HtmlElement('div', 'id="content"'), 'main', 'content');
	$HTML->AddElement(new HtmlElement(), 'content', 'contenttop');
	$HTML->AddElement($breadcrumbs->GetHtml());
	$HTML->AddElement(new HtmlElement('a', 'class="hidden" id="kb_maincontent"'));
	$HTML->AddElement(new HtmlElement(), 'content', 'contentmain');
	$HTML->AddElement(new HtmlElement(), 'content', 'contentbottom');
	
	// - bottom
	$HTML->AddElement(new HtmlElement('div', 'id="bottom"'), 'main', 'bottom');
	$HTML->AddElement(new HtmlElement('span', 'class="left"', 'Valid and beautiful <a href="http://validator.w3.org/check?uri='.URLROOT.$_SERVER['REQUEST_URI'].'">HTML5</a> provided by <a href="https://github.com/iensenfirippu/reiZ">reiZ</a>'));
	$HTML->AddElement(new HtmlElement('span', 'class="right"', '<!--{EXECUTIONTIME}--!> <!--{QUERYCOUNT}--!>'));
}
?>