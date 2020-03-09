<!-- ======================== 单纯享元模式  ============================ -->

<?php
	/**
	 * 抽象享元角色
	 */
	abstract class Flyweight {
		/**
		 * 示意性方法
		 * @param string $state 外部状态 
		 */
		abstract public function operation($state);
	}


	/**
	 * 具体享元角色 
	 */
	class ConcreteFlyweight extends Flyweight {
		
		private $_intrinsicState = null;

		/**
		 * 构造方法 
		 * @param string $state  内部状态 
		 */
		public function __construct($state) {
			$this->_intrinsicState = $state;
		}

		public function operation($state) {
			echo 'ConcreteFlyweight operation, Intrinsic State = ' . $this->_intrinsicState  . ' Extrinsic State = ' . $state . '<br />';
		}
	}


	/**
	 * 享元工厂角色
	 */
	class FlyweightFactory {
		
		private $_flyweights;

		public function __construct() {
			$this->_flyweights = array();
		}

		public function getFlyWeight($state) {
			if (isset($this->_flyweights[$state])) {
				return $this->_flyweights[$state];
			} else {
				return $this->_flyweights[$state] = new ConcreteFlyweight($state);
			}
		}
	}


	class Client {
		static function main() {
			$flyweightFactory = new FlyweightFactory();
			$flyweight = $flyweightFactory->getFlyWeight('state A');
			$flyweight->operation('other state A');

			$flyweight = $flyweightFactory->getFlyWeight('state B');
			$flyweight->operation('other state B');
		}
	}
?>



<!-- ======================== 复合享元模式  ============================ -->

<?php
	/**
	 * 复合享元模式
	 */
	abstract class Flyweight {
		/**
		 * 示意性方法
		 * @param string $state 外部状态 
		 */
		abstract public function operation($state);
	}


	/**
	 * 具体享元角色 
	 */
	class ConcreteFlyweight extends Flyweight {
		
		private $_intrinsicState = null;

		/** 
		 * 构造方法 
		 * @param string $state  内部状态 
		 */  
		public function __construct($state) {  
			$this->_intrinsicState = $state;  
		}

		public function operation($state) {
			echo 'ConcreteFlyweight operation, Intrinsic State = ' . $this->_intrinsicState  . ' Extrinsic State = ' . $state . '<br />';
		}
	}

	
	/**
	 * 不共享的具体享元，客户端直接调用
	 */
	class UnsharedConcreteFlyweight extends Flyweight {

		private $_flyweights;

		/** 
		 * 构造方法 
		 * @param string $state  内部状态 
		 */
		public function __construct() {
			$this->_flyweights = array();
		}

		public function operation($state) {
			foreach ($this->_flyweights as $flyweight) {
				$flyweight->operation($state);
			}
		}

		public function add($state, Flyweight $flyweight) {
			$this->_flyweights[$state] = $flyweight;
		}
	}


	/**
	 * 享元工厂角色
	 */
	class FlyweightFactory {
		
		private $_flyweights;

		public function __construct() {
			$this->_flyweights = array();
		}

		public function getFlyWeight($state) {
			if (is_array($state)) {		//复合模式
				$uFlyWeight = new UnsharedConcreteFlyweight();
				foreach ($state as $vo) {
					$uFlyWeight->add($vo, $this->getFlyWeight($vo));
				}
				return $uFlyWeight;
			} elseif (is_string($state)) {
				if (isset($this->_flyweights[$state])) {
					return $this->_flyweights[$state];
				} else {
					return $this->_flyweights[$state] = new ConcreteFlyweight($state);
				}
			} else {
				return null;
			}
		}
	}


	class Client {
		static function main() {
			$flyweightFactory = new FlyweightFactory();
			$flyweight = $flyweightFactory->getFlyweigth('state A');
			$flyweight->operation('other state A');  

			$flyweight = $flyweightFactory->getFlyweigth('state B');  
			$flyweight->operation('other state B');
			
			// 复合对象
			$uflyweight = $flyweightFactory->getFlyweigth(array('state A', 'state B'));  
			$uflyweight->operation('other state A');  
		}
	}

?>