<?php
/*
 * gallery module test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (WasAccessedDirectly()) { BackToDisneyland(); }
else
{
	include_once(FOLDERMODULES.'/gallery/module.php');
	$module = new GalleryModule();
	foreach ($module->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
	foreach ($module->GetJavascripts() as $js) { $HTML->AddJavascript($js); }
	
	$HTML->AddContent($module->GetHtml());
	$HTML->GetRight()->GetChildren()[0]->AddChild($module->GetHtml_HiddenInfo());
}
?>
