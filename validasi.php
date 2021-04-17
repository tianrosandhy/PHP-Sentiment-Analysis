<?php
$basepath = true;
$data['title'] = "Uji Validasi";
$data['menu'] = 5;
$data['submenu'] = 52;
include "header.php";

$set = 0;
if(isset($_GET['set']))
	$set = intval($_GET['set']);

?>
<br>
<h1>Uji Validasi</h1>

<form action="" method="get">
	<div class="input-group">
		<select name="set" class="form-control" onchange="this.form.submit()">
			<option value=""> - Rangkuman Validasi - </option>
			<?php
			$san = query("SELECT sets, tgl FROM skripsi_analisa GROUP BY sets");
			foreach($san as $rs){
				$add = "";
				if($set == $rs['sets']){
					$add = "selected";
				}
				echo "<option $add value='$rs[sets]'>Set $rs[sets] - ".date("d F Y H:i:s",strtotime($rs['tgl']))."</option>
				";
			}
			?>
		</select>
		<div class="input-group-btn">
			<button class="btn btn-primary"><span class="fa fa-search"></span></button>
		</div>
	</div>
</form>

<a href="validasi-add.php" class="btn btn-primary btn-lg pmd-ripple-effect">
	Tambah Data Set Uji Validasi
</a>

<?php
if($set > 0){
	echo "
	<a href='crud/delete-validasi&set=$set' class='btn btn-danger btn-lg delete-button pmd-ripple-effect'>Hapus Data Set Uji Ini</a>
	";
}
?>

<?php
if($set > 0){
?>
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
		$set = intval($_GET['set']);
		$query = query("SELECT * FROM skripsi_analisa WHERE sets = ".quote($set));
		$no = 0;
		$salah = 0;
		foreach($query as $row){
			$stem = explode(",",$row['stem']);
			$stemmed = "";
			if(count($stem) > 0){
				$stemmed = "<span class='label label-info'>".implode("</span> <span class='label label-info'>",$stem)."</span>";
			}

			$jarak_belajar = number_format((100 - ($row['jarak_ke_pusat'] * 3)), 3, ",",".")."%";

			if($row['sentimen'] == 0){
				$senti = "<span title='Persentase keyakinan : $jarak_belajar' class='btn btn-danger'>Negatif</span>";
			}
			else if($row['sentimen'] == 1){
				$senti = "<span title='Persentase keyakinan : $jarak_belajar' class='btn btn-success'>Positif</span>";
			}
			else{
				$senti = "<span title='Persentase keyakinan : $jarak_belajar' class='btn btn-warning'>Netral</span>";
			}

			if($row['truesentimen'] === '1')
				$reviseto = 0;
			elseif($row['truesentimen'] === '0')
				$reviseto = 1;
			else{
				if($row['sentimen'] == -1){
					$reviseto = -1;
				}
				else{
					$reviseto = $row['sentimen'] == 1 ? 0 : 1;
				}
			}

			$trclass = "";
			$btn = "
				<a href='crud/revise&id=$row[id]&revise=$reviseto' class='revise-btn btn btn-info btn-sm pmd-ripple-effect'>Tandai sbg kesalahan</a>
			";
			if(is_numeric($row['truesentimen'])){
				if($row['sentimen'] <> $row['truesentimen']){
					$trclass = "class='danger'";
					$btn = "
					<a href='crud/revise&id=$row[id]&revise=$reviseto' class='revise-btn btn btn-warning btn-sm pmd-ripple-effect'>Hapus Tanda Kesalahan</a>
					";
					$salah++;
				}
			}
			if($row['sentimen'] == -1){
				$btn = "";
				$salah++;
				$trclass = "class='danger'";
			}

			$no++;		
			echo "
			<tr $trclass>
				<td>$no</td>
				<td>".indo_date($row['tgl'],"full")."</td>
				<td>$row[komentar]</td>
				<td>$stemmed</td>
				<td>$senti</td>
				<td>$btn</td>
			</tr>
			";
		}
	?>
	</tbody>
</table>


<script>
	$("body").on("click", ".revise-btn", function(e){
		e.preventDefault();
		target = $(this).attr("href");
		ctx = $(this);
		$.ajax({
			url : target,
			dataType : 'json'
		}).done(function(dt){
//			alertify.alert("Success",dt["msg"]);
			ctx.closest("tr").attr("class","");
			ctx.closest("tr").addClass(dt['cls']);
			ctx.replaceWith(dt['btn']);
			count_tb();
		});
	});


	function count_tb(){
		var all = $("table tr").length - 1;
		var wrong = $("table tr.danger").length;
		var valid = ((all - wrong) / all ) * 100;

		$(".all-span").text(all);
		$(".wrong-span").text(wrong);
		$(".percentage").text(valid+"%");
	}
</script>


<?php
}


if($set > 0):
?>
<input type="hidden" name="jml_data" value="<?=$no?>">
<input type="hidden" name="jml_salah" value="<?=$salah?>">


<div class="row">
	<div class="col-sm-3">
		<strong>Jumlah Data Uji</strong>
	</div>
	<div class="col-sm-3"> : <span class="all-span"><?=$no?></span></div>
</div>
<div class="row">
	<div class="col-sm-3">
		<strong>Jumlah Kesalahan</strong>
	</div>
	<div class="col-sm-3"> : <span class="wrong-span"><?=$salah?></span></div>
</div>
<div class="row">
	<div class="col-sm-3">
		<strong>Persentase Validasi</strong>
	</div>
	<div class="col-sm-3"> : <mark><span class="percentage"><?php
		$skor = (($no - $salah) / $no) * 100;
		$skor = number_format($skor,2,",",".");
		echo $skor;
	?>%</span></mark></div>
</div>
<?php else : 
$query = query("
	SELECT
	(
	SELECT COUNT(id) AS total_data FROM skripsi_analisa
	) AS total_data,
	(
	SELECT COUNT(id) AS total_salah FROM skripsi_analisa WHERE (truesentimen IS NOT NULL AND sentimen <> truesentimen) OR (sentimen = -1)
	) AS total_salah
	");
$row = $query->fetch();
if($row['total_data'] > 0){
?>
<div class="row">
	<div class="col-sm-3">
		<strong>Jumlah Data Uji</strong>
	</div>
	<div class="col-sm-3"> : <?=$row['total_data']?></div>
</div>
<div class="row">
	<div class="col-sm-3">
		<strong>Jumlah Kesalahan</strong>
	</div>
	<div class="col-sm-3"> : <?=$row['total_salah']?></div>
</div>
<div class="row">
	<div class="col-sm-3">
		<strong>Persentase Validasi</strong>
	</div>
	<div class="col-sm-3"> : <mark><span class="percentage"><?php
		$skor = (($row['total_data'] - $row['total_salah']) / $row['total_data']) * 100;
		$skor = number_format($skor,2,",",".");
		echo $skor;
	?>%</span></mark></div>
</div>

<?php
} 
endif;?>

<?php
include "footer.php";
?>