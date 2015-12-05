<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		HTML dropdown definition
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/11
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * Contains the definition of a dropdown selector (combobox) in HTML
	 **/
	class HtmlDropDown extends HtmlElement
	{
		public function __construct($name, $options, $selected=null, $args=null)
		{
			if ($args == null || !is_array($args)) { $args = array(); }
			$args['name'] = $name;
			
			parent::__construct('select', HtmlElement::ArgsToString($args));
			
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
				
				$optionargs = array('value' => $o_value);
				if ($selected == $o_value) { $optionargs['selected'] = true; }
				$this->AddChild(new HtmlElement('option', HtmlElement::ArgsToString($optionargs), $o_title));
			}
		}
	}
}
?>