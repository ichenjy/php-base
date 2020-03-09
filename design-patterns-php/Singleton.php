
<?php
	class Singleton {
		
		static private $_instance = null; // 静态成员唯一实例

		private function __construct() {}

		public static function getInstance() {
			if (!self::$_instance) {
				self::$_instance = new self();
			}
			
			return self::$_instance;
		}
	}
?>


<!-- ======================== 单件模式可以多个实例 ============================ -->

<?php
	class User {
		
		static private $_instance = array();

		private $_uid;

		private function __construct($uid) {
			$this->_uid = $uid;
		}

		public static function getInstance($uid=0) {
			if (!self::$_instance || !isset(self::$_instance[$uid])) {
				self::$_instance[$uid] = new self($uid);
			}

			return self::$_instance[$uid];
		}
	}
?>