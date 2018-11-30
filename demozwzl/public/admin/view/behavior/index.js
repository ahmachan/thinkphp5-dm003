;(function(){
//功能按钮监听事件
$(".menu_buttons .menu_3").on("click",function(){
	  var node=$(this);
	  $("#myModalTitle").text(node.text());
	  if(node.text().indexOf("制卡")>-1){
	  	
	  }else{
	  		toastr['success']("操作成功");
	  }
});
	
///////////////////////数据表格////////////////////////////
//统一点击删除弹出对话框
	var userManage = {
		
    currentItem : null,
    fuzzySearch : false,
    getQueryCondition : function(data) {
		  var param = {};
	        //组装排序参数
	        if (data.order&&data.order.length&&data.order[0]) {
	            switch (data.order[0].column) {
	            }
	            param.orderDir = data.order[0].dir;
	        }
						param.card_no=$("#myform #card_no").val();
						param.name=$("#myform #name").val();
						param.mobile=$("#myform #mobile").val();
						param.id_number=$("#myform #id_number").val();
						param.behavior_type=$("#myform #behavior_type").val();
	        //组装分页参数
	        param.startIndex = data.start;
	        param.pageSize = data.length;
	        return param;
	},

	}
	
    var $table = $('#data-table');
    var _table = $table.dataTable($.extend(true,{},CONSTANT.DATA_TABLES.DEFAULT_OPTION, {
        ajax : function(data, callback, settings) {//ajax配置为function,手动调用异步查询
            //手动控制遮罩
            //封装请求参数
            var param = userManage.getQueryCondition(data);
             $("#excel_param").val($.param(param));
            loading();
            $.ajax({
                    type: "GET",
                    url: "/admin/behavior/ajax_index",
                    cache : false,  //禁用缓存
                    data: param,    //传入已封装的参数
                    dataType: "json",
                    success: function(result) {
                        //setTimeout仅为测试延迟效果
                        setTimeout(function(){
                            //异常判断与处理
                            if (result.errorCode) {
                                toastr['error']("查询失败errorCode:"+result.errorCode);
                                close_loding();
                                return;
                            }
 
                            //封装返回数据，这里仅演示了修改属性名
                            var returnData = {};
                            returnData.draw = data.draw;//这里直接自行返回了draw计数器,应该由后台返回
                            returnData.recordsTotal = result.total;
                            returnData.recordsFiltered = result.total;//后台不实现过滤功能，每次查询均视作全部结果
                            returnData.data = result.pageData;
                            //关闭遮罩
                            //调用DataTables提供的callback方法，代表数据已封装完成并传回DataTables进行渲染
                            //此时的数据需确保正确无误，异常判断应在执行此回调前自行处理完毕
                            callback(returnData);
                        },200);
                        close_loding();
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                       toastr['error']("查询失败");
                       close_loding();
                    }
                });
        },
        columns: [
            CONSTANT.DATA_TABLES.COLUMN.CHECKBOX,
            {	
                data: "name",
                render : CONSTANT.DATA_TABLES.RENDER.ELLIPSIS,//会显示省略号的列，需要用title属性实现划过时显示全部文本的效果
            },
             {
              	  data : "mobile",
              	   render :  CONSTANT.DATA_TABLES.RENDER.ELLIPSIS,//会显示省略号的列，需要用title属性实现划过时显示全部文本的效果
            },
             {
              	  data : "card_no",
              	   render :  CONSTANT.DATA_TABLES.RENDER.ELLIPSIS,//会显示省略号的列，需要用title属性实现划过时显示全部文本的效果
            },
             {
              	  data : "id_number",
              	   render :  CONSTANT.DATA_TABLES.RENDER.ELLIPSIS,//会显示省略号的列，需要用title属性实现划过时显示全部文本的效果
            },
            {
                data: "behavior_type",
                render : function(data){
                	 if(data==1){return "<span class='text text-warning'>进场</span>";}else{return "<span class='text text-danger'>出场</span>";}
                }
           	 },
	       	  {
	            data: "create_time",
	            render : CONSTANT.DATA_TABLES.RENDER.ELLIPSIS,//会显示省略号的列，需要用title属性实现划过时显示全部文本的效果
	       	 },

        ],
        "createdRow": function ( row, data, index ) {},
        "drawCallback": function( settings ) {
           // $(":checkbox[name='cb-check-all']",$wrapper).prop('checked', false);
            //默认选中第一行
           // $("tbody tr",$table).eq(0).click();
        }
    })).api();//此处需调用api()方法,否则返回的是JQuery对象而不是DataTables的API对象
     $("#myform  #btn-advanced-search").click(function(){
			_table.draw();
		});
		
		//行点击事件
		$("tbody",$table).on("click","tr",function(event) {
			$(this).addClass("active").siblings().removeClass("active");
			//获取该行对应的数据
			var item = _table.row($(this).closest('tr')).data();
	    });
})()
