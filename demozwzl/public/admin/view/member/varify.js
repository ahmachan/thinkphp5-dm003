;(function() {
		init_parsley();
		$("#read_card").click(function(){
			var $card_no=read_card();
			if($card_no==false){
				return;
			}
			if($card_no.length>5){
				loading();
				$.post(
					"/admin/member/find_bycard",
					{"card_no":$card_no},
					function(data){
						close_loding();
						if(data.flag){
							$("#name").val(data.data.name);
							$("#mobile").val(data.data.mobile);
							$("#icon").attr("src",data.data.icon);
							$("#form-submit").removeAttr("disabled")
						}else{
							toastr['error'](data.msg);
						}
					}
				)
			}
			$("#bind_card_no").val($card_no);
		});
	   	$("#form-submit").click(function(){
	   		var flag=$('#validate-form').parsley('validate' );
	   		if(flag){
	   			$.post(
	   				"/admin/member/varify",
	   				$("#validate-form").serialize(),
	   				function(data){
			      		if(data.flag){
			      			toastr['success']("操作成功！", ''); 
			      		}else{
			      		 	toastr['error'](data.msg,"操作失败！"); 
			      		}
	   				}
	   			);
	   		}
	   	})
})();