<?php
if(isset($_GET['id']) and isset($_GET['revise'])){
	$id = intval($_GET['id']);
	$revise = intval($_GET['revise']);

	$sel = query("SELECT * FROM skripsi_analisa WHERE id = ".quote($id));
	$row = $sel->fetch();

	$query = query("UPDATE skripsi_analisa SET truesentimen = ".quote($revise)." WHERE id = ".quote($id));



	//perbaiki link
	if($_GET['revise'] == 0){
		$chg = 1;
	}
	else{
		$chg = 0;
	}
	$url = "crud/revise&id=".intval($_GET['id'])."&revise=".intval($chg);
	$data['url'] = $url;


	if($row['sentimen'] == $revise){
		$data['msg'] = "Berhasil menghapus tanda kesalahan analisis";
		$data['cls'] = "";
		$data['btn'] = "<a href='$url' class='revise-btn btn btn-info btn-sm pmd-ripple-effect'>Tandai sbg kesalahan</a>";
//		create_alert("Success","Berhasil menghapus tanda kesalahan analisis","../validasi.php?set=$row[sets]");
	}
	else{
//		create_alert("Success","Berhasil menandai data sebagai kesalahan analisis ","../validasi.php?set=$row[sets]");
		$data['msg'] = "Berhasil menandai data sebagai kesalahan analisis";
		$data['cls'] = "danger";
		$data['btn'] = "<a href='$url' class='revise-btn btn btn-warning btn-sm pmd-ripple-effect'>Hapus tanda kesalahan</a>";
	}





	echo json_encode($data);
}
