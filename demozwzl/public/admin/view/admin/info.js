;(function() {
		window.close_loding();
	   	$("#form-submit").click(function(){
	   			$.post(
	   				"/admin/admin/info",
	   				$("#form-1").serialize(),
	   				function(data){
			      		if(data.flag){
			      			toastr['success']("操作成功！", '');
			      		}else{
			      		 	toastr['error'](data.msg,"操作失败！"); 
			      		}
			      		return false;
	   				},
	   				"json"
	   			);
	   			return false;
	   	})
})();