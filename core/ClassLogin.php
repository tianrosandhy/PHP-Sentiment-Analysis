<?php
Class Login{
	var $db;

	public function __construct(){
		//entahlah.. nggak ada yg perlu diconstruct sepertinya
		global $db;
		$this->db = $db;
	}


	public function cek_login(){
		/*kondisi user dinyatakan login adalah : 
		1. Memiliki $_COOKIE['adv_token'];
		2. $_SESSION['adv_token'] terdaftar di tabel skripsi_admin_log, dan dalam keadaan masih belum expired
		3. IP dan User Agent sesuai dengan token yang terdaftar
		*/
		if(isset($_COOKIE['adv_token'])){
			$token = $_COOKIE['adv_token'];
			$now = date("Y-m-d H:i:s");
			$cek = $this->db->query("SELECT * FROM skripsi_admin_log WHERE token = ".$this->db->quote($token)." AND expired > ".$this->db->quote($now));
			if($cek){
				$row = $cek->fetch();
				if($row['ip'] == $_SERVER['REMOTE_ADDR'] || $row['useragent'] == $_SERVER['HTTP_USER_AGENT']){
					//kondisi bisa disesuaikan utk kebutuhan dengan ATAU / DAN
					$username = $row['username'];

					//kembalikan data user yg sedang login,, siapa tahu nanti ingin diolah
					$get_admin = $this->db->query("SELECT * FROM skripsi_admin WHERE user = ".$this->db->quote($username));
					$rget = $get_admin->fetch();

					return array(
						"username" => $rget['user'],
						"name" => $rget['name'],
						"email" => $rget['email'],
						"priviledge" => $rget['priviledge']
					);

				}

			}
		}
		return false;
	}


	public function salah_login_action($username){

		$tgl = date("Y-m-d H:i:s");
		$ip = $_SERVER['REMOTE_ADDR'];
		$useragent = $_SERVER['HTTP_USER_AGENT'];

		//memasukkan data ke skripsi_admin_log dengan flag STAT = 0.
		$save = $this->db->prepare("INSERT INTO skripsi_admin_log VALUES (NULL, ?, '', '', ?, ?, ?, 0)");
		$save->execute(array(
			$tgl, $username, $ip, $useragent
		));
		return true;
	}


	public function cek_salah_login($limit=5){
		//cek apakah di tabel skripsi_admin_log ada 5 IP yang sama dalam keadaan salah login (STAT = 0)


		$ip = $_SERVER['REMOTE_ADDR'];
		$cek = $this->db->prepare("SELECT * FROM skripsi_admin_log WHERE stat = 0 AND ip = ?");

		$cek->execute(array($ip));
		if($cek->rowCount() >= $limit)
			return false;
		return true;
	}


	public function true_login($username, $expired){

		$tgl = date("Y-m-d H:i:s");
		if($expired <> 0){
			$expireddb = date("Y-m-d H:i:s",strtotime($expired));
		}
		else{
			$expireddb = date("Y-m-d H:i:s",strtotime("+6 hours"));
		}

		$ip = $_SERVER['REMOTE_ADDR'];
		$useragent = $_SERVER['HTTP_USER_AGENT'];

		$token = sha1($ip.$expireddb."string_random_apasaja".microtime()); //intinya membuat karakter acak saja


		//update skripsi_admin_log yang salah2 di IP dan User Agent bersangkutan bila ada
		$upd = $this->db->query("UPDATE skripsi_admin_log SET stat = 9 WHERE token = '' AND ip = ".$this->db->quote($ip)." AND useragent = ".$this->db->quote($useragent));


		//memasukkan data ke skripsi_admin_log dengan flag STAT = 1.
		$save = $this->db->prepare("INSERT INTO skripsi_admin_log VALUES (NULL, ?, ?, ?, ?, ?, ?, 1)");
		$save->execute(array(
			$tgl, $expireddb, $token, $username, $ip, $useragent
		));


		//simpan token ke cookie
		$expr = 0;
		if($expired <> 0){
			$expr = intval(strtotime($expired));
		}
		setcookie("adv_token", $token, $expr, "/");

		return true;
	}

	public function logout(){
		
		if(isset($_COOKIE['adv_token'])){
			$token = $_COOKIE['adv_token'];
			//unset cookie

			//flag database
			$now = date("Y-m-d H:i:s");
			unset($_COOKIE['adv_token']);
			setcookie("adv_token",null,$now,"/");

			$this->db->query("UPDATE skripsi_admin_log SET expired = ".$this->db->quote($now)." WHERE token = ".$this->db->quote($token));
		}

		return true;
	}

	public function login_redir(){
		if(!$this->cek_login())
			header("location:index.php");
	}

}