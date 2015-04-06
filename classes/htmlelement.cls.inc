<?php
/*
 * HtmlElement class, for containing a nested structure of HTML elements
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
// TODO: Find a way to preserve the neat markup with textareas

if (defined('reiZ') or exit(1))
{
	// Tags to be protected from single tag closing (<tag />)
	define("NONCLOSINGELEMENTS", "!doctype");			// Tags that must not be closed in any way
	define("NONVOIDELEMENTS", "script|a|div|textarea");	// Tags that must not be closed by "/>"
	define("PRESERVECONTENTS", "textarea");				// Tags which contents must not be altered (i.e. indented)
	
	if (ONELINEOUTPUT) {
		define("OUTPUTNEWLINE",	EMPTYSTRING);
		define("OUTPUTINDENT",	EMPTYSTRING);
	} else {
		define("OUTPUTNEWLINE",	NEWLINE);
		define("OUTPUTINDENT",	INDENT);
	}

	class HtmlElement
	{
		protected $_indent = 0;
		protected $_oneline = false;
		protected $_tag = EMPTYSTRING;
		protected $_endtag = EMPTYSTRING;
		protected $_attributes = EMPTYSTRING;
		protected $_content = EMPTYSTRING;
		protected $_children = array();
		//protected $_container = null;
		protected $_containers = array();
		protected $_pointer = null;
		
		public function GetIndent()			{ return $this->_indent; }
		public function GetOneline()		{ return $this->_oneline; }
		public function GetTag()			{ return $this->_tag; }
		public function GetAttributes()		{ return $this->_attributes; }
		public function GetContent()		{ return $this->_content; }
		public function GetChildren()		{ return $this->_children; }
		public function HasChildren()		{ return sizeof($this->_children) > 0; }
		
		public function SetOneline($value)	{ $this->_oneline = ($value == true); }
		
		public function __construct($tag=EMPTYSTRING, $attributes=EMPTYSTRING, $content=EMPTYSTRING, $child=null)
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
			//$this->_container = $container;
		}
		
		protected static function ArgsToString($args)
		{
			$result = EMPTYSTRING;
			
			if ($args != null)
			{
				if (is_string($args))
				{
					$result = SINGLESPACE.$key.'="'.$val.'"';
				}
				if (is_array($args))
				{
					ksort($args);
					foreach ($args as $key => $val) {
						$result .= SINGLESPACE.$key.'="'.$val.'"';
					}
				}
			}
			
			return trim($result);
		}
		
		//public function SetIndent($int)	{ $this->_indent = $int; UpdateChildren(); }
		public function SetContent($content) { $this->_content = $content; }
		
		/* Added container stuff */
		
		public function SetPointer($containerindex)
		{
			if (isset($this->_containers[$containerindex]))
			{
				$this->_pointer = $this->_containers[$containerindex];
			}
		}
		
		public function AddContainer($HtmlElement, $name)
		{
			$this->AddChild($HtmlElement);
			//while ($HtmlElement->HasChildren()) { $HtmlElement = $HtmlElement->_children[sizeof($HtmlElement->_children) -1]; }
			while ($HtmlElement->HasChildren()) { $HtmlElement = end($HtmlElement->_children); }
			$this->_pointer = $this->_containers[$name] = $HtmlElement;
		}
		
		protected function AddToContainer($HtmlElement, $container=null)
		{
			if ($container != null && reiZ::SetAndNotNull($this->_containers[$container]))
			{
				$this->_containers[$container]->AddChild($HtmlElement);
			}
			elseif ($this->_pointer != null)
			{
				$this->_pointer->AddChild($HtmlElement);
			}
			else
			{
				$this->AddChild($HtmlElement);
			}
		}
		
		/* Added Container stuff */
		
		public function AddChild($child, $index=null)
		{
			$child->_indent = $this->_indent + 1;
			$child->UpdateChildren();
			
			if ($index !== null && $index >= 0 && $index < sizeof($this->_children))
			{
				array_splice($this->_children, $index, 0, array($child));
			}
			else
			{
				array_push($this->_children, $child);
			}
		}
		
		private function UpdateChildren()
		{
			foreach ($this->_children as $c)
			{
				//if (is_a($c, 'HtmlElement'))
				//{
					$c->_indent = $this->_indent + 1;
					if ($this->_tag == EMPTYSTRING) { $c->_indent = $this->_indent; }
					$c->Updatechildren();
				//}
			}
		}
		
		public function __tostring()
		{
			$return = EMPTYSTRING;
			if ($this->_tag != EMPTYSTRING)
			{
				$return = str_repeat(OUTPUTINDENT, $this->_indent);
				$return .= "<".$this->_tag;
				if ($this->_attributes != EMPTYSTRING) { $return .= " ".$this->_attributes; }
				if ($this->_endtag != EMPTYSTRING) { $return .= $this->_endtag.">"; }
				else
				{
					if (sizeof($this->_children) == 0)
					{
						if ($this->_content != EMPTYSTRING)
						{
							if (strstr($this->_content, NEWLINE) && !strstr(PRESERVECONTENTS, $this->_tag))
							{
								$search = array('/([^>])\n/', '/>\n/');
								$replace = array('$1<br />'.OUTPUTNEWLINE, '>'.OUTPUTNEWLINE.str_repeat(OUTPUTINDENT, $this->_indent + 1));
								
								$return .= ">".OUTPUTNEWLINE.str_repeat(OUTPUTINDENT, $this->_indent + 1).
									preg_replace($search, $replace, reiZ::EnforceProperLineEndings($this->_content)).
									OUTPUTNEWLINE.str_repeat(OUTPUTINDENT, $this->_indent)."</".$this->_tag.">";
							}
							else { $return .= ">".$this->_content."</".$this->_tag.">"; }
						}
						elseif (strstr(NONCLOSINGELEMENTS, $this->_tag)) { $return .= ">"; }
						elseif (strstr(NONVOIDELEMENTS, $this->_tag) || $this->_oneline) { $return .= "></".$this->_tag.">"; }
						else { $return .= " />"; }
					}
					else
					{
						if ($this->_oneline)
						{
							$return .= ">";
							foreach ($this->_children as $c) { $return .= $c; }
							$return .= "</".$this->_tag.">";
						}
						else
						{
							$return .= ">".OUTPUTNEWLINE;
							if ($this->_content != EMPTYSTRING) { $return .= str_repeat(OUTPUTINDENT, $this->_indent + 1).str_replace("\n", "<br />".OUTPUTNEWLINE.str_repeat(OUTPUTINDENT, $this->_indent), $this->_content).OUTPUTNEWLINE; }
							foreach ($this->_children as $c) { $return .= $c.OUTPUTNEWLINE; }
							$return .= str_repeat(OUTPUTINDENT, $this->_indent)."</".$this->_tag.">";
						}
					}
				}
			}
			else
			{
				$sizeofchildren = sizeof($this->_children);
				for ($i = 0; $i < $sizeofchildren; $i++)
				{
					$return .= $this->_children[$i];
					if ($i != $sizeofchildren -1) { $return .= OUTPUTNEWLINE; }
				}
			}
			return $return;
		}
	}
	
	// Include all predefined elements (for shorthand inclusion in theming)
	foreach(glob(FOLDERCLASSES.'/elements/*.inc') as $classfile) { include_once($classfile); }
}
?>
