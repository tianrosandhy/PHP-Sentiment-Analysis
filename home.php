<?php
$basepath = true;
$data['title'] = "Data Master Kata Dasar";
$data['menu'] = 1;
$data['submenu'] = 11;
include "header.php";
?>

<div class="row">
	<div class="col-lg-6 col-lg-push-3 col-md-8 col-md-push-2">
		<br>
		<h1>Data Master Kata Dasar</h1>

		<div class="alert alert-info" style="margin-top:1em; font-size:13px">Dibawah ini adalah data master kata dasar. Seluruh kalimat yang nanti akan dianalisis akan diperiksa dahulu kata per katanya, untuk memastikan hanya mengambil potongan kata2 yang tersimpan ini saja.</div>

		<div class="well well-sm">
			<form action="" method="get">
				<div class="input-group">
					<input type="search" name="katadasar" class="form-control" placeholder="Filter Tabel Kata Dasar" value="<?=isset($_GET['katadasar']) ? $_GET['katadasar'] : "" ?>">
					<div class="input-group-btn">
						<button class="btn btn-primary"><span class="fa fa-search"></span></button>
					</div>
				</div>
			</form>





			<form action="crud/add-katadasar" method="post" class="form-horizontal">
				<table class="data table pmd-table table-sm">
					<thead>
						<tr>
							<th>#</th>
							<th>Kata Dasar</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan=3>
								<a data-toggle="collapse" data-target="#addform" class="btn btn-block btn-primary pmd-ripple-effect">Tambah Data</a>
							</td>
						</tr>
						<tr>
							<td colspan=3 bgcolor="#ffffff">
								<div class="collapse" id="addform">
									<div class="input-group">
										<input type="text" class="form-control" name="katadasar" id="katadasar" placeholder="Nama Kata Dasar">
										<div class="input-group-btn">
											<button class="btn btn-primary pmd-ripple-effect"><span class="fa fa-plus"></span> Tambah</button>
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
						$addQ = "WHERE katadasar LIKE ".quote($kw."%");
					}

					$sql = "SELECT * FROM skripsi_katadasar $addQ LIMIT $offset, $limit";
					$query = query($sql);
					$no = $offset + 1;
					if($query->rowCount() == 0){
						echo "
						<tr>
							<td colspan=3>Tidak ada data master kata dasar yang dapat ditampilkan.</td>
						</tr>
						";
					}
					foreach($query as $row){
						echo "
						<tr>
							<td>$no</td>
							<td data-ctn='$row[katadasar]'>$row[katadasar]</td>
							<td align='right' width=200>
								<a class='ktdasar-update btn btn-info pmd-ripple-effect'><span class='fa fa-pencil' title='Update'></span></a>
								<a data-href='crud/delete_katadasar&id=$row[id_ktdasar]' class='delete-button btn btn-danger pmd-ripple-effect'><span class='fa fa-trash' title='Hapus'></span></a>
							</td>
						</tr>
						";
						$no++;
					}

					$prev = 1;
					$next = $pg1;
					$totalpg1 = query("SELECT COUNT(id_ktdasar) AS jml FROM skripsi_katadasar $addQ");
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

		
</div>



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
				<label>Ubah kata dasar ke </label>
				<div class="append-edit form-group pmd-textfield"></div>
			</div>
			<div class="pmd-modal-action">
				<button class="upd-button btn pmd-ripple-effect btn-primary" type="button">Save changes</button>
				<button data-dismiss="modal"  class="btn pmd-ripple-effect btn-default" type="button">Close</button>
			</div>
		</div>
	</div>
</div>


<?php
include "footer.php";
?>