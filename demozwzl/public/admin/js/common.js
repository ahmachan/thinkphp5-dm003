;(function(){
	//需要立即执行的初始化操作
	var windows_init={
		init_toastr:function(){init_toastr();},
		init_url:function(url){init_url(url);},
	}
	//二级菜单点击事件
	$(".menu_2").on("click",function(){menu_2_click($(this))});
	// 列表页刷新按钮
	$("body").on("click","#myform #btn-advanced-refresh",function(){
		$("#myform")[0].reset();
		$("#myform #btn-advanced-search").trigger("click");// 刷新页面
	});
	//全选按钮
	$("body").on("click","#div-table-container .selecte_all",function(){
		  var flag=$(this).is(":checked");
		  if(flag){
		  	 $("#div-table-container input[name='ids']").prop("checked",true);
		  	 $("#div-table-container .selecte_all").prop("checked",true);
		  }else{
		  	 $("#div-table-container input[name='ids']").prop("checked",false);
		  	 $("#div-table-container .selecte_all").prop("checked",false);
		  }
	});
	//选择tr 选择 checkbox
	$("body").on("click","#div-table-container #data-table tr",function(e){
		 if(e.target.tagName!='INPUT'){
		 	 var flag=$(this).find("input[name='ids']:first").is(":checked");
			 if(flag){
			 	$(this).find("input[name='ids']:first").prop("checked",false);
			 }else{
			 	$(this).find("input[name='ids']:first").prop("checked",true);
			 }
		 }
		 e.stopPropagation();
	});
	init();
	
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//初始化事件
	function init(){
		windows_init.init_url($.hash("code"));
		windows_init.init_toastr();
		window.layers=layer;
		window.loading=function(){window.layers.load(2)};//调用方式 直接 loading();
 		window.close_loding=function(){window.layers.closeAll('loading')};//调用方式 直接 close_loading();
 		window.delete_dialog=function(url,msg){delete_dialog(url,msg)},//调用方式 delete_dialog(url);
 		window.init_parsley=function(){init_parsley()}//调用方式 init_parsley();
 		window.checkuplogo=function(up_file, size){checkuplogo(up_file, size)}//调用方式 checkuplogo();
 		window.read_card=function(){read_card();}
 		window.read_card_btn=function(node){read_card_btn(node);}
 		window.SignFinger1CallBack=function(data){SignFinger1CallBack(data);}
 		window.SignFinger2CallBack=function(data){SignFinger2CallBack(data);}
 		window.FingerDataCallBack=function(data){FingerDataCallBack(data);}
 		window.clear_store=function(){clear_store();}
 		window.read_card_reset=function(){read_card_reset();}
	}
	//初始化url
	function init_url(url){
		if(url!=null&&typeof(url)!='undefined'){//第二次进入后台
			var auth_code='';
			try{
				$("#sidebar").children().removeClass("active");
				auth_code=code_spilt($.base64.decode(url));
				setTimeout(function(){$("#"+auth_code[0]).trigger("click");},500);
				setTimeout(function(){$("#"+auth_code[1]).trigger("click");},800);				
			}catch(e){
				alert("禁止非法访问！！！！");					
			}
		}else{// 第一次进入后台
			$("#sidebar").children().removeClass("active").eq("1").addClass("active")
			console.log("the first time in");
		}
	}
	
	/**二级菜单点击事件*/
	function menu_2_click(node){
			loading();
			var $url=node.attr("data-url");
			var $code=$.base64.encode(node.attr("data-code"));
			$("#sidebar").children().removeClass("active");
			node.parent().parent().parent().addClass("active");
			$.hash("code",$code);
			$("#my_container").load($url,function(response){
				if(response.indexOf('login.html_kewwords')>0){
					toastr['error']("登录超时！请重新登陆");
					location.href="/admin/main/login";
				}
				close_loding();
			});
	}
	/**初始化表单验证器*/
	function init_parsley(){
			$("#validate-form").parsley({
	        successClass: 'has-success',
	        errorClass: 'has-error',
	        errors: {
	            classHandler: function(el) {
	                return $(el).closest('.form-group');
	            },
	            errorsWrapper: '<ul class=\"help-block list-unstyled\"></ul>',
	            errorElem: '<li></li>'
	        }
			});
	}
	/**点击删除按钮 弹出框*/
	function delete_dialog(url,msg){
		var $msg=msg;
		var $title='';
		if($msg==null||$msg=='undifined'||$msg==''){
			$msg='您确定要删除吗';
			$title='删除';
		}else{
			$msg='您确定要操作吗';
			$title='操作';
		}
		 bootbox.dialog({
            message:$msg,
            title: $title,
            className:"delete_dialog",
            buttons: {
                    success: {
                      label: "取消",
                      className: "btn-default",
                      callback: function() {
                      	
                    }
                  },
                  danger: {
                    label: "确定",
                    className: "btn-danger",
                    callback: function() {
                    	$.post(
                    		url,
                    		function(data){
                    			if(data.flag){
                    				toastr['success']("操作成功");
                    				$("#myform #btn-advanced-refresh").trigger("click");// 刷新页面
                    			}else{
                    				toastr['error'](data.msg,"操作失败");
                    			}
                    		}
                    	)
                    }
                }
            }
        });
	}
	
	//toastr 初始化配置
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
	
	//jQuery.filer  文件上传配置
     window.filer_default_opts = {
        changeInput2: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"><i class="icon-jfi-cloud-up-o"></i></div><div class="jFiler-input-text"><h3>Drag&Drop files here</h3> <span style="display:inline-block; margin: 15px 0">or</span></div><a class="jFiler-input-choose-btn blue-light">点击 或 拖拽文件</a></div></div>',
        limit: null,
        templates: {
            box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
            item: '<li class="jFiler-item" style="width: 49%">\
                        <div class="jFiler-item-container">\
                            <div class="jFiler-item-inner">\
                                <div class="jFiler-item-thumb">\
                                    <div class="jFiler-item-status"></div>\
                                    <div class="jFiler-item-info">\
                                        <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                                        <span class="jFiler-item-others">{{fi-size2}}</span>\
                                    </div>\
                                    {{fi-image}}\
                                </div>\
                                <div class="jFiler-item-assets jFiler-row">\
                                    <ul class="list-inline pull-left">\
                                        <li>{{fi-progressBar}}</li>\
                                    </ul>\
                                    <ul class="list-inline pull-right">\
                                        <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                    </ul>\
                                </div>\
                            </div>\
                        </div>\
                    </li>',
            itemAppend: '<li class="jFiler-item" style="width: 49%">\
                            <div class="jFiler-item-container">\
                                <div class="jFiler-item-inner">\
                                    <div class="jFiler-item-thumb">\
                                        <div class="jFiler-item-status"></div>\
                                        <div class="jFiler-item-info">\
                                            <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                                            <span class="jFiler-item-others">{{fi-size2}}</span>\
                                        </div>\
                                        {{fi-image}}\
                                    </div>\
                                    <div class="jFiler-item-assets jFiler-row">\
                                        <ul class="list-inline pull-left">\
                                            <li><span class="jFiler-item-others">{{fi-icon}}</span></li>\
                                        </ul>\
                                        <ul class="list-inline pull-right">\
                                            <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                        </ul>\
                                    </div>\
                                </div>\
                            </div>\
                        </li>',
            progressBar: '<div class="bar"></div>',
            itemAppendToEnd: false,
            removeConfirmation: true,
            _selectors: {
                list: '.jFiler-items-list',
                item: '.jFiler-item',
                progressBar: '.bar',
                remove: '.jFiler-item-trash-action'
            }
        },
        dragDrop: {},
        uploadFile: {
            url: "/admin/UploaderFile/upload",
            data: {},
            type: 'POST',
            enctype: 'multipart/form-data',
            beforeSend: function(){},
            success: function(data, el){
                var parent = el.find(".jFiler-jProgressBar").parent();
                el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                    $("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Success</div>").hide().appendTo(parent).fadeIn("slow");
                });
                $("#pics").val($("#pics").val()+","+data);
                console.log($("#pics").val());
            },
            error: function(el){
                var parent = el.find(".jFiler-jProgressBar").parent();
                el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                    $("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Error</div>").hide().appendTo(parent).fadeIn("slow");    
                });
            },
            statusCode: null,
            onProgress: null,
            onComplete: null
        },
        onRemove: function(itemEl, file, id, listEl, boxEl, newInputEl, inputEl){
        	console.log(file);
            var file = file.name;
            $.post('/admin/UploaderFile/delete', {file: file,"dir":"upload"});
        },
        captions: {
			button: "选择文件",
			feedback: "选择文件上传",
			feedback2: "文件已经被选中",
			drop: "拖拽文件到此处上传",
			removeConfirmation: "您确定删除此文件吗?",
			errors: {
			filesLimit: "最多能上传 {{fi-limit}} 个文件.",
			filesType: "只能上传指定类型文件.",
			filesSize: "{{fi-name}} -文件大小超出限制!最大允许上传 {{fi-maxSize}} MB.",
			filesSizeAll: "上传的文件的总大小不得超过 {{fi-maxSize}} MB."
			}
		}
    };
    /**
     * 读卡程序
     */
	function read_card(){
		var csharp=window.external;
		var card_no=csharp.ReadCard();
		if(card_no<0){//读卡错误
			toastr['error'](csharp.GetError(card_no)+","+card_no);
			return false;
		}else{
			return card_no;
		}
	}
	/**
	 * 读卡按钮
	 */
	function read_card_btn(node){
		var csharp=window.external;
		var card_no=csharp.ReadCard();
		if(card_no<0){//读卡错误
			toastr['error'](csharp.GetError(card_no)+","+card_no);
		}else{
			$(node).val(card_no);
		}
	}
	
		function SignFinger1CallBack(data){
			//$("#finger_image1").attr("src","/data/finger/finger1.bmp?t"+new Date().getTime());
			$("#finger_image1").attr("src",data);
			alert("第一次录制成功,请抬起手指在按一次");
		}
		function SignFinger2CallBack(data){
			$("#finger_image2").attr("src",data);
			alert("第二次录制成功");
		}
		function FingerDataCallBack(data){
			$("#fingerprint").val(data);
		}
	
	/**
	 * 根据3级菜单 拆分成  xxxx,xxxxxxxx,xxxxxxxxxxxx模式
	 * @param code  第三级代码菜单
	 * */
	function code_spilt(code){
		if(code.length==8){//代码正确
			var menu_1=code.substring(0,4);//第一级菜单
			var menu_2=code.substring(0,8);//第二级菜单
			return [menu_1,menu_2];
		}else{
			alert("禁止非法访问！！！");
			return false;
		}
	}
	
	       function checkuplogo($up_file, $size) {
            var $filesize = $up_file.size;
            var $filetype = $up_file.type; //image/jpeg image/png audio/mp3 video/mp4 
            var $img_type = new Array("jpeg", "jpg", "png");
            var $filetype_arr = $filetype.split("/");
            if ($filetype_arr[0] === "image") {
                if ($.inArray($filetype_arr[1], $img_type) !== -1) {
                    if ($size === 1) {
                        if ($filesize > 300 * 1024) {
                           toastr['error']("错误提示！",  "你上传的图片为" + ($filesize / 1024).toFixed(2) + "kb,大小超过300kb");
                           layer.closeAll();
                            return -2;
                        }
                    }
                } else {
                	toastr['error']("错误提示！", "你上传的图片格式为" + $filetype_arr[1] + "，暂时只支持jpg或png");
                	layer.closeAll();
                    return -2;
                }
            }
            
        }
	/**清理柜子*/
	function clear_store(){		
	  	  alert("请将卡放到读卡器上");
	  	  var csharp = window.external;
			  var $flag=csharp.ClearCard();
			  if($flag=='0'){
						toastr['success']("成功","成功！");
					}else{
						toastr['error']("失败","失败！");
					}
	  
	}
	/**读卡器重连*/
	function read_card_reset(){
  	   var csharp = window.external;
		csharp.Reconncet();
	}
	/**个人信息修改**/
	$("#modify_info").click(function(){
		$("#10031000").trigger("click");
	});
	/**个人密码修改**/
	$("#modify_psw").click(function(){
		$("#10031001").trigger("click");
	});
})()
	

