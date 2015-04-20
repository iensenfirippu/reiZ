<?php
/*
 * Default theme test wide view (no right pane)
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	include_once($THEME->GetDirectory().'/'.FOLDERCOMMON.'/default.php');
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/wide.css');
	
	$HTML->SetPointer('content');
	$HTML->AddElement(new HtmlElement('div', '', $PAGE->GetContent()));
}
?>
