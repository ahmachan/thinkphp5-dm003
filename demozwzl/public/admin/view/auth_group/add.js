;(function() {
		init_parsley();
		$("#validate-form .menu-btn").click(function(){
			var flag=$(this).hasClass("btn-default-alt");
			if(flag){
				var flag1=$(this).hasClass("menu-btn-3");
				if(flag1){
					$(this).removeClass("btn-default-alt").addClass("btn-danger-alt");	
				}else{
					$(this).parent().parent().find(".btn-default-alt").removeClass("btn-default-alt").addClass("btn-danger-alt");	
				}
				var	$code=$(this).attr("data-code");
				if($code.length==8){
					var temp=$code.substring(0,4);
					$("#validate-form").find("a[data-code="+temp+"]").removeClass("btn-default-alt").addClass("btn-danger-alt");
				}else if($code.length=12){
					var temp=$code.substring(0,4);
					var temp1=$code.substring(0,8);
					$("#validate-form").find("a[data-code="+temp+"]").removeClass("btn-default-alt").addClass("btn-danger-alt");
					$("#validate-form").find("a[data-code="+temp1+"]").removeClass("btn-default-alt").addClass("btn-danger-alt");
				}
				
						
				
			}else{
				var flag1=$(this).hasClass("menu-btn-3");
				if(flag1){
					$(this).removeClass("btn-danger-alt").addClass("btn-default-alt");	
				}else{
					$(this).parent().parent().find(".btn-danger-alt").removeClass("btn-danger-alt").addClass("btn-default-alt");	
				}
			}
		});
	   	$("#form-submit").click(function(){
	   		var flag=$('#validate-form').parsley('validate' );
	   		var menu_1=$(".menu-btn-1.btn-danger-alt");//第一级菜单
	   		var menu_2=$(".menu-btn-2.btn-danger-alt");//第二级菜单
	   		var menu_3=$(".menu-btn-3.btn-danger-alt");//第三级菜单
	   		
	   		var pool_1="",pool_2="",pool_3="";
	   		menu_1.each(function(){
	   			pool_1+=$(this).attr("data-code")+",";
	   		});
	   		menu_2.each(function(){
	   			pool_2+=$(this).attr("data-code")+",";
	   		});
	   		menu_3.each(function(){
	   			pool_3+=$(this).attr("data-code")+",";
	   		});
	   		
	   		$("#pool_1").val(pool_1.substring(0,pool_1.lastIndexOf(",")));
	   		$("#pool_2").val(pool_2.substring(0,pool_2.lastIndexOf(",")));
	   		$("#pool_3").val(pool_3.substring(0,pool_3.lastIndexOf(",")));
	   		if(flag){
	   			$.post(
	   				"/admin/AuthGroup/add_post",
	   				$("#validate-form").serialize(),
	   				function(data){
			      		if(data.flag){
			      			$("#myModal").modal("hide");
			      			toastr['success']("操作成功！", ''); 
			      			$("#myform #btn-advanced-refresh").trigger("click");// 刷新页面
			      		}else{
			      		 	toastr['error'](data.msg,"操作失败！"); 
			      		}
	   				},
	   				"json"
	   			);
	   		}
	   	})
})();