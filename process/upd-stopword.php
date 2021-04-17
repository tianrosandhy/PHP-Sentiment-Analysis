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
			$ret['message'] = "Mohon mengisi stopword pada input yang sudah disediakan.";
		}
		else{
			$cek = query("SELECT * FROM stopword_list WHERE stopword = ".quote($new));
			if($cek->rowCount() > 0){
				//data sudah ada
				$ret['success'] = 0;
				$ret['message'] = "Stopword tersebut sudah ada.";
			}
			else{
				$ret['success'] = 1;
				$ret['input'] = $new;
				$upd = query("UPDATE stopword_list SET stopword = ".quote($new)." WHERE stopword = ".quote($old));
				//sipps
				$ret['message'] = "Berhasil menyimpan data stopword";
			}
		}

	}

	echo json_encode($ret);
}