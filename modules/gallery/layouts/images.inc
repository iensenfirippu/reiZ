<?php
class HtmlElement_GalleryImages extends HtmlElement
{
	public function __construct($module, $images)
	{
		if (is_array($images))
		{
			parent::__construct('div', 'class="galleryview"');
			
			foreach ($images as $i)
			{
				$this->AddChild(new HtmlElement_GalleryImage($module, $i));
			}
		}
		else { parent::__construct(); }
	}
}
?>
