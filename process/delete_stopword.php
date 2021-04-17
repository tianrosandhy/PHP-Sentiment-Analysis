<?php
$qry = "DELETE FROM stopword_list WHERE id = ".quote(intval($_GET['id']));
$sql = query($qry);

if($sql){
	create_alert("Success","Berhasil menghapus data master stopword","../stopword.php");
}
else{
	create_alert("Error","SQL : $qry ","../stopword.php");
}