<?php
	/**
	 * 装饰器模式
	 * !抽象组件角色（Component）:定义一个对象接口，以规范准备接受附加责任的对象，即可以给这些对象动态地添加职责。 
	 * !具体组件角色（ConcreteComponent） :被装饰者，定义一个将要被装饰增加功能的类。可以给这个类的对象添加一些职责。
	 * !抽象装饰器（Decorator）:维持一个指向构件Component对象的实例，并定义一个与抽象组件角色Component接口一致的接口。 
	 * 具体装饰器角色（ConcreteDecorator): 向组件添加职责。
	 */
	
	//抽象组件角色（Component）
	class ComponentWidget {
		function paint() {
			return $this->_asHtml();
		}
	}

	/**
	 * 具体组件角色（ConcreteComponent）
	 * 基本的text输入组件开始。它（组件）必须要包含输入区域的名字（name）而且输入内容可以以HTML的方式渲染。 
	 */
	class ConcreteComponentTextInput extends ComponentWidget {
		protected $_name;
		protected $_value;

		function textInput($name, $value='') {
			$this->_name = $name;
			$this->_value = $value;
		}

		function _asHtml() {
			return '<input type="text" name="'.$this->_name.'" value="'.$this->_value.'">';  
		}
	}

	/**
	 * 抽象装饰器（Decorator）:维持一个指向构件Component对象的实例，并定义一个与抽象组件角色Component接口一致的接口。
	 */
	class WidgetDecorator {
		protected $_widget;

		function __construct(&$widget) {
			$this->_widget = $widget;
		}

		function paint() {
			return $this->_widget->paint();
		}
	}

	/**
	 * 具体装饰器角色(ConcreteDecorator)
	 */ 
	class ConcreteDecoratorLabeled extends WidgetDecorator {
		protected $_label;

		function __construct($label, &$widget) {
			$this->_label = $label;
			parent::__construct($widget);
		}

		function paint() {
			return '<b>'.$this->_label.':</b> '.$this->_widget->paint();  
		}
	}



	//实现
	class FormHandler {
		static function build(&$post) {
			return array(
				new ConcreteDecoratorLabeled('First Name', new ConcreteComponentTextInput('fname', $post->get('fname'))),
				new ConcreteDecoratorLabeled('Last Name', new ConcreteComponentTextInput('lname', $post->get('lname'))),
				new ConcreteDecoratorLabeled('Email', new ConcreteComponentTextInput('email', $post->get('email'))),
			);
		}
	}

	//通过$_post提交的数据
	class Post {
		private $store = array();

		function get($key) {
			if (array_key_exists($key, $this->store))
				return $this->store[$key];
		}

		function set($key, $val) {
			$this->store[$key] = $val;
		}

		static function autoFill() {
			$ret = new self();
			foreach($_POST as $k=>$vo) {
				$ret->set($k, $vo);
			}
			return $ret;
		}
	}

?>

<!------------ 扩展化 ------------->

<?php

	class Invalid extends WidgetDecorator {
		function paint() {
			return  '<span  class="invalid">'.$this->widget->paint().'</span>';
		}
	}


	class FormHandler {
		function build(&$post) {
			return array(
				new ConcreteDecoratorLabeled('First Name', new ConcreteComponentTextInput('fname', $post->get('fname'))),
				new ConcreteDecoratorLabeled('Last Name', new ConcreteComponentTextInput('lname', $post->get('lname'))),
				new ConcreteDecoratorLabeled('Email', new ConcreteComponentTextInput('email', $post->get('email'))),
			);
		}

		function validate(&$form, &$post) {
			$valid = true;
			if (!strlen($post->get('fname'))) {
				$form[0] = &new Invalid($form[0]);
				$valid = false;
			}

			if (!strlen($post->get('lname'))) {
				$form[1] = &new Invalid($form[1]);
				$valid = false;
				if (!preg_match('~\w+@(\w+\.)+\w+~', $post->get('email'))) {
					$form[2] = &new Invalid($form[2]);
					$valid = false;
				}

				return $valid;
			}
		}
	}

?>



<!-- ======================== 静态HTML  ============================ -->

<?php
	
	<<<EOT
		<form action="" method="post">
	EOT;

	$post = &POST::autoFill();
	$form = FormHandler::build($post);
	foreach($form as $widget) {
		echo $widget->paint() ."\r\n";
	}

	<<<EOT
			<input type="submit" value="提交"/>
		</form>
	EOT;
?>
