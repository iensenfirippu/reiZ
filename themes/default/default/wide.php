<?php
/*
 * Default theme test wide view (no right pane)
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	//$HTML = new HtmlDocument('Iensenfirippu.dk');
	include_once($THEME->GetDirectory().'/'.FOLDERCOMMON.'/default.php');
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/wide.css');
	
	$HTML->SetPointer('content');
	$HTML->AddElement(new HtmlElement('div', '', $PAGE->GetContent()));
		
	foreach ($PAGE->GetModules() as $module)
	{
		include_once(FOLDERMODULES.'/'.$module[0].'/module.php');
		$MODULE->Initialize();
		foreach ($MODULE->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
		foreach ($MODULE->GetJavascripts() as $js) { $HTML->AddJavascript($js); }
		
		$HTML->AddElement($MODULE->GetHtml($module[1]));
	}
}
?>
