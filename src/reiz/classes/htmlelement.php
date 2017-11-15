<?php
if (defined('reiZ') or exit(1))
{
	// Tags that must not be closed in any way
	//define("NONCLOSINGELEMENTS", "|!doctype|");
	// Tags that must NOT be closed by "/>"
	define("NONVOIDELEMENTS", "|ul|script|a|tr|td|div|textarea|article|label|");
	// Tags that MUST be closed by "/>"
	define("VOIDELEMENTS", "|area|base|br|col|command|embed|hr|img|input|keygen|link|meta|param|source|track|wbr|");
	// Tags which contents must not be altered (i.e. indented)
	define("PRESERVECONTENTS", "|textarea|");
	// Parameter keys that should not have a value assignment
	define("BOOLEANPARAMETERS", "|checked|selected|");
	
	// Determines what indent and newline to use (for one line output or not)
	if (ONELINEOUTPUT === true) {
		define("OUTPUTNEWLINE",	EMPTYSTRING);
		define("OUTPUTINDENT",	EMPTYSTRING);
	} else {
		define("OUTPUTNEWLINE",	NEWLINE);
		define("OUTPUTINDENT",	INDENT);
	}

	/**
	 * Object representing a single element in HTML
	 */
	class HtmlElement
	{
		protected $_indent = 0;
		protected $_oneline = 0;
		protected $_tag = EMPTYSTRING;
		protected $_endtag = EMPTYSTRING;
		protected $_attributes = array();
		protected $_content = EMPTYSTRING;
		protected $_parent = null;
		protected $_children = array();
		protected $_containers = array();
		protected $_pointer = null;
		protected $_stylesheets = array();
		protected $_javascripts = array();
		
		/**
		 * Get the indentation level of the tag (how many tab characters should preceed it)
		 * @return integer Returns the indentation level
		 **/
		public function GetIndent() { return $this->_indent; }
		/**
		 * Determine if the element (and all it's children) should be put on one line
		 * @return boolean Returns whether the element is oneline or not
		 **/
		public function GetOneline() { return ($this->_oneline == true); }
		/**
		 * Get the tag name (e.g. "a" "div" or "body")
		 * @return string Returns the tag name of the element
		 **/
		public function GetTag() { return $this->_tag; }
		/**
		 * Get the attributes of the element (e.g. href="/path/to.file" or style="color:blue;")
		 * @return HtmlAttributes Returns the attributes of the element
		 **/
		public function GetAttributes() { return $this->_attributes; }
		/**
		 * Get the content (non-tags) inside the element 
		 * @return string Returns The content of the element
		 **/
		public function GetContent() { return $this->_content; }
		/**
		 * Get the parent HtmlElement (or HtmlDocument) of the element 
		 * @return string Returns The the parent object of the element
		 **/
		public function GetParent() { return $this->_parent; }
		/**
		 * Get the child tags inside the element 
		 * @return HtmlElement[] Returns The child elements inside the element
		 **/
		public function GetChildren() { return $this->_children; }
		/**
		 * Checks whether the element has child elements or not 
		 * @return boolean Returns true if the element has child elements
		 **/
		public function HasChildren() { return sizeof($this->_children) > 0; }
		
		/**
		 * Set the tag name of the element
		 * @param string $value The new tag name to apply
		 **/
		public function SetTag($value) { if (is_string($value)) { $this->_tag = $value; } }
		/**
		 * Set the content of the element
		 * @param string $value The new content to apply
		 **/
		public function SetContent($value) { if (is_string($value)) { $this->_content = String_EnforceProperLineEndings($value); } }
		/**
		 * Set the content of the element
		 * @param string $value The new content to apply
		 **/
		public function SetParent($value) { if (is_a($value, 'HtmlElement') || is_a($value, 'HtmlDocument')) { $this->_parent = $value; } }
		/**
		 * Set if the element should be onelined
		 **/
		public function SetOneline() { $this->_oneline = 1; }
		
		/**
		 * Object representing a single element in HTML
		 * @param string $tag The tag name of the element
		 * @param HtmlAttributes $attributes The attributes of the element
		 * @param string $content The content of the element
		 * @param HtmlElement $child child (or children) to insert into the element
		 **/
		public function __construct($tag=null, $attributes=null, $content=null, $child=null)
		{
			if ($tag == 'comment' || $tag == '!--') {
				$this->_tag = '!--';
				$this->_endtag = '--';
			} else {
				$this->_tag = $tag;
			}
			
			$this->AddAttributes($attributes);
			
			$this->_content = String_EnforceProperLineEndings($content);
			if ($child !== null)
			{
				if (!is_array($child)) { $this->AddChild($child); }
				else { foreach ($child as $c) { $this->AddChild($c); } }
			}
		}
		
		/**
		 * Set the "pointer" of the element
		 * @param var $key The name of the "reference"d element
		 **/
		public function SetPointer($key)
		{
			if (isset($this->_containers[$key])) {
				$this->_pointer = $this->_containers[$key];
			}
		}
		
		/**
		 * Adds a list of attributes to the element
		 * @param HtmlAttributes $attributes The list of attributes to add
		 * @param bool $override Allow override if a value already exists at the specified key
		 **/
		public function AddAttributes($attributes, $override=true)
		{
			if (SetAndNotNull($attributes))
			{
				if (is_a($attributes, 'HtmlAttributes')) {
					$attributes = $attributes->GetList();
				}
			
				if (Array_LongerThan($attributes, 0)) {
					foreach ($attributes as $key => $value) {
						$this->AddAttribute($key, $value, $override);
					}
				}
			}
		}
		
		/**
		 * Adds an attribute to the element
		 * @param var $key The key in the array
		 * @param var $value The value to put into the array
		 * @param bool $override Allow override if a value already exists at the specified key
		 **/
		public function AddAttribute($key, $value, $override=true)
		{
			if ($value == null) {
				if ($override == true && array_key_exists($key, $this->_attributes)) {
					$this->RemoveAttribute($key);
				}
			} elseif ($override == true || !array_key_exists($key, $this->_list)) {
				$this->_attributes[$key] = $value;
			}
		}
		
		/**
		 * Remove an HTML attributes from the list
		 * @param var $key The key of the value to remove from the array
		 **/
		public function RemoveAttribute($key)
		{
			Array_Remove($this->_attributes, $key);
		}
		
		/**
		 * Adds a child element, and adds a reference to it's final child
		 * @param HtmlElement $HtmlElement The element to add
		 * @param string $name The name used to reference the element
		 **/
		public function AddContainer($HtmlElement, $name)
		{
			$this->AddChild($HtmlElement);
			while ($HtmlElement->HasChildren()) { $HtmlElement = end($HtmlElement->_children); }
			$this->_pointer = $this->_containers[$name] = $HtmlElement;
		}
		
		/**
		 * Adds a child element to a referenced element
		 * @param HtmlElement $HtmlElement The element to add
		 * @param string $container The name of the reference to insert into
		 **/
		protected function AddToContainer($HtmlElement, $container=null)
		{
			if ($container != null && SetAndNotNull($this->_containers[$container])) {
				$this->_containers[$container]->AddChild($HtmlElement);
			} elseif ($this->_pointer != null) {
				$this->_pointer->AddChild($HtmlElement);
			} else {
				$this->AddChild($HtmlElement);
			}
		}
		
		/**
		 * Adds a stylesheet to the HTML document via the HTML element
		 * @param string $filename The name of the file to add
		 * @param HtmlAttributes $args Allows custom html tag arguments to be specified (not recommended)
		 */
		public function AddStylesheet($filename)
		{
			if ($parent = $this->GetTopParent()) {
				$parent->AddStylesheet($filename);
			} else {
				$this->_stylesheets[] = $filename;
			}
		}
		
		/**
		 * Adds a javascript to the HTML document via the HTML element
		 * @param string $filename The name of the file to add
		 */
		public function AddJavascript($filename)
		{
			if ($parent = $this->GetTopParent()) {
				$parent->AddJavascript($filename);
			} else {
				$this->_javascripts[] = $filename;
			}
		}
		
		/**
		 * Fetches the HtmlDocument at the top of the nested element structure (if available)
		 * @return var The parent HtmlDocument or false if not yet connected to an HtmlDocument
		 **/
		public function GetParentDocument() {
			$result = $this->GetTopParent();
			return is_a($result, 'HtmlDocument') ? $result : false;
		}
		
		/**
		 * Fetches the top parent of the nested element structure
		 * @return var The top parent of either type HtmlElement or HtmlDocument
		 **/
		public function GetTopParent() {
			$result = false;
			if ($this->_parent != null) {
				$parent = $this->_parent;
				while ($result == false) {
					if (is_a($parent, 'HtmlDocument')) { $result = $parent; }
					elseif (is_a($parent, 'HtmlElement')) {
						if ($parent->_parent == null) { $result = $parent; }
						else { $parent = $parent->_parent; }
					}
				}
			}
			return $result;
		}
		
		/**
		 * Fetches the first child of the element
		 * @return HtmlElement The first child inside the element
		 **/
		public function GetFirstChild() {
			$result = false;
			if ($this->HasChildren()) {
				$result = $this->_children[0];
			}
			return $result;
		}
		
		/**
		 * Fetches the nth child of the element
		 * @param integer $n The index in the array (1-indexed)
		 * @return HtmlElement The nth child inside the element
		 **/
		public function GetNthChild($n) {
			$result = false;
			if ($n > 0 && count($this->_children) > $n) {
				$result = $this->_children[$n-1];
			}
			return $result;
		}
		
		/**
		 * Fetches the last child of the element
		 * @return HtmlElement The last child inside the element
		 **/
		public function GetLastChild() {
			$result = false;
			if ($this->HasChildren()) {
				$result = end($this->_children);
			}
			return $result;
		}
		
		/**
		 * Adds a child element
		 * @param HtmlElement $child The element to add
		 * @param integer $index (optional) The index to insert at (doesn't override but pushes the previous element at that index)
		 **/
		public function AddChild($child, $index=null)
		{
			if ($index !== null && !is_numeric($index)) { $index = null; }
			
			if (is_a($child, 'HtmlElement')) {
				$child->SetParent($this);
				$child->_indent = $this->_indent + 1;
				$child->UpdateChildren();
				
				if ($index !== null && $index >= 0 && $index < sizeof($this->_children)) {
					array_splice($this->_children, $index, 0, array($child));
				} else {
					array_push($this->_children, $child);
				}
				
				$child->MigrateJavascripts();
				$child->MigrateStylesheets();
			}
		}
		
		
		/**
		 * Adds an array of child elements
		 * @param array $children The elements to add
		 * @param integer $index (optional) The index to insert at (doesn't override but pushes the previous element at that index)
		 **/
		public function AddChildren($children, $index=null)
		{
			if ($index !== null && !is_numeric($index)) { $index = null; }
			
			if (Array_LongerThan($children, 0)) {
				foreach ($children as $child) {
					if (is_a($child, 'HtmlElement')) {
						$this->AddChild($child, $index);
						if ($index !== null) { $index++; }
					}
				}
			}
		}
		
		/**
		 * Migrate contained javascripts to the top parent, ultimately ending up in the HtmlDocument
		 **/
		public function MigrateJavascripts()
		{
			if (Array_LongerThan($this->_javascripts, 0)) {
				if ($parent = $this->GetTopParent()) {
					if (is_a($parent, 'HtmlDocument')) {
						foreach ($this->_javascripts as $script) {
							$parent->AddJavascript($script);
						}
						$this->_javascripts = array();
					} elseif (is_a($parent, 'HtmlElement')) {
						if (Array_LongerThan($parent->_javascripts, 0)) {
							foreach ($this->_javascripts as $script) { $parent->_javascripts[] = $script; }
						} else { $parent->_javascripts = $this->_javascripts; }
						$this->_javascripts = array();
					}
				}
			}
		}
		
		/**
		 * Migrate contained stylesheets to the top parent, ultimately ending up in the HtmlDocument
		 **/
		public function MigrateStylesheets()
		{
			if (Array_LongerThan($this->_stylesheets, 0)) {
				if ($parent = $this->GetTopParent()) {
					if (is_a($parent, 'HtmlDocument')) {
						foreach ($this->_stylesheets as $script) {
							$parent->AddStylesheet($script);
						}
						$this->_stylesheets = array();
					} elseif (is_a($parent, 'HtmlElement')) {
						if (Array_LongerThan($parent->_stylesheets, 0)) {
							foreach ($this->_stylesheets as $script) { $parent->_stylesheets[] = $script; }
						} else { $parent->_stylesheets = $this->_stylesheets; }
						$this->_stylesheets = array();
					}
				}
			}
		}
		
		/**
		 * Updates the indentation etc. of all child elements
		 **/
		private function UpdateChildren()
		{
			foreach ($this->_children as $c) {
				if ($c instanceof HtmlElement) {
					$c->_indent = $this->_indent + 1;
					if ($this->_tag == EMPTYSTRING) { $c->_indent = $this->_indent; }
					if ($this->_oneline > 0) { $c->_oneline = $this->_oneline + 1; }
					$c->Updatechildren();
				}
			}
		}
		
		public function __tostring()
		{
			$newline = false;
			return $this->ToString($newline);
		}
		
		/**
		 * Converts the element into an HTML string
		 * @param boolean $newline Specifies whether or not to start with a newline
		 * @return string A string containing the entire HTML structure of the element and it's children
		 **/
		public function ToString(&$newline)
		{
			$return = EMPTYSTRING;
			if ($this->_tag != null) {
				if ($newline) { $return .= OUTPUTNEWLINE; } else { $newline = true; }
				if ($this->_oneline <= 1) { $return .= str_repeat(OUTPUTINDENT, $this->_indent); }
				$return .= '<'.$this->_tag;
				if (SetAndNotNull($this->_attributes) && Array_LongerThan($this->_attributes, 0)) {
					ksort($this->_attributes);
					foreach ($this->_attributes as $key => $val) {
						if (strstr(BOOLEANPARAMETERS, '|'.$key.'|') && $val == true) {
							$return .= SINGLESPACE.$key;
						} else {
							$return .= SINGLESPACE.$key.'="'.$val.'"';
						}
					}
				}
				if ($this->_endtag != EMPTYSTRING) { $return .= $this->_endtag.">"; }
				else {
					if (sizeof($this->_children) == 0) {
						if ($this->_content != EMPTYSTRING) {
							if (strstr($this->_content, NEWLINE) && !strstr(PRESERVECONTENTS, $this->_tag)) {
								$return .= '>';
								foreach (explode(NEWLINE, $this->_content) as $line) {
									if ($line == EMPTYSTRING || $line[strlen($line) -1] != '>') { $line .= '<br />'; }
									$return .= OUTPUTNEWLINE.str_repeat(OUTPUTINDENT, $this->_indent + 1).$line;
								}
								$return .= OUTPUTNEWLINE.str_repeat(OUTPUTINDENT, $this->_indent).'</'.$this->_tag.'>';
							}
							else { $return .= '>'.$this->_content.'</'.$this->_tag.'>'; }
						}
						//elseif (strstr(NONCLOSINGELEMENTS, '|'.$this->_tag.'|')) { $return .= '>'; }
						elseif (strstr(VOIDELEMENTS, '|'.$this->_tag.'|')) { $return .= ' />'; }
						elseif (strstr(NONVOIDELEMENTS, '|'.$this->_tag.'|') || $this->_oneline) { $return .= '></'.$this->_tag.'>'; }
						else { $return .= ' />'; }
					} else {
						if ($this->_oneline) {
							$return .= '>';
							foreach ($this->_children as $c) { $return .= $c; }
							$return .= '</'.$this->_tag.'>';
						} else {
							$return .= '>'.OUTPUTNEWLINE;
							if ($this->_content != EMPTYSTRING) { $return .= str_repeat(OUTPUTINDENT, $this->_indent + 1).str_replace("\n", '<br />'.OUTPUTNEWLINE.str_repeat(OUTPUTINDENT, $this->_indent), $this->_content).OUTPUTNEWLINE; }
							if (sizeof($this->_children) > 0)
							{
								$newline = false;
								foreach ($this->_children as $c) { $return .= $c->ToString($newline); }
							}
							$return .= OUTPUTNEWLINE.str_repeat(OUTPUTINDENT, $this->_indent).'</'.$this->_tag.'>';
						}
					}
				}
			} else {
				$sizeofchildren = sizeof($this->_children);	
				if ($sizeofchildren > 0) {
					$this->UpdateChildren();
					
					// INFO: commenting this line seems to have fixed a double-linebreak issue, but may now cause a no-linebreak in some cases... stay tuned...
					//if ($newline) { $return .= OUTPUTNEWLINE; }
					
					for ($i = 0; $i < $sizeofchildren; $i++) { $return .= $this->_children[$i]->ToString($newline); }
				}
			}
			return $return;
		}
	}
}
?>