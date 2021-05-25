<?php

class Host extends Table {
	var $table=[
		'name'=>[],
		'alias'=>[],
	];
	function post($json) {
		global $db;
		$qry=$db->queryarray('select * from host order by name');
		foreach ($qry as $key=>$value) {
			$a=$db->queryarray('select * from alias where host = ? order by name',[ $value->id ]);
			$b='';
			foreach ($a as $c) $b.=(empty($b) ? '' : ', ').$c->name;
			$qry[$key]->alias=$b;
		}
		$json->data=$qry;
		$json->table=array_keys($this->table);
		return $json;
	}
	function getid($json) {
		global $db;
		if (isset($json->id)) {
			$qry=$db->queryrow('select * from host where id = ?',[ $json->id ]);
			$json->data=$qry;
		}
		return $json;
	}
	function put($json) {
		global $db;
		return $json;
	}
}
