<?php
/*
 * htmlpage class, for containing HTML
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */

if (defined('reiZ') or exit(1))
{
	class HtmlDocument
	{
		//protected $_doctype = '';
		//protected $_head = null;
		//protected $_body = null;
		protected $_stylesheets = array();
		protected $_javascripts = array();
		//protected $_content = null;
		protected $_breadcrumbs = null;
		protected $_elements = array();
		protected $_references = array();
		protected $_pointer = null;
		
		public function __construct($title, $doctype='html')
		{
			array_push($this->_elements, new HtmlElement('!doctype', $doctype));
			
			$this->AddElement(new HtmlElement('html'), null, 'HTML');
			$this->AddElement(new HtmlElement('head'), 'HTML', 'HEAD');
			$this->AddElement(new HtmlElement('body'), 'HTML', 'BODY');
			
			$this->AddElement(new HtmlElement('title', null, $title), 'HEAD', 'TITLE');
			
			$this->_pointer = $this->_references['BODY'];
			
			//$this->AddStylesheet(FOLDERCOMMON.'/'.FOLDERSTYLES.'/common.css');
			//$this->_content = new htmlelement('div', 'id="content"');
		}
		
		//public function GetContent()				{ return $this->_content; }
		public function GetBreadcrumbs()			{ return $this->_breadcrumbs; }
		
		//public function SetContent($HtmlElement)	{ $this->_content = $HtmlElement; }
		
		/*public function AddStylesheet($filename)				{ array_push($this->_stylesheets, new HtmlElement('link', 'rel="stylesheet" type="text/css" href="'.URLROOT.'/'.$filename.'"')); }
		public function AddJavascript($filename)					{ array_push($this->_javascripts, new HtmlElement('script', 'src="'.URLROOT.'/'.$filename.'"')); }*/
		//public function AddStylesheet($filename, $args=null)	{ if (!reiZ::SetAndNotNull($this->_stylesheets, $filename)) { $this->_stylesheets[$filename] = new HtmlElement('link', 'rel="stylesheet" type="text/css" href="'.URLROOT.'/'.$filename.'"'); } }
		//public function AddJavascript($filename, $args=null)	{ if (!reiZ::SetAndNotNull($this->_javascripts, $filename)) { $this->_javascripts[$filename] = new HtmlElement('script', 'src="'.URLROOT.'/'.$filename.'"'); } }
		public function AddStylesheet($filename, $args=null)	{ $this->_stylesheets[$filename] = new HtmlElement('link', 'rel="stylesheet" type="text/css" href="'.URLROOT.'/'.$filename.'"'.$args); }
		public function AddJavascript($filename, $args=null)	{ $this->_javascripts[$filename] = new HtmlElement('script', 'src="'.URLROOT.'/'.$filename.'"'.$args); }
		
	/*	private function AddTo($collection, $name, $args=null)
		{
			$type = EMPTYSTRING;
			if ($args != null)
			{
				if (is_string($args))
				{
					if (!reiZ::string_beginswith($args, SINGLESPACE)) { $args = SINGLESPACE.$args; }
				}
				else { $args = null; }
			}
			
			if ($collection == $this->_javascripts)
			{
				echo 'DING!';
				$type = 'script';
				$args = 'src="'.URLROOT.'/'.$name.'"'.$args;
			}
			elseif ($collection == $this->_stylesheets)
			{
				echo 'DONG!';
				$type = 'link';
				$args = 'rel="stylesheet" type="text/css" href="'.URLROOT.'/'.$filename.'"'.$args;
			}
			
			if (!reiZ::SetAndNotNull($collection, $name))
			{
				$collection[$name] = new HtmlElement($type, $args);
			}
		}*/
		
		//public function AddToHead($HtmlElement)					{ $this->_head->AddChild($HtmlElement); }
		//public function AddToBody($HtmlElement)					{ $this->_body->AddChild($HtmlElement); }
		//public function AddContent($HtmlElement)				{ $this->_content->AddChild($HtmlElement); }
		public function SetPointer($name)			{ $this->_pointer = $this->_references[$name]; }
		
		public function AddElement($HtmlElement, $inelement=null, $registeras=null, $index=null)
		{
			if ($HtmlElement != null && is_a($HtmlElement, 'HtmlElement'))
			{
				if ($inelement != null)
				{
					if (isset($this->_references[$inelement]) && is_a($this->_references[$inelement], 'HtmlElement'))
					{
						$this->_references[$inelement]->AddChild($HtmlElement, $index);
					}
					else
					{
						// TODO: Add error reporting at some point
					}
				}
				elseif ($this->_pointer != null)
				{
					$this->_pointer->AddChild($HtmlElement);
				}
				else
				{
					array_push($this->_elements, $HtmlElement);
				}
				
				if ($registeras != null)
				{
					if (!isset($this->_references[$registeras]))
					{
						$this->_pointer = $this->_references[$registeras] = $HtmlElement;
					}
					else
					{
						// TODO: Add error reporting at some point
					}
				}
			}
		}
		
		public function GetReference($name)
		{
			if (isset($this->_references[$name]) && is_a($this->_references[$name], 'HtmlElement'))
			{
				return $this->_references[$name];
			}
			else
			{
				return false;
			}
		}
		
		/*public function AddReference($HtmlElement, $name)
		{
			if ($HtmlElement != null && is_a($HtmlElement, HtmlElement))
			{
				if ($name != null && !isset($this->_references[$name]))
				{
					$this->_references[$name] = $HtmlElement;
				}
				else
				{
					// TODO: Add error reporting at some point
				}
			}
			else
			{
				// TODO: Add error reporting at some point
			}
		}*/
		
		public function __tostring() //($includeprivate=false)
		{
			$html = EMPTYSTRING;
			$headprocessed = false;
			
			// To make sure that stylesheets are always loaded in the same order (important for some rules), sort the stylesheet collection
			// TODO: This wont always be the preferable solution so a "weight" or "importance" system might need to be implemented
			sort($this->_stylesheets);
			
			foreach ($this->_stylesheets as $stylesheet) { $this->_references['HEAD']->AddChild($stylesheet); }
			foreach ($this->_javascripts as $javascript) { $this->_references['HEAD']->AddChild($javascript); }
			$this->_stylesheets = array();
			$this->_javascripts = array();
			
			$newline = false;
			foreach ($this->_elements as $HtmlElement)
			{
				if ($newline) { $html .= OUTPUTNEWLINE; } else { $newline = true; }
				$html .= $HtmlElement;
			}
			
			return $html;
		}
		
		/* TODO: Make function to cache page html
		public function cache()
		{
			$this->__tostring(true);
			// elements should contain a "private" variable to tell if the element (and it's children) should be cached,
			// or if it should be regenerated per request
		}*/
	}
}
?>
