<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;
/*会员管理*/
class User extends AdminBase{
	private $user;	
	protected $beforeActionList = [
        'before_index'=>['only'=>'index'],
    ];
	function _initialize() {
		$this->user=new \app\admin\model\User();
		parent::_initialize();  
    }
	function index(){
		echo $this->fetch();
	}	
	function add(){
		echo $this->fetch();
	}
	/*新增会员*/
	function add_post(){
		if(request()->isPost()){
			model('User')->create($_POST,TRUE);
			$this->ajax_success("成功！");	
		}else{
			$this->ajax_error("非法操作!");
		}
	}
	/*修改会员信息*/
	function edit(){
		if(!empty($_POST['ids'])){
			$id=$_POST['ids'][0];
			$this->assign("model",db("user")->where("id=$id")->find());
		}
		echo $this->fetch();			
	}
	function edit_post(){
		if(request()->isPost()){
			$data=db('User')->fetchSql(FALSE)->update($_POST);
			$this->ajax_success("成功！",$data);	
		}else{
			$this->ajax_error("非法操作!");
		}
	}
	function delete(){
		if(request()->isPost()){
			$id=input("id");
			db("User")->where("id=$id")->delete();
			$this->ajax_success("成功");
		}else{
			$this->ajax_error("非法操作！");
		}
	}
	
	
	/*会员列表数据*/
	function ajax_index(){
		$this->ajax($this->_ajax_index());
	}
	///////////////////////////////////////////////////////////////////////////////////
	/**
	 * 权限菜单
	 **/
	function before_index(){
		$auth_group=sp_get_auth_menu_3("10001000");
		$this->assign("menus",$auth_group);
	}
	
	/*获取会员数据*/
	private function _ajax_index(){
		$query=array('name'=>array("field" =>"name","operator" =>"like"),
					 'mobile'=>array("field" =>"mobile","operator" =>"like"),
					 'nick_name'=>array("field" =>"nick_name","operator" =>"like"),
					 );
		$draw=input("draw",0)+1;//请求时间		 
		$where_and=join(" and ",sp_get_param_sql(request()->isPost(),$query));
		$total=db("user")->where($where_and)->fetchsql(FALSE)->count(1);
		$list=db("user")->where($where_and)->order($this->get_order())->limit($this->get_limit())->fetchsql(FALSE)->select();
		return array('pageData'=>$list,'total'=>$total,"draw"=>$draw);
	}
}
