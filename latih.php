<?php
$basepath = true;
$data['title'] = "Master Data Latih";
$data['menu'] = 1;
$data['submenu'] = 13;
include "header.php";
?>

<br>
<h1>Master Data Latih</h1>

<div class="alert alert-info" style="margin-top:1em; font-size:13px">Data latih berguna sebagai yang alat latih sistem yang membantu pengambilan keputusan sentimen komentar yang akan diinputkan.</div>

<div class="well well-sm">
	<form action="" method="get">
		<div class="input-group">
			<input type="search" name="katadasar" class="form-control" placeholder="Filter Data Training" value="<?=isset($_GET['katadasar']) ? $_GET['katadasar'] : "" ?>">
			<div class="input-group-btn">
				<button class="btn btn-primary"><span class="fa fa-search"></span></button>
			</div>
		</div>
	</form>





	<form action="crud/add-latih" method="post" class="form-horizontal">
		<table class="data table pmd-table table-sm">
			<thead>
				<tr>
					<th>#</th>
					<th>Sentimen</th>
					<th>Kalimat Komentar</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan=4>
						<a data-toggle="collapse" data-target="#addform" class="btn btn-block btn-primary pmd-ripple-effect">Tambah Data</a>
					</td>
				</tr>
				<tr>
					<td colspan=4 bgcolor="#ffffff">
						<div class="collapse" id="addform">
							<div class="col-sm-6 col-sm-push-3">
								<div>
									<label>Kalimat Komentar</label>
									<textarea name="kalimat" class="form-control"></textarea>
								</div>
								<div>
									<label>Sentimen</label>
									<select name="sentimen" class="form-control">
										<option value="0">Negatif</option>
										<option value="1">Positif</option>
									</select>
								</div>
								<div class="padd">
									<button name="btn" class="btn btn-primary pmd-ripple-effect">Simpan</button>
								</div>
							</div>

						</div>
					</td>
				</tr>
			<?php
			$pg1 = isset($_GET['pg1']) ? intval($_GET['pg1']) : 1;
			$limit = 20;
			$offset = ($pg1 - 1) * $limit;

			$addQ = "";
			if(isset($_GET['katadasar'])){
				$kw = $_GET['katadasar'];
				$addQ = "WHERE komentar LIKE ".quote("%".$kw."%");
			}

			$sql = "SELECT * FROM skripsi_komentar $addQ LIMIT $offset, $limit";
			$query = query($sql);
			$no = $offset + 1;
			if($query->rowCount() == 0){
				echo "
				<tr>
					<td colspan=4>Tidak ada data master data latih yang dapat ditampilkan.</td>
				</tr>
				";
			}
			foreach($query as $row){
				if($row['sentimen'] == 0){
					$sentimen = "<span data-value='$row[sentimen]' class='label label-danger'>Negatif</span>";
				}
				else if($row['sentimen'] == 1){
					$sentimen = "<span data-value='$row[sentimen]' class='label label-success'>Positif</span>";
				}
				else{
					$sentimen = "<span data-value='$row[sentimen]' class='label label-info'>Netral</span>";
				}

				$stem = explode(",",$row['stem']);
				$stemmed = "";
				if(count($stem) > 0){
					$stemmed = "<span class='label label-info'>".implode("</span> <span class='label label-info'>",$stem)."</span>";
				}

				echo "
				<tr>
					<td>$no</td>
					<td>$sentimen</td>
					<td data-ctn='$row[komentar]'>$row[komentar]</td>
					<td align='right' width=200>
						<a class='latih-update btn btn-info pmd-ripple-effect'><span class='fa fa-pencil' title='Update'></span></a>
						<a data-href='crud/delete_training&id=$row[no]' class='delete-button btn btn-danger pmd-ripple-effect'><span class='fa fa-trash' title='Hapus'></span></a>
					</td>
				</tr>
				";
				$no++;
			}

			$prev = 1;
			$next = $pg1;
			$totalpg1 = query("SELECT COUNT(no) AS jml FROM skripsi_komentar $addQ");
			$rt = $totalpg1->fetch();
			$ntotal = ceil($rt['jml'] / $limit);

			$btncl1 = $btncl2 = "disabled";
			if($pg1 > 1){
				$prev = $pg1 - 1;
				$btncl1 = "";
			}
			if($pg1 < $ntotal){
				$next = $pg1 + 1;
				$btncl2 = "";
			}

			$ktd = "";
			if(isset($_GET['katadasar'])){
				$ktd = "&katadasar=".$_GET['katadasar'];
			}

			$urla = "?pg1=$prev".$ktd;
			$urlb = "?pg1=$next".$ktd;

			?>
			</tbody>
		</table>
	</form>
	<div class="btn-group btn-group-justified">
		<a href="<?=$urla?>" class="<?=$btncl1?> btn pmd-ripple-effect btn-primary"><span class="fa fa-caret-left"></span> Prev</a> 
		<a href="<?=$urlb?>" class="<?=$btncl2?> btn pmd-ripple-effect btn-primary">Next <span class="fa fa-caret-right"></span></a> 
	</div>


</div>




























<div tabindex="-1" class="modal fade" id="updform" style="display: none;" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header pmd-modal-bordered">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h2 class="pmd-card-title-text">Form Update</h2>
			</div>
			<div class="modal-body">
				<label>Ubah kalimat komentar ke</label>
				<div class="append-edit-2 form-group pmd-textfield"></div>
				<label>Ubah sentimen ke</label>
				<div class="append-edit-3 form-group pmd-textfield"></div>

			</div>
			<div class="pmd-modal-action">
				<button class="upd-button-3 btn pmd-ripple-effect btn-primary" type="button">Save changes</button>
				<button data-dismiss="modal"  class="btn pmd-ripple-effect btn-default" type="button">Close</button>
			</div>
		</div>
	</div>
</div>


<?php
include "footer.php";
?>