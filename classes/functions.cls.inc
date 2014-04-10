<?php

if (defined('reiZ') or exit(1))
{
	class reiZ
	{
		/** Redirecting **/
		
		public static function BackToDisneyland($logout = false)
		{
			if ($logout) { session_destroy(); }
			reiZ::Redirect('/');
		}
		
		public static function Redirect($url, $relative = false)
		{
			if ($relative) { header('Location: '.$url); }
			else { header('Location: '.URLROOT.$url); }
			exit;
		}
		
		public static function GetSafeArgument($id, $keephtml = false)
		{
			$return = '';
			if (isset($_GET[$id]) && !empty($_GET[$id]))
			{
				$return = reiZ::Sanitize($_GET[$id], $keephtml);
			}
			return $return;
		}
		
		public static function GetSafePost($id, $keephtml = false)
		{
			$return = '';
			if (isset($_POST[$id]) && !empty($_POST[$id]))
			{
				$return = reiZ::Sanitize($_POST[$id], $keephtml);
			}
			return $return;
		}
		
		public static function Sanitize($string, $keephtml = false)
		{
			$return = addslashes($string);
			if ($keephtml == false) { htmlspecialchars($return); }
			return $return;
		}
		
		/** Toolbox **/
		
		public static function ERRORLOG($message) // Logs an error message to the log file
		{
			$_SESSION['MESSAGE'] = $message;
			$fp = fopen('errorlog.txt','a');
			fwrite($fp,$message.RNT(0));
			fclose($fp);
		}
		
		public static function TimestampToHumanTime($timestamp, $truetime = false)
		{
			// TODO: ineffective, enhance using halfing technique. (N becomes N/2)
			
			$string = '';
			$diff = STARTTIME - $timestamp;
			
			if ($truetime != '') { $string .= date('H:i', $timestamp); }
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
			
			return $string;
		}
		
		public static function DisplayBoolean($boolean)
		{
			$value;
			if ($boolean == true || $boolean == 1 || $boolean == '1') { $value = 'true'; }
			elseif ($boolean == false || $boolean == 0 || $boolean == '0') { $value = 'false'; }
			else { $value = $boolean; }
			return $value;
		}
		
		public static function MakeHideId()
		{
			// TODO: research possibility for
			
			$GLOBALS['HIDDENINDEX']++;
			$hiddenindex = $GLOBALS['HIDDENINDEX'];
			$classname = 'hiddenstuff_';
			if ($hiddenindex < 1000) { $classname .= '0'; }
			if ($hiddenindex <  100) { $classname .= '0'; }
			if ($hiddenindex <   10) { $classname .= '0'; }
			return $classname.$hiddenindex;
		}
		
		public static function GUID($stripcurlies = false, $stripdashes = false)
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
		}
		
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
		
		public static function StartsWithUpper($str)
		{
			$chr = mb_substr($str, 0, 1, "UTF-8");
			return mb_strtolower($chr, "UTF-8") != $chr;
		}
	}
}
?>
