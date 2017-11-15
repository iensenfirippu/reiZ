<?php
if (defined('reiZ') or exit(1))
{
	/**
	 * Contains the definition of a menu in HTML
	 **/
	//class Reiz_Menu
	class RTK_Menu extends RTK_List
	{
		private $_args = null;
		private $_links = null;
		private $_selected = null;
		
		/**
		 * A widget containing a menu
		 * @param string $id The HTML #id of the element
		 * @param string $class The HTML .class of element
		 * @param string[][] $links The links in the menu
		 * @param string $selected The title of the selected item in the menu
		 * @param HtmlAttributes $args Allows custom html tag arguments to be specified (not recommended)
		 **/
		public function __construct($id, $class, $links, $selected=null, $args=null)
		{
			HtmlAttributes::Assure($args);
			$args->Add('id', $id);
			$args->Add('class', $class);
			
			$url = $title = null;
			$items = array();
			foreach ($links as $link) {
				if (Array_LongerThan($link, 1)) {
					$url = $link[0];
					$title = $link[1];
				} else { $url = $title = $link; }
				
				if (!String_EndsWith($url, '/') && !String_Contains(url_lastfolder($url), SINGLEDOT)) {
					$url .= '/';
				}
				
				$linkargs = null;
				$forcehttps = false;
				if (String_StartsWith($title, '_')) {
					$title = String_RemovePrefix($title, '_');
					$forcehttps = true;
				}
				if ($selected != null && $selected == $title) { $linkargs = array('class' => 'selected'); }
				$items[] = new RTK_Link($url, $title, $forcehttps, $linkargs);
			}
			parent::__construct($items, $args);
		}
		
		/**
		 * Add an item to the menu
		 * @param string $link The link to add
		 * @param string $title The title of the link
		 * @param boolean $forcehttps Specify if the link has to have https 
		 **/
		public function AddMenuItem($link, $title, $forcehttps=false)
		{
			$this->AddItem(new RTK_Link($links[$i], $titles[$i], $forcehttps));
		}
		
		/**
		 * Set the selected item in the menu
		 * @param var $idortitle The index or title of the menuitem
		 **/
		public function SetSelected($idortitle)
		{
			$children = $this->GetChildren();
			if (is_integer($idortitle) && $idortitle < sizeof($children)) {
				$children[$idortitle]->GetAttributes()->Add('selected', true, true);
			} elseif (is_string($idortitle)) {
				foreach ($children as $child) {
					if (strtolower($child->GetContent()) == strtolower($idortitle)) {
						$child->GetAttributes()->Add('class', 'selected');
					}
				}
			}
		}
	}
}
?>