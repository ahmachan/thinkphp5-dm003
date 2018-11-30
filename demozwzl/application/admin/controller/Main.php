<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;
use think\Hook;

/*主体内容部分*/
class Main extends AdminBase
{
	public function _initialize(){
    	$filter=array("login","login_post","login_out");
    	if(!in_array(request()->action(),$filter)){
    		parent::_initialize();
    	}
    }
	  
	protected $beforeActionList = [
        'before_index'=>['only'=>'index'],
    ];
	public function index(){
		$result = Hook::exec('app\\admin\\behavior\\CheckAuth','actionInit');
		return $this->fetch();
	}
	/*登录页面*/
	public function login(){
		$this->assign("web_site",config('company.web_site'));
		return $this->fetch("./public/admin/login.html");
	}
	/*登录post*/
	public function login_post(){
		$this->_login_post();
	}
	/*退出*/
	public function login_out(){
		session(null);
		$this->redirect('login');
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	/**
	 * 权限菜单
	 **/
	function before_index(){
		$auth_group=sp_get_auth_menu();
		$this->assign("menus",$auth_group);
		$this->assign("web_site",config('company.web_site'));
		$this->assign("admin",sp_get_current_admin());
	}
	function _login_post(){
		if(empty(input('account'))||empty(input('psw'))||empty(input('code'))){
			$this->ajax_error('参数错误！');
		}else{
			$account=input('account/s');
			$psw=md5(input('psw/s'));
			$code=input('code/s');
			if(!captcha_check($code)){
 				$this->ajax_error("验证码错误！");
			}
				$admin=db("admin")->where("account='$account' and password='$psw'")->fetchsql(FALSE)->find();
				if(empty($admin)){
					$this->ajax_error("账号或者密码错误！");
				}else{
					session("Admin",$admin);
					$this->ajax_success("验证通过！");
				}
		}
	}
}
