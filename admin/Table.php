<?php

class Table {
	var $table=[];
	var $_page='form';
	function write() {
		echo '<p><a href="#add">Add</a></p>';
		echo '<table name="',get_class(),'">';

		echo '<thead><tr>';
		foreach ($this->table as $key=>$value) {
			if (!isset($value['hidden']))
				echo '<th data="',$key,'">',$key,'</th>';
		}
		echo '</tr></thead>';

		echo '<tbody></tbody>';

		echo '</table>';
		echo '<p><a href="#add">Add</a></p>';
	}
	static function select($name,$value,$json) {
		global $db;
		ob_start();
		echo '<label for="',$name,'">',$name,'</label>';
		echo '<select name="',$name,'">';
		// var_dump($value);
		foreach ($db->queryarray($value['select']) as $_value) {
			echo '<option value="',$_value->id,'"',($_value->id==$json->$name ? ' selected' : ''),'>',$_value->name,'</option>';
		}
		echo '</select>';
		return ob_get_clean();
	}
	static function plaintext($name,$value,$json) {
		ob_start();
		echo '<label for="',$name,'">',$name,'</label>';
		var_dump($json->alias);
		return ob_get_clean();
	}
	static function readonly($name,$value,$json,$type='text') {
		return Table::text($name,$value,$json,'text',[ 'readonly'=>true ]);
	}
	static function text($name,$value,$json,$type='text',$array=[]) {
		ob_start();
		$_array='';
		foreach ($array as $_key=>$_value) {
			$_array.=$_key.'="'.$_value.'"';
		}
		echo '<label for="',$name,'">',$name,'</label>';
		echo '<input name="',$name,'" type="',$type,'" value="',$json->$name,'"',$_array,' />';
		return ob_get_clean();
	}
	function form($json) {
		$json->form=[];
		foreach ($this->table as $key=>$value) {
			// var_dump($value);
			// if (isset($value['hidden']))
			// 	;
			// else
			if (isset($value['plaintext']))
				$json->form[]=Table::plaintext($key,$value,$json->data);
			else
			if (isset($value['select']))
				$json->form[]=Table::select($key,$value,$json->data);
			else
			if (isset($value['readonly']))
				$json->form[]=Table::readonly($key,$value,$json->data);
			else
				$json->form[]=Table::text($key,$value,$json->data,$type='text');
		}
		return $json;
	}
}
