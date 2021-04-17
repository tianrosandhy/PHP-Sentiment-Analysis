<?php
require_once("core/conn.php");

$start = microtime(true);

$out = array();
if(isset($_GET['q'])){
	if(strlen($_GET['q']) <= 2){
		$out['input'] = $_GET['q'];
		$out['error'] = true;
		$out['message'] = "Mohon masukkan kalimat yang ingin dianalisis";
		$out['sentiment'] = -1;
		echo json_encode($out);
		exit();
	}

	$sentimen = single_process($_GET['q']);

	if($sentimen == -1){
		$out['input'] = $_GET['q'];
		$out['error'] = true;
		$out['message'] = "Kalimat tersebut memiliki sentimen netral.";
		$out['sentiment'] = -1;
	}
	else{
		//jangan lupa simpan ke tabel log untuk analisa selanjutnya kalau data belum ada


		$stem = stem($_GET['q']);
		$imploded = implode(",",$stem);

		$cek = $db->query("SELECT * FROM skripsi_rekap WHERE stem = ".$db->quote($imploded));
		if($cek->rowCount() == 0){
			$now = date("Y-m-d H:i:s");
			$sv = $db->prepare("INSERT INTO skripsi_rekap VALUES (NULL, :a, :b, :c, :d, 0)");
			$sv->bindParam(":a",$_GET['q']);
			$sv->bindParam(":b",$imploded);
			$sv->bindParam(":c",$sentimen);
			$sv->bindParam(":d",$now);
			$sv->execute();
			$last_id = $db->lastInsertId();			
		}
		else{
			$row = $cek->fetch();
			$last_id = $row['no'];
		}

		$out['input'] = $_GET['q'];
		$out['error'] = false;
		if($sentimen==1){
			$msg = "positif";
		}
		else{
			$msg = "negatif";
		}

		$out['message'] = "Kalimat tersebut mengarah ke sentimen $msg .";
		$out['sentiment'] = $sentimen;
		$out['unique_id'] = $last_id;
	}

	echo json_encode($out);
}

else if(isset($_GET['id'])){
	//cari unique analysis ID
	$cek = $db->query("SELECT * FROM skripsi_rekap WHERE no = ".$db->quote(intval($_GET['id'])));
	if($cek->rowCount() > 0){
		//data ada
		$row = $cek->fetch();
		if($row['sentimen'] == 0)
			$msg = "negatif";
		else
			$msg = "positif";

		$out['error'] = false;
		$out['id'] = intval($_GET['id']);
		$out['message'] = "Kalimat tersebut menghasilkan sentimen $msg";
		$out['input'] = $row['komentar'];
		$out['sentimen'] = $row['sentimen'];
		$out['stem'] = $row['stem'];
		$out['flag'] = $row['flag'];
	}
	else{
		$out['error'] = true;
		$out['id'] = intval($_GET['id']);
		$out['message'] = "Tidak ditemukan rekap analisis dengan ID ".intval($_GET['id']);
	}

	echo json_encode($out);
}

