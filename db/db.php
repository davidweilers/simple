<?php

defined('SIMPLE') or die();

$creates=[
	'AUTOINCREMENT'=>'sqlite'==$ini['driver'] ? 'AUTOINCREMENT' : 'AUTO_INCREMENT',
	'FOOTER'=>'sqlite'==$ini['driver'] ? '' : 'ENGINE=InnoDB CHARSET=utf8',
];

echo '<pre>';
var_dump($db);

if ($ini['driver']=='sqlite') {
	if (!file_exists('db/simple.db')) {
		file_put_contents('db/simple.db','');
		chmod('db/simple.db', 0777);
	}

	$sql_simples=$db->query("SELECT name FROM sqlite_master")->fetchAll();
	var_dump($sql_simples);
	
}   else {
	$sql_simples=$db->query("SHOW TABLES ")->fetchAll();
	var_dump($sql_simples);
}

if (!in_array('user',$sql_simples))
$db->exec('
CREATE TABLE IF NOT EXISTS user (
	id INTEGER PRIMARY KEY '.$creates['AUTOINCREMENT'].' NOT NULL,
	user varchar(256) UNIQUE,
	password varchar(256)
) '.$creates['FOOTER'].'
');

if (!in_array('remote_addr',$sql_simples))
$db->exec('
CREATE TABLE IF NOT EXISTS remote_addr (
	id INTEGER PRIMARY KEY '.$creates['AUTOINCREMENT'].' NOT NULL,
	user int NOT NULL REFERENCES user(id),
	remote_addr varchar(128),
	`time` time 
) '.$creates['FOOTER'].'
');

if (!in_array('host',$sql_simples)) {
	$db->exec('
	CREATE TABLE IF NOT EXISTS host (
		id INTEGER PRIMARY KEY '.$creates['AUTOINCREMENT'].' NOT NULL,
		name varchar(128) NOT NULL UNIQUE
	) '.$creates['FOOTER'].'
	');
	$db->autoinsert('host',[
		'name'=>'test',
	]);
	$db->autoinsert('host',[
		'name'=>'test2',
	]);
	$db->autoinsert('host',[
		'name'=>'test3',
	]);
}

if (!in_array('alias',$sql_simples)) {
	$db->exec('
	CREATE TABLE IF NOT EXISTS alias (
		id INTEGER PRIMARY KEY '.$creates['AUTOINCREMENT'].' NOT NULL,
		host INTEGER NOT NULL REFERENCES host(id),
		name varchar(128) NOT NULL UNIQUE
	) '.$creates['FOOTER'].'
	');
	$db->autoinsert('alias',[
		'host'=>1,
		'name'=>$_SERVER['HTTP_HOST'],
	]);
	$db->autoinsert('alias',[
		'host'=>1,
		'name'=>'test2.nl',
	]);
	$db->autoinsert('alias',[
		'host'=>2,
		'name'=>'test3.nl',
	]);
	$db->autoinsert('alias',[
		'host'=>3,
		'name'=>'test4.nl',
	]);
}

if (!in_array('settings',$sql_simples)) {
	$db->exec('
	CREATE TABLE IF NOT EXISTS settings (
		id INTEGER PRIMARY KEY '.$creates['AUTOINCREMENT'].' NOT NULL,
		host INTEGER NOT NULL REFERENCES host(id),
		name varchar(128),
		value text,
		UNIQUE (name, host)
	) '.$creates['FOOTER'].'
	');
	$db->autoinsert('settings',[
		'host'=>1,
		'name'=>'title',
		'value'=>'Test1',
	]);

}

if (!in_array('page',$sql_simples)) {
	$db->exec('
	CREATE TABLE IF NOT EXISTS page (
		id INTEGER PRIMARY KEY '.$creates['AUTOINCREMENT'].' NOT NULL,
		host INTEGER NOT NULL REFERENCES host(id),
		url varchar(256) UNIQUE,
		parent INTEGER REFERENCES page(id),
		name varchar(128),
		json json,
		html text,
		meta json
	) '.$creates['FOOTER'].'
	');
	$db->autoinsert('page',[
		'url'=>'',
		'name'=>'test',
		'host'=>1,
	]);
	$db->autoinsert('page',[
		'url'=>'over',
		'name'=>'test over',
		'host'=>1,
	]);
}

header('Location: /');
exit;
