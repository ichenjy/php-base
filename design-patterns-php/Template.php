<?php
	/**
	 * 模板方法模式
	 *
	 * 抽象类
	 */
	abstract class AbstractBank {
		
		private $_number;

		/**
		 * 模板方法:定义为final的方法,从而保证了子类的逻辑永远由父类所控制。
		 */
		public final function templateMethodProcess() {
			$this->takeNumber();
			$this->transact();
			if ($this->isEvaluateHook()) {
				$this->evaluateHook();
			}
		}

		protected abstract function transact();

		protected function evaluaHook() {
			echo 'evaluateHook<br/>';
		}

		protected function isEvaluateHook() {
			return true;
		}

		private function takeNumber() {
			return ++$this->_number;
		}
	}

	
	//具体子类 {存款}
	class ConcreteDeposit extends AbstractBank {
		public function transact() {
			echo 'Deposit', '<br>'; 
		}
	}
	

	// {取款}
	class ConcreteWithdraw extends AbstractBank {
		public function transact() {
			echo 'Withdraw', '<br>'; 
		}
	}


	// {转账}
	class ConcreteTrancfer extends AbstractBank {
		public function transact() {
			echo 'Trancfer', '<br>';  
		}
	}

	$c = new ConcreteTrancfer();
	$c->templateMethodProcess();

?>