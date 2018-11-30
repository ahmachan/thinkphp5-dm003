<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;

/*用于提供公共hearders 和 bottoms 和left 部分*/
class Test extends AdminBase
{
	public function test(){
		var_dump(sp_file_upload("upload"));
	}
}
