<?php

class Host extends Table {
	var $table=[
		'name'=>[],
		'alias'=>[],
	];
	function get($json) {
		$json->text='test';
		return $json;
	}
}
