<?php
/* ################ reiZ CMS #####################
############## Database Connection ###############
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
			$this->connect();
		}
		
		/*public function __destruct()
		{
			$this->disconnect();
		}*/
	
		public function RunQuery(/*query*/ $query)
		{
			//$this->connect();
			$value = mysqli_query($this->_link, $query);
			//$this->disconnect();
			
			$this->_querycount++;
			return $value;
		}
	
		public function RunNonQuery(/*query*/ $query)
		{
			$value = false;
			
			//$this->connect();
			$result = mysqli_query($this->_link, $query);
			if ($result != false) { $value = true; }
			//$this->disconnect();
			
			$this->_querycount++;
			return $value;
		}
		
		public function GetArray($result)
		{
			//if (is_resource($result)) {
				return mysqli_fetch_array($result); //}
			//else { var_dump($result); }
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
			$this->_link = new mysqli(DBSERVER, DBUSERNAME, DBPASSWORD, DBDATABASE) or die(0);
			//$this->_link = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD) or die(0);
			mysqli_set_charset($this->_link, DBENCODING);
			//mysqli_select_db($this->_link, DBDATABASE);
		}
	
		public function Disconnect()
		{
			mysqli_close($this->_link);
		}
	}
}
?>
