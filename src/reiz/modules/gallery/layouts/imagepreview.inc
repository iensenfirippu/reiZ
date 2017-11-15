<?php
class HtmlElement_Gallery_ImagePreview extends RTK_Box
{
	public function __construct($module, $images)
	{
		if (is_array($images))
		{
			parent::__construct(null, 'gallery', null, new HtmlElement_Gallery_Images($module, $images));
		}
		else { parent::__construct(); }
	}
}
?>
