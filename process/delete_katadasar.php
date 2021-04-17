<?php
$qry = "DELETE FROM skripsi_katadasar WHERE id_ktdasar = ".quote(intval($_GET['id']));
$sql = query($qry);

if($sql){
	create_alert("Success","Berhasil menghapus data master kata dasar","../home.php");
}
else{
	create_alert("Error","SQL : $qry ","../home.php");
}