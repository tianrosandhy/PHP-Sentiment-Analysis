<?php
include "core/conn.php";

$max_attempt = get_setting("login_max_attempt");
if(!$login->cek_salah_login($max_attempt)){
	create_alert("error","Mohon maaf Anda tidak dapat login lagi karena kesalahan login Anda terlalu banyak. Hubungi Administrator untuk informasi lebih lanjut","index.php");
}

if(isset($_POST['btn'])){
	$username = $_POST['username'];
	$password = $_POST['password'];

	if(empty($username) or empty($password)){
		$login->salah_login_action($username);
		create_alert("error","Mohon mengisi username atau password dengan tepat","index.php");
	}

	//step 1 : cek apakah username ada di tabel 
	$cek = $db->query("SELECT * FROM skripsi_admin WHERE user = ".$db->quote($username));

	if($cek->rowCount() > 0){
		//username ada, tangkap password yg ada di database
		$row = $cek->fetch();
		$password_db = $row['pass'];
		if(password_verify($password, $password_db)){
			//password sudah cocok

			$expired = 0;
			if(isset($_POST['remember'])){
				if($_POST['remember'] = 1){
					$expired = '+1 year'; // 1 tahun
				}
			}

			$login->true_login($username, $expired);
			$now = date("Y-m-d H:i:s");
			change_setting("last_login",$now);
			create_alert("success","Log In Berhasil","index.php");
		}
		else{
			//password tidak cocok
			$login->salah_login_action($username);
			create_alert("error","Username atau password tersebut salah","index.php");
		}

	}
	else{
		$login->salah_login_action($username);
		create_alert("error","Username atau password tersebut tidak terdaftar","index.php");
	}

}
else{
	header("location:index.php");
}