<?php
/*#######################################################
# reiZ php OOP CMS (initially created for iensenfrippu.dk)
# Copyright 2015 Philip Jensen <me@iensenfrippu.dk>
#######################################################*/

ob_start();

define("STARTTIME", microtime(true));
define("reiZ", true);
session_start();
include_once("classes/defines.inc");

$DB = null;
$HTML = null;
$THEME = null;
$PAGE = null;
$MODULE = null;
$MODULES = null;
$ARGS = null;
$HIDDENINDEX = 0;

foreach (glob(FOLDERCLASSES."/*.cls.inc") as $classfile) { include_once($classfile); }
	
if (MAINTENANCEMODE && $_SERVER['REMOTE_ADDR'] != MAINTENANCEIP)
{
	// TODO: Change to maintenance mode specific theme
	$HTML = new HtmlDocument('Maintenance');
	$HTML->AddElement(new HtmlElement('span', EMPTYSTRING, 'This site is currently undergoing maintenance, please check back later.'));
	$HTML->GetReference('TITLE')->SetContent('test');
}
else
{
	$input_p = reiZ::GetSafeArgument(GETPAGE);
	$ARGS = explode('/', reiZ::GetSafeArgument(GETARGS));
	if (class_exists('Database') && class_exists('Query')) { $DB = new Database(); }
	
	if ($DB == null || !$DB->IsConnected())
	{
		if ($input_p == INSTALLPAGE && $_SERVER['REMOTE_ADDR'] == MAINTENANCEIP && file_exists(FOLDERINSTALL))
		{
			reiZ::Redirect('/'.FOLDERINSTALL.'/'.FOLDERINSTALL.'.php');
		}
		else
		{
			$HTML = new HtmlDocument('No database');
			$HTML->AddContent(new HtmlElement('span', EMPTYSTRING, 'Couldn&apos;t connect to database "'.DBDATABASE.'".'));
		}
	}
	else
	{
		if ($input_p == EMPTYSTRING) { reiZ::Redirect('/'.INDEXPAGE.'/'); }
		elseif ($input_p == ADMINPAGE)
		{
			if (isset($_SESSION["verysecureuserid"]) && $_SESSION["verysecureuserid"] > 0)
			{
				include_once(FOLDERCLASSES.'/administration.inc');
				$THEME = new Theme(DEFAULTTHEME, 'admin');
				$PAGE = new Administration();
				include_once($THEME->GetDirectory().'/default.php');
			}
			else
			{
				reiZ::Redirect('/'.LOGINPAGE.'/'.ADMINPAGE.'/');
			}
		}
		elseif ($input_p == ADMINPAGE || $input_p == LOGINPAGE)
		{	
			if (isset($_SESSION["verysecureuserid"])) { reiZ::BackToDisneyland(true); }
			else
			{
				// TODO: Allow login to be on any page
				$THEME = new Theme(DEFAULTTHEME, DEFAULTSITE);
				$PAGE = Page::LoadByName($input_p);
				include_once($THEME->GetDirectory().'/login.php');
			}
		}
		else
		{
			$THEME = new Theme(DEFAULTTHEME, DEFAULTSITE);
			$PAGE = Page::LoadByName($input_p);
			include_once($THEME->GetDirectory().'/'.$PAGE->GetFilenameMaster());
		}
		
		if (DEBUG)
		{
			// Only for debugging, should be removed or replaced in a later version
			$endtime = round((microtime(true) - STARTTIME) * 1000);
			$HTML = str_replace('<!--{EXECUTIONTIME}--!>', 'page generated in: '.$endtime.'ms.', $HTML);
			$HTML = str_replace('<!--{QUERYCOUNT}--!>', 'sent '.$DB->GetQueryCount().' queries to the database.', $HTML);
		}
		
		$DB->Disconnect();
	}
}

$output = ob_get_flush();
ob_clean();

// TODO: Revise final DEBUG str_replace above so that $HTML remains an HTMLDocument object, and the OB string can be properly inserted

if ($output != EMPTYSTRING) { echo "\n<br>\n<br>\n<br>\nERROR OCCURED, CHECK SOURCE LOGFILE.<br>\n"; }

echo $HTML;

if (DEBUG) { reiZ::LOGSOURCE($output.$HTML); }
?>
