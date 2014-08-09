<?php
class HtmlForm extends HtmlElement
{
	protected $_containers;
	protected $_pointer;
	
	public function __construct($name=EMPTYSTRING, $action=EMPTYSTRING, $method='POST')
	{
		parent::__construct('form', 'name="'.$name.'" action="'.$action.'" method="'.$method.'"');
		$this->_containers = array();
	}
	
	public function AddContainer($HtmlElement, $name)
	{
		$this->AddChild($HtmlElement);
		while ($HtmlElement->HasChildren())
		{
			$HtmlElement = $HtmlElement->_children[sizeof($HtmlElement->_children) -1];
			$this->_pointer = $this->_containers[$name] = $HtmlElement;
		}
	}
	
	public function AddTextField($name, $title, $size=null, $container=null)
	{
		$field = new HtmlElement('div', 'class="formline"');
		$field->AddChild(new HtmlElement('label', 'for="'.$name.'"', $title));
		
		if ($size == null || intval($size) <= 0) {
			$field->AddChild(new HtmlElement('input', 'type="text" name="'.$name.'"'));
		} else {
			$field->AddChild(new HtmlElement('textarea', 'name="'.$name.'" rows="'.$size.'"'));
		}
		
		$this->AddToContainer($field, $container);
	}
	
	public function AddPasswordField($name, $title, $container=null)
	{
		$field = new HtmlElement('div', 'class="formline"');
		$field->AddChild(new HtmlElement('label', 'for="'.$name.'"', $title));
		$field->AddChild(new HtmlElement('input', 'type="pass" name="'.$name.'"'));
		
		$this->AddToContainer($field, $container);
	}
	
	public function AddDropDown($name, $title, $options, $selected=null, $container=null)
	{
		$dropdown = new HtmlElement('select', 'name="'.$name.'"');
		$value = EMPTYSTRING; $title = EMPTYSTRING;
		foreach ($options as $option)
		{
			if (reiZ::IsArrayLongerThan($option, 1))
			{
				$value = $option[0];
				$title = $option[1];
			}
			else
			{
				$value = $option;
				$title = $option;
			}
			
			$selectedtext = EMPTYSTRING; if ($selected == $value) { $selectedtext = 'selected '; }
			$dropdown->AddChild(new HtmlElement('option', $selectedtext.'value="'.$value.'"', $title));
		}
		
		$field = new HtmlElement('div', 'class="formline"');
		$field->AddChild(new HtmlElement('label', 'for="'.$name.'"', $title));
		$field->AddChild($dropdown);
		
		$this->AddToContainer($field, $container);
	}
	
	public function AddButton($name='submit', $title='Submit', $container=null)
	{
		$field = new HtmlElement('div', 'class="formline"');
		$field->AddChild(new HtmlElement('input', 'type="submit" name="'.$name.'" value="'.$title.'"'));
		
		$this->AddToContainer($field, $container);
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
	}
}
?>