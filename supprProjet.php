<?php
$title = 'Suppression d\'un projet';
include('header.php');
include('db_inc.php');

$db = new PDO($server . ':host=' . $host . ';dbname=' . $base, $user, $pass);
$idp = $_GET['id'];
$request = 'DELETE FROM projet WHERE ID = '.$idp.';';
$db->query($request);
include('footer.php');
?>