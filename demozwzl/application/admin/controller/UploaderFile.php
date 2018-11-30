<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;
/*员工管理*/
class UploaderFile extends AdminBase{
	function _initialize() {
		parent::_initialize();  
    }
	/*文件上传*/
	function upload(){
		sp_file_upload("upload");
	}
	/*文件删除*/	
	function delete(){
		if(isset($_POST['file'])){
			$dir=input('dir',"upload");
		    $file = "./data/$dir/".date("Ymd")."/" . $_POST['file'];
			$flag=FALSE;
		    if(file_exists($file)){
		      $flag= unlink($file);
		    }
			if($flag){
				$this->ajax_success($file);	
			}else{
				$this->ajax_error($file);
			}
		}
	}
}
