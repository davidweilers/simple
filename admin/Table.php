<?php

class Table {
	var $table=[];
	function write() {
		echo '<table name="',get_class(),'">';

		echo '<thead><tr>';
		foreach ($this->table as $key=>$value) {
			echo '<th>',$key,'</th>';
		}
		echo '</tr></thead>';

		echo '<tbody></tbody>';

		echo '</table>';
	}
}
