<?php

class Table {
	var $table=[];
	function write() {
		echo '<table name="',get_class(),'">';

		echo '<thead><tr>';
		foreach ($this->table as $key=>$value) {
			echo '<th data="',$key,'">',$key,'</th>';
		}
		echo '</tr></thead>';

		echo '<tbody></tbody>';

		echo '</table>';
	}
	static function select($name,$value) {
		global $db;
		ob_start();
		echo '<label>',$name,'</label>';
		echo '<select name="',$name,'">';
		// var_dump($value);
		foreach ($db->queryarray($value['select']) as $_value) {
			echo '<option value="',$_value->id,'">',$_value->name,'</option>';
		}
		echo '</select>';
		return ob_get_clean();
	}
	static function text($name,$value) {
		ob_start();
		echo '<label>',$name,'</label>';
		echo '<input name="',$name,'" />';
		return ob_get_clean();
	}
	function form($json) {
		$json->form=[];
		foreach ($this->table as $key=>$value) {
			// var_dump($value);
			if (isset($value['select']))
				$json->form[]=Table::select($key,$value);
			else
				$json->form[]=Table::text($key,$value);
		}
		return $json;
	}
}
