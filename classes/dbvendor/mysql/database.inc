<?php
/* ################ reiZ CMS #####################
########### MySql Database Connection ############
Database class for connecting to a MySql Database
#################### v: 1.0 ################### */
// TODO: needs testing and probably also several corrections

if (defined('reiZ') or exit(1))
{
	class Database
	{
		private $_querycount = 0;
		private $_link = null;
		
		public function GetQueryCount() { return $this->_querycount; }
		//public function GetLink() { return $this->_link; }
		
		public function __construct()
		{
			$this->Connect();
		}
		
		/*public function __destruct()
		{
			$this->Disconnect();
		}*/
	
		public function IsConnected()
		{
			return $this->_link != null;
		}
	
		public function RunQuery(/*query*/ $query)
		{
			$value = false;
			if ($this->IsConnected())
			{
				$value = mysql_query($this->_link, $query);
				$this->_querycount++;
			}
			return $value;
		}
	
		public function RunNonQuery(/*query*/ $query)
		{
			$value = false;
			if ($this->IsConnected())
			{
				$result = mysql_query($this->_link, $query);
				if ($result != false) { $value = true; }
				
				$this->_querycount++;
			}
			return $value;
		}
		
		public function GetArray($result)
		{
			if (is_a($result, 'mysql_result')) {
				return mysql_fetch_array($result);
			} //else { var_dump($result); }
		}
		
		/*public function GetObject($result, $class)
		{
			return mysql_fetch_object($result, $class);
		}*/
		
		public function GetError()
		{
			return mysql_error($this->_link);
		}
		
		private function Connect()
		{
			if (!$this->IsConnected())
			{
				$this->_link = mysql_connect(DBSERVER, DBUSERNAME, DBPASSWORD, DBDATABASE);
				if (!$this->_link->connect_errno)
				{
					mysql_set_charset($this->_link, DBENCODING);
					//$this->_connected = true;
				}
				else
				{
					if (DEBUG) { echo $this->_link->connect_error; }
					$this->_link = null;
					//$this->_connected = false;
				}
				
				//$this->_link = mysql_connect(DBSERVER, DBUSERNAME, DBPASSWORD) or die(0);
				//mysql_select_db($this->_link, DBDATABASE);
			}
		}
		
		public function Disconnect()
		{
			mysql_close($this->_link);
		}
		
		public function CanConnect($server = DBSERVER, $username = DBUSERNAME, $password = DBPASSWORD, $database = DBDATABASE)
		{
			$value = false;
			$link = new mysql($server, $username, $password, $database);
			if (!$link->connect_errno || $link->connect_errno == 1049) { $value = true; }
			return $value;
		}
		
		public function TableExists($tablename)
		{
			$value = false;
			if ($this->IsConnected())
			{
				$query = "SELECT count(*) AS count FROM information_schema.tables WHERE table_schema='".DBDATABASE."' AND table_name='".DBPREFIX.$tablename."'";
				$count = $this->GetArray($this->RunQuery($query))['count'];
				if ($count > 0) { $value = true; }
				
				$this->_querycount++;
			}
			return $value;
		}
	}
}
?>
