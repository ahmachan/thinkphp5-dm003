<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;
/*权限管理*/
class Auth extends AdminBase{
	private $auth;	
	protected $beforeActionList = [
        'before_index'=>['only'=>'index'],
    ];
	function _initialize() {
		$this->auth=new \app\admin\model\Auth();
		parent::_initialize();  
    }
	function index(){
		echo $this->fetch();
	}	
	/*修改权限信息*/
	function edit(){
		$id=input("id");// m_admin id
		$this->assign("groups",db("AuthGroup")->select());
		$this->assign("model",db("Admin")->where("id=$id")->find());
		echo $this->fetch();			
	}
	function edit_post(){
		if(request()->isPost()){
			$id=input('id');
			$auth=db('auth')->where("admin_id=$id")->find();
			if(empty($auth)){
				db('Auth')->insert(array('admin_id'=>input('id'),'auth_group_id'=>input('auth_group_id')));
			}else{
				$auth['auth_group_id']=input('auth_group_id');
				db('Auth')->update($auth);
			}
			$this->ajax_success("成功！");	
		}else{
			$this->ajax_error("非法操作!");
		}
	}
	function delete(){
		if(request()->isPost()){
			$ids=input("ids");
			if(!empty($ids)){
				$sql=db("Auth")->where("admin_id =$ids ")->fetchsql(FALSE)->setField(array("auth_group_id"=>null));
			}
			$this->ajax_success("成功");
		}else{
			$this->ajax_error("非法操作！");
		}
	}
	
	/*权限列表数据*/
	function ajax_index(){
		$this->ajax($this->_ajax_index());
	}
	/**
	 * 权限菜单
	 **/
	function before_index(){
		$auth_group=sp_get_auth_menu_3("10011001");
		$this->assign("menus",$auth_group);
	}
	///////////////////////////////////////////////////////////////////////////////////
	/*获取权限数据*/
	private function _ajax_index(){
		$query=array('a_name'=>array("field" =>"a.name","operator" =>"like"),
					 'a_account'=>array("field" =>"a.account","operator" =>"like"),
					 'c_name'=>array("field" =>"c.name","operator" =>"like"),
					 );
		$draw=input("draw",0)+1;//请求时间
		$fields="a.id,a.name as a_name,a.account as a_account ,b.create_time,b.update_time,c.name as c_name";		 
		$where_and=join(" and ",sp_get_param_sql(request()->isPost(),$query));
		$where_and.=" and a.id !=1 ";
		$total=db("Admin")->alias("a")->join([["m_auth b","a.id=b.admin_id","left"],["m_auth_group c","b.auth_group_id=c.id","left"]])->where($where_and)->fetchsql(FALSE)->count(1);
		$list=db("Admin")->alias("a")->field($fields)->join([["m_auth b","a.id=b.admin_id","left"],["m_auth_group c","b.auth_group_id=c.id","left"]])->where($where_and)->order($this->get_order("a"))->limit($this->get_limit())->fetchsql(FALSE)->select();
		return array('pageData'=>$list,'total'=>$total,"draw"=>$draw);
	}
}
