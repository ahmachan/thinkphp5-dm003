jQuery(document).ready(function() {

   $.getJSON(
   	"/admin/frame/getstatistic",
   	function(data){
   		$("#div1").text(data.msg.div1);
   		$("#div2").text(data.msg.div2);
   		$("#div3").text(data.msg.div3);
   		$("#div4").text(data.msg.div4);
   	}
   );


});

