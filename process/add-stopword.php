<?php
if(isset($_POST['stopword'])){
	$kata = $_POST['stopword'];

	if(strlen(trim($kata)) == 0){
		create_alert("Error","Mohon mengisi stopword pada input yang sudah disediakan.","../stopword.php");
		exit();
	}

	//validasi : sudah ada di database atau belum
	$sql = "SELECT id FROM stopword_list WHERE stopword = ".quote($kata);
	$cek = query($sql);
	if($cek->rowCount() > 0){
		//sudah ada
		create_alert("Error","Stopword tersebut sudah ada di database. Mohon daftarkan kata lainnya yang belum ada di database","../stopword.php");
	}
	else{
		//kata belum ada, bisa dimasukkan ke database
		$ins = query("INSERT INTO stopword_list VALUES (NULL, ".quote($kata).")");
		create_alert("Success","Berhasil menambahkan data stopword ke database","../stopword.php");
	}
}