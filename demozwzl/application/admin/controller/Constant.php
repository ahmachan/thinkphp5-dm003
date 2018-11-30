<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;
/*系统常量管理*/
class Constant extends AdminBase{
	function _initialize() {
		parent::_initialize();  
    }
	function index(){
		$this->assign("model",config('company'));
		echo $this->fetch();
	}	
	function edit_post(){
		if(request()->isPost()){
			$nav_tab=input("nav_tab");
			if($nav_tab=='nav-tab-1'){//网站设置
				 $this->nav_tab_1();			
			}else{
				$this->ajax_error("失败");
			}
		}
	}
	//////////////////////////////////////////
	function nav_tab_1(){
		$web_site=config("company")['web_site'];
		$web_site['description']=input('description');
		$web_site['author']=input('author');
		$web_site['keywords']=input('keywords');
		$web_site['title']=input('title');
		$param=array('official_web'=>input("official_web"),'web_site'=>$web_site);
		if(sp_dynamic_config("company",$param)){
			$this->ajax_success("成功");	
		}else{
			$this->ajax_error("失败");
		}
	}
}
