<?php
/*
 * Default theme test
 * Copyright 2013 Iensen Firippu <philip@marugawalite>
 */
 
class Administration
{
	private $_menu = null;
	private $_options = null;
	private $_content = null;
	private $_stylesheets = array();
	private $_javascripts = array();
	
	public function GetMenu() { return $this->_menu; }
	public function GetOptions() { return $this->_options; }
	public function GetContent() { return $this->_content; }
	public function GetStylesheets() { return $this->_stylesheets; }
	public function GetJavaScripts() { return $this->_javascripts; }
	public function AddStylesheet($value) { array_push($this->_stylesheets, $value); }
	public function AddJavaScript($value) { array_push($this->_javascripts, $value); }
	
	public function __construct()
	{
		$args = $GLOBALS['ARGS'];
		
		$this->MakeHtml_Menu($args);
		$this->MakeHtml_Content($args);
	}
	
	private function MakeHtml_Menu($args)
	{
		$html = new HtmlElement('ul', 'class="menu"');
		$selected = 'class="selected" ';
		$pages = array('pages', 'modules', 'themes', 'settings'); 
		
		// Add menuitem for any module that has them
		$search = array(FOLDERMODULES.'/', '/admin.php');
		$replace = array(EMPTYSTRING, EMPTYSTRING);
		foreach (glob(FOLDERMODULES.'/*/admin.php') as $page)
		{
			array_push($pages, str_replace($search, $replace, $page));
		}
		
		foreach ($pages as $name)
		{
			$attributes = 'href="/'.ADMINPAGE.'/'.$name.'/"';
			if ($args[0] == $name) { $attributes = $selected.$attributes; }
			
			$html->AddChild(
				new HtmlElement('li', EMPTYSTRING, EMPTYSTRING,
					new HtmlElement('a', $attributes, $name)
				)
			);
		}
		
		// Add menu point to leave the administration
		$html->AddChild(
			new HtmlElement('li', EMPTYSTRING, EMPTYSTRING,
				new HtmlElement('a', 'href="/"', 'exit')
			)
		);
		
		$this->_menu = $html;
	}
	
	private function MakeHtml_Content($args)
	{
		$this->_content = new HtmlElement();
		$this->_options = new AdminOptions();
		
		if (sizeof($args) > 0)
		{
			if ($args[0] == 'pages') { $this->MakeHtml_Pages($args); }
			elseif ($args[0] == 'modules') { $this->MakeHtml_Modules($args); }
			elseif ($args[0] == 'themes') { $this->MakeHtml_Themes($args); }
			elseif ($args[0] == 'settings') { $this->MakeHtml_Settings($args); }
			else
			{
				$modulefile = FOLDERMODULES.'/'.$args[0].'/admin.php';
				if (file_exists($modulefile))
				{
					include_once($modulefile);
					$classname = 'CustomAdministrationPage';
					if (class_exists($classname))
					{
						$ref = new ReflectionClass($classname);
						$adminpage = $ref->newInstance();
						$adminpage->MakeHtml($this, $args);
					}
					else { $this->MakeHtml_Home($args); } 
				}
				else { $this->MakeHtml_Home($args); } 
			}
		}
	}
	
	private function MakeHtml_Home($args)
	{
		$this->_content->AddChild(new HtmlElement('span', EMPTYSTRING, 'Home'));
	}
	
	private function MakeHtml_Pages($args)
	{
		$showlist = false;
		
		if (sizeof($args) > 1 && $args[1] == 'add')
		{
			$this->MakeHtml_EditPage($args, Page::CreateNew());
		}
		elseif (sizeof($args) > 2 && $args[2] != null)
		{
			$page = Page::LoadFromID($args[2]);
			
			if ($page != null)
			{
				if ($args[1] == 'delete') { $this->MakeHtml_DeletePage($args, $page); }
				elseif ($page != null && $args[1] == 'edit') { $this->MakeHtml_EditPage($args, $page); }
				else { $showlist = true; }
			}
			else { $showlist = true; }
		}
		else { $showlist = true; }
		
		if ($showlist) { $this->MakeHtml_ListPages($args); }
	}
	
