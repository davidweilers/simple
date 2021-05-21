<?php

class Simple {
	var $classes;
	var $hash=null;
	function __construct($classes=[]) {
		$this->time=date('dmYHis');
		foreach ($classes as $key=>$value) {
			$this->classes[$key]=new $key();
			if ($this->hash==null)
				$this->hash=&$this->classes[$key];
		}
	}
	function hash($hash) {
		$this->hash=&$this->classes[$hash];
		// var_dump($hash);
	}
	function write() {
		return $this->hash->write();
	}
}
