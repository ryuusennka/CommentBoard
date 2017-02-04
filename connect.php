<?php
$mysqli=new mysqli('localhost','root','123456','test2');
if($mysqli->errno){
	die('Connect Error:' . $mysqli->error);
} else {
	$mysqli->set_charset('UTF8');
}