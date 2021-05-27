<?php

defined('SIMPLE') or die('?');

$page->name='Admin';

$classes=[
	'Host'=>[],
	'Settings'=>[],
	'User'=>[],
	'Template'=>[],
	'Page'=>[],
	'Form'=>[],
];

// unset($_SESSION['simple']);
$_SESSION['simple']=isset($_SESSION['simple']) ? $_SESSION['simple'] : new Simple($classes);
$simple=&$_SESSION['simple'];

if (/*isset($_url[1]) && !empty($_url[1]) && */
	$_SERVER['REQUEST_METHOD']!='GET') {
	// $simple->hash($_url[1]);
	$json=json_decode(file_get_contents('php://input'));
	$json=is_null($json) ? new stdClass : $json;
	if (isset($_url[1])) {
		$json=$simple->classes[$_url[1]]->{strtolower($_SERVER['REQUEST_METHOD'])}($json);
	}
	echo json_encode($json);
	exit;
}

if (count($_url)==1 || (count($_url)==2 && empty($_url[1]))) {
	header('Location: /admin/Host',TRUE,307);
	exit;
}

if (isset($_url[2])) {
	// echo '<pre>';var_dump($page,$_url,$simple);
	echo $simple->classes[$_url[1]]->{strtolower($_SERVER['REQUEST_METHOD'])}($_url[2]);
	return;
}

$head[]='/admin/style.css';
$footer.='<script src="/admin/script.js"></script>';

// $footer.='<script></script>';

echo '<nav><ul>';
foreach ($classes as $key=>$value) {
	if ('Host'!=$key) echo ' - ';
	echo '<li',($_url[1]==$key ? ' class="show"' : ''),'><a href="',$key,'">',$key,'</a></li>';
}
echo '</ul></nav>';

if (isset($simple->classes[$_url[1]]))
	echo $simple->classes[$_url[1]]->write();

?><div class="modal"><div class="modal-content">
	<form method="post">
		<main></main>
		<footer>
			<button data="save">Save</butter>
			<button data="cancel">Cancel</butter>
		</footer>
	</form>
</div></div>