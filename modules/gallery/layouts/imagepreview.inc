<?php
class HtmlElement_Gallery_ImagePreview extends HtmlElement
{
	public function __construct($module, $images)
	{
		if (is_array($images))
		{
			parent::__construct('div', 'class="gallery"');
			
			$child = new HtmlElement('div', 'class="galleryview"');
			if (sizeof($images) == 1)
			{
				$child->AddChild(new HtmlElement_Gallery_BigImage($module, $images[0]));
			}
			else
			{
				foreach ($images as $i)
				{
					$child->AddChild(new HtmlElement_Gallery_Image($module, $i));
				}
			}
			$this->AddChild($child);
		}
		else { parent::__construct(); }
	}
}
?>
