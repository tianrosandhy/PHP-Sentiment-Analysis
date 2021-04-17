<?php
if(isset($_GET['set'])){
	$target = $_GET['set'];

	$cek = query("SELECT * FROM skripsi_analisa WHERE sets = ".quote($target));
	if($cek->rowCount() > 0){
		$row = $cek->fetch();
			//bole hapus
			$del = "DELETE FROM skripsi_analisa WHERE sets = ".quote($target);
			$run = query($del);
			create_alert("Success","Berhasil menghapus data set analisis","../validasi.php");
	}
	else{
		create_alert("error","Tidak menemukan user yang ingin dihapus","../validasi.php");
	}
}