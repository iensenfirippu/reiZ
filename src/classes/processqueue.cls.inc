<?php
/*
 * ProcessQueue, for setting up and handling of pre- and post- processing events
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	class ProcessQueue
	{
		private $_pre = array();		// PRE	= Events that need to happen before anything else
		private $_final = array();		// FINAL	= Events that need to happen before the request is finalized and the result sent to the user
		private $_post = array();		// POST	= Events that need to happen after the request has been handled (delay creation of thumbnails etc.)
		
		public function __construct($function, $args=null)
		{
			// TODO: Devise a way to set up pre-events and run them before anything else
			/*if (file_exists(reiZ::url_append(FOLDERCONFIG, 'preevents.cfg'))
			{
				$this->_pre;
			}*/
		}
		
		public function GetPreEvents() { return $this->_pre; }
		public function GetFinalEvents() { return $this->_final; }
		public function GetPostEvents() { return $this->_post; }
		
		public function AddPreEvent($function, $args=null) { $this->_pre[] = new Process($function, $args); }
		public function AddFinalEvent($function, $args=null) { $this->_final[] = new Process($function, $args); }
		public function AddPostEvent($function, $args=null) { $this->_post[] = new Process($function, $args); }
		
		public function DoPreEvents() { foreach($this->_pre as $process) { if ($process instanceof Process) { $process->Run(); } } }
		public function DoFinalEvents() { foreach($this->_final as $process) { if ($process instanceof Process) { $process->Run(); } } }
		public function DoPostEvent() { foreach($this->_post as $process) { if ($process instanceof Process) { $process->Run(); } } }
	}
	
	class Process
	{
		private $_function = EMPTYSTRING;
		private $_args = null;
		
		public function __construct($function, $args=null)
		{
			$this->_function = $function;
			$this->_args = $args;
		}
		
		public function Run()
		{
			call_user_func($this->_function, $args);
		}
	}
}
?>