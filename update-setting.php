<?php
$basepath = true;
$data['title'] = "Update User";
$data['menu'] = 4;
$data['submenu'] = 41;
include "header.php";


$get = query("SELECT * FROM skripsi_admin WHERE user = ".quote($_GET['id']));
if($get->rowCount() > 0){
	$row = $get->fetch();





	if(isset($_POST['btn'])){
		$user = $_POST['user'];
		$old_user = $_POST['old_user'];
		$name = $_POST['name'];
		$email = $_POST['email'];

		$pass1 = $_POST['pswd1'];
		$pass2 = $_POST['pswd2'];

		if(empty($user) or empty($name) or empty($email)){
			create_alert("error","Mohon mengisi kolom yang sudah disediakan dengan lengkap.");
		}
		else{
			$cek = query("SELECT * FROM skripsi_admin WHERE user = ".quote($user)." AND user <> ".quote($old_user));
			if($cek->rowCount() == 0){
				//save
				if(strlen($pass1) > 0 or strlen($pass2) > 0){
					if($pass1 <> $pass2){
						create_alert("error","Password yang dimasukkan tidak sama.","update-setting.php?id=$old_user");
					}
					else if(strlen($pass1) < 5){
						create_alert("error","Password tidak aman. Gunakan minimal 5 karakter","update-setting.php?id=$old_user");
					}
					else{
						$newpass = password_hash($pass2, PASSWORD_DEFAULT);
						$addQuery = ", pass = ".quote($newpass);
					}
				}
				else{
					$addQuery = "";
				}

				$sql = "UPDATE skripsi_admin SET user = ".quote($user).", name = ".quote($name)." $addQuery , email = ".quote($email)." WHERE user = ".quote($old_user);
				$upd = query($sql);
				if($upd){
					create_alert("Success","Berhasil mengupdate data pengguna","setting.php");
				}
				else{
					create_alert("Error","Error SQL : $sql");
				}
			}
			else{
				//not save
				create_alert("error","Username tersebut sudah ada, mohon gunakan username lain.");
			}
		}
	}






}
else{
	create_alert("error","Username tidak ditemukan","setting.php");
}
?>
<br>
<h1>Update User</h1>

<form action="" class="form-horizontal" method="post">
	<div class="well">
		
		<div class="form-group">
			<div class="form-group pmd-textfield">
			  <label for="user" class="control-label">
			    Username
			  </label>
			  <input type="text" name="user" id="user" class="form-control" value="<?=$row['user']?>">
			  <input type="hidden" name="old_user" class="form-control" value="<?=$row['user']?>">

			</div>
		</div>
		<div class="form-group">
			<div class="form-group pmd-textfield">
			  <label for="name" class="control-label">
			    Name
			  </label>
			  <input type="text" name="name" id="name" class="form-control" value="<?=$row['name']?>">
			</div>
		</div>
		<div class="form-group">
			<div class="form-group pmd-textfield">
			  <label for="email" class="control-label">
			    Email
			  </label>
			  <input type="text" name="email" id="email" class="form-control" value="<?=$row['email']?>">
			</div>
		</div>

		<div class="form-group">
			<div class="form-group pmd-textfield">
			  <label for="pswd1" class="control-label">
			    New Password
			  </label>
			  <input type="password" name="pswd1" id="pswd1" class="form-control" placeholder="Kosongkan bila password tidak ingin diganti">
			</div>
		</div>
		<div class="form-group">
			<div class="form-group pmd-textfield">
			  <label for="pswd2" class="control-label">
			    Repeat Password
			  </label>
			  <input type="password" name="pswd2" id="pswd2" class="form-control">
			</div>
		</div>
		
		
		<div>
			<button name="btn" class="btn btn-primary btn-lg pmd-ripple-effect">Update User</button>
		</div>
	
	
	</div>	

</form>


<?php
include "footer.php";
?>