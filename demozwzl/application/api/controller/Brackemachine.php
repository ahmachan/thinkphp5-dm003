<?php
namespace app\api\controller;
use app\common\controller\BaseApi;
/**
 * 设备管理
 */
class Brackemachine extends  BaseApi{
	public function _initialize(){
		parent::_initialize();
	}
	
	/**
	 * 新增设备
	 * http://tp5.com/public/api/Brackemachine/add_machine/v/1/t/1/key/zwzl_admin/sign/5de093255ad3e78341dbe00309c9436c/brackemachine_no/123/addr/22:11:11:44:77
	 * */
	public function add_machine(){
		$flag=$this->validate(input(''),'brackemachine');
		if($flag!==TRUE){//数据没有验证通过
			return $this->ajax_error('',"0003",$flag);
		}else{
			model('Brackemachine')->create(input(''),TRUE);
			return $this->ajax_success();
		}
	}
	
	/*删除设备*/
	public function delete_machine(){
		$addr=input('addr');
		$flag=$this->validate(input(''),'brackemachine.addr');
		if($flag!==TRUE){
			return $this->ajax_error('','0003',$flag);
		}else{
			db("Brackemachine")->where("addr='$addr'")->delete();
			return $this->ajax_success();				
		}
	}
	
}
