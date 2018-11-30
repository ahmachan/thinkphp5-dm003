<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;
/*进出日志管理*/
class Behavior extends AdminBase{
	protected $beforeActionList = [
        'before_index'=>['only'=>'index'],
    ];
	function _initialize() {
		parent::_initialize();  
    }
	function index(){
		echo $this->fetch();
	}	
	
	/*卡列表数据*/
	function ajax_index(){
		$this->ajax($this->_ajax_index());
	}
	///////////////////////////////////////////////////////////////////////////////////
	/**
	 * 权限菜单
	 **/
	function before_index(){
		$auth_group=sp_get_auth_menu_3("10041002");
		$this->assign("menus",$auth_group);
	}
	
	/*获取卡数据*/
	private function _ajax_index(){
		$query=array(
				'card_no'=>array("field" =>"card_no","operator" =>"like"),
				'mobile'=>array("field" =>"mobile","operator" =>"like"),
				'name'=>array("field" =>"name","operator" =>"like"),
				'id_number'=>array("field" =>"id_number","operator" =>"like"),
				'behavior_type'=>array("field" =>"behavior_type","operator" =>"in"),
				);
		$draw=input("draw",0)+1;//请求时间		 
		$where_and=join(" and ",sp_get_param_sql(request()->isPost(),$query));
		$where_and.=" and  behavior_status=1 ";
		$total=db("BehaviorLog")->where($where_and)->fetchsql(FALSE)->count(1);
		$list=db("BehaviorLog")->where($where_and)->order($this->get_order())->limit($this->get_limit())->fetchsql(FALSE)->select();
		return array('pageData'=>$list,'total'=>$total,"draw"=>$draw);
	}
}
