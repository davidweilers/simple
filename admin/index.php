<?php

defined('SIMPLE') or die('?');

$page->name='Admin';

$classes=[
	'Host'=>[],
	// 'alias'=>[],
	'Settings'=>[],
	'Template'=>[],
	'Page'=>[],
	// 'host'=>[],
	// 'host'=>[],
	// 'host'=>[],
];

// unset($_SESSION['simple']);
$_SESSION['simple']=isset($_SESSION['simple']) ? $_SESSION['simple'] : new Simple($classes);
$simple=&$_SESSION['simple'];

if (isset($_url[1]) && !empty($_url[1])) {
	$simple->hash($_url[1]);
	if (isset($_url[2])) {
		$json=json_decode(file_get_contents('php://input'));
		$simple->hash->$_SERVER['REQUEST_METHOD']($json);
		echo json_encode($json);
		exit;
	}
	header('Location: /admin/');
	exit;
}

$head[]='/admin/style.css';

// $footer.='<script></script>';

echo '<nav><ul>';
foreach ($classes as $key=>$value) {
	if ('Host'!=$key) echo ' - ';
	echo '<li><a href="',$key,'">',$key,'</a></li>';
}
echo '</ul></nav>';

if ($_SERVER['REQUEST_METHOD']=='GET') {
	echo $simple->write();
}

// var_dump($simple->hash);
