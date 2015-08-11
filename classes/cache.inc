<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		definition for caching of HTML output 
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/11
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * Contains the definition for caching HTML output
	 **/
	class Cache
	{
		/**
		 * Converts requested page URL into a cache filename
		 **
		private static function Filename($page)
		{
			return reiZ::url_append(FOLDERCACHE, str_replace(SINGLESLASH, SINGLEHYPHEN, $page).'html');
		}
		
		/**
		 * Checks if the requested page is cached
		 **/
		private static function Exists($file)
		{
			$result = false;
			if (file_exists($file)) { $result = true; }
			return $result;
		}
		
		/**
		 * Loads the requested page from cache
		 **/
		public static function Load($page)
		{
			$result = null;
			$file = Cache::Filename($page);
			if (Cache::Exists($file))
			{
				$result = file($file);
			}
			return $result;
		}
		
		/**
		 * Clears all cache files
		 **/
		public static function Clear()
		{
			
		}
		
		/**
		 * Clears all cached files for a specific page
		 **/
		public static function ClearPage()
		{
			
		}
	}
}
?>