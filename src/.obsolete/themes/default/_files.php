<?php
/*
 * files module test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (WasAccessedDirectly()) { BackToDisneyland(); }
else
{
	include_once(FOLDERMODULES.'/files/module.php');
	$module = new FilesModule();
	foreach ($module->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
	foreach ($module->GetJavascripts() as $js) { $HTML->AddJavascript($js); }
	
	$HTML->AddContent($module->GetHtml());
	$HTML->GetRight()->GetChildren()[0]->AddChild($module->GetHtml_HiddenInfo());
}
?>
