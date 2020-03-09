<?php
	abstract class ILift {
		//电梯的四个状态
		const OPENING_STATE = 1;	//门敞状态
		const CLOSING_STATE = 2;	//门闭状态
		const RUNNING_STATE = 3;	//运行状态
		const STOPPING_STATE = 4;	//停止状态

		//设置电梯状态
		public abstract function setState($state);

		//开启动作
		public abstract function open();

		//关闭动作
		public abstract function close();

		//上下动作
		public abstract function run();

		//停止
		public abstract function stop();
	}

	/**
	 * 实现类
	 */
	class Lift extends ILift {
		
		private $state;

		public function setState($state) {
			$this->state = $state;
		}

		//关闭操作
		public function close() {
			//什么状态下才关闭
			switch($this->state) {
				case ILift::OPENING_STATE:
					$this->setState(ILift::CLOSING_STATE);
					break;
				case ILift::CLOSING_STATE:
					// do nothing
					return ;
					break;
				case ILift::RUNNING_STATE:
					// do nothing
					return ;
					break;
				case ILift::STOPPING_STATE:
					// do nothing
					return ;
					break;
			}

			echo 'Lift colse <br>';
		}

		//开启动作
		public function open() {
			//什么状态下才打开
			switch($this->state) {
				case ILift::OPENING_STATE:
					// do nothing
					break;
				case ILift::CLOSING_STATE:
					$this->setState(ILift::OPENING_STATE);
					return ;
					break;
				case ILift::RUNNING_STATE:
					// do nothing
					return ;
					break;
				case ILift::STOPPING_STATE:
					// do nothing
					return ;
					break;
			}

			echo 'Lift open <br>';
		}

		//上下运动动作
		public function run() {
			switch($this->state) {
				case ILift::OPENING_STATE:
					// do nothing
					break;
				case ILift::CLOSING_STATE:
					$this->setState(ILift::RUNNING_STATE);
					return ;
					break;
				case ILift::RUNNING_STATE:
					// do nothing
					return ;
					break;
				case ILift::STOPPING_STATE:
					$this->setState(ILift::RUNNING_STATE);
					return ;
					break;
			}

			echo 'Lift run <br>';
		}

		//停止动作
		public function stop() {
			switch($this->state) {
				case ILift::OPENING_STATE:
					// do nothing
					break;
				case ILift::CLOSING_STATE:
					$this->setState(ILift::CLOSING_STATE);
					return ;
					break;
				case ILift::RUNNING_STATE:
					$this->setState(ILift::CLOSING_STATE);
					return ;
					break;
				case ILift::STOPPING_STATE:
					// do nothing
					return ;
					break;
			}

			echo 'Lift stop <br>';
		}
	}

	$lift = new Lift();
	//初始是停止状态
	$lift->setState(ILift::STOPPING_STATE);
	//首先电梯开启，人进入
	$lift->open();
	//然后电梯关闭
	$lift->close();
	//上下运动
	$lift->run();
	//达到目的，停止
	$lift->stop();
?>



<!-- ======================== 状态模式  ============================ -->

<?php
	/**
	 * 定义一个电梯的接口
	 */
	abstract class LiftState {
		//定义一个环境角色，也就是封装状态的变换引起的功能变化  
		protected  $_context;

		public function setContext(Context $context) {
			$this->_context = $context;
		}

		public abstract function open();

		public abstract function close();

		public abstract function run();

		public abstract function stop();
	}

	/**
	 * 环境类:定义客户感兴趣的接口。
	 */
	class Context {
		static $openningState = null;
		static $closeingState = null;
		static $runningState = null;
		static $stoppingState = null;

		public function __construct() {
			self::$openningState = new OpenningState();
			self::$closeingState = new CloseingState();
			self::$runningState = new RunningState();
			self::$stoppingState = new StoppingState();
		}

		private $_liftState;

		public function getLiftState() {
			return $this->_liftState;
		}

		public function setLiftState($liftState) {
			$this->_liftState = $liftState;
			$this->_liftState->setContext($this);
		}

		public function open() {
			$this->_liftState->open();
		}

		public function close() {
			$this->_liftState->close();
		}

		public function run() {
			$this->_liftState->run();
		}

		public function stop() {
			$this->_liftState->stop();
		}
	}


	//具体类（四种状态）
	class OpenningState extends LiftState {
		public function close() {
			$this->_context->setLiftState(Context::$closeingState);
			$this->_context->getLiftState()->close();
		}

		public function open() {
			echo 'lift open...', '<br/>'; 
		}

		public function run() {
			//do nothing
		}

		public function stop() {
			//do nothing
		}
	}

	class CloseingState extends LiftState {
		public function close() {
			echo 'lift close...', '<br/>';  
		}

		public function open() {
			$this->_context->setLiftState(Context::$openningState);  //置为门敞状态  
			$this->_context->getLiftState()->open();  
		}

		public function run() {
			$this->_context->setLiftState(Context::$runningState); //设置为运行状态；  
			$this->_context->getLiftState()->run();  
		}

		public function stop() {
			$this->_context->setLiftState(Context::$stoppingState);  //设置为停止状态；  
			$this->_context->getLiftState()->stop();  
		}
	}

	class RunningState extends LiftState {
		public function close() {
			//do nothing
		}

		public function open() {
			//do nothing
		}

		public function run() {
			echo 'lift run...', '<br/>';  
		}

		public function stop() {
			$this->_context->setLiftState(Context::$stoppingState); //环境设置为停止状态；  
			$this->_context->getLiftState()->stop();  
		}
	}

	class StoppingState extends LiftState {
		public function close() {
			//do nothing
		}

		public function open() {
			$this->_context->setLiftState(Context::$openningState);  
			$this->_context->getLiftState()->open();  
		}

		public function run() {
			$this->_context->setLiftState(Context::$runningState);  
			$this->_context->getLiftState()->run();  
		}

		public function stop() {
			echo 'lift stop...', '<br/>';
		}
	}


	//客户模拟
	class Client {
		public static function main() {
			$context = new Context();  
			$context->setLiftState(new CloseingState());  
  
			$context->open();  
			$context->close();  
			$context->run();  
			$context->stop();  
		}
	}

	Client::main();
?>