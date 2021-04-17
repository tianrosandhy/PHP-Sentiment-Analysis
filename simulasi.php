<?php
$basepath = true;
$data['title'] = "Simulasi Rumus";
$data['menu'] = 5;
$data['submenu'] = 51;
include "header.php";
?>
<br>
<h1>Simulasi Analisis Sentimen</h1>

<div class="alert alert-info" style="margin-top:1em; font-size:13px">Untuk melihat langkah-langkah dan algoritma penentuan sentimen kalimat yang akan diinputkan, masukkan contoh kalimat yang ingin diuji di bawah ini : </div>

<form action="" method="get">
	<div class="input-group">
		<input type="text" class="form-control" name="kalimat">
		<div class="input-group-btn">
			<button class="btn btn-primary">Proses</button>
		</div>
	</div>
</form>

<?php
if(isset($_GET['kalimat'])){
	$sentimen = $analyze->single_process($_GET['kalimat']);

	echo "
	<strong>Kalimat Input : </strong> $_GET[kalimat]
	";
	echo "<br>";

	echo "
	<strong>Stemmed Input : </strong>
	";
	foreach($analyze->input as $stem){
		echo "<span class='label label-primary'>$stem</span> ";
	}
	echo "<br>";


	$jml = count($analyze->use['komentar']) - 1;
	echo "<strong>Jumlah data latih ditemukan : </strong>" . ($jml);
	echo "<br>";
	echo "<ol>";
	for($i=1; $i<count($analyze->use['komentar']); $i++){
		echo "<li>". $analyze->use['komentar'][$i]."</li>";
	}
	echo "</ol>";
	echo "<br>";


	echo "<strong>Stemmed Data Latih : </strong>";
	echo "<br>";
	echo "<ol>";
	for($i=1; $i<count($analyze->use['stem']); $i++){
		echo "<li>";
		foreach($analyze->use['stem'][$i] as $itm){
			echo "<span class='label label-primary'>$itm</span> ";
		}
		echo "</li>";
	}
	echo "</ol>";
	echo "<br>";


/*
	echo "<strong>Word Token : </strong>";
	echo "<ol>";
	foreach($analyze->token as $tkn){
		echo "<li><span class='label label-primary'>$tkn</span></li>";
	}
	echo "</ol>";
	echo "<br>";

	echo "<strong>TF (Term Frequency) & DF (Document Frequency)</strong>";
	echo "<table class='table table-sm pmd-table'>";
	echo "<tr>";
	echo "<th></th>";
	echo "<th>IDF</th>";
	for($i=0; $i<=$jml; $i++){
		echo "<th><span class='label label-primary'>TF-".$i."</span></th>";
	}
	echo "<th>DF</th>";
	echo "<tr>";
	foreach($analyze->tf as $kata=>$ar){
		$ddf[$kata] = number_format(log10(($analyze->df[$kata] == 0) ? 1 : ($jml / $analyze->df[$kata])),3,",",".");

		echo "<tr>";
		echo "<th>$kata</th>";
		echo "<td>$ddf[$kata]</td>";
		for($i=0; $i<=$jml; $i++){
			echo "<td>$ar[$i]</td>";
		}
		echo "<td>".$analyze->df[$kata]."</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<br>";


	echo "<strong>Bobot TF * IDF</strong>";
	echo "<table class='table table-sm pmd-table'>";
	echo "<tr>";
	echo "<th></th>";
	for($i=0; $i<=$jml; $i++){
		echo "<th><span class='label label-primary'>W-".$i."</span></th>";
	}
	echo "<tr>";
	foreach($analyze->tf as $kata=>$ar){
		$ddf[$kata] = log10(($analyze->df[$kata] == 0) ? 1 : ($jml / $analyze->df[$kata]));

		echo "<tr>";
		echo "<th>$kata</th>";
		for($i=0; $i<=$jml; $i++){
			$bobot = $ar[$i] * $ddf[$kata];
			echo "<td>".number_format($bobot,3,".",",")."</td>";

			if(!isset($w[$i]))
				$w[$i] = $bobot;
			else{
				$w[$i] += $bobot;
			}
		}
		echo "</tr>";
	}
	echo "<tr>";
	echo "<td></td>";
	foreach($w as $bbt){
		echo "<td><strong>$bbt</strong></td>";
	}

	echo "</tr>";

	echo "</table>";
*/

	echo "<strong>Pusat Data Setelah Iterasi K-Means : </strong>" . $analyze->pusat; 
	echo "<br>";

	echo "<strong>Cluster Final : </strong>";
	echo "<ul>";

	$stt[0] = "<span class='label label-danger'>Negatif</span> ";
	$stt[1] = "<span class='label label-success'>Positif</span> ";
	$stt[-1] = "<span class='label label-info'>Netral</span> ";


	foreach($analyze->cluster_final as $id){
		if($id > 0){
			$st = $analyze->use["sentimen"][$id];
			echo "<li>Kalimat ".$id." : ".$stt[$st].". Posisi = ".$analyze->bobot[$id]."</li>";			
		}
	}
	echo "</ul>";

	echo "<br><br>";
	echo "<div class='well'><h2><strong>Final Sentiment : </strong>" . $stt[$sentimen]."</h2></div>";
	echo "<br><br>";
}



include "footer.php";
?>