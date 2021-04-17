<?php
if(isset($_POST['btn'])){
	$kalimat = $_POST['kalimat'];
	$sentimen = $_POST['sentimen'];

	if(strlen(trim($kalimat)) == 0){
		create_alert("Error","Mohon mengisi kalimat komentar data latih dengan benar.","../latih.php");
	}

	$cek = query("SELECT * FROM skripsi_komentar WHERE komentar = ".quote($kalimat));
	if($cek->rowCount() > 0){
		//data sudah ada
		create_alert("Error","Data komentar tersebut sudah ada.","../latih.php");
	}
	else{
		//data blm ada. siapkan
		$stem = stem($kalimat);
		$stems = implode(", ",$stem);

		$sql = "INSERT INTO skripsi_komentar VALUES (NULL, ".quote($kalimat).", ".quote($stems).", ".quote($sentimen).")";
		$query = query($sql);
		if($query){
			create_alert("Success","Berhasil menyimpan data latih","../latih.php");
		}
		else{
			create_alert("Error","Error SQL : $sql","../latih.php");
		}
	}
}