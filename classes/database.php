<?php
/************************************************** /
/ Database class for connecting to a MySql Database /
/ Created by Philip Rune Jensen @iensenfirippu.dk   /
/ Version: 1.0          Date: 2013/09/13            /
/ **************************************************/

class Database
{
	private $_querycount = 0;
	
	public function GetQueryCount() { return $_querycount; }
	
	public function __construct()
	{
    }

	public function RunQuery(/*query*/ $query)
	{
		$this->connect();
		$value = mysql_query($query);
		$this->disconnect();
		
		$this->_querycount++;
		return $value;
	}

	public function RunNonQuery(/*query*/ $query)
	{
		$value = false;
		
		$this->connect();
		$return = mysql_query($query);
		if ($return != false) { $value = true; }
		$this->disconnect();
		
		$this->_querycount++;
		return $value;
	}
	
	public function GetArray($result)
	{
		return mysql_fetch_array($result);
	}

	private function Connect()
	{
		$link = mysql_connect(DBSERVER, DBUSERNAME, DBPASSWORD) or die(0);
		mysql_set_charset(DBENCODING, $link);
		mysql_select_db(DBDATABASE);
	}

	private function Disconnect()
	{
		mysql_close();
	}
}
?>
