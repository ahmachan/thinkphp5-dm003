<?php
namespace app\common\controller;
use think\Controller;

/*智能闸机 公用Class*/
class BaseApi extends  Controller
{
	  public function _initialize()
    {
    	if(!request()->isPost()){
    		exit(json_encode($this->ajax_error("","0000",config('err_code.0000'),TRUE)));	
    	}
    	if(empty(input("v"))){//判断版本号
    		header('Content-Type:application/json; charset=utf-8');
    		exit(json_encode($this->ajax_error("","0001",config("err_code.0001")),TRUE));	
    	}
		if(empty(input("t"))){//判断时间
    		exit(json_encode($this->ajax_error("","0003",config("err_code.0003")),TRUE));	
    	}
		if(!in_array(input("key"),config("auth_key"))){//判断授权key
			exit(json_encode($this->ajax_error("","0002",config("err_code.0002")),TRUE));
		}
		if(input("sign")!=$this->_get_sign(input("key"),input("t"))){//判断签名
			exit(json_encode($this->ajax_error("","0002",config("err_code.0002")),TRUE));
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
	/**
	 * 获取签名
	 * @param $key
	 * @param $timestamp
	 * */
	private function _get_sign($key,$timestamp){
		return md5($key."_".$timestamp);
	}
	}