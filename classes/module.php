<?php
/*
 * Module class, for containing module code
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
class Module
{
	protected $_name = '';
	protected $_title = '';
	protected $_author = 0;
	protected $_version = 0;
	protected $_description = '';
	protected $_html = null;
	protected $_htmlextra = array();
	protected $_stylesheets = array();
	protected $_javascripts = array();
	
	public function __construct($name = '', $title = '', $author = 'John Doe', $version = 0.1, $description = '')
	{
		$this->_name = $name;
		$this->_title = $title;
		$this->_author = $author;
		$this->_version = $version;
		$this->_description = $description;
	}
	
	public function GetName()			{ return $this->_name;				}
	public function GetTitle()			{ return $this->_title;				}
	public function GetAuthor()			{ return $this->_author;			}
	public function GetVersion()		{ return $this->_version;			}
	public function GetDescription()	{ return $this->_description;		}
	/*public function GetHtml()			{ return $this->_html;				}
	public function GetHtmlExtra($id)	{ return $this->_htmlextra[$id];	}*/
	public function GetStylesheets()	{ return $this->_stylesheets;		}
	public function GetJavascripts()	{ return $this->_javascripts;		}
	
	public function Install()
	{
		// Do something when the module is enabled/installed
		// Like create filestructure, database tables etc.
	}
	
	public function Uninstall()
	{
		// Do something when the module is disabled/uninstalled
		// Like cleaning up filestructure, database tables etc.
	}
	
	public function isInstalled()
	{
		// TODO: Module installation management 
		return true;
	}
	
	public function GetHtml()
	{
		// Alternate Html output methods can also be defined in the child class.
		// For ease of use, please stick to this naming "GetHtml_" plus the title of that output.
		// E.G. "GetHtml_Small()", "GetHtml_GalleryView($url)" etc.
		// This will allow modules to make use of each others functionality.
		// These methods should always return a valid HtmlElement object.
		return new HtmlElement("comment", "No GetHtml() method was defined in this module");
	}
	
	public function TranslateBreadcrumb($breadcrumb)
	{
		// This method is for implenting "translation" of breadcrumbs.
		// E.G. from "prod-cat-86" to "e-books". This logic can be customized for every module.
		// If not implemented in the child class, the given breadcrumb will be unaffected.
	}
}
?>
