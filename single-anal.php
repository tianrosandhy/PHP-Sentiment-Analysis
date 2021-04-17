<?php
$basepath = true;
$data['title'] = "Single Analysis";
$data['menu'] = 2;
$data['submenu'] = 21;
include "header.php";
?>
<br>
<h1>Single Data Analysis</h1>

<div class="well">
	<div class="form-group pmd-textfield">
		<input type="text" class="form-control" id="analyze" placeholder="Masukkan kalimat komentar disini">	
		<br>
		<button id="analyzes" name="btn" class="btn btn-primary pmd-ripple-effect">Proses</button>
		<a class="btn-clear btn btn-info pmd-ripple-effect">Clear</a>
		
	</div>
</div>

<div id="hasil">
	<img src="assets/pie.gif" class="hide">
	<div id="out"></div>
	<input type="hidden" name="unique_id" value="">
</div>


<script>
	$(function(){
		$(".btn-clear").on("click",function(){
			$("#analyze").val("");
		});
		$("#analyze").on("keypress",function(e){
			if(e.which == 13){
				anal_run();
			}
		});

		$("#refresh").click(function(){
			location.reload();
		});
	});

	$("#analyzes").on("click",function(){
		anal_run();
	});

	function anal_run(){
		$("img").removeClass("hide");
		$.ajax({
			url : "api.php",
			method : "GET",
			data : {q : $("#analyze").val()},
			dataType : "json"
		}).done(function(data){
			$("img").addClass("hide");
			if(data['error'] == 0){
				if(data['sentiment'] == 1){
					vclass = 'alert-success';
				}
				else{
					vclass = 'alert-danger';
				}
				$("#out").html("<div class='alert "+vclass+"'>"+data['message']+"</div>");
				$("input[name=unique_id]").val(data['unique_id']);
			}
			else{
				$("#out").html("<div class='alert alert-info'>"+data['message']+"</div>");
			}
		});
	}


</script>

<?php
include "footer.php";
?>