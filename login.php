<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login Sistem Analisis Sentimen</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="assets/bootstrap.min.css">
<link rel="stylesheet" href="assets/propeller.min.css">
<link rel="stylesheet" href="assets/alertify.min.css">
<link rel="stylesheet" href="assets/jquery.mCustomScrollbar.css">
<link rel="stylesheet" href="assets/font-awesome.min.css">
<link rel="stylesheet" href="assets/animate.css">
<link rel="stylesheet/less" href="style.less">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>
<body>
	
<div id="wrapper" class="login-wrapper">
	<form action="login-proses.php" method="post" class="form-login form-horizontal pmd-z-depth">
		<center>
			<i class="fa fa-user fa-3x"></i>
			<h1>Sistem Analisis Sentimen Komentar</h1>
		</center>

		<div class="form-group pmd-textfield pmd-textfield-floating-label">
		   <label for="username" class="control-label">
		     Username
		   </label>
		   <input type="text" name="username" id="username" class="form-control">
		</div>		

		<div class="form-group pmd-textfield pmd-textfield-floating-label">
		   <label for="password" class="control-label">
		     Password
		   </label>
		   <input type="password" name="password" id="password" class="form-control">
		</div>

		<div class="checkbox pmd-default-theme">
		    <label class="pmd-checkbox pmd-checkbox-ripple-effect">
		        <input type="checkbox" value="" checked>
		        <span>Remember Me</span>
		    </label>
		</div>
		<br>
		<button name="btn" class="btn btn-block btn-primary pmd-ripple-effect">
			Log In
		</button>


	</form>
</div>


<script src="assets/jquery-1.12.3.min.js"></script>	
<script src="assets/less-1.3.3.min.js"></script>
<script src="assets/bootstrap.min.js"></script>	
<script src="assets/propeller.min.js"></script>	
<script src="assets/alertify.min.js"></script>	
<script src="assets/jquery.mousewheel.min.js"></script>	
<script src="assets/smoothscroll.js"></script>	
<script>
	$(function(){
		<?=show_alert()?>
	});
</script>
</body>
</html>