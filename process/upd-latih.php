<?php
if(isset($_POST)){

	$old = $_POST['old'];
	$new = $_POST['new']; //kalimat
	$sentimen = $_POST['sentimen']; //sentimen flag
	$ret = array();


	//get data lama dulu
	$get = query("SELECT * FROM skripsi_komentar WHERE komentar = ".quote($old)." LIMIT 1");
	$row = $get->fetch();



	if($old == $new and $sentimen == $row['sentimen']){
		$ret['success'] = 1;
		$ret['message'] = "Tidak ada perubahan dilakukan";
	}
	else{
		$cek = query("SELECT * FROM skripsi_komentar WHERE komentar = ".quote($new)." AND komentar <> ".quote($old));
		if($cek->rowCount() > 0){
			//data sudah ada
			$ret['success'] = 0;
			$ret['message'] = "Kalimat komentar tersebut sudah ada. Mohon gunakan kalimat lain";
		}
		else{
			$ret['success'] = 1;
			$ret['input'] = $new;

			//proses stem
			$stem = stem($new);
			$stems = implode(", ",$stem);

			$upd = query("UPDATE skripsi_komentar SET komentar = ".quote($new).", stem = ".quote($stems).", sentimen = ".quote($sentimen)." WHERE komentar = ".quote($old));
			//sipps
			$ret['message'] = "Berhasil menyimpan data komentar";
		}
	}

	echo json_encode($ret);
}