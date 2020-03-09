<?php
	/**
	 * 外观模式
	 */
	class SwitchFacade {
		private $_light = null;		//电灯
		private $_ac = null;		//空调
		private $_fan = null;		//电扇
		private $_tv = null;		//电视

		public function __construct() {
			$this->_light = new Light();
			$this->_ac = new AirConditioner();
			$this->_fan = new Fan();
			$this->_tv = new Television();
		}

		// 晚上开灯
		public function method1($isOpen=1) {
			if ($isOpen==1) {
				$this->_light->on();  
				$this->_fan->on();  
				$this->_ac->on();  
				$this->_tv->on(); 
			} else {
				$this->_light->off();  
				$this->_fan->off();  
				$this->_ac->off();  
				$this->_tv->off(); 
			}
		}

		// 白天不开灯
		public function method2() {
			if ($isOpen==1) {
				$this->_fan->on();
				$this->_ac->on();
				$this->_tv->on();
			} else {
				$this->_fan->off();
				$this->_ac->off();
				$this->_tv->off();
			}
		}
	}


	//--------------------- 子系统类 ----------------------
	class Light {
		private $_isOpen = 0;

		public function on() {
			echo 'Light is open', '<br/>';
			$this->_isOpen = 1;
		}

		public function off() {
			echo 'Light is off', '<br/>';  
			$this->_isOpen = 0;
		}
	}

	class Fan {
		private $_isOpen = 0;

		public function on() {
			echo 'Fan is open', '<br/>';
			$this->_isOpen = 1;
		}

		public function off() {
			echo 'Fan is off', '<br/>';  
			$this->_isOpen = 0;
		}
	}

	class AirConditioner {
		private $_isOpen = 0;

		public function on() {
			echo 'AirConditioner is open', '<br/>';
			$this->_isOpen = 1;
		}

		public function off() {
			echo 'AirConditioner is off', '<br/>';  
			$this->_isOpen = 0;
		}
	}

	class Television {
		private $_isOpen = 0;

		public function on() {
			echo 'Television is open', '<br/>';
			$this->_isOpen = 1;
		}

		public function off() {
			echo 'Television is off', '<br/>';  
			$this->_isOpen = 0;
		}
	}

	
	/**
	 * 客户类
	 */
	class Client {
		
		static function open() {
			$f = new SwitchFacade();
			$f->method1(1);
		}

		static function close() {
			$f = new SwitchFacade();
			$f->method1(0);
		}
	}

	Client::open();

?>