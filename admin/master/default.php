<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (WasAccessedDirectly()) { BackToDisneyland(); }
else
{
	$HTML = new SimpleLayout('Iensenfirippu.dk');
	
	$HTML->AddToHead(new HtmlElement('meta', 'charset="UTF-8"'));
	$HTML->AddToHead(new HtmlElement('link',
		'rel="icon" type="image/png" href="'.URLROOT.'/'.FOLDERADMIN.'/'.FOLDERIMAGES.'/favicon.png"'));
	$HTML->AddStylesheet(FOLDERADMIN.'/'.FOLDERSTYLES.'/default.css');
	$HTML->AddToTop(new HtmlElement('span', 'id="logo"', '&nbsp;'));
	$HTML->AddToTop(new HtmlElement('h1', '', 'test.Iensenfirippu.dk'));
	$HTML->AddToLeft(
		new HtmlElement('div', 'id="left-fixed"', '',
			array(
				new HtmlElement('a', 'class="toplink-left" href="#top"', 'To top'),
				AdminMenu::GetHtml()
			)
		)
	);
	$HTML->AddToBottom(new HtmlElement('span', 'class="left"', 'Copyright Philip Jensen'));
	$HTML->AddToBottom(new HtmlElement('span', 'class="right"', '<!--{EXECUTIONTIME}--!>'));
}
?>
