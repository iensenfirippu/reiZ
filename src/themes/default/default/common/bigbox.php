<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	include_once($THEME->GetDirectory().'/'.FOLDERCOMMON.'/common.php');
	
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/bigbox.css');
	
	// BODY
	$HTML->AddElement(new HtmlElement('div', 'id="wrapper"'), 'BODY', 'wrapper');
	$HTML->AddElement(new HtmlElement('div', 'id="main"'), 'wrapper', 'main');
	
	// - bigbox
	$HTML->AddElement(new HtmlElement('div', 'id="bigbox"'), 'main', 'bigbox');
	
	$HTML->AddElement(new HtmlElement('div', 'id="bigbox-top"'), 'bigbox', 'top');
	$HTML->AddElement(new HtmlElement('span', 'id="bigbox-logo"', '&nbsp;'));
	$HTML->AddElement(new HtmlElement('span', 'id="bigbox-title"', WEBSITETITLE), 'top', 'title');
	
	$HTML->AddElement(new HtmlElement('div', 'id="bigbox-content"'), 'bigbox', 'content');
	
	$HTML->AddElement(new HtmlElement('div', 'id="bigbox-bottom"'), 'bigbox', 'bottom');
	$HTML->AddElement(new HtmlLink(reiZ::url_append(URLROOT, INDEXPAGE.'/'), 'Go back to the home page'));
}
?>
