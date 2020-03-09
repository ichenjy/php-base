
<?php
	/**
	 * 抽象原型角色
	 */
	interface Prototype {
		public function copy();
	}


	/**
	 * 具体原型角色
	 */
	class ConcretePrototype implements Prototype {
		
		private $_name;

		public function __construct($name) {
			$this->_name = $name;
		}

		public function setName($name) {
			$this->_name = $name;
		}

		public function getName() {
			return $this->_nama;
		}

		public function copy() {
			/** 深拷贝 */
			return clone $this;
			/** 浅拷贝 */
			//return $this;
		}
	}


	class Client {
		public static function main() {
			$object1 = new ConcretePrototype(11);
			$object_copy = $object1->copy();

			var_dump($object1->getName());
			echo '<br/>';
			var_dump($object_copy->getName());
			echo '<br/>';

			$object1->setName(22);
			var_dump($object1->getName());
			echo '<br/>';
			var_dump($object_copy->getName());
			echo '<br/>';
		}
	}

	Client::main();
?>



<!-- ======================== 带Prototype Manager的原型模式  ============================ -->

<?php
	
	/**
	 * abstract Prototype
	 */
	abstract class ColorPrototype {
		abstract function copy();
	}

	/**
	 * Concrete Prototype
	 */
	class Color extends ColorPrototype {
		private $red;
		private $green;
		private $blue;

		function __construct($red, $green, $blue) {
			$this->red = $red;
			$this->green = $green;
			$this->blue = $blue;
		}

		public function setRed($red) {
			$this->red = $red;
		}

		public function getRed() {
			return $this->red;
		}

		public function setGreen($green) {
			$this->green = $green;
		}

		public function getGreen() {
			return $this->green;
		}

		public function setBlue($blue) {
			$this->blue = $blue;
		}

		public function getBlue() {
			return $this->blue;
		}

		function copy() {
			return clone $this;
		}

		function display() {
			echo $this->red.','.$this->green.','.$this->blue.'<br/>';
		}
	}


	class ColorManager {
		static $colors = array();

		public static function add($name, $value) {
			self::$colors[$name] = $value;
		}

		public static function getCopy($name) {
			return self::$colors[$name]->copy();
		}
	}


	/**
	 * Client
	 */
	class Client {
		public static function Main() {
			//原型：白色
			ColorManager::add('white', new Color(255,0,0));
			
			//红色由原型白色对象得到，只是重新修改白色：r
			$red = ColorManager::getCopy('white');
			$red->setRed(255);
			$green->display();

			//绿色由原型白色对象得到，只是重新修改白色：g
			$green = ColorManager::getCopy('white');
			$green->getGreen(255);
			$green->display();

			//蓝色由原型白色对象得到，只是重新修改白色：b
			$blue = ColorManager::get('white');
			$blue->setBlue(255);
			$blue->display();
		}
	}


	ini_set('display_errors', 'On');
	error_reporting(E_ALL & ~ E_DEPRECATED);
	Client::Main();

?>