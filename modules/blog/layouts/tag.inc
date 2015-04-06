<?php
class HtmlElement_BlogTag extends HtmlElement
{
	public function __construct($module, $tag)
	{
		if (is_a($tag, 'BlogTag'))
		{
			parent::__construct('li');
			$this->AddChild(new HtmlElement('a', 'href="'.$module->GetBaseUrl().$tag->GetName().'/"', $tag->GetName()));
		}
		else { parent::__construct(); }
	}
}
?>
