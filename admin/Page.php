<?php

class Page extends Table {
	var $table=[
		'name'=>[],
		'url'=>[ 'readonly'=>true ],

		'html'=>[ 'hidden'=>true ],
		'json'=>[ 'hidden'=>true ],
		'meta'=>[ 'hidden'=>true ],
	];
	function post($json) {
		global $db;
		$qry=$db->queryarray('select * from page');
		$json->data=$qry;
		$json->table=array_keys($this->table);
		return $json;
	}
	function getid($json) {
		global $db;
		if (isset($json->id)) {
			$qry=$db->queryrow('select * from page where id = ?',[ $json->id ]);
			$json->data=$qry;
			$json=$this->form($json);
		}
		return $json;
	}
	function put($json) {
		global $db;
		$db->autoupdate('page',$json->id,$json->data);
		return $json;
	}
}
