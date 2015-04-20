<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	include_once($THEME->GetDirectory().'/'.FOLDERCOMMON.'/bigbox.php');
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/default.css');
	
	$HTML->SetPointer('content');
	// Add something?
}
?>
