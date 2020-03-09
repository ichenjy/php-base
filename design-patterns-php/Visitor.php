<?php
	/**
	 * 访问者模式
	 */
	interface Visitor {
		public function visitConcreteElementA(ConcreteElementA $elementsA);
		public function visitConcreteElementB(ConcreteElementB $elementsB);
	}

	interface Element {
		public function accept(Visitor $visitor);
	}


	//具体访问者
	class ConcreteVisitor1 implements Visitor {
		public function visitConcreteElementA(ConcreteElementA $elementsA) {
			echo $elementA->getName(),' visitd by ConcerteVisitor1 <br />'; 
		}

		public function visitConcreteElementB(ConcreteElementB $elementsB) {
			echo $elementB->getName().' visited by ConcerteVisitor1 <br />';
		}
	}

	class ConcreteVisitor2 implements Visitor {
		public function visitConcreteElementA(ConcreteElementA $elementsA) {
			echo $elementA->getName(),' visitd by ConcerteVisitor2 <br />'; 
		}

		public function visitConcreteElementB(ConcreteElementB $elementsB) {
			echo $elementB->getName().' visited by ConcerteVisitor2 <br />';
		}
	} 
	

	//具体元素
	class ConcreteElementA implements Element {

		private $_name;

		public function __construct($name) {
			$this->_name = $name;
		}

		public function getName() {
			return $this->_name;
		}

		public function accept(Visitor $visitor) {
			$visitor->visitConcreteElementA($this);
		}
	}

	class ConcreteElementB implements Element {

		private $_name;

		public function __construct($name) {
			$this->_name = $name;
		}

		public function getName() {
			return $this->_name;
		}

		public function accept(Visitor $visitor) {
			$visitor->visitConcreteElementB($this);
		}
	}


	//对象结构
	class ObjectStructure {
		
		private $_collection;

		public function __construct() {
			$this->_collection = array();
		}

		public function attach(Element $element) {
			return array_push($this->_collection, $element);
		}

		public function detach(Element $element) {
			$index = array_search($element, $this->_collection);
			if (!$index==false) {
				unset($this->_collection[$index]);
			}
			
			return $index;
		}

		public function accept(Visitor $visitor) {
			foreach ($this->_collection as $element) {
				$element->accept($visitor);
			}
		}
	}


	class Client {
		public static function main() {
			$elementA = new ConcreteElementA("ElementA");  
			$elementB = new ConcreteElementB("ElementB");  
			$elementA2 = new ConcreteElementB("ElementA2");  
			$visitor1 = new ConcreteVisitor1();  
			$visitor2 = new ConcreteVisitor2();  

			$os = new ObjectStructure();  
			$os->attach($elementA);  
			$os->attach($elementB);  
			$os->attach($elementA2);  
			$os->detach($elementA);  
			$os->accept($visitor1);  
			$os->accept($visitor2); 
		}
	}

	Client::main();

?>