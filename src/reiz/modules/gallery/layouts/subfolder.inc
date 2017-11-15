<?php
class HtmlElement_Gallery_SubFolder extends HtmlElement
{
	public function __construct($module, $folder)
	{
		if ($folder instanceof GalleryFolder)
		{
			$attr = new HtmlAttributes(array('class' => 'gallerylink'));
			if ($folder->HasInfo())
			{
				$attr['onmouseover'] = 'showdiv(\''.$folder->GetHideId().'\')';
				$attr['onfocus'] = 'showdiv(\''.$folder->GetHideId().'\')';
				
				$module->GetHtml_HiddenInfo()->AddChild(
					new RTK_Box($folder->GetHideId(), 'hidden', null,
						array(
							new RTK_Header($folder->GetTitle(), 3),
							new RTK_TextView($folder->GetText(), true)
						)
					)
				);
			}
			
			$images = array();
			for ($i = $fail = 0; $fail < 4 && ($i - $fail) < 4; $i++)
			{
				$image = $folder->GetRandomImage();
				if ($image != null && !in_array($image, $images)) {
					$images[] = $image;
				} else {
					$fail++;
				}
			}
			
			$gallerypreview = new RTK_Box(null, 'gallerypreview');
			foreach ($images as $image)
			{
				$gallerypreview->AddChild(new RTK_Image((new URL($image->GetThumbnail())), $image->GetName(), array('class' => 'gallerythumbnail')));
			}
			
			$attr->Add('href', (new URL($module->GetName().URLARGS.$folder->GetPath()))->GetAbsolutePath(GetBaseURL()));
			parent::__construct('a', $attr, null,
				array(
					new RTK_Box(null, 'gallerylinktitle', array('style' => 'text-decoration: none;'), $folder->GetTitle()),
					$gallerypreview
				)
			);
		}
		else { parent::__construct(); }
	}
}
?>
