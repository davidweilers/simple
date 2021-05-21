<?php

require 'head.php';

// ---

ob_start();

try {
	$db=new EPDO('db.ini');

	$url=isset($_GET['page']) ? $_GET['page'] : '';
	$_url=explode('/',$url);

	$ini=parse_ini_file('db.ini',TRUE) or call_user_func(function(){throw new Exception("db.ini?");});

	if ($_url[0]=='admin') {
		$page=new stdClass;
		require 'admin/index.php';
	}	else
	if ($page=$db->queryrow('select * from page where url = ?',[ $url ])) {
		var_dump($page);
		require 'main.php';
	}	else {
		require 'db/db.php';
		// header('Location: /');
		exit;
	}
}	catch (PDOException $e) {
	require 'db/db.php';
	exit;
}	catch (Exception $e) {
	ob_clean();
	header("Content-Type: text/plain");
	echo $e;
	exit;
}

$html=ob_get_clean();

$template=file_get_contents('templates/template.html') or die(__LINE__);

if ($page==false) {
	$page=new stdClass;
	http_response_code(404);
	$html.='404';
}

$_templates=[
	'title'=>isset($page->name) ? $page->name : '?',
	'html'=>$html,
	'head'=>array_reduce($head,function($a,$b) {
		if (!is_null($a)) $a.="\n\t";
		return $a.'<link rel="stylesheet" href="'.$b.'">';
	}),
	'footer'=>$footer,
];

$template=preg_replace_callback('/\[([a-z]+)\]/i',function($match) use ($_templates) {
	if (isset($match[1])) return $_templates[$match[1]];
	return '?';
},$template);

echo $template;

?>