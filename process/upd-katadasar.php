<?php
if(isset($_POST)){

	$old = $_POST['old'];
	$new = $_POST['new'];
	$ret = array();

	if($old == $new){
		$ret['success'] = 1;
		$ret['message'] = "Tidak ada perubahan dilakukan";
	}
	else{
		if(strlen($new) == 0){
			$ret['success'] = 0;
			$ret['message'] = "Mohon mengisi kata dasar pada input yang sudah disediakan.";
		}
		else{
			$cek = query("SELECT * FROM skripsi_katadasar WHERE katadasar = ".quote($new));
			if($cek->rowCount() > 0){
				//data sudah ada
				$ret['success'] = 0;
				$ret['message'] = "Kata dasar sudah ada di database. Mohon daftarkan kata lainnya yang belum ada di database";
			}
			else{
				$ret['success'] = 1;
				$ret['input'] = $new;
				$upd = query("UPDATE skripsi_katadasar SET katadasar = ".quote($new)." WHERE katadasar = ".quote($old));
				//sipps
				$ret['message'] = "Berhasil menyimpan data kata dasar";
			}
		}
	}

	echo json_encode($ret);
}