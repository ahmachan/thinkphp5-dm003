;(function() {
		init_parsley();
		
		var in_varifycation=$("#in_varifycation").val();
		if(in_varifycation==1){
			$("#free_finger_div").hide();
		}else{
			$("#free_finger_div").show();
		}
		$("#free_finger").change(function(){
			if($("#free_finger").val()==0&&in_varifycation==0){
				$("#free_finger_div").show();
			}else{
				$("#free_finger_div").hide();
			}
		});
		$("#finger1").click(function(){
			var csharp = window.external;
		 	csharp.SignFinger1();
		});
		$("#finger2").click(function(){
			var csharp = window.external;
			csharp.SignFinger2();
			
		});
		
		
	   	$("#form-submit").click(function(){
	   		var flag=$('#validate-form').parsley('validate' );
	   		if(flag){
	   			$.post(
	   				"/admin/member/bind_card",
	   				$("#validate-form").serialize(),
	   				function(data){
			      		if(data.flag){
			      			$("#myModal").modal("hide");
			      			toastr['success']("操作成功！", ''); 
			      			$("#myform #btn-advanced-refresh").trigger("click");// 刷新页面
			      		}else{
			      		 	toastr['error'](data.msg,"操作失败！"); 
			      		}
	   				}
	   			);
	   		}
	   	})
})();
