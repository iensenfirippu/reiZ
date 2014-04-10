<?php
	$prefix = DBPREFIX;
	
	$created = null;
	
	if (isset($_POST['step2-submit']))
	{
		$prefix = reiZ::GetSafePost('step2-prefix');
		
		$oldfile = file_get_contents('../config/config.cfg.inc');
		$newfile = $oldfile;
		$haschanges = false;
		foreach(explode("\n", $oldfile) as $line)
		{
			$newline = EMPTYSTRING;
			if (strstr($line, 'DBPREFIX')) { $newline = str_replace('"'.DBPREFIX.'"', '"'.$prefix.'"', $line); }
			
			
			
			if ($newline != EMPTYSTRING) { $newfile = str_replace($line, $newline, $newfile); $haschanges = true; }
		}
		
		if ($haschanges)
		{
			if (file_put_contents('../config/config.cfg.inc', $newfile) == 0)
			{
				$footer = 'Can&apos;t overwrite config file';
				$created = false;
			}
			else
			{
				$sql = str_replace('<[!PREFIX!]>', $prefix, file_get_contents('sql/structure.sql'));
				
				if ($DB->RunNonQuery($sql))
				{
					$footer = 'Database created successfully';
					$created = true;
				}
				else
				{
					echo $sql;
				}
			}
		}
	}
	
	include('header.htm.inc');
?>
					<span>
						Next we must create the tables in the database.<br />
						Before we do this however, please choose a prefix<br />
						to prepend to the tables. (or leave blank if not desired)
					</span>
					<form method="post" action="">
						<br />
						<div class="formline">
							<h5>Prefix</h5>
							<?php echo '<input type="text" name="step2-prefix" value="'.$prefix.'" />'."\n"; ?>
						</div>
						<div class="formline">
							<h5>&nbsp;</h5>
							<?php
								if ($created === null)
									echo '<input type="submit" class="submit" name="step2-submit" value="Submit" />';
								elseif ($created === false)
									echo '<input type="submit" class="submit fail" name="step2-submit" value="Try again" />';
								elseif ($created === true)
									echo '<input type="submit" class="submit success" name="step2-submit" value="Go to step 3" />';
								echo "\n";
							?>
						</div>
					</form>
<?php include('footer.htm.inc'); ?>