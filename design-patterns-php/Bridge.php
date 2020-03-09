<?php
	/**
	 * ---------- Abstraction ----------
	 *
	 * 抽象类的接口
	 */
	abstract class BrushPenAbstraction {
		
		protected $_implementorColor = null;

		public function setImplementorColor(ImplementorColor $color) {
			$this->_implementorColor = $color;
		}

		public abstract function operationDraw();
	}

	
	/**
	 * ---------- RefinedAbstraction ----------
	 *
	 * 扩充由Abstraction;大毛笔
	 */
	class BigBrushPenRefinedAbstraction extends BrushPenAbstraction {
		public function operationDraw() {
			echo 'Big and '.$this->_implementorColor->bepaint().' drawing';
		}
	}

	/**
	 * 扩充由Abstraction;中毛笔 
	 */
	class MiddleBrushPenRefinedAbstraction extends BrushPenAbstraction {
		public function operationDraw() {
			echo 'Middle and '.$this->_implementorColor->bepaint().' drawing';
		}
	}

	/**
	 * 扩充由Abstraction;小毛笔
	 */
	class SmallBrushPenRefinedAbstraction extends BrushPenAbstraction {
		public function operationDraw() {
			echo 'Small and '.$this->_implementorColor->bepaint().' drawing';
		}
	}
?>

<?php
	/**
	 * 实现类接口(Implementor) 
	 */
	class ImplementorColor {
		
		protected $value;

		//着色
		public function bepaint() {
			echo $this->value;
		}
	}

	
	class oncreteImplementorRed extends ImplementorColor {
		
		public function __construct() {
			$this->value = 'red';
		}

		public function bepaint() {
			echo $this->value;
		}
	}


	class oncreteImplementorBlue extends ImplementorColor {
		
		public function __construct() {
			$this->value = 'blue';
		}
	}


	class oncreteImplementorGreen extends ImplementorColor {
		
		public function __construct() {
			$this->value = 'green';
		}
	}

	
	class oncreteImplementorWhite extends ImplementorColor {
		
		public function __construct() {
			$this->value = 'white';
		}
	}


	class oncreteImplementorBlack extends ImplementorColor {
		
		public function __construct() {
			$this->value = 'black';
		}
	}


	/**
	 * 客户端实现
	 */
	class Client {

		public static function main {
			//小笔画红色
			$objRAbstraction = new SmallBrushPenRefinedAbstraction();
			$objRAbstraction->setImplementorColor(new oncreteImplementorRed());
			$objRAbstraction->operationDraw();
		}
	}

	Client::main();
?>