	private function MakeHtml_ListPages($args)
	{
		$this->_options->AddOption('list');
		$this->_options->AddOption('add');
		
		$pages = Page::LoadAll(null, 0, 50, 'weight', DBOD::Asc);
		
		$admin = new AdminBox('List');
		
		$list = new HtmlList(array('Name', 'Title', 'In menu', 'Weight', EMPTYSTRING));
		foreach ($pages as $page)
		{
			$inmenu = $page->GetInMenu() ? 'true' : 'false';
			$options = new HtmlElement();
			$options->AddChild(new HtmlElement('a', 'href="/'.$page->GetName().'/"', 'V'));
			$options->AddChild(new HtmlElement('a', 'href="/'.ADMINPAGE.'/pages/edit/'.$page->GetId().'/"', 'E'));
			$options->AddChild(new HtmlElement('a', 'href="/'.ADMINPAGE.'/pages/delete/'.$page->GetId().'/"', 'D'));
			$list->AddRow(array($page->GetName(), $page->GetTitle(true), $inmenu, $page->GetWeight(), $options));
		}
		$admin->AddContent($list);
		
		$this->_content->AddChild($admin);
	}
	
	private function MakeHtml_EditPage($args, $page)
	{
		// TODO: Make a nice way of selecting before/after certain other page
		// TODO: Allow the user to add Modules to the page, and their position (leave to another page/form if too difficult)
		
		if ($page != null)
		{
			if (isset($_POST['submit']) && $_POST['submit'] != null)
			{
				$valid = true;
				
				if (isset($_POST['masterpage'])) { $page->SetMasterpage(MasterPage::Load($_POST['masterpage'])); }
				if (isset($_POST['name'])) { $page->SetName($_POST['name']); }
				if (isset($_POST['title'])) { $page->SetTitle($_POST['title']); }
				if (isset($_POST['content'])) { $page->SetContent($_POST['content']); }
				if (isset($_POST['inmenu'])) { $page->SetInMenu(true); } else { $page->SetInMenu(false); }
				if (isset($_POST['weight'])) { $page->SetWeight($_POST['weight']); }
				
				if ($valid)
				{
					$saved = $page->Save();
					
					if ($args[1] == 'add' && $saved)
					{
						reiZ::Redirect('/'.ADMINPAGE.'/pages/');
						//reiZ::Redirect('/'.ADMINPAGE.'/pages/edit/'.$page->GetId());
					}
				}
			}
			
			$this->_options->AddOption('home', '/'.ADMINPAGE.'/pages/', 'Back');
			
			$options = array();
			$masterpages = Masterpage::LoadAll();
			foreach ($masterpages as $m) { array_push($options, array($m->GetId(), $m->GetName())); }
			
			$form = new HtmlForm('page_form');
			
			$form->AddContainer(new AdminBox('Meta'), 'box1');
			$form->AddTextField('name', 'Name:', $page->GetName());
			$form->AddTextField('title', 'Title:', $page->GetTitle());
			$form->AddCheckBox('inmenu', 'In menu:', $page->GetInMenu());
			$form->AddTextField('weight', 'Weight:', $page->GetWeight());
			
			$selectedid = $page->GetMasterPage() != null ? $page->GetMasterPage()->GetId() : null;
			$form->AddDropDown('masterpage', 'MasterPage:', $options, $selectedid);
			
			$form->AddContainer(new AdminBox('Content'), 'box2');
			$form->AddTextField('content', 'Text:', $page->GetContent(), 20);
			$form->AddButton('submit', 'Save changes');
			
			$this->_content->AddChild($form);
		}
	}
	
	private function MakeHtml_DeletePage($args, $page)
	{
		if ($page != null)
		{
			if (isset($_POST['submit']) && $_POST['submit'] != null)
			{
				$page->Delete();
				reiZ::Redirect('/'.ADMINPAGE.'/pages/');
			}
			
			$this->_options->AddOption('home', '/'.ADMINPAGE.'/pages/', 'Back');
			
			$form = new HtmlForm('page_delete');
			
			$form->AddContainer(new AdminBox('Delete Page'), 'box1');
			$form->AddText('Are you sure you want to delete this page object?<br />'.
				'(this action can be undone by visiting the recycle center)<br /><br />');
			$form->AddButton('submit', 'Yes, I am sure I want to delete it.');
			
			$this->_content->AddChild($form);
		}
	}
	
