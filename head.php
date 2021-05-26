<?php

define('SIMPLE','0.1');

error_reporting(E_ALL);
ini_set('display_errors', '1');

spl_autoload_register(function ($class) {
	$fn=__DIR__."/admin/".str_replace("\\", "/",$class).".php";
	if (is_file($fn)) {
		require_once($fn);
		return;
	}
	$fn=__DIR__."/classes/".str_replace("\\", "/",$class).".php";
	if (!file_exists($fn)) {
		throw new Exception("Kan $fn niet laden");
	}
	if (is_file($fn))
		require_once($fn);
});

$footer='<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous"></script>';
$head=[];

require 'functions.php';

session_start();
