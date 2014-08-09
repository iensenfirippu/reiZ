<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	$HTML = new HtmlPage('Iensenfirippu.dk');
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/default.css');
	include_once($THEME->GetDirectory().'/'.FOLDERCOMMON.'/bigbox.php');
	
	$HTML->SetPointer('content');
	// Add something?
}
?>
