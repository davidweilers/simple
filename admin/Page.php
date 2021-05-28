<?php

class Page extends Table {
	var $table=[
		'name'=>[],
		'url'=>[ 'readonly'=>true ],

		'html'=>[ 'hidden'=>true ],
		'json'=>[ 'hidden'=>true ],
		'meta'=>[ 'hidden'=>true ],
	];
	var $_page=__CLASS__;
	function post($json) {
		global $db;
		$qry=$db->queryarray('select * from page');
		$json->data=$qry;
		$json->table=array_keys($this->table);
		return $json;
	}
	function get($id) {
		global $db, $footer;
		$qry=$db->queryrow('select * from page where id = ?',[ $id ]);
		// var_dump($qry);
		ob_start();?>
		<script>
			var page = <?=json_encode($qry);?>;
			<?=file_get_contents(__DIR__.'/edit.js'); ?>
		</script>
		<? $footer.=ob_get_clean();
		echo '<main contenteditable="true">',$qry->html,'</main>';
	}
	function getid($json) {
		global $db;
		if (isset($json->id)) {
			$qry=$db->queryrow('select * from page where id = ?',[ $json->id ]);
			$this->page=$qry;
			$json->data=$qry;
			$json->form=['location'=>'/admin/Page/'.$json->id];
			// $json=$this->form($json);
		}
		return $json;
	}
	function put($json) {
		global $db;
		$db->autoupdate('page',$json->id,$json->data);
		return $json;
	}
}
