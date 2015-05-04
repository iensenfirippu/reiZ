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
	
	if (RESPONSIVE)
	{
		// Responsive Web Design ...ish
		$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/z_rwd1.css');
		$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/z_rwd2.css', ' media="(max-width: 1600px)"');
		$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/z_rwd3.css', ' media="(max-width: 1300px)"');
		$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/z_rwd4.css', ' media="(max-width: 1100px)"');
		$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/z_rwd5.css', ' media="(max-width: 950px)"');
		$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/z_rwd6.css', ' media="(max-width: 800px)"');
	}
	
	// BODY
	if (!ALLOWMOUSE || rand(0, 100) == 50)
	{
		$HTML->AddJavascript(FOLDERCOMMON.'/'.FOLDERSCRIPTS.'/hider.js');
		$HTML->AddElement(new HtmlElement('div', 'id="troll-overlay" onmouseover="showdiv(\'troll-text\')" onmouseout="hidediv(\'troll-text\')"'), 'BODY', 'troll-overlay');
		$HTML->AddElement(new HtmlElement('div', 'id="troll-text" class="hidden"', EMPTYSTRING,
			array(
				new HtmlElement('h1', EMPTYSTRING, 'PROBLEM? MOUSE-FAG'),
				new HtmlElement('span', EMPTYSTRING, 'Move your mouse cursor out of the browser window, and I&apos;ll make this (slightly annoying?) popup go away. ;)'),
				new HtmlElement('span', EMPTYSTRING, 'Please navigate the site with your keyboard by using TAB and SHIFT+TAB. (as the flying spaghetti monster always intented)')
			)
		));
	}
	
	$HTML->AddElement(new HtmlElement('div', 'id="wrapper"'), 'BODY', 'wrapper');
	$HTML->AddElement(new HtmlElement('div', 'class="hiddenlink"', EMPTYSTRING,
		new HtmlElement('a', 'href="#maincontent"', 'Skip navigation links')
	));
	
	/*if (!ALLOWMOUSE)
	{
		$HTML->AddJavascript(FOLDERCOMMON.'/'.FOLDERSCRIPTS.'/hider.js');
		$HTML->AddJavascript(FOLDERCOMMON.'/'.FOLDERSCRIPTS.'/troller.js');
		$HTML->AddElement(new HtmlElement('div', 'id="troll-main" class="hidden"'), 'wrapper', 'troll-main');
		$HTML->AddElement(new HtmlElement('span', 'id="troll-text"',
			'HAHA LAWL! YU CANT HAS BRAUS DIS PAEG WIFF STOOPID MOUSE. PROBLEM? MOUSE-FAG, HAHAHAHA LAWL LAWL YU FAILZ'.NEWLINE.NEWLINE.
			'Move your mouse cursor out of the browser window, and I&apos;ll let you back in. ;)'.NEWLINE.
			'Please navigate the site with your keyboard by using TAB and SHIFT+TAB. (as the flying spaghetti monster always intented)'
		));
		$HTML->AddElement(new HtmlElement('div', 'id="main" class="unhidden"'), 'wrapper', 'main');
	}
	else
	{*/
		$HTML->AddElement(new HtmlElement('div', 'id="main"'), 'wrapper', 'main');
	//}
	
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
				new HtmlElement('a', 'class="toplink-left" href="#top" tabindex="-1"', 'To top'),
				$menu->GetHtml()
			)
		)
	);
	//			new HtmlElement('article', 'id="menu"'),
	
	// - content
	$HTML->AddElement(new HtmlElement('div', 'id="content"'), 'main', 'content');
	$HTML->AddElement(new HtmlElement(), 'content', 'contenttop');
	$HTML->AddElement(new HtmlElement('div', 'class="breadcrumbs"', EMPTYSTRING, $breadcrumbs->GetHtml()));
	$HTML->AddElement(new HtmlElement(), 'content', 'contentmain');
	$HTML->AddElement(new HtmlElement('article', 'id="maincontent"'));
	$HTML->AddElement(new HtmlElement(), 'content', 'contentbottom');
	
	// - bottom
	$HTML->AddElement(new HtmlElement('div', 'id="bottom"'), 'main', 'bottom');
	$HTML->AddElement(new HtmlElement('span', 'class="left"', 'Valid and beautiful <a href="http://validator.w3.org/check?uri='.URLROOT.$_SERVER['REQUEST_URI'].'">HTML5</a> provided by <a href="https://github.com/iensenfirippu/reiZ">reiZ</a>'));
	$HTML->AddElement(new HtmlElement('span', 'class="right"', '<!--{EXECUTIONTIME}--!> <!--{QUERYCOUNT}--!>'));
}
?>