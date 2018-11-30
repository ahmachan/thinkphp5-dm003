;(function() {
	   	$("#form-submit").click(function(){
    		loading();
            var $file=$("#file")[0].files[0];
            if($file==null||typeof $file=='undefined'){
            	 close_loding();
            	toastr['error']("请上传文件","错误提示");
            	return;
            }
            var $result = checkuplogo($("#file")[0].files[0], 1);
            if ($result !== -2) {
                var fd = new FormData();
                fd.append("files", $file);
                $.ajax({
                    url: "/Admin/Member/import_excel",
                    type: 'POST',
                    data: fd,
                    dataType:"json",
                    processData: false,
                    contentType: false
                }).done(function (data) {
                	 close_loding();
                    if (data.flag) {
                    	toastr['success']("上传成功");
                    	$("#myModal").modal("hide");
                    	$("#myform #btn-advanced-refresh").trigger("click");// 刷新页面
                    } else if (!data.flag) {
                    	if(typeof data.msg=="string"&&data.msg!=''){
                    		toastr['error'](data.msg,"错误提示");
                    	}else{
                    		$.each(data.data, function(key,value) {
                    			$str="<tr><td>param1</td><td>param2</td><td>param3</td><td>param4</td><td>param5</td><td>param6</td><td>param7</td><td>param8</td><td>param9</td><td>param10</td><td><span class='text-danger'>param11<span></td></tr>";
                    			$str=$str.replace("param1",value['姓名']).replace("param2",value['手机']).replace("param3",value['会员编号']).replace("param4",value['开始时间'])
                    					 .replace("param5",value['结束时间']).replace("param6",value['是否出场验证指纹']).replace("param7",value['是否出场验证指纹']).replace("param8",value['进场次数限制'])
                    					 .replace("param9",value['进场后剩余次数减一']).replace("param10",value['会员状态'])
                    			.replace("param11",value['error']);
                    			$("#error-data-table").append($str);
                    		});
                    	}
                    }
                     close_loding();
                    if (data.status == 0) {
                        var d = data.data;
                        if (typeof d !== 'undefined' && d) {}
                    }
                });
            }
        
	
	   	})
})();
