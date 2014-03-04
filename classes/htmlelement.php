<?php
/*
 * HtmlElement class, for containing a nested structure of HTML elements
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

// Tags to be protected from single tag closing (<tag />)
define("NONVOIDELEMENTS", "script|a|div|textarea");
 
class HtmlElement
{
	protected $_indent = 0;
	protected $_tag = '';
	protected $_endtag = '';
	protected $_attributes = '';
	protected $_content = '';
	protected $_children = array();
	
	public function __construct($tag, $attributes = '', $content = '', $child = null)
	{
		if ($tag == 'comment' || $tag == '!--')
		{
			$this->_tag = '!--';
			$this->_endtag = '--';
		}
		else
		{
			$this->_tag = $tag;
		}
		$this->_attributes = $attributes;
		$this->_content = $content;
		if ($child !== null)
		{
			if (!is_array($child)) { $this->AddChild($child); }
			else { foreach ($child as $c) { $this->AddChild($c); } }
		}
	}
	
	public function GetIndent()		{ return $this->_indent; }
	public function GetTag()			{ return $this->_tag; }
	public function GetAttributes()	{ return $this->_attributes; }
	public function GetContent()		{ return $this->_content; }
	public function GetChildren()		{ return $this->_children; }
	
	//public function SetIndent($int)	{ $this->_indent = $int; UpdateChildren(); }
	public function SetContent($content) { $child->_content = $content; }
	
	public function AddChild($child)
	{
		$child->_indent = $this->_indent + 1;
		$child->UpdateChildren();
		array_push($this->_children, $child);
	}
	
	private function UpdateChildren()
	{
		foreach ($this->_children as $c)
		{
			$c->_indent = $this->_indent + 1;
			$c->updatechildren();
		}
	}
	
	public function __tostring()
	{
		$return = str_repeat(INDENT, $this->_indent);
		$return .= "<".$this->_tag;
		if ($this->_attributes != '') { $return .= " ".$this->_attributes; }
		if ($this->_endtag != '') { $return .= $this->_endtag.">"; }
		else
		{
			if (sizeof($this->_children) == 0)
			{
				if ($this->_content != '')
				{
					if (strstr($this->_content, "\n"))
					{
						$return .= ">".
							NEWLINE.str_repeat(INDENT, $this->_indent + 1).
							preg_replace(
								array('/[^>]\n/',	'/>\n/'),
								array('<br />'.NEWLINE,
								'>'.NEWLINE.str_repeat(INDENT, $this->_indent + 1)),
							$this->_content).
						NEWLINE.str_repeat(INDENT, $this->_indent)."</".$this->_tag.">";
					}
					else { $return .= ">".$this->_content."</".$this->_tag.">"; }
				}
				elseif (strstr(NONVOIDELEMENTS, $this->_tag)) { $return .= "></".$this->_tag.">"; }
				else { $return .= " />"; }
			}
			else
			{
				$return .= ">".NEWLINE;
				if ($this->_content != '') { $return .= str_repeat(INDENT, $this->_indent + 1).str_replace("\n", "<brlol />".NEWLINE.str_repeat(INDENT, $this->_indent), $this->_content).NEWLINE; }
				foreach ($this->_children as $c) { $return .= $c.NEWLINE; }
				$return .= str_repeat(INDENT, $this->_indent)."</".$this->_tag.">";
			}
		}
		return $return;
	}
}
?>
