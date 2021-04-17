<?php
$basepath = true;
$data['title'] = "Analysis History";
$data['menu'] = 3;
$data['submenu'] = 31;
include "header.php";
?>
<br>
<h1>Analysis History</h1>

<form action="" method="get">
	<div class="input-group">
		<input type="search" name="keyword" class="form-control" placeholder="Filter Analysis History" value="<?=isset($_GET['keyword']) ? $_GET['keyword'] : "" ?>">
		<div class="input-group-btn">
			<button class="btn btn-primary"><span class="fa fa-search"></span></button>
		</div>
	</div>
</form>


<table class="data table pmd-table table-sm">
	<thead>
		<tr>
			<th>#</th>
			<th>Tanggal Analisis</th>
			<th>Kalimat Komentar</th>
			<th>Stemmed Token</th>
			<th>Sentimen</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
	$limit = 20;
	$offset = ($page - 1) * $limit;

	$addQ = "";
	if(isset($_GET['keyword'])){
		$addQ = "WHERE komentar LIKE ".quote("%".$_GET['keyword']."%")." OR stem LIKE ".quote("%".$_GET['keyword']."%");
	}

	$sql = query("SELECT * FROM skripsi_rekap $addQ ORDER BY tgl DESC LIMIT $offset,$limit");
	$no = $offset + 1;
	foreach($sql as $row){
		$stem = explode(",",$row['stem']);
		$stemmed = "";
		if(count($stem) > 0){
			$stemmed = "<span class='label label-info'>".implode("</span> <span class='label label-info'>",$stem)."</span>";
		}

		if($row['sentimen'] == 0){
			$senti = "<span class='btn btn-danger'>Negatif</span>";
		}
		else if($row['sentimen'] == 1){
			$senti = "<span class='btn btn-success'>Positif</span>";
		}
		else{
			$senti = "<span class='btn btn-warning'>Netral</span>";
		}

		if($row['flag'] == 0){
			//belum ditandai
		}
		else{
			//sudah ditandai
		}

		echo "
		<tr>
			<td>$no</td>
			<td>".indo_date($row['tgl'],"full")."</td>
			<td>$row[komentar]</td>
			<td>$stemmed</td>
			<td>$senti</td>
			<td></td>
		</tr>
		";
		$no++;
	}


	$prev = 1;
	$next = $page;
	$totalpage = query("SELECT COUNT(no) AS jml FROM skripsi_rekap $addQ");
	$rt = $totalpage->fetch();
	$ntotal = ceil($rt['jml'] / $limit);

	$btncl1 = $btncl2 = "disabled";
	if($page > 1){
		$prev = $page - 1;
		$btncl1 = "";
	}
	if($page < $ntotal){
		$next = $page + 1;
		$btncl2 = "";
	}

	$ktd = "";
	if(isset($_GET['katadasar'])){
		$ktd = "&katadasar=".$_GET['katadasar'];
	}

	$urla = "?page=$prev".$ktd;
	$urlb = "?page=$next".$ktd;	
	?>
	</tbody>
</table>

<div class="btn-group btn-group-justified">
	<a href="<?=$urla?>" class="<?=$btncl1?> btn pmd-ripple-effect btn-primary"><span class="fa fa-caret-left"></span> Prev</a> 
	<a href="<?=$urlb?>" class="<?=$btncl2?> btn pmd-ripple-effect btn-primary">Next <span class="fa fa-caret-right"></span></a> 
</div>


<?php
include "footer.php";
?>