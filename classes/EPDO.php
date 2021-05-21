<?php

// use \PDO;

class EPDO extends PDO {
	var $settings=[];
	function __construct($file) {
        if (!$settings = parse_ini_file($file, TRUE)) throw new Exception('Unable to open ' . $file . '.');
        
		$this->settings=$settings;
        $dns = $settings['driver'] . ':' .
        (isset($settings['host']) ? 'host=' . $settings['host'] . ';' : '' ).
        ((!empty($settings['port'])) ? ('port=' . $settings['port']) . ';' : '') .
        ($settings['driver']=='sqlite' ? '':'dbname=') . $settings['schema'];
		
		// var_dump($settings,$dns);

		parent::__construct($dns,
			isset($settings['username']) ? $settings['username'] : '',
			isset($settings['password']) ? $settings['password'] : '',
			[
        		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        	]);
    }
	function autoinsert($tbl,$values) {
		$qry="insert into $tbl (".implode(",",array_keys($values)).") values (".implode(",",array_fill(0,sizeof($values),"?")).")";
		$params=array_values($values);

		//echo $qry."\n".implode(", ",$params)."\n";

		return $this->queryupdate($qry,$params);
	}
	function autoupdate($tbl,$id,$values,$idcol="id") {
		$params=array();
		$qry="update $tbl set ";
		$idx=0;
		foreach ($values as $fn=>$v) {
			$qry.=($idx++>0 ? ", " : "")."$fn = ?\n";
			$params[]=$v;
		}
		$qry.="where $idcol = ?";$params[]=$id;

		return $this->queryupdate($qry,$params);
	}
	function queryrow($qry,$params=array()) {
		if (!$p=$this->prepare($qry)) {
			$e=$this->errorInfo();
			return;
			// throw new PDOException($e[2]."\n$qry (".implode(", ",$params).")");
		}
		if (!$p->execute($params)) {
			$e=$p->errorInfo();
			// throw new PDOException($e[2]."\n$qry (".implode(", ",$params).")");
		}
		$tmp=$p->fetch(PDO::FETCH_OBJ);
		$p->closeCursor();
		return $tmp;
	}
	function queryvalue($qry,$params=array()) {
		if (!$p=$this->prepare($qry)) {
			$e=$this->errorInfo();
			return;
			// throw new \PDOException($e[2]."\n$qry (".implode(", ",$params).")");
		}
		if (!$p->execute($params)) {
			$e=$p->errorInfo();
			// throw new \PDOException($e[2]."\n$qry (".implode(", ",$params).")");
		}
		$a=$p->fetch(PDO::FETCH_NUM);
		return $a[0];
	}
	function queryarray($qry,$params=array(),$id=false) {
		if (!$p=$this->prepare($qry)) {
			$e=$this->errorInfo();
			return;
			// throw new \PDOException($e[2]."\n$qry (".implode(", ",$params).")");
		}
		if (!$p->execute($params)) {
			$e=$p->errorInfo();
			// throw new \PDOException($e[2]."\n$qry (".implode(", ",$params).")");
		}
		$tmp=array();
		if ($id==true) {
			while ($a=$p->fetch(PDO::FETCH_OBJ))
				$tmp[$a['id']]=$a;
		}	else
			while ($a=$p->fetch(PDO::FETCH_OBJ))
				$tmp[]=$a;
		$p->closeCursor();
		return $tmp;
	}
	function queryupdate($qry,$params=array()) {
		if (!$p=$this->prepare($qry)) {
			$e=$this->errorInfo();
			// return;
			throw new \PDOException($e[2]."\n$qry (".implode(", ",$params).")");
		}
		if (!$p->execute($params)) {
			$e=$p->errorInfo();
			throw new \PDOException($e[2]."\n$qry (".implode(", ",$params).")");
		}
		$p->closeCursor();
		return true;
	}
	function querykeyvalues($qry,$params=array()) {
		if (!$p=$this->prepare($qry)) {
			$e=$this->errorInfo();
			return;
			// throw new \PDOException($e[2]."\n$qry (".implode(", ",$params).")");
		}
		if (!$p->execute($params)) {
			$e=$this->errorInfo();
			// throw new \PDOException($e[2]."\n$qry (".implode(", ",$params).")");
		}
		$tmp=array();
		while ($a=$p->fetch(PDO::FETCH_NUM)) {
			$tmp[$a[0]]=$a[1];
		}
		return $tmp;
	}
};

?>
