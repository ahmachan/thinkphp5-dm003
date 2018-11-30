;(function() {
		init_parsley();

		$("#read_card").click(function(){
			var csharp = window.external;
			var $card_no=csharp.ReadCard();
			$("#card_no").val($card_no);
		});
	   	$("#form-submit").click(function(){
	   		var flag=$('#validate-form').parsley('validate' );
	   		if(flag){
	   			if($("#type").val()=='2'){
	   				var csharp = window.external;
					var $flag=csharp.QueryCard($("#card_no").val());
					if($flag=='0'){
						toastr['success']("成功","成功！");
						return false;
					}else{
						toastr['error']("失败","失败！");
						return false;
					}
					setTimeout(function () { 
      						 csharp.StartRead();
    				},5000);
		   			return false;
	   			}
	   			$.post(
	   				"/admin/temp/add_post",
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