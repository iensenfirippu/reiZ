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
	* Returns true if the client is connecting via HTTPS, otherwise it returns false.
	*/
	function HasHttps()
	{
		return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on");
	}
	
	/**
	 * Returns the baseurl for the website.
	 * @param boolean $forcehttps Specify if the link has to have https
	 */
	function GetBaseURL($forcehttps=false)
	{
		return (($forcehttps || USEHTTPS)?'https://':'http://').WEBSITEROOT.SINGLESLASH;
	}
	
	/**
	 * Returns true if the client that is connecting is the server itself.
	 */
	function ClientIsServer() {
		return (Array_Contains(array('127.0.0.1','::1'), $_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']));
	}
	
	/**
	 * Creates a Security token to secure a form against XSRF.
	 */
	function CreateSecurityToken($formname)
	{
		$formname .= '_securitytoken';
		$token = md5(utf8_encode(uniqid().uniqid()));
		return $_SESSION[$formname] = $token;
	}
	
	/**
	 * Checks a Security token against the token stored in the users session.
	 */
	function CheckSecurityToken($formname)
	{
		$result = false;
		$formname .= '_securitytoken';
		if (SetAndNotNull($_POST, $formname)) {
			if (SetAndNotNull($_SESSION, $formname)) {
				$result = SetAndEqualTo($_POST[$formname], $_SESSION[$formname]);
			}
		}
		return $result;
	}
		
	/** Redirection **/
	
	/**
	 * Redirects the page to the root of the website
	 * @param logout, if set to true will also log out the user.
	 */
	function RedirectToHome($logout=false)
	{
		if ($logout) { session_destroy(); }
		echo "uhm... sorry... I was supposed to redirect to home...";
		//Redirect('/');
	}
	
	/**
	 * Redirects the page to the specified URL
	 * @param relative, specifies if the URL is relative to the current page.
	 */
	function Redirect($url, $relative=false)
	{
		$url = new URL($url);
		if ($relative) { header('Location: '.$url); }
		else { header('Location: '.$url->GetAbsolutePath(GetBaseURL())); }
		exit;
	}
	
	/** GET/POST **/
	
	/**
	 * description
	 * @param param, description.
	 */
	function GetSafeArgument($id, $keephtml = false)
	{
		$return = '';
		if (isset($_GET[$id]) && !empty($_GET[$id]))
		{
			$return = String_Sanitize($_GET[$id], $keephtml);
		}
		return $return;
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function GetSafePost($id, $keephtml = false)
	{
		$return = '';
		if (isset($_POST[$id]) && !empty($_POST[$id]))
		{
			$return = String_Sanitize($_POST[$id], $keephtml);
		}
		return $return;
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function MakeHideId()
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
	function CheckLogin($username=null, $password=null)
	{
		if (isset($username) && !empty($username))
		{
			$redirect = null;
			if (SetAndNotNull($GLOBALS['ARGS'][0])) { $redirect = $GLOBALS['ARGS'][0]; }
			Login($username, $password, $redirect);
		}
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function Login($username, $password, $redirect=null)
	{
		$success = false;
		
		$query = new Query();
		$query->SetType('select');
		$query->AddField('u_id');
		$query->AddTable(DBPREFIX.'user');
		$query->AddCondition('username', DBOP::Is, String_Sanitize($_POST['username']));
		$query->AddCondition('password', DBOP::Is, String_Sanitize($_POST['password']));
		$result = $GLOBALS['DB']->RunQuery($query);
					
		if (Array_LongerThan($result, 0)) {
			$result = $result[0];
			if (SetAndNotNull($result, 'u_id')) {
				$result = intval($result['u_id']);
				if ($result > 0) {
					$success = true;
					$_SESSION["verysecureuserid"] = $result;
				}
			}
		}
		
		$query = new Query();
		$query->SetType('insert');
		$query->AddTable(DBPREFIX.'login');
		$query->AddField('occured', time());
		$query->AddField('ip', String_Sanitize($_SERVER['REMOTE_ADDR']));
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{ $query->AddField('altip', String_Sanitize($_SERVER['HTTP_X_FORWARDED_FOR'])); }
		$query->AddField('username', String_Sanitize($_POST['username']));
		if (!$success)
		{ $query->AddField('password', String_Sanitize($_POST['password'])); }
		$query->AddField('success', $success);
		
		$GLOBALS['DB']->RunNonQuery($query);
		
		if ($success)
		{
			if ($redirect == null) { RedirectTohome(); }
			else { Redirect('/'.$redirect); }
		}
	}
	
	/** Variable checking **/
	
	/**
	 * description
	 * @param param, description.
	 */
	function SetAndNotNull($variable, $key=null)
	{
		if ($key !== null && is_array($variable))
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
	function SetAndNotEmpty($variable, $key=null)
	{
		if ($key !== null && is_array($variable))
		{
			if (array_key_exists($key, $variable)) { $variable = $variable[$key]; }
			else { $variable = null; }
		}
		return isset($variable) && !empty($variable);
	}
	
	/**
	 * description
	 * @param param, description.
	 */
	function SetAndNumerical($variable, $key=null)
	{
		if ($key !== null && is_array($variable))
		{
			if (array_key_exists($key, $variable)) { $variable = $variable[$key]; }
			else { $variable = null; }
		}
		return isset($variable) && is_numeric($variable);
	}
	
	/**
	 * Alias for var_dump()
	 * @param var, the variable to dump.
	 * @param anddie, Specifies if the program should stop afterwards.
	 */
	function vd($var, $anddie=false)
	{
		echo "<pre>";
		var_dump($var);
		echo "</pre>";
		if ($anddie !== false) { die(); }
	}
	
	/**
	 * Alias for "var_dump(); die();"
	 * @param var, the variable to dump.
	 */
	function vdd($var)
	{
		vd($var, true);
	}
	
	/**
	 * Alias for "die(var_dump());"
	 * @param var, the variable to dump.
	 */
	function dvd($var)
	{
		vd($var, true);
	}
	
	function ExecuteCommands($string)
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
				$module = SetAndNotNull($args, 0) ? $args[0] : null;
				$method = SetAndNotNull($args, 1) ? $args[1] : null;
				$args = SetAndNotNull($args, 2) ? array_slice($args, 2) : null;
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
		}
		
		return $string;
	}
	
	/**
	 * Returns the amount of ms it takes to run a given function a given amount of times
	 * @param function, the function to call.
	 * @param times, the amount of times to call.
	 * @param args, the arguments to feed into the function.
	 * @param obj, object to call the function on.
	 */
	function Stress($function, $times=100, $args=null, $obj=null)
	{
		$result = true;
		$start = microtime(true);
		
		if ($times > 0)
		{
			if ($obj == null && function_exists($function))
			{
				if ($args == null)
				{
					for ($i = 0; $i < $times; $i++) { call_user_func($function); }
				}
				/*elseif (is_array($args))
				{
					for ($i = 0; $i < $times; $i++) { call_user_func($function, $args); }
				}*/
				else
				{
					for ($i = 0; $i < $times; $i++) { call_user_func($function, $args); }
				}
			}
			elseif ($obj != null && method_exists($obj, $function))
			{
				if ($args == null)
				{
					for ($i = 0; $i < $times; $i++) { call_user_func(array($obj, $function)); }
				}
				else
				{
					for ($i = 0; $i < $times; $i++) { call_user_func(array($obj, $function, $args)); }
				}
			}
			else { $result = false; }
		}
		else { $result = false; }
		
		if ($result) { $result = round((microtime(true) - $start) * 1000); }
		return $result."ms";
	}
	
	/**
	 * Tongue-in-cheek alias for require_once()
	 * @param file, The file to require_once().
	 */
	function Using($file)
	{
		require_once($file);
	}
}
?>
