;(function() {
	   	$("#form-submit").click(function(){
	   			$.post(
	   				"/admin/SystemSet/edit_post",
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