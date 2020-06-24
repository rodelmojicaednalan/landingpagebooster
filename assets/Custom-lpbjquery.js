
$(".modal-content").draggable({
  handle: ".modal-header" 
});
$("#alertBox").draggable({
  handle: "#alertBox h1" 
});

// function myFunction(clicked_id) 
// { 
// var now = new Date();
// var time = now.getTime();
// time += 3600 * 1000;
// now.setTime(time);
// var expires = "; expires="+now.toGMTString();
// document.cookie = "version_cookie=<?php echo $this->new_version_cookies; ?>"+expires;
// $('#'+clicked_id).parent().parent().addClass("updated-slide");
// }
// $(document).ready(function()
// {
// $('#click_button').click(function(){
// $.ajax({
// url:'buttonclick.php',
// success: function(Response){
	// alert(Response);
// }
// });
// });
// });