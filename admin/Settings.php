<?php

class Settings extends Table {
	var $table=[
		'host'=>[ 'select'=>'select id, name from host order by name', ],
		'name'=>[],
		'value'=>[],
	];
	function post($json) {
		global $db;
		$qry=$db->queryarray('select settings.*, host.name as host from settings, host where host.id = settings.host order by host, name');
		$json->data=$qry;
		$json->table=array_keys($this->table);
		return $json;
	}
	function getid($json) {
		global $db;
		if (isset($json->id)) {
			$qry=$db->queryrow('select * from settings where id = ?',[ $json->id ]);
			$json->data=$qry;
			$json=$this->form($json);
		}
		return $json;
	}
	function put($json) {
		global $db;
		$db->autoupdate('settings',$json->id,$json->data);
		$json->data=$db->queryrow('select settings.*, host.name as host from settings, host where host.id = settings.host order by host, name');
		return $json;
	}
}
