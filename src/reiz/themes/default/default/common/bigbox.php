<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	include_once(url_append($THEME->GetDirectory(), array('common', 'common.php')));
	
	$HTML->AddStylesheet(url_append($THEME->GetDirectory(), array('styles', 'bigbox.css')));
	
	// BODY
	$HTML->AddElement(new RTK_Box('wrapper'), 'BODY', 'wrapper');
	$HTML->AddElement(new RTK_Box('main'), 'wrapper', 'main');
	
	// - bigbox
	$HTML->AddElement(new RTK_Box('bigbox'), 'main', 'bigbox');
	
	$HTML->AddElement(new RTK_Box('bigbox-top'), 'bigbox', 'top');
	$HTML->AddElement(new RTK_Textview('&nbsp;', true, 'bigbox-logo'));
	$HTML->AddElement(new RTK_Textview(WEBSITETITLE, true, 'bigbox-title'), 'top', 'title');
	
	$HTML->AddElement(new RTK_Box('bigbox-content'), 'bigbox', 'content');
	
	$HTML->AddElement(new RTK_Box('bigbox-bottom'), 'bigbox', 'bottom');
	$HTML->AddElement(new RTK_Link(url_append(GetBaseURL(), INDEXPAGE), 'Go back to the home page'));
}
?>
