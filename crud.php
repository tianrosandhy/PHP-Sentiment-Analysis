<?php
include_once("core/conn.php");
if(isset($_GET['action'])){

	$dir = "process/";
	$open = $_GET['action'].".php";

	if(is_file($dir.$open)){
		include $dir.$open;
	}
	else{
		create_alert("error","File ".$dir.$open." tidak ditemukan","../home.php");
	}

}
