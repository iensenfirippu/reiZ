<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	// HEAD
	$HTML->SetPointer('HEAD');
	$HTML->AddElement(new HtmlElement('meta', array('charset' => 'UTF-8')));
	$HTML->AddElement(new HtmlElement('meta', array('name' => 'robots', 'content' => 'noindex,noarchive,nosnippet,nofollow')));
	$HTML->AddElement(new HtmlElement('link', array('rel' => 'icon', 'type' => 'image/png',
		'href' => (new URL(array('common', 'images', 'reiz', 'favicon.png')))->GetAbsolutePath(GetBaseURL()))));
		//'rel="icon" type="image/png" href="'.URLROOT.'/'.$THEME->GetDirectory().'/'.FOLDERIMAGES.'/favicon.png"'));
	$HTML->AddStylesheet(url_append('common', array('styles', 'common.css')));
	$HTML->AddStylesheet(url_append($THEME->GetDirectory(), array('styles', 'common.css')));
}
?>
