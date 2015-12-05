<?php
/*
 * home module test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (WasAccessedDirectly()) { BackToDisneyland(); }
else
{
	$modulelist = new HtmlElement('ul', 'class="module"');
	foreach (glob(FOLDERMODULES.'/*/') as $module) { $modulelist->AddChild(new HtmlElement('li', '', $module)); }
	
	$HTML->AddContent(new HtmlElement('h1', '', 'Modules'));
	$HTML->AddContent(new HtmlElement('span', '', 'Here you can administrate all your modules.'));
	$HTML->AddContent($modulelist);
}
?>
