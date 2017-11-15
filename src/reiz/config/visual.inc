<?php
/* ################ reiZ CMS #####################
########## Default visual settings file ##########
Sets a default value for all the visual options
that wasn't set in the themes config file.
############# DON'T EDIT THIS FILE! ########### */

if (defined('reiZ') or exit(1))
{
	if (!defined("WIDGET_STYLE"))         define("WIDGET_STYLE",        'default'); // options include: default, flat, oldschool
	
	if (!defined("PAGINATION_LINKS"))     define("PAGINATION_LINKS",     3);     // amount of page-links in either direction
	if (!defined("PAGINATION_FIRST"))     define("PAGINATION_FIRST",    '&Lt;'); // text on button for: first page
	if (!defined("PAGINATION_LAST"))      define("PAGINATION_LAST",     '&Gt;'); // text on button for: last page
	if (!defined("PAGINATION_PREV"))      define("PAGINATION_PREV",     '&lt;'); // text on button for: previous page
	if (!defined("PAGINATION_NEXT"))      define("PAGINATION_NEXT",     '&gt;'); // text on button for: next page
	if (!defined("PAGINATION_SHOWEMPTY")) define("PAGINATION_SHOWEMPTY", false); // determines weither or not to display an empty pagination
	
	/* THEME OPTIONS */
	if (!defined("KEYBOARDSHORTCUTS")) define("KEYBOARDSHORTCUTS", true);
	if (!defined("ALLOWMOUSE"))        define("ALLOWMOUSE",        true);
	if (!defined("RESPONSIVE"))        define("RESPONSIVE",        true);
	if (!defined("ITEMSPERPAGE"))      define("ITEMSPERPAGE",      25);
}
?>
