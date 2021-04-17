<?php
include "core/conn.php";

$sql = query("SELECT * FROM skripsi_komentar");
foreach($sql as $row){
	$a = microtime(true);
	$string = $row['komentar'];
	$id = $row['no'];

	$stem = $analyze->stem($string);
	$analyze->stopword();
	$hasil = $analyze->input;
	$analyze->input = null;

	$imp = implode(",",$hasil);
	$sql = query("UPDATE skripsi_komentar SET stem = ".quote($imp)." WHERE no = ".quote($id));

	echo "Data $id berhasil di re-stem dalam ".(microtime(true) - $a) ." detik.<br>";
}