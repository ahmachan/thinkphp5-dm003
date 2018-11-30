<?php
namespace app\admin\model;
use think\Model;
class user extends  Model
{
	protected function initialize(){
		parent::initialize();
	}
	/**
	 * 根据open_id判断是否注册
	 * @param $open_id  微信open_id
	 * */
	 public function is_register($open_id){
		if(empty($open_id)){
			return FALSE;
		}else{
			if($this->where("open_id='$open_id'")->count()==1){
				 return TRUE;
			}else{
				return FALSE;
			}
		}
	}
	 
	 
	
} 