<?php
namespace app\admin\model;

use think\Model;

class Auth extends Model
{
	protected function initialize(){
		parent::initialize();
	}
	/*新增一个员工后 新建一个权限*/
	public function auto_add($admin_id){
		$this->save(array("admin_id"=>$admin_id,"auth_group_id"=>0));
	}
}