<?php
$basepath = true;
require("core/conn.php");

$login_status = $login->cek_login();
if($login_status){
	//bawa ke halaman single analytic jika sudah login
	$priv = $login_status['priviledge'];
	header("location:single-anal.php");
	exit();
}
else{
	//include form log in jika belum log in
	include "login.php";
}