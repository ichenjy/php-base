<?php
	/**
	 * 具体迭代器(ConcreteIterator):  具体迭代器实现迭代器Iterator接口。对该聚合遍历时跟踪当前位置。
	 */
	class ConcreteIterator implements Iterator {
		
		protected $_key;

		protected $_collection;

		public function __construct($collection) {
			$this->_collection = $collection;
			$this->_key = 0;
		}

		public function rewind() {
			$this->_key = 0;
		}

		public function valid() {
			return isset($this->_collection[$this->_key]);
		}

		public function key() {
			return $this->_key;
		}

		public function current() {
			return $this->_collection[$this->_key];
		}

		public function next() {
			return ++$this->_key;
		}
	}


	/**
	 * 具体聚合类(ConcreteAggregate):
	 */
	class ConcreteAggregate implements IteratorAggregate {
		
		protected $_arr;

		public function __construct($array) {
			$this->_arr = $array;
		}

		public function getIterator() {
			return new ConcreteIterator($this->_arr);
		}
	}

	$_collections = array(1,2,3,3,4);
	$its = new ConcreteIterator($_collections);
	foreach($its as $k=>$vo) {
		echo $k.":".$vo."<br/>";
	}
?>