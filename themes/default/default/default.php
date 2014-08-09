<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	/*foreach ($PAGE->GetMasterPage()->GetModules() as $module)
	{
		include_once(FOLDERMODULES.'/'.$module.'/module.php');
	}*/
	
	$HTML = new HtmlPage('Iensenfirippu.dk');
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/default.css');
	include_once($THEME->GetDirectory().'/'.FOLDERCOMMON.'/default.php');
	
	// - right
	$HTML->AddElement(new HtmlElement('div', 'id="right"'), 'main', 'right', 3);
	$HTML->AddElement(new HtmlElement('div', 'id="right-fixed"', '',
		new HtmlElement('a', 'class="toplink-right" href="#top"', 'To top')), 'right', 'right-fixed');
	
	$HTML->SetPointer('content');
	$HTML->AddElement(new HtmlElement('div', '', $PAGE->GetContent()), 'content');
		
	foreach ($PAGE->GetModules() as $module)
	{
		include_once(FOLDERMODULES.'/'.$module[0].'/module.php');
		$MODULE->Initialize();
		foreach ($MODULE->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
		foreach ($MODULE->GetJavascripts() as $js) { $HTML->AddJavascript($js); }
		
		$HTML->AddElement($MODULE->GetHtml($module[1]));
		$HTML->AddElement($MODULE->GetHtml_RightPane(), 'right-fixed');
	}
}
?>
