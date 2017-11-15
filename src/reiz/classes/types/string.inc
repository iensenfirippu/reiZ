<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		Extention of basic PHP functionality
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/10
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * description
	 * @param param, description.
	 */
	function String_Sanitize($string, $keephtml = false)
	{
		$string = addslashes($string);
		if ($keephtml == false) { htmlspecialchars($string); }
		String_EnforceProperLineEndings($string);
		return $string;
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function String_DecodeHTML($text)
	{
		return str_replace(array('\n', '\t'), array(NEWLINE, INDENT), htmlspecialchars_decode($text));
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function String_EncodeHTML($html)
	{
		return htmlspecialchars(str_replace(array(NEWLINE, INDENT), array('\n', '\t'), $html));
	}
	
	/**
	 * description
	 * @param haystack, string to look in
	 * @param needles, string or string[] to look for
	 */
	function String_InString($haystack, $needles)
	{
		$result = false;
		
		if (is_string($needles)) { $result = strstr($haystack, $needles); }
		elseif (is_array($needles))
		{
			$i = 0;
			while ($result == false && $i < sizeof($needles))
			{
				if (is_string($needles[$i]) && strstr($haystack, $needles[$i])) { $result = true; }
				$i++;
			}
		}
		
		return $result;
	}
	
	/**
	 * Removes a text from the beginning of another
	 * @param string, the string to look in.
	 * @param prefix, the prefix to remove.
	 */
	function String_RemovePrefix($string, $prefix)
	{
		if (strpos($string, $prefix) === 0) { $string = substr($string, strlen($prefix)).EMPTYSTRING; }
		return $string;
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function String_EnforceProperLineEndings(& $string)
	{
		$improper_lineending = "\r\n";
		if (strstr($string, $improper_lineending)) { $string = str_replace($improper_lineending, NEWLINE, $string); }
		return $string;
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function String_StartsWithUpper($str)
	{
		$chr = mb_substr($str, 0, 1, "UTF-8");
		return mb_strtolower($chr, "UTF-8") != $chr;
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function String_Contains($haystack, $needle, $match=-1, &$out=null)
	{
		$value = false;
		
		if (is_string($haystack)) {
			if (is_array($needle))
			{
				$matches = 0;
				//foreach ($needle as $n)
				for ($i = 0; $i < sizeof($needle); $i++)
				{
					$n = $needle[$i];
					if (strpos($haystack, $n) !== false)
					{
						$matches++;
						if ($matches == 1) $out = $i;
					}
				}
				
				// IF match=-1(all), and all needles matched...
				if ($match == -1 && $matches == sizeof($needle)) $value = true;
				// OR match=0(none), and no matches...
				elseif ($match == 0 && $matches == 0) $value = true;
				// OR match=x(at least x), and more than x matches...
				elseif ($match > 0 && $matches >= $match) $value = true;
				// ...return true
			}
			else
			{
				$value = ($out = strpos($haystack, $needle)) !== false;
			}
		}
		
		return $value;
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function String_StartsWith($haystack, $needle)
	{
		$result = false;
		if (is_string($haystack) && is_string($needle)) {
			$result = strpos($haystack, $needle) === 0;
		}
		return $result;
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function String_EndsWith($haystack, $needle)
	{
		$result = false;
		if (is_string($haystack) && is_string($needle)) {
			$result = strrpos($haystack, $needle) === (strlen($haystack)-strlen($needle));
		}
		return $result;
	}
	
	/**
	 * Checks if a string contains HTML tags
	 * @param string, the string to check.
	 */
	function String_ContainsHTML($string)
	{
		return sizeof(preg_match_all('/(<([^>]+)>)/', $string)) > 0 ? true : false;
	}
	
	/**
	 * Strips all HTML tags from string
	 * @param string, the string to strip.
	 */
	function String_StripHTML($string)
	{
		return preg_replace('/(<([^>]+)>)/', EMPTYSTRING, $string);
	}
	
	/**
	 * Shortens and ellipsizes a string
	 * @param string, the string to ellipsize.
	 * @param length, the length of the new string (incl. ellipsis).
	 */
	function String_Ellipsize($string, $length)
	{
		// TODO: consider making a contant ellipsize string ("...")
		$value = $string;
		if (is_string($string) && strlen($string) > $length)
		{
			$ellipsis = '...';
			$tmp = substr($string, 0, $length - sizeof($ellipsis));
			$value = substr($tmp, 0, strrpos($tmp, SINGLESPACE)).$ellipsis;
		}
		return $value;
	}
}
?>
