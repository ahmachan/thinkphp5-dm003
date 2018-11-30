<?php
namespace app\api\controller;
use app\common\controller\BaseApi;

/*进出记录api*/
class BehaviorLog extends  BaseApi
{
	/**
	 * 测试地址
	 * http://tp5.com/api/BehaviorLog/to_list/v/1/t/1/key/zwzl_admin/sign/5de093255ad3e78341dbe00309c9436c
	 * */
	 public function _initialize()
    {
        parent::_initialize();
    }
	/**
	 * 日志列表
	 * */
	public  function to_list(){
		$page_cur=input("page_cur/d",0);// 当前页
		$page_size=input('page_size/d',10);//每页个数
		$page_count=ceil(db("BehaviorLog")->count(1)/$page_size);
		$list=db("BehaviorLog")->field("*")->limit($page_cur*$page_size,$page_size)->fetchsql(FALSE)->select();
		return $this->ajax_success($this->page($page_count,$list));
	}
	
	/**
	 * 获取日志的最后一条记录 id
	 * */
	 public function get_list_last_id(){
	 	 $last_id=db("BehaviorLog")->max("id");
		 if(empty($last_id)){
		 	$last_id=0;
		 }
		 return $this->ajax_success(array("last_id"=>$last_id));
	 }
	
////////////////////////////////////////////////////////////////////////////////////
}
