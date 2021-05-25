<?php

class Simple {
	var $classes;
	function __construct($classes=[]) {
		$this->time=date('dmYHis');
		foreach ($classes as $key=>$value) {
			$this->classes[$key]=new $key();
		}
	}
}
