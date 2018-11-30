<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;
/*权限管理*/
class AuthGroup extends AdminBase{
	private $auth_group;	
	protected $beforeActionList = [
        'before_index'=>['only'=>'index'],
    ];
	function _initialize() {
		$this->model=new \app\admin\model\AuthGroup();
		parent::_initialize();  
    }
	function index(){
		echo $this->fetch();
	}	
	function add(){
		$this->assign("menus",sp_get_all_menu());
		echo $this->fetch();
	}
	/*新增权限*/
	function add_post(){
		if(request()->isPost()){
			$data=model('AuthGroup')->create($_POST,TRUE);
			$this->ajax_success("成功！");	
		}else{
			$this->ajax_error("非法操作!");
		}
	}
	/*修改权限信息*/
	function edit(){
		if(!empty($_POST['ids'])){
			$id=$_POST['ids'][0];
			$this->assign("menus",sp_get_all_menu());
			$this->assign("model",db("AuthGroup")->where("id=$id")->find());
		}
		echo $this->fetch();			
	}
	function edit_post(){
		if(request()->isPost()){
			$data=db('AuthGroup')->fetchSql(FALSE)->update($_POST);
			$this->ajax_success("成功！",$data);	
		}else{
			$this->ajax_error("非法操作!");
		}
	}
	function delete(){
		if(request()->isPost()){
			$id=input("id");
			db("AuthGroup")->where("id=$id")->delete();
			$this->ajax_success("成功");
		}else{
			$this->ajax_error("非法操作！");
		}
	}
	
	
	/*权限列表数据*/
	function ajax_index(){
		$this->ajax($this->_ajax_index());
	}
	///////////////////////////////////////////////////////////////////////////////////
	/**
	 * 权限菜单
	 **/
	function before_index(){
		$auth_group=sp_get_auth_menu_3("10021000");
		$this->assign("menus",$auth_group);
	}
	
	/*获取权限数据*/
	private function _ajax_index(){
		$query=array('name'=>array("field" =>"name","operator" =>"like"),
					 'desc'=>array("field" =>"desc","operator" =>"like"),
					 );
		$draw=input("draw",0)+1;//请求时间		 
		$where_and=join(" and ",sp_get_param_sql(request()->isPost(),$query));
		$total=db("AuthGroup")->where($where_and)->fetchsql(FALSE)->count(1);
		$list=db("AuthGroup")->where($where_and)->order($this->get_order())->limit($this->get_limit())->fetchsql(FALSE)->select();
		return array('pageData'=>$list,'total'=>$total,"draw"=>$draw);
	}
}
