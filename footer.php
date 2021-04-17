<?php
if(!isset($basepath))
  exit("No direct script access allowed!");
?>
    </div>
    <div class="pmd-sidebar-overlay"></div>
</section>



<script src="assets/less-1.3.3.min.js"></script>	
<script src="assets/bootstrap.min.js"></script>	
<script src="assets/propeller.min.js"></script>	
<script src="assets/alertify.min.js"></script>	
<script src="assets/jquery.mCustomScrollbar.min.js"></script>	
<script src="assets/jquery.mousewheel.min.js"></script>	
<script src="assets/jquery.autocomplete.js"></script>	
<script>
$(function(){
	<?=show_alert()?>

	$('.main-menu>li>a').click(function(){
		$(this).next("ul.submenu").slideToggle(250);
	});
	
	$('.pmd-tabs').pmdTab();


	$('#katadasar').autocomplete({
	    serviceUrl: 'crud/ajax',
	    type : 'POST'
	});

	$("#katadasar").on("keypress",function(e){
		kc = e.keyCode || e.which;
		if(kc == 32)
			e.preventDefault();
	});


	$(".ktdasar-update").click(function(){
		var x = $(this).parent("td").prev("td").html();

		var item = '<input type="hidden" id="old-kata" value="'+x+'">';
		item += '<input type="text" id="new-kata" class="form-control newkata" value="'+x+'">';

		$(".append-edit").html(item);

		$("#updform").modal('show');

		$(".newkata").on("keydown",function(e){
			kc = e.keyCode || e.which;
			console.log(kc);
			if(kc == 13){
				upd_ktdasar();
			}
			else{
				console.log(kc);
			}
		});

		console.log(x);
	});

	$(".upd-button").click(function(){
		upd_ktdasar();
	});
	




	$('#stopword').autocomplete({
	    serviceUrl: 'crud/ajax&stopword',
	    type : 'POST'
	});

	$("#stopword").on("keypress",function(e){
		kc = e.keyCode || e.which;
		if(kc == 32)
			e.preventDefault();
	});

	$(".stopword-update").click(function(){
		var x = $(this).parent("td").prev("td").html();

		var item = '<input type="hidden" id="old-kata" value="'+x+'">';
		item += '<input type="text" id="new-kata" class="form-control newkata" value="'+x+'">';

		$(".append-edit").html(item);

		$("#updform").modal('show');

		$(".newkata").on("keydown",function(e){
			kc = e.keyCode || e.which;
			console.log(kc);
			if(kc == 13){
				upd_ktdasar('crud/upd-stopword');
			}
			else{
				console.log(kc);
			}
		});

		console.log(x);
	});

	$(".upd-button-2").click(function(){
		upd_ktdasar('crud/upd-stopword');
	});







	$(".latih-update").click(function(){
		var x = $(this).parent("td").prev("td").html();
		var y = $(this).parent("td").prev("td").prev("td").html();
		var st = $(this).parent("td").prev("td").prev("td").children("span").attr("data-value");

		if(st == 1){
			it1 = "selected";
			it2 = "";
		}
		else{
			it2 = "selected";
			it1 = "";
		}

		var item = '<input type="hidden" id="old-kata" value="'+x+'">';
		var item2 = '<textarea class="form-control" id="new-kata">'+x+'</textarea>';
		var item3 = '<select id="sentimen" class="form-control">';

		item3 += '<option value="0" '+it2+'>Negatif</option>';
		item3 += '<option value="1" '+it1+'>Positif</option>';

		item3 += '</select>';

		$(".append-edit-2").html(item+item2);
		$(".append-edit-3").html(item3);

		$("#updform").modal('show');
	});

	$(".upd-button-3").click(function(){
		upd_latih();
	});

	function upd_latih(lnk = 'crud/upd-latih'){
		$.ajax({
			method : 'POST',
			dataType : 'json',
			url : lnk,
			data : {
				old : $("#old-kata").val(),
				new : $("#new-kata").val(),
				sentimen : $("#sentimen").val()
			},
			error : function (data){
				console.log(data);
			}
		}).done(function(data){
			console.log(data);
			if(data["success"] == 1){
				hh = [
					"<span data-value='0' class='label label-danger'>Negatif</span>",
					"<span data-value='0' class='label label-danger'>Negatif</span>"
				];


				lbl = 'Success';
				old = $("#old-kata").val();
				baru = $("#new-kata").val();
				stm = $("#sentimen").val();

				$("[data-ctn='"+old+"']").html(baru);
				$("[data-ctn='"+old+"']").attr("data-ctn",baru);

				$("[data-ctn='"+old+"']").prev("td").html(hh[stm]);
				$("#old-kata").attr("value",baru);
			}
			else{
				lbl = 'Error';
			}
			alertify.alert(lbl,data["message"]);
		});
	}





	

	function upd_ktdasar(lnk = 'crud/upd-katadasar'){
		$.ajax({
			method : 'POST',
			dataType : 'json',
			url : lnk,
			data : {
				old : $("#old-kata").val(),
				new : $("#new-kata").val()
			}
		}).done(function(data){
			console.log(data);
			if(data["success"] == 1){
				lbl = 'Success';
				old = $("#old-kata").val();
				baru = $("#new-kata").val();

				$("[data-ctn="+old+"]").html(baru);
				$("[data-ctn="+old+"]").attr("data-ctn",baru);
			}
			else{
				lbl = 'Error';
			}
			alertify.alert(lbl,data["message"]);
		});
	}


	$(".delete-button").on("click",function(e){
		e.preventDefault();
		target = $(this).attr("href") || $(this).attr("data-href");
		alertify.confirm("Hapus Data?","Apakah Anda yakin ingin menghapus data ini?", function(){
			window.location = target;
		}, function(){});
	});
});
</script>
</Body>
</html>