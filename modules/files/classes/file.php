<?php
/*
 * FilesFile class, for containing file objects for the files module
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
class FilesFile
{	
	protected $_hideid = '';
	protected $_name = '';
	protected $_type = '';
	protected $_url = '';
	protected $_link = '';
	protected $_title = '';
	protected $_text = '';
	
	public function __construct($url)
	{
		$pathinfo = pathinfo($url);
		
		$this->_name = $pathinfo['basename'];
		$this->_type = strtolower($pathinfo['extension']);
		$this->_url = $url;
		$this->_link = FILESDIR.'/'.$url;
		$infofile = FILESDIR.'/'.$pathinfo['dirname'].'/'.$pathinfo['filename'].'.'.$pathinfo['extension'].'.info';
		if (file_exists($infofile))
		{
			$infofile = file($infofile);
			$this->_hideid = MakeHideId();
			$this->_title = trim(preg_replace('/\s\s+/', ' ', $infofile[0]));
			$this->_text = trim(preg_replace('/\s\s+/', ' ', $infofile[1]));
		}
		else
		{
			$this->_title = $pathinfo['basename'];
		}
	}
	
	public function GetHideId()		{ return $this->_hideid;	}
	public function GetName()		{ return $this->_name;		}
	public function GetType()		{ return $this->_type;		}
	public function GetUrl()		{ return $this->_url;		}
	public function GetLink()		{ return $this->_link;		}
	public function GetTitle()		{ return $this->_title;		}
	public function GetText()		{ return $this->_text;		}
}
?>
