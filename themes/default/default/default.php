<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	include_once($THEME->GetDirectory().'/'.FOLDERCOMMON.'/default.php');
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/default.css');
	
	// - right
	$HTML->AddElement(new HtmlElement('div', 'id="right"'), 'main', 'right', 3);
	$HTML->AddElement(new HtmlElement('div', 'id="right-fixed"', EMPTYSTRING,
		new HtmlElement('a', 'class="toplink-right" href="#top"', 'To top')), 'right', 'right-fixed');
	
	$HTML->SetPointer('content');
	$HTML->AddElement(new HtmlElement('div', EMPTYSTRING, $PAGE->GetContent()), 'contentmain');
	
	$HTML->AddElement($PAGE->GetModule()->GetHtml('rightpane'), 'right-fixed');
}
?>
