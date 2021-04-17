<?php
include_once "core/conn.php";
$ret = array();
$keyword = $_POST['query'];


if(!isset($_GET['stopword'])){
	$sql = query("SELECT * FROM skripsi_katadasar WHERE katadasar LIKE ".quote($keyword."%")." LIMIT 15");
	if($sql->rowCount() > 0){
		$in = array();
		foreach($sql as $row){
			$in[] = array(
				"value" => $row['katadasar'],
				"data" => 1
				);
		}
		$ret = array(
			"suggestions" => $in
		);
	}
	else{
		$ret = array(
			"suggestions" => array()
		);
	}

}
else{
	$sql = query("SELECT * FROM stopword_list WHERE stopword LIKE ".quote($keyword."%")." LIMIT 15");
	if($sql->rowCount() > 0){
		$in = array();
		foreach($sql as $row){
			$in[] = array(
				"value" => $row['stopword'],
				"data" => 1
				);
		}
		$ret = array(
			"suggestions" => $in
		);
	}
	else{
		$ret = array(
			"suggestions" => array()
		);
	}

}



echo json_encode($ret);
