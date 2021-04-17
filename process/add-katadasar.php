<?php
if(isset($_POST['katadasar'])){
	$kata = $_POST['katadasar'];

	if(strlen(trim($kata)) == 0){
		create_alert("Error","Mohon mengisi kata dasar pada input yang sudah disediakan.","../home.php");
	}

	//validasi : sudah ada di database atau belum
	$sql = "SELECT id_ktdasar FROM skripsi_katadasar WHERE katadasar = ".quote($kata);
	$cek = query($sql);
	if($cek->rowCount() > 0){
		//sudah ada
		create_alert("Error","Kata dasar sudah ada di database. Mohon daftarkan kata lainnya yang belum ada di database","../home.php");
	}
	else{
		//kata belum ada, bisa dimasukkan ke database
		$ins = query("INSERT INTO skripsi_katadasar VALUES (NULL, ".quote($kata).", 1)");
		create_alert("Success","Berhasil menambahkan data kata dasar ke database","../home.php");
	}
}