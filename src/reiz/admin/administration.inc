<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		Administration Interface
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/10
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * Contains definitions for the administration interface of the CMS.
	 * It can be extended by admin.php files in each module.
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
		
		/* HTML-output methods ->  */
		
			/* HTML-output: "General" -> */
				
				private function MakeHtml_Menu($args)
				{
					$html = new RTK_List(null, array());
					//$pages = array('pages', 'modules', 'themes', 'settings');
					$pages = array('pages', 'modules');
					
					// Add menuitem for any module that has them
					$search = array('modules/', '/admin.php');
					$replace = array(EMPTYSTRING, EMPTYSTRING);
					foreach (glob('modules/*/admin.php') as $page)
					{
						$module = Module::Load(str_replace($search, $replace, $page), false);
						if ($module->isInstalled())
						{
							array_push($pages, $module->GetName());
						}
					}
					
					foreach ($pages as $name)
					{
						$attributes = null;
						if ($args[0] == $name) { $attributes = new HtmlAttributes(array('selected' => true)); }
						$html->AddItem(new RTK_Link(new URL(ADMINPAGE, $name), $name, null, $attributes));
					}
					
					// Add menu point to leave the administration
					$html->AddItem(new RTK_Link('/', 'exit'));
					
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
							$modulefile = 'modules/'.$args[0].'/admin.php';
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
			
			/* <- HTML-output: "General" */
			/* HTML-output: "Home" -> */
				
				private function MakeHtml_Home($args)
				{
					$this->_content->AddChild(new RTK_Textview('Home', null, null, true));
				}
			
			/* <- HTML-output: "Home" */
			/* HTML-output: "Pages" -> */
			
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
							if ($args[1] == 'move') { $this->MakeHtml_MovePage($args, $page); }
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
					$sizeofpages = sizeof($pages);
					$first = 1;
					
					//foreach ($pages as $page) { vd($page->GetWeight()); }
					//vdd(null);
					
					$last = ($pages[$sizeofpages -1]->GetWeight());
					
					$admin = new AdminBox('List');
					
					//$list = new HtmlList(array('Name', 'Title', 'In menu', 'Weight', '_Move', '_'));
					$list = new RTK_Listview(array('Name', 'Title', 'In menu', '_Move', '_'));
					for ($i = 0; $i < $sizeofpages; $i++)
					{
						$page = $pages[$i];
						$prev = $i > 0 ? $pages[$i -1]->GetWeight() : $first;
						$next = $i < ($sizeofpages -1) ? $pages[$i +1]->GetWeight() : $last;
						
						$move = new HtmlElement();
						if ($page->GetWeight() != $first) { $move->AddChild(new RTK_ImageLink('/'.ADMINPAGE.'/pages/move/'.$page->GetId().'/'.$first.'/', '/common/images/oxygen/16/go-top.png', 'F')); }
						else { $move->AddChild(new RTK_Image('/common/images/oxygen/16/go-top.png', 'F', array('class' => 'disabled'))); }
						if ($page->GetWeight() != $prev) { $move->AddChild(new RTK_ImageLink('/'.ADMINPAGE.'/pages/move/'.$page->GetId().'/'.$prev.'/', '/common/images/oxygen/16/go-up.png', 'U')); }
						else { $move->AddChild(new RTK_Image('/common/images/oxygen/16/go-up.png', 'F', array('class' => 'disabled'))); }
						if ($page->GetWeight() != $next) { $move->AddChild(new RTK_ImageLink('/'.ADMINPAGE.'/pages/move/'.$page->GetId().'/'.$next.'/', '/common/images/oxygen/16/go-down.png', 'D')); }
						else { $move->AddChild(new RTK_Image('/common/images/oxygen/16/go-down.png', 'F', array('class' => 'disabled'))); }
						if ($page->GetWeight() != $last) { $move->AddChild(new RTK_ImageLink('/'.ADMINPAGE.'/pages/move/'.$page->GetId().'/'.($last +1).'/', '/common/images/oxygen/16/go-bottom.png', 'L')); }
						else { $move->AddChild(new RTK_Image('/common/images/oxygen/16/go-bottom.png', 'F', array('class' => 'disabled'))); }
						
						$options = new HtmlElement();
						$options->AddChild(new RTK_ImageLink('/'.$page->GetName().'/', '/common/images/oxygen/16/view-preview.png', 'V'));
						$options->AddChild(new RTK_ImageLink('/'.ADMINPAGE.'/pages/edit/'.$page->GetId().'/', '/common/images/oxygen/16/configure.png', 'E'));
						$options->AddChild(new RTK_ImageLink('/'.ADMINPAGE.'/pages/delete/'.$page->GetId().'/', '/common/images/oxygen/16/edit-delete.png', 'D'));
						
						//$list->AddRow(array($page->GetName(), $page->GetTitle(true), DisplayBoolean($page->GetInMenu()), $page->GetWeight(), $move, $options));
						$list->AddRow(array($page->GetName(), $page->GetTitle(true), Boolean_Display($page->GetInMenu()), $move, $options));
					}
					
					// TODO: Temporary solution until the inherited tostring can be overridden.
					//$list->Finalize();
					
					$admin->AddContent($list);
					
					$this->_content->AddChild($admin);
				}
				
				private function MakeHtml_EditPage($args, $page)
				{
					// TODO: Make a nice way of selecting before/after certain other page (i.e. editing Weight without displaying it)
					
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
									Redirect('/'.ADMINPAGE.'/pages/');
									//Redirect('/'.ADMINPAGE.'/pages/edit/'.$page->GetId());
								}
							}
						}
						
						$this->_options->AddOption('home', '/'.ADMINPAGE.'/pages/', 'Back');
						
						$options = array();
						$masterpages = Masterpage::LoadAll();
						foreach ($masterpages as $m) { array_push($options, array($m->GetId(), $m->GetName())); }
						
						$form = new RTK_Form('page_form');
						
						$form->AddContainer(new AdminBox('Meta'), 'box1');
						$form->AddTextField('name', 'Name:', $page->GetName());
						$form->AddTextField('title', 'Title:', $page->GetTitle());
						$form->AddCheckBox('inmenu', 'In menu:', $page->GetInMenu());
						$form->AddTextField('weight', 'Weight:', $page->GetWeight());
						
						$selectedid = $page->GetMasterPage() != null ? $page->GetMasterPage()->GetId() : null;
						$form->AddDropDown('masterpage', 'MasterPage:', $options, $selectedid);
						
						$form->AddContainer(new AdminBox('Content'), 'box2');
						$form->AddTextField('content', 'Text:', $page->GetContent(false), 20);
						$form->AddButton('submit', 'Save changes');
						
						$this->_content->AddChild($form);
					}
				}
				
				private function MakeHtml_MovePage($args, $page)
				{
					if ($page instanceof Page)
					{
						if (is_numeric($args[3]))
						{
							$page->SetWeight($args[3]);
							$page->Save();
						}
						Redirect('/'.ADMINPAGE.'/pages/');
					}
				}
				
				private function MakeHtml_DeletePage($args, $page)
				{
					if ($page instanceof Page)
					{
						if (isset($_POST['submit']) && $_POST['submit'] != null)
						{
							$page->Delete();
							Redirect('/'.ADMINPAGE.'/pages/');
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
			
			/* <- HTML-output: "Pages" */
			/* HTML-output: "Modules" -> */
			
				private function MakeHtml_Modules($args)
				{
					$showlist = false;
					$module = null;
					
					if (sizeof($args) > 2 && $args[2] != null)
					{
						$module = Module::Load($args[2], false);
						if ($module == null)
						{
							$tmp = new Module($args[2]);
							if ($tmp->LoadInfo()) { $module = $tmp; }
						}
					}
					
					if (sizeof($args) > 1 && $module != null)
					{
						if ($args[1] == 'install') { $this->MakeHtml_InstallModule($args, $module); }
						elseif ($args[1] == 'uninstall') { $this->MakeHtml_UninstallModule($args, $module); }
						elseif ($args[1] == 'configure') { $this->MakeHtml_ConfigureModule($args, $module); }
						else { $showlist = true; }
					}
					else { $showlist = true; }
					
					if ($showlist) { $this->MakeHtml_ListModules($args); }
				}
				
				private function MakeHtml_ListModules($args)
				{
					$search = 'modules/';
					$modules_db = Module::LoadInstalledNames();
					$modules_dir = str_replace(array($search, '/'), EMPTYSTRING, glob($search.'*/'));
					$modules = array_unique(array_merge($modules_db, $modules_dir));
					sort($modules);
					
					$pages = Page::LoadAll(null, null, null, 'name', DBOD::Asc);
					$pageoptions = array(array(EMPTYSTRING, EMPTYSTRING));
					foreach ($pages as $page) { $pageoptions[] = array($page->GetId(), $page->GetName()); }
					$pageoptions[] = array(-1, '[New Page]');
					
					$admin1 = new AdminBox('Installed modules');
					$form1 = new HtmlForm('PageSelections');
					$list1 = new HtmlList(array('_', 'Name', 'Author', 'Version', 'Page', '_'));
					$show1 = false;
					
					$admin2 = new AdminBox('Installable Modules');
					$list2 = new HtmlList(array('_', 'Name', 'Author', 'Version', '_'));
					$show2 = false;
					
					$admin3 = new AdminBox('Invalid Modules');
					$list3 = new HtmlList(array('_', 'Name', '_'));
					$show3 = false;
					
					foreach ($modules as $name)
					{
						$module = Module::Load($name, false);
						
						$options = new HtmlElement();
						if ($module instanceof Module)
						{
							$hasdir = $module->Exists();
							$installed = $module->isInstalled();
							
							if ($hasdir)
							{
								$icon = $module->HasIcon() ? SINGLESLASH.$module->GetIcon() : '/common/images/oxygen/16/list-remove.png';
								$icon = new HtmlImage($icon, '#');
								
								if ($installed) // Installed modules
								{
									if (SetAndNotNull($_POST, $module->GetId()))
									{
										$pageid = $_POST[$module->GetId()];
										if ($pageid == -1)
										{
											$page = Page::Create($module->GetName());
											$page->Save();
											$module->SetPage($page->GetId());
										} 
										elseif ($pageid != $module->GetPage())
										{
											$module->SetPage($pageid);
										}
									}
									
									$pageselect = new HtmlDropDown($module->GetId(), $pageoptions, $module->GetPage());
									
									if ($module->HasConfig()) { $options->AddChild(new RTK_ImageLink('/admin/modules/configure/'.$module->GetName(), '/common/images/oxygen/16/configure.png', 'S')); }
									$options->AddChild(new RTK_ImageLink('/admin/modules/uninstall/'.$module->GetName(), '/common/images/oxygen/16/list-remove.png', 'D'));
									$list1->AddRow(array($icon, $module->GetName(), $module->GetAuthor(), $module->GetVersion(), $pageselect, $options));
									$show1 = true;
								}
								else // Installable modules
								{
									$options->AddChild(new RTK_ImageLink('/admin/modules/install/'.$module->GetName(), '/common/images/oxygen/16/list-add.png', 'E'));
									$list2->AddRow(array($icon, $module->GetName(), $module->GetAuthor(), $module->GetVersion(), $options));
									$show2 = true;
								}
							}
							else // Modules with invalid files
							{
								$list3->AddRow(array($icon, $module->GetName(), $options));
								$show3 = true;
							}
						}
						else // Installed modules with no files
						{
							$options->AddChild(new RTK_ImageLink('/admin/modules/uninstall/'.$name, '/common/images/oxygen/16/list-remove.png', 'D'));
							$list3->AddRow(array($icon, $name, $options));
							$show3 = true;
						}
					}
					$list1->AddRow(array(EMPTYSTRING, EMPTYSTRING, EMPTYSTRING, EMPTYSTRING, new HtmlButton('submit', 'Save'), EMPTYSTRING));
					
					// TODO: Temporary solution
					$list1->Finalize();
					$list2->Finalize();
					$list3->Finalize();
					
					$form1->AddChild($list1);
					$admin1->AddContent($form1);
					$admin2->AddContent($list2);
					$admin3->AddContent($list3);
					if ($show1) { $this->_content->AddChild($admin1); }
					if ($show2) { $this->_content->AddChild($admin2); }
					if ($show3) { $this->_content->AddChild($admin3); }
				}
				
				private function MakeHtml_InstallModule($args, $module)
				{
					if ($module instanceof Module && $module->Exists())
					{
						if (isset($_POST['submit']) && $_POST['submit'] != null)
						{
							$module->Install();
							Redirect('/'.ADMINPAGE.'/modules/');
						}
						
						$this->_options->AddOption('home', '/'.ADMINPAGE.'/modules/', 'Back');
						
						$form = new HtmlForm('module_install');
						
						$form->AddContainer(new AdminBox('Install Module "'.$module->GetTitle().'"'), 'box1');
						$form->AddText('Are you sure you want to install this module?<br />');
						$form->AddButton('submit', 'Yes, I am sure I want to install it.');
						
						$this->_content->AddChild($form);
					}
				}
				
				private function MakeHtml_UninstallModule($args, $module)
				{
					if ($module instanceof Module && $module->Exists())
					{
						if (isset($_POST['submit']) && $_POST['submit'] != null)
						{
							$module->Uninstall();
							Redirect('/'.ADMINPAGE.'/modules/');
						}
						
						$this->_options->AddOption('home', '/'.ADMINPAGE.'/modules/', 'Back');
						
						$form = new HtmlForm('module_install');
						
						$form->AddContainer(new AdminBox('Uninstall Module "'.$module->GetTitle().'"'), 'box1');
						$form->AddText('Are you sure you want to uninstall this module?<br />');
						$form->AddButton('submit', 'Yes, I am sure I want to uninstall it.');
						
						$this->_content->AddChild($form);
					}
				}
				
				private function MakeHtml_ConfigureModule($args, $module)
				{
					if ($module instanceof Module)
					{
						$settings = $module->GetSettings();
						
						if (SetAndNotNull($_POST, 'submit'))
						{
							$valid = true;
							
							foreach ($settings as $setting)
							{
								$value = false;
								if (SetAndNotNull($_POST, $setting->GetName())) { $value = $_POST[$setting->GetName()]; }
								$valid = ($valid && $setting->SetValue($value));
							}
							
							if ($valid) { $saved = $settings->Save(); }
						}
						
						$this->_options->AddOption('home', '/'.ADMINPAGE.'/modules/', 'Back');
						
						$form = new HtmlForm('page_form');
						
						$form->AddContainer(new AdminBox('Config for '.$module->GetTitle()), 'box1');
						
						foreach ($settings as $setting)
						{
							if ($setting->GetType() == ST::BooleanValue)
							{
								$form->AddCheckBox($setting->GetName(), $setting->GetTitle(), ($setting->GetValue() == true));
							}
							// TODO: Change to numerical input eventually
							/*elseif ($setting->GetType() == ST::IntegerValue)
							{
								$form->AddTextField($setting->GetName(), $setting->GetTitle(), $setting->GetValue());
							}*/
							// TODO: Change to multiselect input eventually
							elseif ($setting->GetType() == ST::StringList)
							{
								$form->AddTextField($setting->GetName(), $setting->GetTitle(), $setting->GetValue());
							}
							else
							{
								if ($setting->GetOptions() != null)
								{
									$form->AddDropDown($setting->GetName(), $setting->GetTitle(), $setting->GetOptions(), $setting->GetValue());
								}
								else
								{
									$form->AddTextField($setting->GetName(), $setting->GetTitle(), $setting->GetValue());
								}
							}
						}
						
						$form->AddButton('submit', 'Save changes');
						
						$this->_content->AddChild($form);
					}
				}
			
			/* <- HTML-output: "Modules" */
			/* HTML-output: "Themes" -> */
			
				private function MakeHtml_Themes($args)
				{
					$this->_options->AddOption('list');
					
					$search = FOLDERTHEMES.'/';
					$themes = str_replace(array($search, '/'), EMPTYSTRING, glob($search.'*/'));
					
					$admin = new AdminBox('List');
					
					$list = new HtmlList(array('Name', '_'));
					foreach ($themes as $theme)
					{
						$options = new HtmlElement();
						$options->AddChild(new HtmlElement('a', 'href="#"', 'V')); //view
						$options->AddChild(new HtmlElement('a', 'href="#"', 'S')); //settings
						$list->AddRow(array($theme, $options));
					}
					
					// TODO: Temporary solution
					$list->Finalize();
					
					$admin->AddContent($list);
					
					$this->_content->AddChild($admin);
				}
			
			/* <- HTML-output: "Themes" */
			/* HTML-output: "Settings" -> */
			
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
			
			/* <- HTML-output: "Settings" */
		/* <- HTML-output methods */
	}
	
	/**
	 * Html object for containing a section of the Admin Interface (should be generalized or put into theme specific code, eventually)
	 * @extends HtmlElement
	 */
	class AdminBox extends HtmlElement
	{
		protected $_container;
		
		public function __construct($title)
		{
			parent::__construct();
			$this->_container = new RTK_Box(null, 'content');
			
			$this->AddChild(new RTK_Box(null, 'box_title', null, new RTK_Header($title, 1)));
			$this->AddChild(new RTK_Box(null, 'box_main', null, new RTK_Box(null, 'box_content', null, $this->_container)));
		}
		
		public function AddContent($HtmlElement, $index=null)
		{
			$this->_container->AddChild($HtmlElement, $index);
		}
	}
	
	/**
	 * Html object for containing an set of options (links) in the Admin Interface (should be generalized or put into theme specific code, eventually)
	 * @extends HtmlElement
	 */
	class AdminOptions extends RTK_List
	{
		public function __construct()
		{
			parent::__construct(null, array('class' => 'options'));
		}
		
		public function AddOption($name, $link=null, $title=null, $icon=null)
		{
			$anchor = new RTK_Link(new URL(array(ADMINPAGE, $GLOBALS['ARGS'][0], $name)));
			if ($title === null) { $title = $name; }
			if ($icon === null) { $anchor->AddChild(new RTK_Textview($title, null, 'noicon', true)); }
			else
			{
				$anchor->AddChild(new RTK_Image($icon, '[icon]'));
				$anchor->AddChild(new RTK_Textview($title, null, null, true));
			}
			
			$this->AddItem($anchor);
		}
	}
}

?>
