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
	
	//$HTML = new HtmlDocument('Iensenfirippu.dk');
	include_once($THEME->GetDirectory().'/'.FOLDERCOMMON.'/bigbox.php');
	$HTML->AddStylesheet($THEME->GetDirectory().'/'.FOLDERSTYLES.'/default.css');
	
	$HTML->GetReference('title')->SetContent('Login');
	
	$HTML->SetPointer('content');
	$loginform = new HtmlForm('login');
	$loginform->AddTextField('username', 'Username:');
	$loginform->AddPasswordField('password', 'Password:');
	$loginform->AddButton('submit', 'Log in');
	
	$HTML->AddElement($loginform);
}
?>
