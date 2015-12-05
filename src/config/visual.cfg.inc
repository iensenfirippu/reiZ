<?php
/* ################ reiZ CMS #####################
########## Default visual settings file ##########
Sets a default value for all the visual options
that wasn't set in the themes config file.
############# DON'T EDIT THIS FILE! ########### */

if (defined('reiZ') or exit(1))
{
	if (!defined("PAGINATIONLINKS"))		define("PAGINATIONLINKS",		3);			// amount of page-links in either direction
	if (!defined("PAGINATIONFIRST"))		define("PAGINATIONFIRST",		'&Lt;');	// text on button for: first page
	if (!defined("PAGINATIONLAST"))			define("PAGINATIONLAST",		'&Gt;');	// text on button for: last page
	if (!defined("PAGINATIONPREV"))			define("PAGINATIONPREV",		'&lt;');	// text on button for: previous page
	if (!defined("PAGINATIONNEXT"))			define("PAGINATIONNEXT",		'&gt;');	// text on button for: next page
	if (!defined("PAGINATIONSHOWEMPTY"))	define("PAGINATIONSHOWEMPTY",	false);		// determines weither or not to display an empty pagination 
}
?>
