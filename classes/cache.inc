<?php
/*
 * Cache class, for containing cache code
 * Copyright 2015 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	class Cache
	{
		/**
		 * Converts requested page URL into a cache filename
		 **/
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