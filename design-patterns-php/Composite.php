<?php
	/**
	 * 组合模式
	 * 树形菜单：将对象组合成树形结构以表示"部分-整体"的层次结构,使得客户对单个对象和复合对象的使用具有一致性 
	 *
	 * 抽象构件角色（component）
	 */
	abstract class MenuComponent {
		public function add($component) {}
		public function remove($component) {}
		public function getName() {}
		public function getUrl() {}
		public function displayOperation() {}
	}


	/**
	 * 树枝构件角色（Composite）
	 */
	class MenuComposite extends MenuComponent {
		private $_items = array();
		private $_name = null;
		private $_align = '';
		public function __construct($name) {
			$this->_name = $name;
		}

		public function add($component) {
			$this->_items[$component->getName()] = $component;
		}

		public function remove($component) {
			$key = array_search($component, $this->_items);
			if ($key !== false) unset($this->_items[$key]);
		}

		public function getItems() {
			return $this->_items;
		}

		public function displayOperation() {
			static $align = '|';
			if ($this->getItems()) {
				$align .= ' _ _ ';  
			} else {
				$align .= '';
			}
			echo $this->_name . "\r\n";
			foreach($this->_items as $k=>$vo) {
				echo $align;
				$vo->displayOperation();
			}
		}

		public function getName() {
			return $this->_name;
		}
	}


	/**
	 * 树叶构件角色（Leaf）
	 */
	class ItemLeaf extends MenuComponent {
		private $_name = null;
		private $_url = null;

		public function __construct($name, $url) {
			$this->_name = $name;
			$this->_url = $url;
		}

		public function displayOperation() {
			echo '<a href="'. $this->_url .'">'. $this->_name .'</a><br/>';  
		}

		public function getName() {
			return $this->_name;
		}
	}


	/**
	 * 客户类
	 */
	class Client {

		public static function displayMenu() {
			$subMenu1 = new MenuComposite("submenu1");  
			$subMenu2 = new MenuComposite("submenu2");  
			$subMenu3 = new MenuComposite("submenu3");  
			$subMenu4 = new MenuComposite("submenu4");  
			$subMenu5 = new MenuComposite("submenu5");  

			$item3 = new ItemLeaf("baidu","www.baidu.com");  
			$item4 = new ItemLeaf("google","www.google.com");
			
			$subMenu2->add($item3);  
			$subMenu2->add($item4);

			$allMenu = new MenuComposite("AllMenu");
			$allMenu->add($subMenu1);  
			$allMenu->add($subMenu2);  
			$allMenu->add($subMenu3);  
			$subMenu3->add($subMenu4);  
			$subMenu4->add($subMenu5);  
			$allMenu->displayOperation(); 
		}
	}

	Client::displayMenu();  
?>