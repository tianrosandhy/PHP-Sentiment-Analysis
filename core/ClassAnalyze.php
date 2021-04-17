<?php
Class Analyze{
	var $data; //data latih komentar
	var $input; //stem2an data uji
	var $token; //list item kata
	var $use; //data analisa yg digunakan (data uji + data latih)
	var $tf;
	var $df;
	var $bobot;
	var $pusat; //pusat data final
	var $cluster_final; //data yg berisi file cluster final

	public function __construct(){
		$sql = query("SELECT * FROM vw_komentar");
		$arr = array();
		$no = 0;
		foreach($sql as $row){
			$arr[$no]['komentar'] = $row['komentar'];
			$pecah = explode(",",$row['stem']);
			foreach($pecah as $pc){
				$arr[$no]['stem'][] = trim($pc);
			}
			$arr[$no]['sentimen'] = $row['sentimen'];
			$no++;
		}
		$this->data = $arr;
	}

	public function stem($string){
		global $naz;
		$input = filter_var(strtolower($string), FILTER_SANITIZE_STRING);
		$result = preg_replace("/[^a-zA-Z ]/", "", $input);
		$pecah = explode(" ",$result);
		foreach($pecah as $item){
			if(strlen($item) > 0){
				$this->input[] = $naz->nazief($item);
			}
		}
	}


	public function stopword(){
		$sql = query("SELECT stopword FROM stopword_list");
		foreach($sql as $row){
			$stopword[] = $row['stopword'];
		}

		foreach($this->input as $inp){
			if(!in_array($inp, $stopword)){
				$saved[] = $inp;
			}
		}
		$this->input = $saved;
	}

	public function get_data_latih(){
		//cari di $data[]['stem'] yang arraynya intersect dengan array ini
		$use = array();
		$sip = 0;
		$n = 1;
		foreach($this->data as $row){
			if(count(array_intersect($row['stem'], $this->input)) > 0){
				//ada
				$use["komentar"][$n] = $row['komentar'];
				$use["stem"][$n] = $row['stem'];
				$use["sentimen"][$n] = $row['sentimen'];
				$n++;
			}
			$sip++;
		}

		//tweak penyesuaian
		$use["komentar"][0] = null;
		$use["stem"][0] = $this->input;
		$use["sentimen"][0] = null;

		$this->use = $use;
	}

	public function create_token(){
		foreach($this->use['stem'] as $tk){
			foreach($tk as $itm){
				$token[] = $itm;
			}
		}
		$token = array_unique($token);
		$this->token = $token;
	}


	public function cari_tf(){
		foreach($this->token as $kata){
			foreach($this->use['stem'] as $key=>$value){
				$val = array_count_values($value);
				if(isset($val[$kata]))
					$tf[$kata][$key] = $val[$kata];
				else
					$tf[$kata][$key] = 0;
			}
		}
		$this->tf = $tf;
	}

	public function cari_df(){
		foreach($this->tf as $key=>$value){
			$df = 0;
			$n = count($value);
			for($i=1; $i<$n; $i++){
				if($value[$i] > 0)
					$df++;
			}
			$this->df[$key] = $df;
		}
	}

	
	public function hitung_bobot(){
		$jumlahdata = count($this->use['komentar'])-1;
		foreach($this->tf as $kk=>$vv){
			$ddf[$kk] = ($this->df[$kk] == 0) ? 1 : ($jumlahdata / $this->df[$kk]);
			$idf[$kk] = log10($ddf[$kk]);
		}


		$bobot = array();
		foreach($idf as $key=>$value){
			if(!isset($this->tf[$key])){
				$n = 0;
			}
			else{
				$n = count($this->tf[$key]);
			}

			for($i=0; $i<$n; $i++){
				if(!isset($bobot[$i]))
					$bobot[$i] = 0;
				$bobot[$i] += ($this->tf[$key][$i] * $value);
			}
		}

		$this->bobot = $bobot;
	}	



	public function hitung_jarak($x1, $y1){
		$n = count($y1);
		for($i=0;$i<$n;$i++){
			$jarak[$i] = abs($x1-$y1[$i]);
		}
		return $jarak;
	}

	public function bagi_cluster($jarak1, $jarak2){
		$n = count($jarak1);
		$c1 = array();
		$c2 = array();

		for($i=0;$i<$n;$i++){
			if($jarak1[$i] < $jarak2[$i])
				$c1[] = $i;
			else
				$c2[] = $i;
		}

		return array("c1" => $c1, "c2" => $c2);
	}

	public function means($index, $bobot){
		$sum = 0;
		$n = 0;
		foreach($index as $key=>$value){
			$sum += $bobot[$value];
			$n++;
		}

		$means = ($n == 0) ? 1 : $sum / $n;

		return $means;
	}

	public function kmeans(){
		$pusat1 = $this->bobot[0];
		$jarak1 = $this->hitung_jarak($pusat1, $this->bobot);
		$jarak_max = array_keys($jarak1, max($jarak1));

		$pusat2 = $this->bobot[$jarak_max[0]];
		$jarak2 = $this->hitung_jarak($pusat2, $this->bobot);

		$bagi = $this->bagi_cluster($jarak1, $jarak2);

		$c1_temp = $bagi["c1"];
		$c2_temp = $bagi["c2"];
	
		$max_iterasi = 500;
		$sama = 0;
		for($i=0; $i<$max_iterasi; $i++){
			$pusat1 = $this->means($c1_temp, $this->bobot);
			$jarak1 = $this->hitung_jarak($pusat1, $this->bobot);
			$pusat2 = $this->means($c2_temp, $this->bobot);
			$jarak2 = $this->hitung_jarak($pusat2, $this->bobot);

			$cluster = $this->bagi_cluster($jarak1, $jarak2);

			if(count($cluster["c1"]) == count($c1_temp)){
				if($sama == ceil($max_iterasi/10)){
					break;
				}
				else{
					$sama++;
				}
			}
			else{
				$sama = 0;
			}

			$c1_temp = $cluster["c1"];
			$c2_temp = $cluster["c2"];
		}

		//sampai disini perulangan berakhir
		//cari cluster dengan value 0 ada dimana
		if(in_array(0, $cluster["c1"])){
			$c_final = $cluster["c1"];
			$pusat = $pusat1;
			$outp = "Cluster 1";
		}
		else{
			$c_final = $cluster["c2"];
			$pusat = $pusat2;
			$outp = "Cluster 2";
		}

		$this->pusat = $pusat;
		$this->cluster_final = $c_final;
	}


	public function knn($debug=false){
		//golongkan nilai sentimen yang ada di dalam cluster
		$cluster = $this->cluster_final;
		$sentimen = $this->use['sentimen'];
		$pusat = $this->pusat;
		$bobot = $this->bobot;


		$positif = 0;
		$negatif = 0;
		foreach($this->cluster_final as $c){
			if($c <> 0){
				if($sentimen[$c] == 1){
					$positif++;
				}
				else{
					$negatif++;
				}
			}
		}

		if($debug){
			echo "
			<span class='label label-success'>Data positif : $positif</span>
			<span class='label label-danger'>Data negatif : $negatif</span>
			<br>
			";
		}

		//jalan tengah
		//kalau selisih data positif dan negatif tidak lebih dari 6.66%, maka KNN baru dijalankan untuk mencari tetangga terdekat
		//selebihnya, sentimen kebanyakan dalam sebuah cluster seharusnya sudah mewakili.
		$total_coba = $positif + $negatif;
		$selisih_coba = abs($positif - $negatif);
		if($selisih_coba < ($total_coba / 15)){

			//Metode K-NN ditentukan di baris ini.
			$jarak = array();
			foreach($cluster as $c){
				//jadikan data uji sebagai pusat data
				if($c == 0){
					$pusat = $bobot[$c];
					continue;
				}
				$jarak[$c] = abs($pusat-$bobot[$c]);
				if($debug)
					echo "Jarak <strong>K-$c</strong> ke pusat data = ".$pusat." - ".$bobot[$c]." = <strong>".$jarak[$c]."</strong><br>";
			}

			if(count($jarak) > 0){
				//nggak ada data apapun di cluster tersebut
				$jarak_min = array_keys($jarak, min($jarak));
				$hasil = $sentimen[$jarak_min[0]];

				if($hasil==0)
					$cl = "danger";
				else
					$cl = "success";

				$dbug_text = "Sentimen ditentukan berdasarkan jarak terdekat yaitu di <span class='label label-$cl'>K-".$jarak_min[0]."</span><br>";
			}
			else{
				$hasil = -1;
				$dbug_text = "Tidak ada data apapun yang dapat dijadikan dasar penentuan sentimen.";
			}

		}
		else{
			if($positif > $negatif)
				$hasil = 1;
			else
				$hasil = 0;

			if($positif == 0 and $negatif == 0){
				$hasil = -1;
			}
			$dbug_text = "Metode KNN tidak dijalankan karena mengikuti sentimen terbanyak di cluster tersebut";
		}


		if($debug){
			echo $dbug_text;
		}
		return intval($hasil);
	}




	public function single_process($kalimat, $debug=false){
		//clear old variable
		$this->input = $this->token = $this->use = $this->tf = $this->df = $this->bobot = $this->pusat = $this->cluster_final = null;

		$this->stem($kalimat);

		$this->stopword();
		$this->get_data_latih();
		$this->create_token();

		$this->cari_tf();
		$this->cari_df();

		$this->hitung_bobot();

		$this->kmeans();
		$final = $this->knn($debug);
		return $final;
	}


	public function jarak_hasil_ke_pusat(){
		$pusat = $this->pusat;
		$bobot = $this->bobot[0];
		return abs($pusat - $bobot);
	}
}