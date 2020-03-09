<?php
	/**
	 * 观察者模式
	 *
	 * 这一模式的概念是SplSubject类维护了一个特定状态，当这个状态发生变化时，它就会调用notify()方法。 
	 * 调用notify()方法时，所有之前使用attach()方法注册的SplObserver实例的update方法都会被调用。
	 */
	interface SplSubject {
		public function attach(SplObserver $observer);	//注册观察者
		public function detach(SplObserver $observer);	//释放观察者
		public function notify();	//通知所有注册的观察者
	}

	interface SplObserver {
		public function update(SplSubject $subject);
	}
	
?>


<!-- ======================== 代码实现(基础)  =========================== -->

<?php
	/**
	 * 具体目标 
	 */
	class ConcreteSubject implements SplSubject {
		
		private $observers, $value;

		public function __construct() {
			$this->observers = array();
		}

		public function attach(SplObserver $observer) {
			$this->observers[] = $observer;
		}

		public function detach(SplObserver $observer) {
			if ($idx = array_search($observer, $this->observers, true)) {
				unset($this->observers[$idx]);
			}
		}

		public function notify() {
			foreach ($this->observers as $observer) {
				$observer->update($this);
			}
		}

		public function setValue($value) {
			$this->value = $value;
			$this->notify();
		}

		public function getValue() {
			return $this->value;
		}
	}

	/**
	 * 具体观察者 
	 */
	class ConcreteObserver1 implements SplObserver {
		
		public function update(SplSubject $subject) {
			echo 'ConcreteObserver1  value is '. $subject->getValue() .'<br>';  
		}
	}
	
	class ConcreteObserver2 implements SplObserver {
		
		public function update(SplSubject $subject) {
			echo 'ConcreteObserver2  value is '. $subject->getValue() .'<br>';  
		}
	}

	$subject = new ConcreteSubject();  
	$observer1 = new ConcreteObserver1();  
	$observer2 = new ConcreteObserver2();  
	$subject->attach($observer1);  
	$subject->attach($observer2);  
	$subject->setValue(5); 

?>



<!-- ======================== 代码实现(扩展化)  =========================== -->

<?php
	/**
	 * 具体目标 
	 */
	class ConcreteSubject implements SplSubject {
		
		private $observers, $_state;

		public function __construct() {
			$this->observers = array();
		}

		/**
		 * 注册观察者
		 * 
		 * @param SplObserver $observer 
		 */
		public function attach(SplObserver $observer) {
			$this->observers[] = $observer;
		}

		/**
		 * 释放观察者 
		 *
		 * @param SplObserver $observer 
		 */
		public function detach(SplObserver $observer) {
			if ($idx = array_search($observer, $this->observers, true)) {
				unset($this->observers[$idx]);
			}
		}

		/**
		 * 通知所有观察者
		 */
		public function notify() {
			// 只要状态改变，就通知观察者
			foreach($this->observers as $observer) {
				if ($observer->getState() == $this->_state) {
					$observer->update($this);
				}
			}
		}

		/**
		 * 设置状态
		 */
		public function setState($state) {
			$this->_state = $state;
			$this->notify();
		}

		public function getState() {
			return $this->_state;
		}
	}


	/**
	 * 抽象观察者
	 */
	abstract class bserver {
		
		private $_state;

		function __construct($state) {
			$this->_state = $state;
		}

		public function setState($state) {
			$this->_state = $state;
			$this->notify();
		}

		public function getState() {
			return $this->_state;
		}
	}


	/**
	 * 具体观察者
	 */
	class ConcreteObserver1 extends bserver implements SplObserver {
		
		function __construct($state) {
			parent::__construct($state);
		}
		
		public function update(SplSubject $subject) {
			echo 'ConcreteObserver1  state is '. $subject->getState() .'<br>';
		}
	}

	class ConcreteObserver2 extends bserver implements SplObserver {
		
		function __construct($state) {
			parent::__construct($state);
		}
		
		public function update(SplSubject $subject) {
			echo 'ConcreteObserver2  state is '. $subject->getState() .'<br>';
		}
	}

	class ConcreteObserver3 extends bserver implements SplObserver {
		
		function __construct($state) {
			parent::__construct($state);
		}
		
		public function update(SplSubject $subject) {
			echo 'ConcreteObserver3  state is '. $subject->getState() .'<br>';
		}
	}

	$subject = new ConcreteSubject();  
	$observer1 = new ConcreteObserver1(1);  
	$observer2 = new ConcreteObserver2(1);  
	$observer3 = new ConcreteObserver3(2);  
	$subject->attach($observer1);  
	$subject->attach($observer2);  
	$subject->attach($observer3);  
	echo 'Subject state is 1', '<br>';  
	$subject->setState(1);  
	echo 'Subject state is 2', '<br>';  
	$subject->setState(2);
?>