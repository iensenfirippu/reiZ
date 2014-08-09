<?php
/* ################ reiZ CMS #####################
########### MySqli Database Connection ###########
Database class for connecting to a MySql Database
#################### v: 1.0 ################### */
// TODO: Implement prepared statements, to protect against SQL injections

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
				$value = mysqli_query($this->_link, $query);
				$this->_querycount++;
			}
			return $value;
		}
	
		public function RunNonQuery(/*query*/ $query)
		{
			$value = false;
			if ($this->IsConnected())
			{
				$result = mysqli_query($this->_link, $query);
				if ($result != false) { $value = true; }
				
				$this->_querycount++;
			}
			return $value;
		}
		
		public function GetArray($result)
		{
			if (is_a($result, 'mysqli_result')) {
				return mysqli_fetch_array($result);
			} //else { var_dump($result); }
		}
		
		/*public function GetObject($result, $class)
		{
			return mysqli_fetch_object($result, $class);
		}*/
		
		public function GetError()
		{
			return mysqli_error($this->_link);
		}
		
		private function Connect()
		{
			if (!$this->IsConnected())
			{
				$this->_link = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD, DBDATABASE);
				if (!$this->_link->connect_errno)
				{
					mysqli_set_charset($this->_link, DBENCODING);
					//$this->_connected = true;
				}
				else
				{
					if (DEBUG) { echo $this->_link->connect_error; }
					$this->_link = null;
					//$this->_connected = false;
				}
				
				//$this->_link = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD) or die(0);
				//mysqli_select_db($this->_link, DBDATABASE);
			}
		}
		
		public function Disconnect()
		{
			mysqli_close($this->_link);
		}
		
		public function CanConnect($server = DBSERVER, $username = DBUSERNAME, $password = DBPASSWORD, $database = DBDATABASE)
		{
			$value = false;
			$link = new mysqli($server, $username, $password, $database);
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
