<?php
class HtmlElement_Gallery_BigImage extends HtmlElement
{
	public function __construct($module, $image)
	{
		if (is_a($image, "GalleryImage"))
		{
			$attr = 'class="galleryimagebig';
			$lightbox = GALLERYUSELIGHTBOX ? ' data-lightbox="gallery" title="'.$image->GetName().'"' : EMPTYSTRING;
			
			if ($image->HasInfo())
			{
				//$attr .= ' hasinfo" onmouseover="showdiv(\''.$image->GetHideId().'\')"';
				$attr .= ' hasinfo"';
				
				/*$module->GetHtml_HiddenInfo()->AddChild(
					new HtmlElement('div', 'id="'.$image->GetHideId().'" class="hidden"', EMPTYSTRING,
						array(
							new HtmlElement('h3', EMPTYSTRING, $image->GetTitle()),
							new HtmlElement('span', EMPTYSTRING, $image->GetText())
						)
					)
				);*/
			}
			else { $attr .= '"'; }
			
			parent::__construct('div', $attr, EMPTYSTRING);
			
			/*$this->AddChild(
				new HtmlElement('a', 'href="'.URLROOT.'/'.$image->GetLink().'"'.$lightbox, EMPTYSTRING,
					array(
						new HtmlElement('img', 'src="'.URLROOT.'/'.$image->GetLink().'" alt="'.$image->GetName().'"'),
						new HtmlElement('span', 'class="name"', $image->GetTitle())
					)
				)
			);*/
			
			parent::__construct('a', 'href="'.URLROOT.'/'.$image->GetLink().'"'.$attr.$lightbox, EMPTYSTRING,
				array(
					new HtmlElement('img', 'src="'.URLROOT.'/'.$image->GetThumbnail().'" alt="'.$image->GetName().'"'),
					new HtmlElement('span', 'class="name"', $image->GetTitle())
				)
			);
		}
		else { parent::__construct(); }
	}
}
?>
