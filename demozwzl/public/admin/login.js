;(function(){
		/**初始化表单验证器*/
	init_parsley();
	init_toastr();
	$("#change_code").click(function(){
		$(this).attr("src","/captcha.html?time="+Math.random());
	});
	$(document).keydown(function(event){
		if(event.keyCode==13){
			$("#form-submit").click();
		}
	});
	$("#form-submit").click(function(){
   		var flag=$('#validate-form').parsley('validate' );
   		if($("input[name = checked]:checkbox").is(":checked")){
   			$.cookie("login_account",$("#account").val(),{expires:7});
   			$.cookie("login_psw",$("#psw").val(),{expires:7});
   			$.cookie("login_checked",true,{expires:7});
   		};
   		if(flag){
   			layer.load(2);
   			$.post(
   				"/admin/main/login_post",
   				$("#validate-form").serialize(),
   				function(data){
   					layer.closeAll('loading')
		      		if(data.flag){
		      			toastr['success']("操作成功！", ''); 
		      			location.href="/admin/main/index";
		      			return true;
		      		}else{
		      		 	toastr['error'](data.msg,"错误提示");
		      		 	$("#change_code").trigger("click");
		      		 	return false;
		      		}
   				}
   			);
   		}
   	})	
		
	function init_parsley(){
			if($.cookie('login_checked')){
				$("#account").val($.cookie("login_account"));
				$("#psw").val($.cookie("login_psw"))
				$("input[name = checked]:checkbox").attr("checked", true);
			}
			$("#validate-form").parsley({
	        successClass: 'has-success',
	        errorClass: 'has-error',
	        errors: {
	            classHandler: function(el) {
	                return $(el).closest('.form-group');
	            },
	            container: function(node) {
	              return node.parent().parent();
	            },
	            errorsWrapper: '<ul class=\"help-block list-unstyled\"></ul>',
	            errorElem: '<li style=\"margin-left:15%\"></li>'
	        }
			});
	}
		function init_toastr(){
		toastr.options = {
		"closeButton": true,
		"debug": true,
		"progressBar": true,
		"positionClass": "toast-top-center",
		"showDuration": "300",
		"hideDuration": "1000",
		"timeOut": "2000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
		}
	}
})()
