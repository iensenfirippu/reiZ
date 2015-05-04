<?php
function Test1()
{
	$result = "Checking what is fastests, using an empty string CONSTANT or \"\" (1m times).<br />\n";
	$result .= "Constants: ".reiZ::Stress("Test1_1", 1000000)."<br />\n";
	$result .= "Literals: ".reiZ::Stress("Test1_2", 1000000)."<br />\n<br />\n";
	
	$result .= "Checking what is fastests, string concatination of constant values or literal values (1m times).<br />\n";
	$result .= "Constants: ".reiZ::Stress("Test1_3", 1000000)."<br />\n";
	$result .= "Literals: ".reiZ::Stress("Test1_4", 1000000)."<br />\n<br />\n";
	
	$result .= "Checking what is fastests, array_push or array[] assigning (100k times).<br />\n";
	$result .= "array_push: ".reiZ::Stress("Test1_5", 100000)."<br />\n";
	$result .= "assign: ".reiZ::Stress("Test1_6", 100000)."<br />\n";
	
	return $result;
}

function Test1_1() { return (EMPTYSTRING == 1.2345); }					function Test1_2() { return ("" == 1.2345); }
function Test1_3() { return SINGLESLASH.SINGLEDOT; }						function Test1_4() { return "/"."."; }
function Test1_5() { return array_push($GLOBALS['array'], 1); }		function Test1_6() { return $GLOBALS['array'][] = 1; }

function Test2()
{
	$result = "Comparing array speed and memory footprint.<br />\n";
	$d = "b";
	
	$array = array();
	reiZ::Stress("Test2_2", 2, array($array, null));
	$mem = $time = $usage = null;
	unset($array); unset($mem); unset($time); unset($usage);
	
	
	
	$mem = get_memory_usage($d) - strlen($result) - strlen($d);
	$array = array();
	$time = reiZ::Stress("Test2_1", 100000, array($array, "\n"));
	$usage = get_memory_usage($d) - $mem - strlen($result) - strlen($d);
	$result .= "1) \$array[] = *: ".$time.", ".$usage.$d."<br />\n";
	unset($array); unset($mem); unset($time); unset($usage);
	
	$mem = get_memory_usage($d) - strlen($result) - strlen($d);
	$array = array();
	$time = reiZ::Stress("Test2_2", 100000, array($array, NEWLINE));
	$usage = get_memory_usage($d) - $mem - strlen($result) - strlen($d);
	$result .= "1) array_push(\$array, *): ".$time.", ".$usage.$d."<br />\n";
	unset($array); unset($mem); unset($time); unset($usage);
	
	$mem = get_memory_usage($d) - strlen($result) - strlen($d);
	$array = array();
	$time = reiZ::Stress("Test2_1", 100000, array($array, "\n"));
	$usage = get_memory_usage($d) - $mem - strlen($result) - strlen($d);
	$result .= "2) \$array[] = *: ".$time.", ".$usage.$d."<br />\n";
	unset($array); unset($mem); unset($time); unset($usage);
	
	$mem = get_memory_usage($d) - strlen($result) - strlen($d);
	$array = array();
	$time = reiZ::Stress("Test2_2", 100000, array($array, NEWLINE));
	$usage = get_memory_usage($d) - $mem - strlen($result) - strlen($d);
	$result .= "2) array_push(\$array, *): ".$time.", ".$usage.$d."<br />\n";
	unset($array); unset($mem); unset($time); unset($usage);
	
	$mem = get_memory_usage($d) - strlen($result) - strlen($d);
	$array = array();
	$time = reiZ::Stress("Test2_1", 100000, array($array, "\n"));
	$usage = get_memory_usage($d) - $mem - strlen($result) - strlen($d);
	$result .= "3) \$array[] = *: ".$time.", ".$usage.$d."<br />\n";
	unset($array); unset($mem); unset($time); unset($usage);
	
	$mem = get_memory_usage($d) - strlen($result) - strlen($d);
	$array = array();
	$time = reiZ::Stress("Test2_2", 100000, array($array, NEWLINE));
	$usage = get_memory_usage($d) - $mem - strlen($result) - strlen($d);
	$result .= "3) array_push(\$array, *): ".$time.", ".$usage.$d."<br />\n";
	unset($array); unset($mem); unset($time); unset($usage);
	
	return $result;
}

function Test2_1($args) { return $args[0] = $args[1]; }
function Test2_2($args) { return array_push($args[0], $args[1]); }

function get_memory_usage(&$d)
{
	$mem = memory_get_usage();
	
	/*if ($mem < 1024) {
		$d = "b";
	} elseif ($mem < 1048576) {
		$mem = round($mem/1024,2);
		$d = "kb";
	} else {
		$mem = round($mem/1048576,2);
		$d = "mb";
	}*/
	
	return $mem;
}

define("reiZ", true);
session_start();
include_once("classes/defines.inc");

$DB = null;
$HTML = null;
$MODULE = null;
$MODULES = null;

$array = array();

foreach (glob(FOLDERCLASSES."/*.typ.inc") as $classfile) { include_once($classfile); }
foreach (glob(FOLDERCLASSES."/*.cls.inc") as $classfile) { include_once($classfile); }

$HTML .= Test1();

echo $HTML;

?>