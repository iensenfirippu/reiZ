<?php
class HtmlForm extends HtmlElement
{
	public function __construct($name=EMPTYSTRING, $action=EMPTYSTRING, $method='POST')
	{
		parent::__construct('form', 'name="'.$name.'" action="'.$action.'" method="'.$method.'"');
		$this->_containers = array();
	}
	
	public function AddText($text, $container=null)
	{
		$field = new HtmlElement('div', 'class="formtext"', $text);
		$this->AddToContainer($field, $container);
	}
	
	public function AddTextField($name, $title, $value=null, $size=null, $container=null)
	{
		$field = new HtmlElement('div', 'class="formline"');
		$field->AddChild(new HtmlElement('label', 'for="'.$name.'"', $title));
		
		if ($size == null || intval($size) <= 0) {
			$field->AddChild(new HtmlElement('input', 'type="text" name="'.$name.'" value="'.$value.'"'));
		} else {
			$field->AddChild(new HtmlElement('textarea', 'name="'.$name.'" rows="'.$size.'"', $value));
		}
		
		$this->AddToContainer($field, $container);
	}
	
	public function AddPasswordField($name, $title, $container=null)
	{
		$field = new HtmlElement('div', 'class="formline"');
		$field->AddChild(new HtmlElement('label', 'for="'.$name.'"', $title));
		$field->AddChild(new HtmlElement('input', 'type="password" name="'.$name.'"'));
		
		$this->AddToContainer($field, $container);
	}
	
	public function AddCheckBox($name, $title, $checked=false, $value='true', $text=null, $container=null)
	{
		$field = new HtmlElement('div', 'class="formline"');
		$field->AddChild(new HtmlElement('label', 'for="'.$name.'"', $title));
		
		$checkedtext = EMPTYSTRING; if ($checked) { $checkedtext = ' checked'; }
		$field->AddChild(new HtmlElement('input', 'class="checkbox" type="checkbox" name="'.$name.'" value="'.$value.'"'.$checkedtext));
		
		if ($text != null) { $field->AddChild(new HtmlElement('span', EMPTYSTRING, $text)); }
		
		$this->AddToContainer($field, $container);
	}
	
	public function AddRadioButtons($name, $title, $options, $selected=null, $container=null)
	{
		$buttons = new HtmlElement('div', 'class="radiobuttons"');
		
		$o_value = EMPTYSTRING;
		$o_title = EMPTYSTRING;
		foreach ($options as $option)
		{
			if (reiZ::IsArrayLongerThan($option, 1))
			{
				$o_value = $option[0];
				$o_title = $option[1];
			}
			else { $o_value = $o_title = $option; }
			
			$checked = EMPTYSTRING; if ($selected == $o_value) { $checked = ' checked'; }
			$buttons->AddChild(new HtmlElement('input', 'type="radio" name="'.$name.'" value="'.$o_title.'"'.$checked));
		}
		
		$field = new HtmlElement('div', 'class="formline"');
		$field->AddChild(new HtmlElement('label', 'for="'.$name.'"', $title));
		$field->AddChild($buttons);
		
		$this->AddToContainer($field, $container);
	}
	
	public function AddDropDown($name, $title, $options, $selected=null, $container=null)
	{
		$dropdown = new HtmlElement('select', 'name="'.$name.'"');
		
		$o_value = EMPTYSTRING;
		$o_title = EMPTYSTRING;
		foreach ($options as $option)
		{
			if (reiZ::IsArrayLongerThan($option, 1))
			{
				$o_value = $option[0];
				$o_title = $option[1];
			}
			else { $o_value = $o_title = $option; }
			
			$selectedtext = EMPTYSTRING; if ($selected == $o_value) { $selectedtext = 'selected '; }
			$dropdown->AddChild(new HtmlElement('option', $selectedtext.'value="'.$o_value.'"', $o_title));
		}
		
		$field = new HtmlElement('div', 'class="formline"');
		$field->AddChild(new HtmlElement('label', 'for="'.$name.'"', $title));
		$field->AddChild($dropdown);
		
		$this->AddToContainer($field, $container);
	}
	
	public function AddButton($name='submit', $title='Submit', $container=null)
	{
		$field = new HtmlElement('div', 'class="formline"');
		$field->AddChild(new HtmlElement('input', 'type="submit" class="submit" name="'.$name.'" value="'.$title.'"'));
		
		$this->AddToContainer($field, $container);
	}
	
	public function AddElement($htmlelement, $container=null)
	{
		$field = new HtmlElement('div', 'class="formline"');
		$field->AddChild($htmlelement);
		
		$this->AddToContainer($field, $container);
	}
	
	// Moved to Parent Class
	/*protected $_containers;
	protected $_pointer;
	
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
		while ($HtmlElement->HasChildren()) { $HtmlElement = $HtmlElement->_children[sizeof($HtmlElement->_children) -1]; }
		$this->_pointer = $this->_containers[$name] = $HtmlElement;
	}
	
	private function AddToContainer($HtmlElement, $container=null)
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
	}*/
}
?>