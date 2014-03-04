<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (WasAccessedDirectly()) { BackToDisneyland(); }
else
{
	/*foreach ($PAGE->GetMasterPage()->GetModules() as $module)
	{
		include_once(FOLDERMODULES.'/'.$module.'/module.php');
	}*/
	
	$HTML = new DirectionalLayout('Iensenfirippu.dk');
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/default.css');
	include_once($THEME->GetDirectory().'/'.FOLDERMASTER.'/'.FOLDERCOMMON.'/common.php');
	$HTML->AddContent(new HtmlElement('div', '', $PAGE->GetText()));
	$HTML->AddToRight(new HtmlElement('div', 'id="right-fixed"', '',
		new HtmlElement('a', 'class="toplink-right" href="#top"', 'To top')));
		
	foreach ($PAGE->GetModules() as $module)
	{
		include_once(FOLDERMODULES.'/'.$module[0].'/module.php');
		$MODULE->Initialize();
		foreach ($MODULE->GetStylesheets() as $css) { $HTML->AddStylesheet($css); }
		foreach ($MODULE->GetJavascripts() as $js) { $HTML->AddJavascript($js); }
		
		$HTML->AddContent($MODULE->GetHtml($module[1]));
		$HTML->GetRight()->GetChildren()[0]->AddChild($MODULE->GetHtml_RightPane());
	}
}
?>
