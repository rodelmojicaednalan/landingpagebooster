
$("#restore_cmd").click(function() {  
dataform = $("#restore_cmd").val();
$.ajax({
		type: "POST",
		 data: dataform,
		 success: function() {
		 //  alert('success!');
		   
		  }
	   })   
	 
	});