<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
require_once 'create_link.php';
// create link
$connParam = array(
		'host' => 'localhost',
		'user' => 'root',
		'pass' => 'tanusree1',
		'db'   => 'test'
);
$link = 'http://localhost/test/download/';
$expiryDay = 7;
$createLinkObj = new createSecureDownloadLink($link, $connParam, $expiryDay);
$createLinkObj->createLink();
?>