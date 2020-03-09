<?php
	/**
	 * 类适配器模式
	 * @version 1.0
	 */
	class Target {
		
		public function hello() {
			echo 'Hello';
		}

		public function world() {
			echo 'world';
		}
	}

	/**
	 * Client程序
	 */
	class Client {
		
		public static function main() {
			$target = new Target();
			$target->hello();
			$target->world();
		}
	}

	Client::main();
?>



<!-- ======================== && ============================ -->

<?php
	/**
	 * 类适配器模式
	 * @version 2.0
	 */
	class Target {
		
		public function greet() {
			echo 'Greet';
		}

		public function world() {
			echo 'world';
		}
	}
	
?>

<!----------- 类适配器的继承方式 ------------->

<?php
	/**
	 * 类适配器模式
	 * @version 2.1
	 */
	interface Target {
		
		public function hello();

		public function world();
	}


	/** 
	 * 源角色:被适配的角色
	 */
	class Adaptee {
		
		public function world() {
			echo 'world <br/>';
		}

		public function greet() {
			echo 'Greet';
		}
	}


	/**
	 * 类适配器角色
	 */
	class Adapter extends Adaptee implements Target {
		
		public function hello() {
			parent::Target();
		}
	}


	/**
	 * 客户端程序
	 */
	class Client {
		
		public static function main() {
			$adapter = new Adapter();
			$adapter->hello();
			$adapter->world();
		}
	}

	Client::main();

?>

<!----------- 类适配器的委派方式 ------------->

<?php
	/**
	 * 类适配器模式
	 * @version 2.2
	 */
	interface Target {

		public function hello();

		public function world();
	}


	/** 
	 * 源角色:被适配的角色
	 */
	class Adaptee {
		
		public function world() {
			echo 'world <br/>';
		}

		public function greet() {
			echo 'Greet';
		}
	}


	/**
	 * 类适配器角色
	 */
	class Adapter implements Target {
		
		private $_adaptee;

		public function __construct(Adaptee $adaptee) {
			$this->_adaptee = $adaptee;
		}

		public function hello() {
			$this->_adaptee->greet();
		}

		public function world() {
			$this->_adaptee->world();
		}
	}


	/**
	 * 客户端程序
	 */
	class Client {
		
		public static function main() {
			$adaptee = new Adaptee();
			$adapter = new Adapter($adaptee);
			$adapter->hello();
			$adapter->world();
		}
	}

	Client::main();

?>