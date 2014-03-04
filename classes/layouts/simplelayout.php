<?php
/*
 * extends htmlpage class, for containing HTML
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
class SimpleLayout extends HtmlPage
{
	protected $_top = null;
	protected $_left = null;
	protected $_bottom = null;
	
	public function __construct($title)
	{
		parent::__construct($title);
		
		$this->_top = new HtmlElement('div', 'id="top"');
		$this->_left = new htmlelement('div', 'id="left"');
		$this->_bottom = new htmlelement('div', 'id="bottom"');
	}
	
	public function GetTop()		{ return $this->_top; }
	public function GetLeft()		{ return $this->_left; }
	public function GetBottom()		{ return $this->_bottom; }
	
	public function SetTop($HtmlElement)		{ $this->_top = $HtmlElement; }
	public function SetLeft($HtmlElement)		{ $this->_left = $HtmlElement; }
	public function SetBottom($HtmlElement)		{ $this->_bottom = $HtmlElement; }
	
	public function AddToTop($HtmlElement)		{ $this->_top->AddChild($HtmlElement); }
	public function AddToLeft($HtmlElement)		{ $this->_left->AddChild($HtmlElement); }
	public function AddToBottom($HtmlElement)	{ $this->_bottom->AddChild($HtmlElement); }
	
	public function __tostring()
	{
		$wrapper = new HtmlElement('div', 'id="wrapper"');
		$main = new HtmlElement('div', 'id="main"');
		$main->AddChild($this->_top);
		$main->AddChild($this->_left);
		$main->AddChild($this->_content);
		$main->AddChild($this->_bottom);
		$wrapper->AddChild($main);
		parent::AddTobody($wrapper);
		return parent::__tostring();
	}
}
?>
