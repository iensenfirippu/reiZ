<?php
/*
 * htmlpage class, for containing HTML
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
class HtmlPage
{
	protected $_doctype = '';
	protected $_head = null;
	protected $_body = null;
	protected $_stylesheets = array();
	protected $_javascripts = array();
	protected $_content = null;
	protected $_breadcrumbs = null;
	
	public function __construct($title, $doctype = '<!doctype html>')
	{
		$this->_doctype = $doctype;
		$this->_head = new HtmlElement('head');
		$this->_body = new HtmlElement('body');
		$this->_head->AddChild(new HtmlElement('title', '', $title));
		$this->AddStylesheet(FOLDERCOMMON.'/'.FOLDERSTYLES.'/common.css');
		$this->_content = new htmlelement('div', 'id="content"');
	}
	
	public function GetContent()				{ return $this->_content; }
	public function GetBreadcrumbs()			{ return $this->_breadcrumbs; }
	
	//public function SetContent($HtmlElement)	{ $this->_content = $HtmlElement; }
	
	public function AddStylesheet($filename)	{ array_push($this->_stylesheets, new HtmlElement('link', 'rel="stylesheet" type="text/css" href="'.URLROOT.'/'.$filename.'"')); }
	public function AddJavascript($filename)	{ array_push($this->_javascripts, new HtmlElement('script', 'src="'.URLROOT.'/'.$filename.'"')); }
	public function AddToHead($HtmlElement)	{ $this->_head->AddChild($HtmlElement); }
	public function AddToBody($HtmlElement)	{ $this->_body->AddChild($HtmlElement); }
	public function AddContent($HtmlElement)	{ $this->_content->AddChild($HtmlElement); }
	
	public function __tostring()
	{
		$html = new HtmlElement('html');
		foreach ($this->_stylesheets as $HtmlElement) { $this->_head->AddChild($HtmlElement); }
		foreach ($this->_javascripts as $HtmlElement) { $this->_head->AddChild($HtmlElement); }
		$html->AddChild($this->_head);
		$html->AddChild($this->_body);
		
		$return  = $this->_doctype.NEWLINE;
		$return .= $html;
		return $return;
	}
}
?>
