<?php
class HtmlElement_Blog_Post extends HtmlElement
{
	public function __construct($module, $post, $full)
	{
		if ($post instanceof BlogPost)
		{
			$baseurl = new URL(array($module->GetBaseUrl(), $module->GetCategoryName(), $module->GetTagName(), $module->GetPage()));
			$timestamp = TimestampToHumanTime($post->GetPosted());
			
			// Make top of post
			$posttop = new RTK_Box(null, 'top', EMPTYSTRING,
				array(
					new RTK_Header($post->GetTitle(), 3, array('class' => 'title')),
					new RTK_Textview($timestamp, true, null, 'timestamp')
				)
			);
			
			// Make middle of post
			$postcontent = new RTK_Box(null, 'center');
			if ($full)
			{
				$tmp = new RTK_Textview($post->GetFullText(), false, null, 'text');
				$postcontent->AddChild($tmp);
			}
			else
			{
				$postcontent->AddChild(new RTK_Textview($post->GetText(), false, null, 'text'));
				if ($post->HasFullText())
				{
					$postcontent->AddChild(
						new RTK_Box(null, 'readmore', null,
							new RTK_Link(new URL(array($baseurl, $post->GetPostID())), BLOGREADMORE)
						)
					);
				}
			}
			
			// Make bottom of post
			$postbottom = new RTK_Box(null, 'bottom');
			if (sizeof($post->GetTags()))
			{
				$postbottom->AddChild(new HtmlElement_Blog_PostTags($module, $post->GetTags()));
			}
			if ($post->GetEdited() > 0)
			{
				$postbottom->AddChild(
					new RTK_Textview('(edited: '.TimestampToHumanTime($post->GetEdited()).')', true, null, 'timestamp')
				);
			}
			
			parent::__construct('div', array('class' => 'blogpost'), EMPTYSTRING,
				array(
					$posttop,
					$postcontent,
					$postbottom
				)
			);
		}
		else { parent::__construct(); }
	}
}
?>
