<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	if (isset($_POST['username']) && $_POST['username'] != null) {
		reiZ::CheckLogin($_POST['username'], $_POST['password']);
	}
	
	include_once($THEME->GetDirectory().'/'.FOLDERCOMMON.'/bigbox.php');
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/default.css');
	
	/* TODO: fix title overriding
	$title = $HTML->GetReference('title');
	if ($title instanceof HtmlElement) { $title->SetContent('Login'); }*/
	
	$HTML->SetPointer('content');
	$loginform = new HtmlForm('login');
	$loginform->AddTextField('username', 'Username:');
	$loginform->AddPasswordField('password', 'Password:');
	$loginform->AddButton('submit', 'Log in');
	
	$HTML->AddElement($loginform);
}
?>
