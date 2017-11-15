<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		mySQL database connection
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/11
//
// TODO: needs testing and probably also several corrections
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * Contains logic for connecting to the database
	 */
	class Database
	{
		private $_link = null;
		private $_querycount = 0;
		
		/**
		 * Returns the amount of queries run
		 */
		public static function GetQueryCount() { return $GLOBALS['DB']->_querycount; }
		
		/**
		 * Tries to connect to the database
		 */
		public static function Connect()
		{
			$dsn = DBVENDOR.':host='.DBSERVER.';dbname='.DBDATABASE.';charset='.DBENCODING;
			$opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
			$GLOBALS['DB']->_link = new PDO($dsn, DBUSERNAME, DBPASSWORD, $opt);
			Database::Check();
		}
		
		/**
		 * Performs a Query on the database
		 */
		private static function Execute($query)
		{
			$statment = null;
			
			try {
				$query->PrepareSQL();
				if ($query->GetSQL() != EMPTYSTRING) {
					$statement = $GLOBALS['DB']->_link->prepare($query->GetSQL());
					
					$params = $query->GetParams();
					$values = array();
					foreach ($params as $param) {
						if (is_a($param, 'Param')) {
							$values[$param->GetName()] = $param->GetValue();
						}
					}
					$statement->execute($values);
					$GLOBALS['DB']->_querycount++;
				}
			} catch (PDOException $e) {
				if (DEBUG) { $query->Debug(); }
				else { die('Failed to run database query, check log.'); }
			}
			
			return $statement;
		}
		
		/**
		 * Performs a Query on the database
		 */
		public static function RunQuery($query)
		{
			$results = array();
			try {
				$statement = Database::Execute($query);
				$results = $statement->fetchAll();
				$statement->closeCursor();
			} catch (PDOException $e) {
				if (DEBUG) { /*vdd($query);*/ }
				else { die('Failed to run database query, check log.'); }
			}
			return $results;
		}
		
		/**
		 * Performs a NonQuery on the database
		 */
		public static function RunNonQuery($query)
		{
			try {
				$statement = Database::Execute($query);
				$statement->closeCursor();
			} catch (PDOException $e) {
				if (DEBUG) { /*vdd($query);*/ }
				else { die('Failed to run database query, check log.'); }
			}
		}
		
		/**
		 * Checks if a connection to the database has been established
		 */
		public static function Check()
		{
			// TODO: Find out how to check the connection with PDO
			return true;
		}
		
		/**
		 * Disconnects from the database
		 */
		public static function Disconnect()
		{
			unset($GLOBALS['DB']->_link);
		}
	}
	
	$GLOBALS['DB'] = new Database();
	Database::Connect();
}
?>
