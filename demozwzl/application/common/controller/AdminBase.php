<?php
namespace app\common\controller;
use think\Controller;

/*心率训练 公用class*/
class AdminBase extends  Controller
{
	  public function _initialize()
    {
    	if(empty(sp_get_current_admin())){
    		 if(request()->isPost()){
						$this->ajax_error("您还没有登录！请先登录");
    		 }else{
    		 		$this->redirect("main/login");
    		 }
    	}
    }
	  
	  /**
	   * ajax 成功
	   * @param $msg 成功提示
	   * @param $data 数据
	   * */
	  protected function ajax_success($msg="",$data=''){
	  		header('Content-Type:application/json; charset=utf-8');
        exit(json_encode(array("flag"=>TRUE,"msg"=>$msg,"data"=>$data),JSON_UNESCAPED_UNICODE));	 
	  }
	  /**
	   * ajax 失败
	   * @param $msg 失败提示
	   * @param $data 数据
	   * */
	  protected function ajax_error($msg="",$data=''){
	  	  header("Content-Type:application/json;charset=utf-8");
		  	exit(json_encode(array("flag"=>FALSE,"msg"=>$msg,"data"=>$data),JSON_UNESCAPED_UNICODE));
	  }
	  /*ajax*/
	  protected function ajax($data=''){
	  	  header("Content-Type:application/json;charset=utf-8");
		  	exit(json_encode($data,JSON_UNESCAPED_UNICODE));
	  }
		/*获取查询页数*/
		protected function get_limit(){
			return input("startIndex",0).",".input("pageSize",20);
		}
		/**
		 * 获取排序信息
		 * $table_name 表名称
		 * */
		protected function get_order($table_name=''){
			if(empty($table_name)||strpos(input("orderColumn"), ".")){
					return input("orderColumn","id")." ".input("orderDir","desc");	
			}else{
					return $table_name.".".input("orderColumn","id")." ".input("orderDir","desc");
			}
		}
}
