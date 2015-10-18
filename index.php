<?php
//-----------------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		index behaviour and main entry point for reiZ CMS 
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/11
//-----------------------------------------------------------------------

// We use output buffering to ensure that no output is sent to the user until the entire requets has been completed.
// (since the only echo statement in the entire code is in the very end, the only output that this should catch, is errors and warnings)
// TODO: Catch process and present errors accordingly
ob_start();

define("STARTTIME", microtime(true)); // for calculating the processing time
define("reiZ", true); // this value is checked by all class files, to ensure that they are not being run individually 
session_start();
include_once("classes/defines.inc");

$DB = null;
$HTML = null;
$THEME = null;
$PAGE = null;
//$MODULE = null;
//$PROCESSQUEUE = new ProcessQueue();
$MODULES = array();
$ARGS = null;
$HIDDENINDEX = 0;

// include all class files
include_once("classes/include.inc");

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
	
	// if database not connected
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
		$HTML = new HtmlDocument(WEBSITETITLE);
		
		// redirect to website index if no page specified
		if ($input_p == EMPTYSTRING) { reiZ::Redirect('/'.INDEXPAGE.'/'); }
		// if admin page requested:
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
		// if login page requested:
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
		// if a specific page was requested:
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
			// TODO: Change into module functionality?
			$HTML = str_replace('<!--{EXECUTIONTIME}--!>', 'page generated in: '.$endtime.'ms.', $HTML);
			$HTML = str_replace('<!--{QUERYCOUNT}--!>', 'sent '.$DB->GetQueryCount().' queries to the database.', $HTML);
		}
		
		$DB->Disconnect();
	}
}

$output = ob_get_flush();
ob_clean();

// TODO: Revise final DEBUG str_replace above so that $HTML remains an HTMLDocument object, and the OB string can be properly inserted

if (DEBUG && $output != EMPTYSTRING) { echo "\n<br>\n<br>\n<br>\nERROR OCCURED, CHECK SOURCE LOGFILE.<br>\n"; }

//echo str_replace('"wrapper"', '"wrapper-test"', $HTML);
echo $HTML;

// TODO: implement pre- and post- processing sceduled events (to move EXECUTIONTIME and QUERYCOUNT into module functionality, and allow the gallery to generate thumbnails after the request)
//if (function_exists('fastcgi_finish_request')) { fastcgi_finish_request(); /* do whatever post-processing requests you want */ }

if (DEBUG) { reiZ::LOGSOURCE($output.$HTML); }
?>
