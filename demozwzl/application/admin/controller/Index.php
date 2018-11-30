<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;
use think\View;
use think\Hook;
class Index extends AdminBase
{
    public function index()
    {
    	$this->assign("xx","123");
		$this->assign("list",array(array('id'=>1,'name'=>'张三'),array('id'=>2,'name'=>'xx')));
		var_dump(db("user")->column("id",'mobile','sex'))	;die;
		var_dump(config('template.view_path'));
        return $this->fetch('',['xx'=>'xxx']);
    }
}