	private function MakeHtml_Modules($args)
	{
		$search = FOLDERMODULES.'/';
		$modules = str_replace(array($search, '/'), EMPTYSTRING, glob($search.'*/'));
		
		$admin = new AdminBox('List');
		
		$list = new HtmlList(array('Name', EMPTYSTRING));
		foreach ($modules as $module)
		{
			$options = new HtmlElement();
			$options->AddChild(new HtmlElement('a', 'href="#"', 'S')); //settings
			$options->AddChild(new HtmlElement('a', 'href="#"', 'E/D')); //enable/disable
			$list->AddRow(array($module, $options));
		}
		$admin->AddContent($list);
		
		$this->_content->AddChild($admin);
	}
	
	private function MakeHtml_Themes($args)
	{
		$this->_options->AddOption('list');
		
		$search = FOLDERTHEMES.'/';
		$themes = str_replace(array($search, '/'), EMPTYSTRING, glob($search.'*/'));
		
		$admin = new AdminBox('List');
		
		$list = new HtmlList(array('Name', EMPTYSTRING));
		foreach ($themes as $theme)
		{
			$options = new HtmlElement();
			$options->AddChild(new HtmlElement('a', 'href="#"', 'V')); //view
			$options->AddChild(new HtmlElement('a', 'href="#"', 'S')); //settings
			$list->AddRow(array($theme, $options));
		}
		$admin->AddContent($list);
		
		$this->_content->AddChild($admin);
	}
	
	private function MakeHtml_Settings($args)
	{
		$search = FOLDERCLASSES.'/dbvendor/';
		$vendors = str_replace(array($search, '/'), EMPTYSTRING, glob($search.'*/'));
		
		$form = new HtmlForm('adminsettings_form');
		
		$form->AddContainer(new AdminBox('Database'), 'box1');
		$form->AddDropDown('vendor', 'Vendor: ', $vendors);
		$form->AddTextField('address', 'Address: ');
		$form->AddTextField('username', 'Username: ');
		$form->AddTextField('password', 'Password: ');
		$form->AddTextField('database', 'Database: ');
		$form->AddTextField('prefix', 'Prefix: ');
		
		$form->AddContainer(new AdminBox('another section'), 'box2');
		$form->AddTextField('option1', 'Option 1: ');
		$form->AddTextField('option2', 'Option 2: ');
		$form->AddTextField('option3', 'Option 3: ');
		
		$form->AddContainer(new AdminBox('a whole other section'), 'box3');
		$form->AddTextField('option4', 'Option 4: ');
		$form->AddButton('submit', 'Post it!');
		
		$this->_content->AddChild($form);
	}
}

class AdminBox extends HtmlElement
{
	protected $_container;
	
	public function __construct($title)
	{
		parent::__construct();
		$this->_container = new HtmlElement('div', 'class="content"');
		
		$this->AddChild(
			new HtmlElement('div', 'class="box_title"', EMPTYSTRING,
				new HtmlElement('h1', EMPTYSTRING, $title)
			)
		);
		$this->AddChild(
			new HtmlElement('div', 'class="box_main"', EMPTYSTRING,
				new HtmlElement('div', 'class="box_content"', EMPTYSTRING, $this->_container)
			)
		);
	}
	
	public function AddContent($HtmlElement, $index=null)
	{
		$this->_container->AddChild($HtmlElement, $index);
	}
}

class AdminOptions extends HtmlElement
{
	public function __construct()
	{
		parent::__construct('ul', 'class="options"');
	}
	
	public function AddOption($name, $link=null, $title=null, $icon=null)
	{
		$attributes = 'href="';
		if ($link === null) { $attributes .= URLROOT.'/'.ADMINPAGE.'/'.$GLOBALS['ARGS'][0].'/'.$name.'/"'; }
		else { $attributes .= $link.'"'; }
		$anchor = new HtmlElement('a', $attributes);
		if ($title === null) { $title = $name; }
		if ($icon === null) { $anchor->AddChild(new HtmlElement('span', 'class="noicon"', $title)); }
		else
		{
			$anchor->AddChild(new HtmlElement('img', 'src="'.$icon.'" alt="[icon]"'));
			$anchor->AddChild(new HtmlElement('span', EMPTYSTRING, $title));
		}
		
		$this->AddChild(new HtmlElement('li', EMPTYSTRING, EMPTYSTRING, $anchor));
	}
}

?>
