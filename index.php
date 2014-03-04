<?php
/*
 * reiZ php OOP CMS (initially created for iensenfrippu.dk)
 * Copyright 2013 Philip Jensen <me@iensenfrippu.dk>
 */

define("STARTTIME", microtime(true));
session_start();
include_once("config/defines.php");

$endtime = round((microtime(true) - STARTTIME) * 1000);

// Only for debugging, should be removed or replaced in a later version
$HTML = str_replace('<!--{EXECUTIONTIME}--!>', 'page generated in: '.$endtime.'ms', $HTML);
echo $HTML;

// Test to serve as proof of concept for post-HTML execution
flush();
for ($i = 0; $i < 100000; $i++) { $i = $i + $i - $i; }
echo "{ { DELAYED TEXT } }";
?>
