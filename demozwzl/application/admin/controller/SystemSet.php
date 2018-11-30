<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;
/*系统设置*/
class SystemSet extends AdminBase{
	protected $beforeActionList = [
        'before_index'=>['only'=>'index'],
    ];
	function _initialize() {
		parent::_initialize();  
    }
	function index(){
		$sets=db('SystemSet')->select();
		$this->assign("models",$sets);	
		echo $this->fetch();
	}	
	function edit_post(){
		if(request()->isPost()){
			for ($i=0; $i < 20; $i++) { 
				db('SystemSet')->where("id=$i")->setField(array('pvalue'=>input("s_s_$i")));
			}
			$this->ajax_success("成功");	
		}
	}
	///////////////////////////////////////////////////////////////////////////////////
	/**
	 * 权限菜单
	 **/
	function before_index(){
		$auth_group=sp_get_auth_menu_3("10041004");
		$this->assign("menus",$auth_group);
	}
	
}
