<?php
/*#######################################################
# Install script for reiZ CMS
# Copyright 2013 Philip Jensen <me@iensenfrippu.dk>
#######################################################*/

//error_reporting(E_ALL);
//ini_set('display_errors', '1');
	
define("reiZ", true);
include_once("../classes/constants.inc");
include_once('../'.FOLDERCONFIG.'/config.cfg.inc');
include_once('../'.FOLDERCONFIG.'/default.cfg.inc');
include_once('../'.FOLDERCLASSES.'/database.cls.inc');
include_once('../'.FOLDERCLASSES.'/query.clphps.inc');
include_once('../'.FOLDERCLASSES.'/functions.cls.inc');

if ($_SERVER['REMOTE_ADDR'] == MAINTENANCEIP or exit(1))
{
	$DB = null;
	$HTML = null;
	
	$stepnumber = 0;
	$title = EMPTYSTRING;
	$footer = EMPTYSTRING;

	$DB = new Database();
	
	if (!$DB->CanConnect()) // Step 1 // TODO: should be !CompletedStep1
	{
		$stepnumber = 1;
		$title = "Database";
		$footer = "Please enter your database login";
		include('step1.htm.inc');
	}
	elseif (!$DB->TableExists('page')) // Step 2 // TODO: should be !CompletedStep2
	{
		$stepnumber = 2;
		$title = "Tables";
		$footer = "Please enter a prefix";
		include('step2.htm.inc');
	}
	else // Step 3
	{
		$stepnumber = 3;
		$title = "Cleanup";
		$footer = "Thank you for choosing reiZ";
		include('step3.htm.inc');
	}
	
}
else { reiZ::BackToDisneyland(); }
?>