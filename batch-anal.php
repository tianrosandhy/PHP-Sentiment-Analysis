<?php
$basepath = true;
$data['title'] = "Batch Data Analysis";
$data['menu'] = 2;
$data['submenu'] = 22;
include "header.php";

require("core/spreadsheet-reader/php-excel-reader/excel_reader2.php");
require("core/spreadsheet-reader/SpreadsheetReader.php");
?>
<br>
<h1>Batch Data Analysis</h1>

<form action="" method="post" enctype="multipart/form-data">
	<div class="well">
		<div class="alert alert-info">
			Masukkan kumpulan kalimat yang ingin dianalisa dalam format Excel untuk diolah secara langsung.<br><a href="assets/contoh.xlsx" class="btn btn-info pmd-ripple-effect" target="_blank">Download Contoh Format File</a>
		</div>
		<input type="file" name="file_batch" class="form-control" accept=".xls, .xlsx, .csv">
		<button name="btn" class="btn btn-primary pmd-ripple-effect">Process</button>
	</div>
</form>


<?php
if(isset($_POST['btn'])){
	$start = microtime(true);

	$file = $_FILES['file_batch'];
	if($file['error'] > 0){
		create_alert("error","Mohon mengupload file dalam format Excel untuk diolah oleh sistem");
	} 
	else{
		$filename = $file['name'];
		$ext = get_extension($filename);
		if(!in_array($ext, array("xls", "xlsx", "csv"))){
			create_alert("error","Mohon mengupload file dalam format Excel untuk diolah");
		}
		else{
			$skrg = date("YmdHis");
			$loc = "temp/".$skrg.".".$ext;

			$save = move_uploaded_file($file['tmp_name'], $loc);
			//akses file yang sudah diupload
			$sp = new SpreadsheetReader($loc);
			$sheet = $sp->Sheets();

			$text = array();
			foreach($sheet as $index=>$name){

				$sp->ChangeSheet($index);
				foreach($sp as $key=>$row){
					if(!isset($row[0])){
						break;
					}
					if(strlen($row[0]) > 0){
						$text[] = $row[0]; //simpan ke array
					}
				}
			}

			$r = 0;
			foreach($text as $key=>$value){
				$out_text[$r] = $value;
				$sentimen[$r] = $analyze->single_process($value); //hasil analisa tersimpan di sini
				$stem[$r] = $analyze->input;

				$imploded = implode(",",$stem[$r]);
				$now = date("Y-m-d H:i:s");
				$sv = $db->prepare("INSERT INTO skripsi_rekap VALUES (NULL, :a, :b, :c, :d, 0)");
				$sv->bindParam(":a",$out_text[$r]);
				$sv->bindParam(":b",$imploded);
				$sv->bindParam(":c",$sentimen[$r]);
				$sv->bindParam(":d",$now);
				$sv->execute();

				$r++;
			}
			if($r == 0){
				//tidak ada data kalimat yg diolah
				create_alert("error","File excel tidak berisi kalimat komentar apapun. Mohon diperiksa kembali");
			}
			?>

			<div class="well">
				<h2>Batch Analysis Result</h2>
				<table class="data table table-sm pmd-table">
					<thead>
						<tr>
							<th>#</th>
							<th>Komentar</th>
							<th>Stemmed Token</th>
							<th>Sentimen</th>
						</tr>
					</thead>
					<tbody>
					<?php
					$n = 1;
					for($i=0; $i<$r;$i++){	
						$stemmed = "";
						if(count($stem[$i]) > 0){
							$stemmed = "<span class='label label-primary'>".implode("</span> <span class='label label-primary'>",$stem[$i])."</span>";
						}

						if($sentimen[$i] == 0){
							$out_sentimen = "<span class='btn btn-danger btn-sm'>Negatif</span>";
						}
						else if($sentimen[$i] == 1){
							$out_sentimen = "<span class='btn btn-success btn-sm'>Positif</span>";
						}
						else{
							$out_sentimen = "<span class='btn btn-warning btn-sm'>Netral</span>";
						}

						echo "
						<tr>
							<td>$n</td>
							<td>$out_text[$i]</td>
							<td>$stemmed</td>
							<td>$out_sentimen</td>
						</tr>
						";
						$n++;
					}
					?>
					</tbody>
				</table>
			</div>


			<?php
		}
	}
	$end = microtime(true);
	$eksekusi = $end - $start;
	echo "<em>Dieksekusi dalam waktu ".number_format($eksekusi,2,".",",")." detik</em>";

}

include "footer.php";
?>