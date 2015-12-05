<?php
	$servername = DBSERVER;
	$databasename = DBDATABASE;
	$username = DBUSERNAME;
	$password = DBPASSWORD;
	
	$connected = null;
	
	if (isset($_POST['step1-submit']))
	{
		$servername = reiZ::GetSafePost('step1-server');
		$databasename = reiZ::GetSafePost('step1-database');
		$username = reiZ::GetSafePost('step1-username');
		$password = reiZ::GetSafePost('step1-password');
		
		$connected = $GLOBALS['DB']->CanConnect($servername, $username, $password, $databasename);
		
		if ($connected)
		{
			$oldfile = file_get_contents('../config/config.cfg.inc');
			$newfile = $oldfile;
			$haschanges = false;
			foreach(explode("\n", $oldfile) as $line)
			{
				$newline = EMPTYSTRING;
				if     (strstr($line, 'DBSERVER'))   { $newline = str_replace('"'.DBSERVER.'"',   '"'.$servername.'"',   $line); }
				elseif (strstr($line, 'DBDATABASE')) { $newline = str_replace('"'.DBDATABASE.'"', '"'.$databasename.'"', $line); }
				elseif (strstr($line, 'DBUSERNAME')) { $newline = str_replace('"'.DBUSERNAME.'"', '"'.$username.'"',     $line); }
				elseif (strstr($line, 'DBPASSWORD')) { $newline = str_replace('"'.DBPASSWORD.'"', '"'.$password.'"',     $line); }
				if ($newline != EMPTYSTRING) { $newfile = str_replace($line, $newline, $newfile); $haschanges = true; }
			}
			
			if ($haschanges)
			{
				if (file_put_contents('../config/config.cfg.inc', $newfile) == 0)
				{
					$footer = 'Can&apos;t overwrite config file';
				}
				else
				{
					$footer = 'Database successfully connected';
				}
			}
		}
		else
		{
			$footer = 'Can&apos;t connect to the database';
		}
	}
	
	include('header.htm.inc');
?>
					<span>
						Before we can do anything else,<br />
						we must first set up a database connection.
					</span>
					<form method="post" action="">
						<div class="formline">
							<h5>Server</h5>
							<?php echo '<input type="text" name="step1-server" value="'.$servername.'" />'."\n"; ?>
						</div>
						<div class="formline">
							<h5>Database</h5>
							<?php echo '<input type="text" name="step1-database" value="'.$databasename.'" />'."\n"; ?>
						</div>
						<div class="formline">
							<h5>Username</h5>
							<?php echo '<input type="text" name="step1-username" value="'.$username.'" />'."\n"; ?>
						</div>
						<div class="formline">
							<h5>Password</h5>
							<?php echo '<input type="password" name="step1-password" value="'.$password.'" />'."\n"; ?>
						</div>
						<div class="formline">
							<h5>&nbsp;</h5>
							<?php
								if ($connected === null)
									echo '<input type="submit" class="submit" name="step1-submit" value="Submit" />';
								elseif ($connected === false)
									echo '<input type="submit" class="submit fail" name="step1-submit" value="Try again" />';
								elseif ($connected === true)
									echo '<input type="submit" class="submit success" name="step1-submit" value="Go to step 2" />';
								echo "\n";
							?>
						</div>
					</form>
<?php include('footer.htm.inc'); ?>