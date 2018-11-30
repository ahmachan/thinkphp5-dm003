<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;
/*员工管理*/
class Admin extends AdminBase{
	private $admin;	
	protected $beforeActionList = [
        'before_index'=>['only'=>'index'],
    ];
	function _initialize() {
		$this->admin=new \app\admin\model\Admin();
		parent::_initialize();  
    }
	function index(){
		echo $this->fetch();
	}	
	function add(){
		echo $this->fetch();
	}
	/*新增员工*/
	function add_post(){
		if(request()->isPost()){
			$this->varify_uniq();
			$_POST['password']=md5($_POST['password']);
			$admin_id=model("Admin")->create($_POST,TRUE)['id'];
			model("auth")->auto_add($admin_id);
			$this->ajax_success("成功!");	
		}else{
			$this->ajax_error("非法操作!");
		}
	}
	/*修改员工信息*/
	function edit(){
		if(!empty($_POST['ids'])){
			$id=$_POST['ids'][0];
			$this->assign("model",db("admin")->where("id=$id")->find());
		}
		echo $this->fetch();			
	}
	function edit_post(){
		if(request()->isPost()){
			$data=db('Admin')->fetchSql(FALSE)->update($_POST);
			$this->ajax_success("成功！",$data);	
		}else{
			$this->ajax_error("非法操作!");
		}
	}
	/*个人信息*/
	function info(){
		$admin_id=sp_get_current_admin()['id'];
		if(!empty(input('id'))){//修改个人信息
			$pics=sp_get_file_name($_POST['pics']);
			unset($_POST['pics']);
			$_POST['icon']=$pics[0];
			$data=db('Admin')->fetchSql(FALSE)->update($_POST);
			$this->ajax_success(0);
		}
		$this->assign("model",db('admin')->where("id=$admin_id")->find());
		echo $this->fetch();
	}
	/*密码修改信息*/
	function edit_psw(){
		if(request()->isPost()){
			$admin_id=sp_get_current_admin()['id'];
			$old_password=db("admin")->where("id=$admin_id")->value("password");
			if($old_password!=md5(input("old_password"))){
				$this->ajax_error("原密码错误");
			}
			db("admin")->where("id=$admin_id")->setField(array("password"=>md5(input("password"))));
			$this->ajax_success(0);
		}else{
			echo $this->fetch();	
		}
		
	}
	
	function delete(){
		if(request()->isPost()){
			$id=input("id");
			db("admin")->where("id=$id")->delete();
			$this->ajax_success("成功");
		}else{
			$this->ajax_error("非法操作！");
		}
	}
	
	/*员工列表数据*/
	function ajax_index(){
		$this->ajax($this->_ajax_index());
	}
	/**验证账号唯一**/
	function varify_uniq(){
		$account=input("account",'');
		$count=db("admin")->where("account='$account'")->count(1);
		if($count>0){
			$this->ajax_error("账号已经存在");
		}
	}
	
	///////////////////////////////////////////////////////////////////////////////////
	/**
	 * 权限菜单
	 **/
	function before_index(){
		$auth_group=sp_get_auth_menu_3("10011000");
		$this->assign("menus",$auth_group);
	}
	
	/*获取员工数据*/
	private function _ajax_index(){
		$query=array('name'=>array("field" =>"name","operator" =>"like"),
					 'mobile'=>array("field" =>"mobile","operator" =>"like"),
					 'account'=>array("field" =>"account","operator" =>"like"),
					 );
		$draw=input("draw",0)+1;//请求时间		 
		$where_and=join(" and ",sp_get_param_sql(request()->isPost(),$query));
		$where_and.=" and id!=1 ";
		$total=db("admin")->where($where_and)->fetchsql(FALSE)->count(1);
		$list=db("admin")->where($where_and)->order($this->get_order())->limit($this->get_limit())->fetchsql(FALSE)->select();
		return array('pageData'=>$list,'total'=>$total,"draw"=>$draw);
	}
}
