<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	include_once(new URL(array($THEME->GetDirectory(), 'common', 'common.php')));
	
	$menu = Module::Load('menu');
	$breadcrumbs = Module::Load('breadcrumbs');
	
	/*if (KEYBOARDSHORTCUTS)
	{
		$HTML->AddJavascript(new URL(array('common', 'scripts', 'mousetrap.js')));
		$HTML->AddJavascript(new URL(array('common', 'scripts', 'keyboard-bindings.js')));
	}*/
	
	if (RESPONSIVE)
	{
		$HTML->AddStylesheet(new URL(array($THEME->GetDirectory(), 'styles', 'z_rwd1.css')));
		$HTML->AddStylesheet(new URL(array($THEME->GetDirectory(), 'styles', 'z_rwd2.css')));
		$HTML->AddStylesheet(new URL(array($THEME->GetDirectory(), 'styles', 'z_rwd3.css')));
		$HTML->AddStylesheet(new URL(array($THEME->GetDirectory(), 'styles', 'z_rwd4.css')));
		$HTML->AddStylesheet(new URL(array($THEME->GetDirectory(), 'styles', 'z_rwd5.css')));
		$HTML->AddStylesheet(new URL(array($THEME->GetDirectory(), 'styles', 'z_rwd6.css')));
	}
	
	// BODY
	if (!ALLOWMOUSE || rand(0, 100) == 42)
	{
		$HTML->AddJavascript(new URL(array('common', 'scripts', 'hider.js')));
		$HTML->AddElement(new RTK_Box('troll-overlay', null, array('onmouseover' => 'showdiv(\'troll-box\')', 'onmouseout' => 'hidediv(\'troll-box\')')), 'BODY');
		$HTML->AddElement(new RTK_Box('troll-wrapper', null, null,
			new RTK_Box('troll-box', 'hidden', null, array(
				new RTK_Header('PROBLEM? MOUSE-FAG', 1),
				new RTK_Textview('Move your mouse cursor out of the browser window, and I&apos;ll make this (slightly annoying?) popup go away. ;)', true),
				new RTK_Textview('Please navigate the site with your keyboard by using TAB and SHIFT+TAB. (as the flying spaghetti monster always intented)', true),
				new RTK_Textview('...you could also <i>try</i> the Konami code?', true)
			))
		), 'BODY');
	}
	
	$HTML->AddElement(new RTK_Box('wrapper'), 'BODY', 'wrapper');
	$HTML->AddElement(new RTK_Box(null, 'hiddenlink', null,
		array(
			new RTK_Link('#kb_maincontent', 'Skip navigation links'),
			new RTK_Link('#kb_maincontent', 'Go to main content', false, array('id' => 'kb_c', 'tabindex' => '-1')),
			new RTK_Link('#kb_navigation', 'Go to menu', false, array('id' => 'kb_m', 'tabindex' => '-1')),
			new RTK_Link('#kb_rightpane', 'Go to right pane', false, array('id' => 'kb_r', 'tabindex' => '-1')),
			new RTK_Link('#kb_footer', 'Go to footer', false, array('id' => 'kb_f', 'tabindex' => '-1')),
		)
	));
	
	$HTML->AddElement(new RTK_Box('main'), 'wrapper', 'main');
	
	// - top
	$HTML->AddElement(new RTK_Box('top'), 'main', 'top');
	$HTML->AddElement(new RTK_Textview('&nbsp;', true, 'logo'));
	$HTML->AddElement(new RTK_Textview(WEBSITETITLE, true, 'sitetitle'));
	
	// - left
	$HTML->AddElement(new RTK_Box('left'), 'main', 'left');
	$HTML->AddElement(
		new RTK_Box('left-fixed', null, null,
			array(
				new RTK_Link(null, null, false, array('class' => 'hidden', 'id' => 'kb_navigation')),
				new RTK_Link('#top', 'To top', false, array('class' => 'toplink-left')),
				$menu->GetHtml()
			)
		)
	);
	
	// - content
	$HTML->AddElement(new RTK_Box('content'), 'main', 'content');
	$HTML->AddElement(new HtmlElement(), 'content', 'contenttop');
	$HTML->AddElement($breadcrumbs->GetHtml());
	$HTML->AddElement(new RTK_Link(null, null, false, array('class' => 'hidden', 'id' => 'kb_maincontent')));
	$HTML->AddElement(new HtmlElement(), 'content', 'contentmain');
	$HTML->AddElement(new HtmlElement(), 'content', 'contentbottom');
	
	// - bottom
	$HTML->AddElement(new RTK_Box('bottom'), 'main', 'bottom');
	$HTML->AddElement(new RTK_Textview('Valid and beautiful <a href="http://validator.w3.org/check?uri='.GetBaseURL().$_SERVER['REQUEST_URI'].'">HTML5</a> provided by <a href="https://github.com/iensenfirippu/reiZ">reiZ</a>', true, null, 'left'));
	$HTML->AddElement(new RTK_Textview('<!--{EXECUTIONTIME}--!> <!--{QUERYCOUNT}--!>', true, null, 'right'));
}
?>