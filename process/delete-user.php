<?php
if(isset($_GET['id'])){
	$target = $_GET['id'];

	$qry = "SELECT * FROM skripsi_admin WHERE user = ".quote($target);
	$cek = query($qry);
	if($cek->rowCount() > 0){
		$row = $cek->fetch();
		if($row['priviledge'] == 2){
			//bole hapus
			$del = "DELETE FROM skripsi_admin WHERE user = ".quote($target);
			$run = query($del);
			create_alert("Success","Berhasil menghapus user yang bersangkutan","../setting.php");
		}
		else{
			create_alert("Permission Error","Tidak dapat menghapus admin user","../setting.php");
		}
	}
	else{
		create_alert("error","Tidak menemukan user yang ingin dihapus.","../setting.php");
	}
}