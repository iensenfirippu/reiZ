<?php
if (defined('reiZ') or exit(1))
{
	/**
	 * Contains the definition of a list in HTML
	 **/
	class RTK_List extends HtmlElement
	{
		/**
		 * A widget containing an unordered list (ul)
		 * @param HtmlElement[] $items The items for the list
		 * @param HtmlAttributes $args Allows custom html tag arguments to be specified (not recommended)
		 **/
		public function __construct($items=null, $args=null)
		{
			parent::__construct('ul', $args);
			if (SetAndNotNull($items)) {
				foreach ($items as $item) {
					$this->AddItem($item);
				}
			}
		}
		
		/**
		 * Adds an item to the list
		 * @param HtmlElement $item The item to add
		 **/
		public function AddItem($item, $args=null)
		{
			if (is_a($item, 'HtmlElement')) {
				if ($item->GetTag() == 'li') {
					$item->AddAttributes($args);
					$this->AddChild($item);
				} else {
					$this->AddChild(new HtmlElement('li', $args, null, $item));
				}
			} elseif (is_string($item)) {
				$this->AddChild(new HtmlElement('li', $args, $item));
			}
		}
	}
}	
?>
