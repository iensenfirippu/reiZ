<?php
class HtmlElement_Gallery_Images extends HtmlElement
{
	public function __construct($module, $images)
	{
		if (is_array($images))
		{
			parent::__construct('div', 'class="galleryview"');
			
			foreach ($images as $i)
			{
				$this->AddChild(new HtmlElement_Gallery_Image($module, $i));
			}
		}
		else { parent::__construct(); }
	}
}
?>
