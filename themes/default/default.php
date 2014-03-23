<?php
/*
 * home module test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (reiZ::WasAccessedDirectly()) { reiZ::BackToDisneyland(); }
else
{
	include_once(FOLDERTHEMES.'/'.DEFAULTTHEME.'/'.FOLDERMASTER.'/wide.php');
	$HTML->AddContent(new HtmlElement('div', '', $PAGE->GetText()));
}
?>
