;(function() {
	   	$("#form-submit").click(function(){
	   		var flag=$('#validate-form').parsley('validate' );
	   		if(flag){
	   			$.post(
	   				"/admin/temp/query",
	   				$("#validate-form").serialize(),
	   				function(data){
			      		if(data.flag){
			      			var result=data.data;
			      			$("#err_msg").val(result.err_msg);
			      			$("#enable_in").val(result.enable_in);
			      			$("#owner_type").val(result.owner_type);
			      		}else{
			      		 	toastr['error'](data.msg,"操作失败！"); 
			      		}
	   				}
	   			);
	   		}
	   	})
})();