<?php
/*
 * home module test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (WasAccessedDirectly()) { BackToDisneyland(); }
else
{
	$themelist = new HtmlElement('ul', 'class="themes"');
	foreach (glob(FOLDERTHEMES.'/*/') as $theme) { $themelist->AddChild(new HtmlElement('li', '', $theme)); }
	
	$HTML->AddContent(new HtmlElement('h1', '', 'Themes'));
	$HTML->AddContent(new HtmlElement('span', '', 'Here you can administrate all your themes.'));
	$HTML->AddContent($themelist);
}
?>
