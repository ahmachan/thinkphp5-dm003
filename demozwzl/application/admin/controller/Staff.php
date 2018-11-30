<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;
/*员工管理*/
class Staff extends AdminBase{
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
	/*新增员工*/
	function add_post(){
		if(request()->isPost()){
			$card_no=trim(input('card_no'));
			$count=db('Interface')->where("card_no='$card_no'")->count(1);
			if($count>0){
				$this->ajax_error("卡号已经存在!");	
			}
			db('Interface')->insert(array('card_no'=>$card_no,'mobile'=>input('mobile'),'owner_type'=>2,'name'=>input('name'),'start_date'=>'2017-01-01','effect_date'=>'2050-01-01'));
			$this->ajax_success("成功！");	
		}else{
			$this->ajax_error("非法操作!");
		}
	}
	/*修改员工信息*/
	function edit(){
		$id=input('id');
		$model=db("Interface")->where(" id=$id ")->fetchsql(FALSE)->find();
		$this->assign("model",$model);
		echo $this->fetch();			
	}
	function edit_post(){
		if(request()->isPost()){
			$old_card_no=input('old_card_no');
			$card_no=input('card_no');
			$count=db('Interface')->where("card_no='$card_no' and card_no!='$old_card_no'")->count(1);
			if($count>0){
				$this->ajax_error("卡号已经存在!");	
			}
			$data=db('Interface')->update(array('id'=>input('id'),'card_no'=>input('card_no'),'mobile'=>input('mobile'),'name'=>input('name')));
			$this->ajax_success("成功！",$data);	
		}else{
			$this->ajax_error("非法操作!");
		}
	}
	//删除员工
	function delete(){
			$ids=input("ids");
			db("Interface")->where(" id in ($ids)")->fetchsql(FALSE)->delete();
			$this->ajax_success("成功");
	}
	
	/*员工卡借卡*/
	function  borrow(){
		$id=input('id');
		$model=db("Interface")->where("id=$id")->fetchsql(FALSE)->find();
		$this->assign("model",$model);
		echo $this->fetch();			
	}
	
		/*员工卡借卡*/
	function  borrow_post(){
		$id=input('id');
		$temp_no=input('temp_no');
		
		$count=db('Interface')->where("is_temp=1 and card_no='$temp_no'")->count(1);
		if($count<1){
			$this->ajax_error("临时卡不存在!");	
		}
		$interface=db("Interface")->where(" id=$id")->find();
		if(!empty($interface['temp_no'])){
			$this->ajax_error("--请先还卡--!");	
		}
		$model=db("Interface")->where("id=$id")->setField(array('temp_no'=>$temp_no));
		$this->ajax_success("成功")		;
	}
	
	/*员工卡还卡*/
	function  give_back(){
		$temp_no=input('temp_no');
		$id=input('id');
		if(empty($temp_no)){
			$model=db("Interface")->where("id=$id")->fetchsql(FALSE)->find();
			$this->assign("model",$model);
			echo $this->fetch();		
			die;	
		}else{
			$count=db('Interface')->where("id=$id and temp_no='$temp_no'")->count(1);
			if($count<1){
				$this->ajax_error("借卡和还卡不一致!");	
			}
			$interface=db("Interface")->where(" id=$id")->find();
			if(!empty($interface['temp_no'])){
				$this->ajax_error("--请先还卡--!");	
			}
			$model=db("Interface")->where("id=$id")->setField(array('temp_no'=>$temp_no));
			$this->ajax_success("成功")		;
		}
	}
	
	/*员工列表数据*/
	function ajax_index(){
		$this->ajax($this->_ajax_index());
	}
	///////////////////////////////////////////////////////////////////////////////////
	/**
	 * 权限菜单
	 **/
	function before_index(){
		$auth_group=sp_get_auth_menu_3("10041000");
		$this->assign("menus",$auth_group);
	}
	
	/*获取员工数据*/
	private function _ajax_index(){
		$query=array('a_name'=>array("field" =>"a.name","operator" =>"like"),
					 'a_mobile'=>array("field" =>"a.mobile","operator" =>"like"),
					 'a_card_no'=>array("field" =>"a.card_no","operator" =>"like"),
					 );
		$draw=input("draw",0)+1;//请求时间		 
		$where_and=join(" and ",sp_get_param_sql(request()->isPost(),$query));
		$where_and.=" and a.owner_type=2";
		$total=db("interface")->alias("a")->where($where_and)->fetchsql(FALSE)->count(1);
		$list=db("interface")->alias("a")->where($where_and)->order($this->get_order("a"))->limit($this->get_limit())->fetchsql(FALSE)->select();
		return array('pageData'=>$list,'total'=>$total,"draw"=>$draw);
	}
}
