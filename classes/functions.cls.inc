<?php

if (defined('reiZ') or exit(1))
{
	class reiZ
	{
		/** Redirection **/
		
		/**
		 * Redirects the page to the root of the website
		 * @param logout, if set to true will also log out the user.
		 */
		public static function BackToDisneyland($logout=false)
		{
			if ($logout) { session_destroy(); }
			reiZ::Redirect('/');
		}
		
		/**
		 * Redirects the page to the specified URL
		 * @param relative, specifies if the URL is relative to the current page.
		 */
		public static function Redirect($url, $relative=false)
		{
			if ($relative) { header('Location: '.$url); }
			else { header('Location: '.URLROOT.$url); }
			exit;
		}
		
		/** GET/POST **/
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function GetSafeArgument($id, $keephtml = false)
		{
			$return = '';
			if (isset($_GET[$id]) && !empty($_GET[$id]))
			{
				$return = reiZ::Sanitize($_GET[$id], $keephtml);
			}
			return $return;
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function GetSafePost($id, $keephtml = false)
		{
			$return = '';
			if (isset($_POST[$id]) && !empty($_POST[$id]))
			{
				$return = reiZ::Sanitize($_POST[$id], $keephtml);
			}
			return $return;
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function Sanitize($string, $keephtml = false)
		{
			$string = addslashes($string);
			if ($keephtml == false) { htmlspecialchars($string); }
			reiZ::EnforceProperLineEndings($string);
			return $string;
		}
		
		/** HTML syntax **/
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function ConvertTextToHtml($text)
		{
			return str_replace(array('\n', '\t'), array(NEWLINE, INDENT), htmlspecialchars_decode($text));
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function ConvertHtmlToText($html)
		{
			return htmlspecialchars(str_replace(array(NEWLINE, INDENT), array('\n', '\t'), $html));
		}
		
		/* Serialization */
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function Unserialize($text)
		{
			return unserialize(gzuncompress(base64_decode($text)));
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function Serialize($object)
		{
			return base64_encode(gzcompress(serialize($object)));
		}
		
		/** File methods **/
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function LOGSOURCE($source) // Write html source to file
		{
			reiZ::WriteToFile('source.txt', $source, true);
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function ERRORLOG($message) // Logs an error message to the log file
		{
			$_SESSION['MESSAGE'] = $message;
			reiZ::WriteToFile('errorlog.txt', $message);
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function WriteToFile($file, $string, $overwrite=false) // Writes a string to a file
		{
			if ($overwrite && file_exists($file)) { unlink($file); }
			$fp = fopen($file,'a');
			// NEWLINE removed because it caused NEWLINE to be inserted into output
			//fwrite($fp,$string.NEWLINE);
			fwrite($fp,$string);
			fclose($fp);
		}
		
		/** Time methods **/
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function TimestampToHumanTime($timestamp, $format = false)
		{
			// TODO: ineffective, enhance using halfing technique. (N becomes N/2)
			
			$string = EMPTYSTRING;
			
			if ($timestamp != null)
			{
				$diff = STARTTIME - $timestamp;
				
				if (is_string($format) !== false) { $string .= date($format, $timestamp); }
				elseif ($diff > (ONEDAY + TODAYSTIME)) { $string .= date('\t\h\e dS \o\f F, Y \a\t H:i', $timestamp); }
				elseif ($diff > TODAYSTIME) { $string .= 'yesterday at '.date('H:i', $timestamp); }
				elseif ($diff > ONEHOUR) { $string .= 'today at '.date('H:i', $timestamp); }
				elseif ($diff > ONEMINUTE) {
					$plural = ''; if(intval($diff / ONEMINUTE) != 1) { $plural = 's';}
					$string .= intval($diff / ONEMINUTE).' minute'.$plural.' ago';
				}
				elseif ($diff > 0) {
					$plural = ''; if($diff != 1) { $plural = 's';}
					$string .= round($diff).' second'.$plural.' ago';
				}
				elseif ($diff == 0) { $string .= 'just now!'; }
				elseif ($diff > -ONEMINUTE) {
					$plural = ''; if($diff != -1) { $plural = 's';}
					$string .= 'in '.abs($diff).' second'.$plural;
				}
				elseif ($diff > -ONEHOUR) {
					$plural = ''; if(abs(intval($diff / ONEMINUTE)) != 1) { $plural = 's';}
					$string .= 'in '.abs(intval($diff / ONEMINUTE)).' minute'.$plural;
				}
				elseif ($diff > -ONEDAY) {
					$plural = ''; if(abs(intval($diff / ONEHOUR)) != 1) { $plural = 's';}
					$string .= 'in '.abs(intval($diff / ONEHOUR)).' hour'.$plural;
				}
				else {
					$plural = ''; if(abs(intval($diff / ONEDAY)) != 1) { $plural = 's';}
					$string .= 'in '.abs(intval($diff / ONEDAY)).' day'.$plural;
				}
			}
			
			return $string;
		}
		
		/** Boolean methods **/
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function DisplayBoolean($boolean)
		{
			$value;
			if ($boolean == true || $boolean == 1 || $boolean == '1') { $value = 'true'; }
			elseif ($boolean == false || $boolean == 0 || $boolean == '0') { $value = 'false'; }
			else { $value = $boolean; }
			return $value;
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function FlipBoolean(&$boolean)
		{
			if (is_bool($boolean))
			{
				$boolean = !$boolean;
			}
		}
		
		/** reiZ specific **/
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function MakeHideId()
		{
			// TODO: research possibility for "later addition: '...WTF Past-Philip, finish your comments!'"
			
			$GLOBALS['HIDDENINDEX']++;
			$hiddenindex = $GLOBALS['HIDDENINDEX'];
			$classname = 'hiddenstuff_';
			if ($hiddenindex < 1000) { $classname .= '0'; }
			if ($hiddenindex <  100) { $classname .= '0'; }
			if ($hiddenindex <   10) { $classname .= '0'; }
			return $classname.$hiddenindex;
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function CheckLogin($username=null, $password=null)
		{
			if (isset($username) && !empty($username))
			{
				$redirect = null;
				if (reiZ::SetAndNotNull($GLOBALS['ARGS'][0])) { $redirect = $GLOBALS['ARGS'][0]; }
				reiZ::Login($username, $password, $redirect);
			}
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function Login($username, $password, $redirect=null)
		{
			$success = false;
			
			$query = new Query();
			$query->SetType('select');
			$query->AddField('u_id');
			$query->AddTable(DBPREFIX.'user');
			$query->AddCondition('username', DBOP::Is, reiZ::Sanitize($_POST['username']));
			$query->AddCondition('password', DBOP::Is, reiZ::Sanitize($_POST['password']));
			$result = $GLOBALS['DB']->RunQuery($query);
			$row = $GLOBALS['DB']->GetArray($result);
			
			if ($row['u_id'] != 0)
			{
				$success = true;
				$_SESSION["verysecureuserid"] = $row['u_id'];
			}
			
			$query = new Query();
			$query->SetType('insert');
			$query->AddTable(DBPREFIX.'login');
			$query->AddField('occured', time());
			$query->AddField('ip', reiZ::Sanitize($_SERVER['REMOTE_ADDR']));
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			{ $query->AddField('altip', reiZ::Sanitize($_SERVER['HTTP_X_FORWARDED_FOR'])); }
			$query->AddField('username', reiZ::Sanitize($_POST['username']));
			if (!$success)
			{ $query->AddField('password', reiZ::Sanitize($_POST['password'])); }
			$query->AddField('success', $success);
			//var_dump($query);
			$GLOBALS['DB']->RunNonQuery($query);
			
			if ($success)
			{
				if ($redirect == null) { reiZ::BackToDisneyland(); }
				else { reiZ::Redirect('/'.$redirect); }
			}
		}
		
		/** Math **/
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function GetFileSize($bytes)
		{
			$value = '';
			if ($bytes < 1024)
			{
				$value = $bytes.' bytes';
			}
			else if ($bytes < 1048576)
			{
				$value = number_format(($bytes / 1024), 1, ',', '').' KB';
			}
			else
			{
				$value = number_format(($bytes / 1048576), 1, ',', '').' MB';
			}
			return $value;
		}
		
		/** Variable checking **/
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function SetAndNotNull($variable, $key=null)
		{
			if ($key != null && is_array($variable))
			{
				if (array_key_exists($key, $variable)) { $variable = $variable[$key]; }
				else { $variable = null; }
			}
			return isset($variable) && $variable != null;
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function SetAndNotEmpty($variable, $key=null)
		{
			//var_dump($variable);
			if ($key != null && is_array($variable))
			{
				if (array_key_exists($key, $variable)) { $variable = $variable[$key]; }
				else { $variable = null; }
			}
			return isset($variable) && !empty($variable);
		}
		
		/** Array methods **/
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function IsArrayLongerThan($array, $size)
		{
			return is_array($array) && sizeof($array) > $size;
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function RemoveFromArray(&$array, $index)
		{
			$result = false;
			if (is_array($array) && sizeof($array) > $index)
			{
				unset($array[$index]);
				$array = array_values($array);
				$result = true;
			}
			return $result;
		}
		
		/* String methods */
		
		/**
		 * description
		 * @param haystack, string to look in
		 * @param needles, string or string[] to look for
		 */
		public static function string_in_string($haystack, $needles)
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
		public static function strstr($a,$b) { return reiZ::string_in_string($a,$b); }
		
		/**
		 * Removes a text from the beginning of another
		 * @param string, the string to look in.
		 * @param prefix, the prefix to remove.
		 */
		public static function string_remove_prefix($string, $prefix)
		{
			if(strpos($string, $prefix) === 0) { $string = substr($string, strlen($prefix)).EMPTYSTRING; }
			return $string;
		}
		public static function strrempre($a,$b) { return reiZ::string_remove_prefix($a,$b); }
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function EnforceProperLineEndings(& $string)
		{
			$improper_lineending = "\r\n";
			if (strstr($string, $improper_lineending)) { $string = str_replace($improper_lineending, NEWLINE, $string); }
			return $string;
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function StartsWithUpper($str)
		{
			$chr = mb_substr($str, 0, 1, "UTF-8");
			return mb_strtolower($chr, "UTF-8") != $chr;
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function string_is_one_of($array, $string, &$out=null)
		{
			$result = false;
			
			if (is_array($array))
			{
				$result = in_array($string, $array);
				if ($out !== null) { $out = array_search($string, $array); }
			}
			
			return $result;
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function string_contains($haystack, $needle, $match=-1, &$out=null)
		{
			$value = false;
			
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
			
			return $value;
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function string_beginswith($haystack, $needle)
		{
			return strpos($haystack, $needle) === 0;
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function string_endswith($haystack, $needle)
		{
			return strpos($haystack, $needle) === sizeof($haystack-1);
		}
		
		/**
		 * Checks if a string contains HTML tags
		 * @param string, the string to check.
		 */
		public static function string_has_tags($string)
		{
			return sizeof(preg_match_all('/(<([^>]+)>)/', $string)) > 0 ? true : false;
		}
		
		/**
		 * Strips all HTML tags from string
		 * @param string, the string to strip.
		 */
		public static function string_strip_tags($string)
		{
			return preg_replace('/(<([^>]+)>)/', EMPTYSTRING, $string);
		}
		
		/**
		 * Shortens and ellipsizes a string
		 * @param string, the string to ellipsize.
		 * @param length, the length of the new string (incl. ellipsis).
		 */
		public static function string_ellipsize($string, $length)
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
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function url_append($url, $append)
		{
			$value = $url;
			
			if (is_array($append))
			{
				$array = $append;
				$append = EMPTYSTRING;
				foreach ($array as $item)
				{
					$append = reiZ::url_append($append, $item);
				}
			}
			
			if (is_string($append))
			{
				if ($url != EMPTYSTRING && $append != EMPTYSTRING)
				{
					$slash = '/';
					$trailing = (substr($url, -1) == $slash);
					$preceding = (substr($append, 0, 1) == $slash);
					
					if ($trailing && $preceding) { $url = substr($url, 0, -1); $append = substr($append, 1); }
					elseif (!$trailing && !$preceding) { $value .= $slash; }
				}
				$value .= $append;
			}
			
			return $value;
		}
		
		/**
		 * description
		 * @param param, description.
		 */
		public static function url_lastfolder($url)
		{
			$pieces = explode(SINGLESLASH, $url);
			$last = sizeof($pieces) -1;
			$foldername = $pieces[$last];
			
			if ($foldername == EMPTYSTRING || reiZ::string_contains($foldername, SINGLEDOT))
			{
				$foldername = $pieces[$last -1];
			}
			
			return $foldername;
		}
		
		/*public static function StripMarkupFromTextArea($text)
		{
			$text = explode(NEWLINE, $text);
			foreach ($text as $line) { $line = trim($line); }
			return implode(NEWLINE, $text);
		}*/
		
		/*function IPv4To6($ip)
		{
			if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false)
			{
				if (strpos($ip, '.') > 0)
				{
					$ip = substr($ip, strrpos($ip, ':')+1);
				}
				else
				{
					//native ipv6
					return $ip;
				}
			}
			
			$is_v4 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
			if (!$is_v4) { return false; }
			
			$iparr = array_pad(explode('.', $ip), 4, 0);
			$Part7 = base_convert(($iparr[0] * 256) + $iparr[1], 10, 16);
			$Part8 = base_convert(($iparr[2] * 256) + $iparr[3], 10, 16);
			return '::ffff:'.$Part7.':'.$Part8;
		}*/
		
		/*public static function GUID($stripcurlies = false, $stripdashes = false)
		{
			$value = '';
			if (function_exists('com_create_guid'))
			{
				$value = com_create_guid();
			}
			else
			{
				mt_srand((double)microtime()*10000);
				$charid = strtoupper(md5(uniqid(rand(), true)));
				$hyphen = chr(45);
				$uuid = chr(123)
						.substr($charid, 0, 8).$hyphen
						.substr($charid, 8, 4).$hyphen
						.substr($charid,12, 4).$hyphen
						.substr($charid,16, 4).$hyphen
						.substr($charid,20,12)
						.chr(125);
				$value = $uuid;
			}
			
			if ($stripcurlies) { $value = str_replace(array('{', '}'), '', $value); }
			if ($stripdashes) { $value = str_replace('-', '', $value); }
			
			return $value;
		}*/
		
		public static function vd($var)
		{
			var_dump($var);
		}
		
		public static function vdd($var)
		{
			var_dump($var);
			die(1);
		}
		
		public static function ExecuteCommands($string)
		{
			$out = null;
			$pattern_command = '/[\s\S]?\<!--{([a-zA-Z]*)(?:\|([a-zA-Z0-9\-,]*))*}--!\>[\s\S]?/';
			//$pattern_argument = '/[\{\|]([a-zA-Z0-9\-,]*)[\|\}]/';
			$pattern_argument = '/(?:[\{\|])([a-zA-Z0-9\-,]+)/';
			preg_match_all($pattern_command, $string, $out);
			$matches = $out[0];
			
			//for ($i = 1; $i < sizeof($matches); $i++)
			foreach ($matches as $search)
			{
				$replace = EMPTYSTRING;
				
				preg_match_all($pattern_argument, $search, $out);
				if (sizeof($out) > 1)
				{
					$args = $out[1];
					$module = reiZ::SetAndNotNull($args, 0) ? $args[0] : null;
					$method = reiZ::SetAndNotNull($args, 1) ? $args[1] : null;
					$args = reiZ::SetAndNotNull($args, 2) ? array_slice($args, 2) : null;
					$result = Module::Execute($module, $method, $args);
					
					// Get first and last character
					$prev = $search[0] != '<' ? $search[0] : null;
					$indexlast = strlen($search) - 1;
					$next = $search[$indexlast] != '>' ? $search[$indexlast] : null;
					$keep = 0;
					
					if ($result !== null)
					{
						$replace = $result.EMPTYSTRING;
						
						if ($prev != null) { $keep += 1; }
						if ($next != null) { $keep += 2; }
						/*reiZ::vd($keep);
						reiZ::vd($search);
						reiZ::vd($replace);*/
					}
					else
					{
						if ($prev == NEWLINE && $next == NEWLINE) { $keep = 1; /*$search = substr($search, 1);*/ /* 2 Newlines = delete previous from search = don't replace previous char */ }
						elseif (($prev == NEWLINE || $next == NEWLINE) && ($prev == null || $next == null)) { /* Newline/null = keep both in search = replace both */ }
						else
						{
							/* otherwise: remove both values from search = don't replace either (if not already null) */
							if ($prev != null) { $keep += 1; }
							if ($next != null) { $keep += 2; }
							//if ($prev != null) { $search = substr($search, 1); }
							//if ($next != null) { $search = substr($search, 0, $indexlast -1); }
						}
					}
					
					/* otherwise: remove not null values from search = don't replace either */
					if ($keep == 2 || $keep == 3) { $search = substr($search, 0, $indexlast); }
					if ($keep == 1 || $keep == 3) { $search = substr($search, 1); }
					//if ($prev != null) { $search = substr($search, 1); }
					//if ($next != null) { $search = substr($search, 0, $indexlast -1); }
				}
				
				$string = str_replace($search, $replace, $string);
				
				//reiZ::vdd($string);
			}
			
			return $string;
		}
		
		/**
		 * Returns the amount of ms it takes to run a given function a given amount of times
		 * @param function, the function to call.
		 * @param times, the amount of times to call.
		 * @param obj, object to call the function on.
		 */
		public static function Stress($function, $times=100, $obj=null)
		{
			$result = true;
			$start = microtime(true);
			
			if ($times > 0)
			{
				if ($obj == null && function_exists($function))
				{
					for ($i = 0; $i < $times; $i++) { call_user_func($function); }
				}
				elseif ($obj != null && method_exists($obj, $function))
				{
					for ($i = 0; $i < $times; $i++) { call_user_func(array($obj, $function)); }
				}
				else { $result = false; }
			}
			else { $result = false; }
			
			if ($result) { $result = round((microtime(true) - $start) * 1000); }
			return $result;
		}
	}
	
	abstract class TimeFormat
	{
		const HumanTime	= false;
		const Date			= 'D-M-Y';
		const DateTime	= 'd-m-y H:i';
		const Time			= 'H:i';
	}
	abstract class TF extends TimeFormat {}
}
?>
