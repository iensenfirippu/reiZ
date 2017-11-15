<?php
class HtmlElement_Gallery_Image extends RTK_Link
{
	public function __construct($module, $image, $single=false)
	{
		if (is_a($image, "GalleryImage"))
		{
			$attr = new HtmlAttributes();
			$attr->AddClass('galleryimage');
			if ($single) { $attr->AddClass('big'); }
			if (GALLERYUSELIGHTBOX) {
				$attr->Add('data-lightbox', 'gallery');
				$attr->Add('title', $image->GetName());
			}
			
			if ($image->HasInfo())
			{
				$attr->AddClass('hasinfo');
				$attr->Add('onmouseover', 'showdiv(\''.$image->GetHideId().'\')');
				$attr->Add('onfocus', 'showdiv(\''.$image->GetHideId().'\')');
				
				$module->GetHtml_HiddenInfo()->AddChild(
					new RTK_Box($image->GetHideId(), 'hidden', null,
						array(
							new RTK_Header($image->GetTitle(), 3),
							new RTK_Textview($image->GetText(), true)
						)
					)
				);
			}
			
			parent::__construct($image->GetPath(), null, false, $attr);
			$this->AddChild(new RTK_Image($single?$image->GetPath():$image->GetThumbnail(), $image->GetName()));
			$this->AddChild(new RTK_Textview($image->GetTitle(), true, null, 'name'));
		}
		else { parent::__construct(); }
	}
}
?>
