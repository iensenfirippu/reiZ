<?php
/*#######################################################
# reiZ php OOP CMS (initially created for iensenfrippu.dk)
# Copyright 2013 Philip Jensen <me@iensenfrippu.dk>
#######################################################*/

define("STARTTIME", microtime(true));
define("reiZ", true);
session_start();
include_once("config/defines.inc");

$DB = null;
$HTML = null;
$THEME = null;
$PAGE = null;
$MODULE = null;
$MODULES = null;
$ARGS = null;
$HIDDENINDEX = 0;

foreach (glob(FOLDERCLASSES."/*.cls.inc") as $classfile) { include_once($classfile); }
foreach (glob(FOLDERCLASSES."/*/*.cls.inc") as $classfile) { include_once($classfile); }

if (MAINTENANCEMODE && $_SERVER['REMOTE_ADDR'] != MAINTENANCEIP)
{
	$HTML = new DirectionalLayout('Maintenance');
	$HTML->AddContent(new HtmlElement('span', '', 'This site is currently undergoing maintenance, please check back later.'));
}
else
{
	$input_p = reiZ::GetSafeArgument(GETPAGE);
	$ARGS = explode('/', reiZ::GetSafeArgument(GETARGS));
	$DB = new Database();
	if ($input_p == '') { reiZ::Redirect('/'.INDEXPAGE.'/'); }
	elseif ($input_p == ADMINPAGE && isset($_SESSION["verysecureuserid"]) && $_SESSION["verysecureuserid"] > 0)
	{
		include_once(FOLDERADMIN.'/admin.php');
	}
	elseif ($input_p == LOGINPAGE)
	{	
		if (isset($_SESSION["verysecureuserid"])) { reiZ::BackToDisneyland(true); }
		else
		{
			$THEME = new Theme(DEFAULTTHEME);
			$PAGE = Page::LoadByName($input_p);
			include_once($THEME->GetDirectory().'/login.php');
		}
	}
	else
	{
		$THEME = new Theme(DEFAULTTHEME);
		$PAGE = Page::LoadByName($input_p);
		include_once($THEME->GetDirectory().'/'.FOLDERMASTER.'/'.$PAGE->GetMasterPage().'.php');
		//include_once($THEME->GetDirectory().'/'.$PAGE->GetFilename());
	}
}


if (DEBUG)
{
	// Only for debugging, should be removed or replaced in a later version
	$endtime = round((microtime(true) - STARTTIME) * 1000);
	$HTML = str_replace('<!--{EXECUTIONTIME}--!>', 'page generated in: '.$endtime.'ms.', $HTML);
	$HTML = str_replace('<!--{QUERYCOUNT}--!>', 'sent '.$DB->GetQueryCount().' queries to the database.', $HTML);
}

$DB->Disconnect();
echo $HTML;
?>
