<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;
/*运动记录*/
class Record extends AdminBase{
	protected $beforeActionList = [
        'before_index'=>['only'=>'index'],
    ];
	function _initialize() {
		parent::_initialize();  
    }
	function index(){
		echo $this->fetch();
	}	
	
	/*运动记录列表数据*/
	function ajax_index(){
		$this->ajax($this->_ajax_index());
	}
	///////////////////////////////////////////////////////////////////////////////////
	/**
	 * 权限菜单
	 **/
	function before_index(){
		$auth_group=sp_get_auth_menu_3("10001001");
		$this->assign("menus",$auth_group);
	}
	
	/*获取运动记录数据*/
	private function _ajax_index(){
		$query=array(
					 'b_nick_name'=>array("field" =>"b.nick_name","operator" =>"like"),
					 'b_mobile'=>array("field" =>"b.mobile","operator" =>"like"),
					 );
		$draw=input("draw",0)+1;//请求时间		 
		$where_and=join(" and ",sp_get_param_sql(request()->isPost(),$query));
		$total=db("record")->alias("a")->join([['m_user b','a.user_id=b.id']])->where($where_and)->fetchsql(FALSE)->count(1);
		$fields="a.id,a.cost_time,a.calorie,a.mac_addr,a.create_time,a.max_heart,a.avg_heart,b.nick_name,b.mobile";
		$list=db("record")->alias("a")->field($fields)->join([['m_user b','a.user_id=b.id']])->where($where_and)->order($this->get_order("a"))->limit($this->get_limit())->fetchsql(FALSE)->select();
		return array('pageData'=>$list,'total'=>$total,"draw"=>$draw);
	}
}
