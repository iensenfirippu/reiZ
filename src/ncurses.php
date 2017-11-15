<?php
if (isset($_SERVER['SHELL']) && $_SERVER['SHELL'] != null) {


	function readline_terminal($prompt = '') {
		$prompt && print $prompt;
		$terminal_device = "php://stdout";
		$h = fopen($terminal_device, 'r');
		if ($h === false) {
			#throw new RuntimeException("Failed to open terminal device $terminal_device");
			return false; # probably not running in a terminal.
		}
		$line = rtrim(fgets($h),"\r\n");
		fclose($h);
		return $line;
	}
	
	
	function read_password() {
		echo "Password: ";
		system('stty -echo');
		$password = trim(fgets(STDIN));
		system('stty echo');
		// add a new line since the users CR didn't echo
		echo "\n";
	}


	function read() {
		$fp1=fopen("php://stdin", "r");
		$input=fgets($fp1, 255);
		fclose($fp1);

		return $input;
	}
	
	
	//fputs(STDOUT, "\nPlease enter your username: ");
	//stream_set_blocking(STDIN, true);
	//$user = utf8_encode(trim(fgets(STDIN)));
	//$user = read_password();
	//readline_terminal("Please enter your username: ");
exec('stty -echo; read -r mypassword; stty echo; echo $mypassword');
//fputs(STDOUT, "[".$user."]");
die();

	fputs(STDOUT, "\nPlease enter your password:\n");
	
	$input = $pass = "";
	while ($input != '\n') {
		$input = fgets(STDIN);
		$pass .= $input;
		fputs(STDOUT, "\r  \r", 4);
	}
	var_dump($pass);
	
	$pass = hash("SHA512", hash("MD5", $user.utf8_encode(trim($pass))));

	echo "\nChecking credentials...";
	var_dump($pass);
	die(0);

} else { echo "You lost the game!"; }
?>
