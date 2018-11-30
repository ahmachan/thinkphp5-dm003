<?php
namespace app\common\controller;
use think\Controller;

/*心率带公用api*/
class HeartApi extends  Controller
{
	  public function _initialize()
    {
//  	if(!request()->isPost()){
//  		exit(json_encode($this->ajax_error("","0000",config('err_code.0000'),TRUE)));	
//  	}
	  	if(empty(h_get_current_user())){
    		exit(json_encode($this->ajax_error("","0000","您还没有登录，请先登录！",TRUE)));	
    	}		
		  
	}
	/**
	 * 成功返回的模板
	 * */
    protected function ajax_success($data="",$code="1000",$msg="成功"){
    	return array('flag'=>TRUE,"data"=>$data,"code"=>$code,"msg"=>$msg);
    } 
	/**
	 * 失败返回的模板
	 * */
	protected function ajax_error($data="",$code="0000",$msg="未知错误"){
		return array('flag'=>FALSE,"data"=>$data,"code"=>$code,"msg"=>$msg);
	}
	
	/**
	 * @param $page_count 总页数
	 * @param $page_data 数据
	 * */
	 protected function page($page_count=0,$page_data=array()){
	 		$page_cur=input("page_cur/d",0);// 当前页
			$page_size=input('page_size/d',10);//每页个数
	 		return array('page_cur'=>$page_cur,'page_size'=>$page_size,'page_count'=>$page_count,"page_data"=>$page_data);
	 }
}
