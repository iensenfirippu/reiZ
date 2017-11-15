<?php
class HtmlElement_Gallery_Images extends RTK_Box
{
	public function __construct($module, $images)
	{
		if (is_array($images))
		{
			parent::__construct(null, 'galleryview');
			
			if (sizeof($images) == 1)
			{
				$this->AddChild(new HtmlElement_Gallery_Image($module, $images[0], true));
			}
			else
			{
				foreach ($images as $i)
				{
					$this->AddChild(new HtmlElement_Gallery_Image($module, $i));
				}
			}
		}
		else { parent::__construct(); }
	}
}
?>
