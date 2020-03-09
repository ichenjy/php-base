<!-- ======================== 抽象建造者  ============================ -->

<?php
	/**
	 * 抽象建造者
	 */
	abstract class Builder {
		
		/**
		 * part1
		 */
		public abstract function buildPart1();

		/**
		 * part2
		 */
		public abstract function buildPart2();

		/**
		 * return
		 */
		public abstract function getProduct();
	}

?>


<!-- ======================== 具体建造者类  ============================ -->

<?php
	/**
	 * 具体建造者类:餐馆员工,返回的套餐是：汉堡两个+饮料一个
	 */
	class ConcreteBuilder1 extends Builder {
		
		protected $_product = null;

		function __construct() {
			$this->_product = new Product();
		}
		
		/**
		 * part1:汉堡=2 
		 */
		public function buildPart1() {
			$this->_product->add('Hamburger', 2);
		}

		/**
		 * part2:饮料=1
		 */
		public function buildPart2() {
			$this->_product->add('Drink', 1);
		}

		/**
		 * return:
		 */
		public function getProduct() {
			return $this->_product;
		}

	}
?>

<?php
	/**
	 * 具体建造者类:餐馆员工,返回的套餐是：汉堡1个+饮料2个
	 */
	class ConcreteBuilder2 extends Builder {
		
		protected $_product = null;

		function __construct() {
			$this->_product = new Product();
		}

		/**
		 * part1:汉堡=1
		 */
		public function buildPart1() {
			$this->_product->add('Hamburger', 1);
		}

		/**
		 * part2:饮料=2
		 */
		public function buildPart2() {
			$this->_product->add('Drink', 2);
		}

		/**
		 * return:
		 */
		public function getProduct() {
			return $this->_product;
		}
	}
?>


<!-- ======================== 产品类  ============================ -->

<?php
	/**
	 * 产品类
	 */
	class Product {
		
		public $products = array();

		public function add($name, $value) {
			$this->products[$name] = $value;
		}

		public function showToClient() {
			foreach ($this->products as $k=>$vo) {
				echo $key.'=>'.$vo."\r\n";
			}
		}
	}

?>


<!-- ======================== 指导者:收银员  ============================ -->

<?php
	/**
	 * 指导者:收银员
	 */
	class DirectorCashier {

		public function buildFood(Builder $builder) {
			$builder->buildPart1();
			$builder->buildPart2();
		}
	}
?>



<!-- ======================== 实现类  ============================ -->

<?php
	class Client {
		
		public function buy($type) {
			$director = new DirectorCashier();
			$class = new ReflectionClass('ConcreteBuilder' . $type);
			$concreteBuilder = $class->newInstanceArgs();
			$director->buildFood($concreteBuilder);
			$concreteBuilder->getProduct()->showToClient();
		}
	}

	//测试  
	ini_set('display_errors', 'On');  
	$c = new Client();  
	$c->buy(1);//购买套餐1  
	$c->buy(2);//购买套餐1

?>