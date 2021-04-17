<?php
$basepath = true;
$data['title'] = "User Management";
$data['menu'] = 4;
$data['submenu'] = 41;
include "header.php";



if(isset($_POST['btn'])){
	$user = $_POST['user'];
	$email = $_POST['email'];
	$name = $_POST['name'];
	$pass1 = $_POST['pswd1'];
	$pass2 = $_POST['pswd2'];

	if(empty($user) or empty($name) or empty($email) or empty($pass1) or empty($pass2)){
		create_alert("error","Mohon mengisi kolom yang sudah disediakan dengan lengkap.");
	}
	else{
		$cek = query("SELECT * FROM skripsi_admin WHERE user = ".quote($user));
		if($cek->rowCount() == 0){
			//save
			if($pass1 <> $pass2){
				create_alert("error","Password yang dimasukkan tidak sama.");
			}
			else if(strlen($pass1) < 5){
				create_alert("error","Password tidak aman. Gunakan minimal 5 karakter");
			}
			else{
				$newpass = password_hash($pass2, PASSWORD_DEFAULT);

				$sql = "INSERT INTO skripsi_admin VALUES (".quote($user).", ".quote($newpass).", ".quote($name).", ".quote($email).", 2);";

				$ins = query($sql);
				if($ins){
					create_alert("Success","Berhasil menginput data pengguna","setting.php");
				}
				else{
					create_alert("Error","Error SQL : $sql");
				}

			}

		}
		else{
			//not save
			create_alert("error","Username tersebut sudah ada, mohon gunakan username lain.");
		}
	}

}

?>
<br>
<h1>User Management</h1>
<br>

<div class="pmd-card pmd-z-depth"> 
	<div class="pmd-tabs">
		<div class="pmd-tab-active-bar"></div>
		<ul class="nav nav-tabs nav-justified" role="tablist">
			<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Manage Users</a></li>
			<li role="presentation"><a href="#about" aria-controls="about" role="tab" data-toggle="tab">New Users</a></li>
		</ul>
	</div>
	<div class="pmd-card-body">
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="home">

				<table class="data table pmd-table">
					<thead>
						<tr>
							<th>#</th>
							<th>Username</th>
							<th>Name</th>
							<th>Email</th>
							<th>Priviledge</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php
					$sql = query("SELECT * FROM skripsi_admin");
					$no = 1;
					foreach($sql as $row){
						if($row['priviledge'] == 1){
							$level = "Administrator";
							$button = "<a href='update-setting.php?id=$row[user]' class='btn btn-primary pmd-ripple-effect'>Update</a>";
						}
						else if($row['priviledge'] ==2){
							$level = "PJM Staff";
							$button = "<a href='update-setting.php?id=$row[user]' class='btn btn-primary pmd-ripple-effect'>Update</a> <a href='crud/delete-user&id=$row[user]' class='btn btn-danger pmd-ripple-effect delete-button'>Hapus</a> ";
						}
						echo "
						<tr>
							<td>$no</td>
							<td>$row[user]</td>
							<td>$row[name]</td>
							<td>$row[email]</td>
							<td>$level</td>
							<td>$button</td>
						</tr>
						";
						$no++;
					}
					?>
					</tbody>
				</table>

			</div>
			<div role="tabpanel" class="tab-pane" id="about">

				<form action="" class="form-horizontal" method="post">
					
					<div class="form-group">
						<div class="form-group pmd-textfield">
						  <label for="user" class="control-label">
						    Username
						  </label>
						  <input type="text" name="user" id="user" class="form-control" >

						</div>
					</div>
					<div class="form-group">
						<div class="form-group pmd-textfield">
						  <label for="name" class="control-label">
						    Name
						  </label>
						  <input type="text" name="name" id="name" class="form-control" >
						</div>
					</div>
					<div class="form-group">
						<div class="form-group pmd-textfield">
						  <label for="email" class="control-label">
						    Email
						  </label>
						  <input type="text" name="email" id="email" class="form-control" >
						</div>
					</div>

					<div class="form-group">
						<div class="form-group pmd-textfield">
						  <label for="pswd1" class="control-label">
						    Password
						  </label>
						  <input type="password" name="pswd1" id="pswd1" class="form-control">
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
						<button name="btn" class="btn btn-primary btn-lg pmd-ripple-effect">Simpan User</button>
					</div>

				</form>

			</div>
		</div>
	</div>
</div>


<?php
include "footer.php";
?>