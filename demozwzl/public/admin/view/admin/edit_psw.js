;(function() {
		init_parsley();
	   	$("#form-submit").click(function(){
	   		var flag=$('#validate-form').parsley('validate' );
	   		if(flag){
	   			$.post(
	   				"/admin/admin/edit_psw",
	   				$("#validate-form").serialize(),
	   				function(data){
			      		if(data.flag){
			      			$("#myModal").modal("hide");
			      			toastr['success']("操作成功！", ''); 
			      		}else{
			      		 	toastr['error'](data.msg,"操作失败！"); 
			      		}
	   				}
	   			);
	   		}
	   	})
})();