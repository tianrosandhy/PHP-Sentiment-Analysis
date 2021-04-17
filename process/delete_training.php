<?php
$qry = "DELETE FROM skripsi_komentar WHERE no = ".quote(intval($_GET['id']));
$sql = query($qry);

if($sql){
	create_alert("Success","Berhasil menghapus data latih","../latih.php");
}
else{
	create_alert("Error","SQL : $qry ","../latih.php");
}