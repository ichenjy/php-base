<?php
	/**
	 * 车子系列
	 */
	Class BMW320 {
		function __construct($pa) {	
		}
	}

	Class BMW523 {
		function __construct($pb) {

		}
	}


	/**
	 * 客户自己创建马车
	 */
	 class Customer {
		function createBMW320 {
			return new BMW320();
		}

		function createBMW523 {
			return new BMW523();
		}
	 }
?>


<!-- ======================== 简单工厂模式 ============================ -->

<!-------------- 产品类 --------------->
<?php
	/**
	 * 车子系列
	 */
	abstract class BWM {
		function __contruct($pa) {

		}
	}

	class BWM320 extends BWM {
		function __construct($pa) {

		}
	}

	class BMW523 extends BWM {
		function __construct($pb) {

		}
	}
?>




<!-------------- 工厂类 --------------->
<?php
	/**
	 * 工厂创建车
	 */
	class Factory {
		static function createBMW($type) {
			switch($type) {
				case 320:
					return new BWM320();
				case 523:
					return new BMW523();
				//...
			}
		}
	}
?>




<!-------------- 客户类 --------------->
<?php
	/**
	 * 客户通过工厂获取车
	 */
	class Customer {

		private $BMW;

		function getBMW($type) {
			$this->BMW = Factory::createBMW($type);
		}
	}
?>


<!-- ======================== 工厂方法模式  ============================ -->

<!-------------- 产品类 --------------->
<?php
	/**
	 * 车子系列
	 */
	abstract class BWM {
		function __construct($pa) {

		}
	}

	class BWM320 extends BWM {
		function __construct($pa) {

		}
	}

	class BWM523 extends BWM {
		function __constrct($pb) {
		
		}
	}

?>

<!-------------- 创建工厂类 --------------->
<?php
	/**
	 * 创建工厂的接口
	 */
	interface FactoryBMW {
		function createBMW();
	}

	/**
	 * !BMW320车
	 */
	class FactoryBMW320 implements FactoryBMW {
		function createBMW($type) {
			return new BWM320();
		}
	}

	/**
	 * !BMW523车
	 */
	class FactoryBMW523 implements FactoryBMW {
		function createBMW($type) {
			return new BWM523();
		}
	}

?>

<!-------------- 客户类 --------------->
<?php
	/**
	 * 客户获取车
	 */
	class Customer {
		
		private $BMW;

		function getBMW($type) {
			switch($type) {
				case 320:
					$BMW320 = new FactoryBMW320();
					return $BMW320->createBMW();
				case 523:
					$BWM523 = new FactoryBMW523();
					return $BWM523->createBMW();
			}
		}
	}


	//// 反射机制
	class rCustomer {
		
		private $BMW;

		function getBMW($type) {
			$class = new ReflectionClass('FactoryBMW' . $type );
			$instance = $class->newInstanceArgs();
			return $instance->createBMW();
		}
	}
?>



<!-- ======================== 工厂方法模式  ============================ -->

<!-------------- 产品类 --------------->
<?php
	/**
	 * 车子系列以及型号
	 */
	abstract class BMW {
	}

	class BMW523 extends BMW {
	}

	class BMW320 extends BMW {
	}

	/**
	 * 空调
	 */
	abstract class aircondition {
	}

	class aircondition523 extends aircondition {
	}

	class aircondition320 extends aircondition {
	}
?>

<!-------------- 创建工厂类 --------------->
<?php
	/**
	 * 工厂类的接口
	 */
	interface FactoryBMW {
		function createBMW();
		function createAirc();
	}

	/**
	 * !BWM320车
	 */
	class FactoryBWM523 implements FactoryBMW {
		function createBMW() {
			return new BMW523();
		}

		function createAirc() {
			return new aircondition523();
		}
	}

	/**
	 * !BWM523车
	 */
	class FactoryBMW320 implements FactoryBMW {
		function createBMW() {
			return new BMW320();
		}

		function createAirc() {
			return new aircondition320();
		}
	}

?>

<!-------------- 客户类 --------------->
<?php
	/**
	 * 客户获取车
	 */
	class Customer {
		
		private $BMW;

		private $airc;

		function getBMW($type) {
			$class = new ReflectionClass('FactoryBMW' . $type );
			$instance = $class->newInstanceArgs();
			$this->BMW = $instance->createBMW();
			$this->airc = $instance->createAirc();
		}
	}
?>