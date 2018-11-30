<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;
/*会员卡管理*/
class Member extends AdminBase{
	protected $beforeActionList = [
        'before_index'=>['only'=>'index'],
    ];
	function _initialize() {
		parent::_initialize();  
    }
	/*会员卡列表*/
	function index(){
		echo $this->fetch();
	}	
	//删除卡
	function delete(){
			$id=input("id");//card_id
			db("Card")->where("card_no = (select card_no from m_interface where id=$id)")->fetchsql(FALSE)->delete();
			db("Interface")->where("id =$id")->delete();
			$this->ajax_success("成功");
	}
	
	/*卡列表数据*/
	function ajax_index(){
		$this->ajax($this->_ajax_index());
	}
	
	/*绑卡*/
	function bind_card(){
		$id=input("id");
		$card_no=input('card_no');
		$old_card_no=input('old_card_no');
		$free_finger=input('free_finger');
		if(!empty($card_no)){
			$count=db("interface")->where("card_no='$card_no' and card_no!='$old_card_no'")->count(1);
			if($count>0){
				$this->ajax_error("卡号已经存在！");
			}
			db('interface')->where("id=$id")->setField(array("card_no"=>input("card_no"),'free_finger'=>input('free_finger'),"fingerprint"=>input('fingerprint')));
			$this->ajax_success("成功");
		}
		$this->assign("model",db('interface')->find($id));
		echo $this->fetch();		
	}

	function varify(){
		if(!empty(input('card_no'))){
			$card_no=input('card_no');
			$interface=db('interface')->where("card_no='$card_no'")->find();
			if(empty($interface)){
				$this->ajax_error("未找到卡");
			}else{
				if($interface['free_finger']!=1){
					$this->ajax_error("此卡不是免指纹卡");
				}else{
					db('interface')->where("card_no='$card_no'")->setField(array('free_finger_varify'=>1));
				}
			}
			$this->ajax_success("成功");
		}else{
			echo $this->fetch();
		}
	}
	function find_bycard(){
		$card_no=input('card_no','xxxx@@xxx');
		$interface=db('interface')->where("card_no='$card_no'")->find();
		if(empty($interface)){
			$this->ajax_error("卡号不存在");
		}else{
			$this->ajax_success("成功",$interface);
		}
	}
	/*excel 导入*/
	function import_excel(){
		if(request()->isGet()){
			echo $this->fetch();
		}else{
			$title_=array("姓名","手机","会员编号","开始时间","结束时间","是否入场验证指纹","是否出场验证指纹","进场次数限制","进场后剩余次数减一","会员状态");
			$result=import_excel($title_,2,0);
			if($result['flag']){
				$err_data=$this->verify_excel_data($result['data']);
				if(empty($err_data)){
					$this->ajax_success("成功");
				}else{
					$this->ajax_error("",$err_data);
				}
			}else{
				$this->ajax_error($result['msg']);
			}
		}
	}
	
	///////////////////////////////////////////////////////////////////////////////////
	/**
	 * 权限菜单
	 **/
	function before_index(){
		$auth_group=sp_get_auth_menu_3("10041003");
		$this->assign("menus",$auth_group);
	}
	
	/*获取卡数据*/
	private function _ajax_index(){
		$query=array(
					 'card_no'=>array("field" =>"card_no","operator" =>"="),
					 'mobile'=>array("field" =>"mobile","operator" =>"="),
					 'id_number'=>array("field" =>"id_number","operator" =>"="),
					 'name'=>array("field" =>"name","operator" =>"="),
					 );
		$draw=input("draw",0)+1;//请求时间		 
		$where_and=join(" and ",sp_get_param_sql(request()->isPost(),$query));
		$where_and.=" and is_temp=0 and owner_type=1 ";//排除临时卡 和会员卡
		$total=db("interface")->where($where_and)->fetchsql(FALSE)->count(1);
		$list=db("interface")->where($where_and)->order($this->get_order())->limit($this->get_limit())->fetchsql(FALSE)->select();
			
		return array('pageData'=>$list,'total'=>$total,"draw"=>$draw);
	}
	
	function verify_excel_data($data){
		////姓名	手机	会员编号	开始时间	结束时间	是否入场验证指纹	是否出场验证指纹	进场次数限制	进场后剩余次数减一	会员状态
		$error_data='';// 错误的数据
		foreach ($data as $key => $value) {
			if(empty($value['会员编号'])){
				$value["error"]="会员编号";
				$error_data[]=$value;
				continue;
			}
			$count=db('Interface')->where(" id_number='".$value['会员编号']."'")->count(1);
			if($count>0){
				$value["error"]="会员编号已经存在";
				$error_data[]=$value;
				continue;
			}
			if(empty($value['手机'])){
				$value["error"]="手机号码不能为空";
				$error_data[]=$value;
				continue;
			}
			if(empty($value['姓名'])){
				$value["error"]="姓名不能为空";
				$error_data[]=$value;
				continue;
			}
			if(!empty($value['开始时间'])&&!preg_match("/^\d{4}(\-)\d{2}(\-)\d{2}$/",$value['开始时间'])){
				$value["error"]="开始时间格式错误";
				$error_data[]=$value;
				continue;
			}
			if(!empty($value['结束时间'])&&!preg_match("/^\d{4}(\-)\d{2}(\-)\d{2}$/",$value['开始时间'])){
				$value["error"]="结束时间格式错误";
				$error_data[]=$value;
				continue;
			}
			if(!in_array($value['是否入场验证指纹'], array(0,1))){
				$value["error"]="数据只能填写（0,或者 1）";
				$error_data[]=$value;
				continue;
			}
			if(!in_array($value['是否出场验证指纹'], array(0,1))){
				$value["error"]="数据只能填写（0,或者 1）";
				$error_data[]=$value;
				continue;
			}
			if(!in_array($value['进场次数限制'], array(0,1))){
				$value["error"]="数据只能填写（0,或者 1）";
				$error_data[]=$value;
				continue;
			}
			if(!in_array($value['进场后剩余次数减一'], array(0,1))){
				$value["error"]="数据只能填写（0,或者 1）";
				$error_data[]=$value;
				continue;
			}
			if(!in_array($value['会员状态'], array(0,1))){
				$value["error"]="数据只能填写（0,或者 1）";
				$error_data[]=$value;
				continue;
			}
			////姓名	手机	会员编号	开始时间	结束时间	是否入场验证指纹	是否出场验证指纹	进场次数限制	进场后剩余次数减一	会员状态
			db("Interface")->insert(array(
										'name'=>$value['姓名'],'mobile'=>$value['手机'],
										'id_number'=>$value['会员编号'],'start_date'=>$value['开始时间'],
										'effect_date'=>$value['结束时间'],'in_varifycation'=>$value['是否入场验证指纹'],
										'out_varifycation'=>$value['是否出场验证指纹'],'in_surplus_limt'=>$value['进场次数限制'],
										'in_surplus_sub'=>$value['进场后剩余次数减一'],'status'=>$value['会员状态']));
		}
		return $error_data;
	}
}
