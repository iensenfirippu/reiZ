<?php
/*
 * Default theme test wide view (no right pane)
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	$HTML = new SimpleLayout('Iensenfirippu.dk');
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/wide.css');
	include_once($THEME->GetDirectory().'/'.FOLDERMASTER.'/'.FOLDERCOMMON.'/common.php');
	$HTML->AddContent(new HtmlElement('div', '', $PAGE->GetText()));
		
	foreach ($PAGE->GetModules() as $module)
	{
		include_once(FOLDERMODULES.'/'.$module[0].'/module.php');
		$MODULE->Initialize();
		foreach ($MODULE->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
		foreach ($MODULE->GetJavascripts() as $js) { $HTML->AddJavascript($js); }
		
		$HTML->AddContent($MODULE->GetHtml($module[1]));
	}
}
?>
