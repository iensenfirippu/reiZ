<?php
class HtmlElement_Blog_Tags extends HtmlElement
{
	public function __construct($module, $tags)
	{
		if (is_array($tags))
		{
			parent::__construct('ul', array('class' => 'blogtags'), EMPTYSTRING);
			foreach ($tags as $tag)
			{
				$this->AddChild(new HtmlElement_Blog_Tag($module, $tag));
			}
		}
		else { parent::__construct(); }
	}
}
?>
