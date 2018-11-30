<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;
/*临时卡管理*/
class Temp extends AdminBase{
	private $user;	
	protected $beforeActionList = [
        'before_index'=>['only'=>'index'],
    ];
	function _initialize() {
		$this->user=new \app\admin\model\User();
		parent::_initialize();  
    }
	/*临时卡列表*/
	function index(){
		echo $this->fetch();
	}	
	function add(){
		if(input('type')==2){//查询卡
			$id=input("id","0");
		    $card_no= db("interface")->where("id=$id")->value("card_no");
		    $this->assign("card_no",$card_no);
			$this->assign("selected","selected");
			$this->assign("type",2);
		}else{
			$this->assign("type",1);
			$this->assign("selected","false");
		}
		echo $this->fetch();
	}
	/*新增卡*/
	function add_post(){
		if(request()->isPost()){
			$type=input('type');//1 临时卡   2查询卡
			$card_no=input('card_no');
			$count=db('interface')->where("card_no='$card_no'")->count(1);
			if($count>0){
				$this->ajax_error("卡号已经存在!");	
			}
			if($type=='1'){//临时卡
				$temp_num='temp_'.time();
				db('interface')->insert(array('owner_type'=>1,'card_no'=>$card_no,'start_date'=>'2010-1-01-01','effect_date'=>'2050-01-01','surplus'=>1,'in_surplus_limt'=>1,'in_surplus_sub'=>1,'is_temp'=>1,'name'=>$temp_num,'id_number'=>$temp_num));
				$this->ajax_success("成功！");	
			}
		}else{
			$this->ajax_error("非法操作!");
		}
	}
	//删除卡
	function delete(){
			$id=input("id");
			db("Interface")->where("id =$id")->delete();
			$this->ajax_success("成功");
	}
	
	/*卡列表数据*/
	function ajax_index(){
		$this->ajax($this->_ajax_index());
	}
	
	/*卡状态查询*/
	function query(){
		$card_no=input('card_no');
		if(empty($card_no)){
			echo $this->fetch();
		}else{
			$result=array('owner_type'=>'会员卡','enable_in'=>'能入场','err_msg'=>'');
			$interface=db("interface")->where("card_no='$card_no'")->find();
			if(empty($interface)){
				$result['owner_type']='未知';$result['enable_in']='不能入场';$result['err_msg']='卡号未找到';
				$this->ajax_success("成功",$result);
			}else{
				if($interface['owner_type']==1){//会员卡
					$behavior_type=db("BehaviorLog")->where(" card_no='$card_no' and behavior_status=1")->order("create_time desc")->value("behavior_type");
					if($interface['is_temp']==1){
				    		 $is_temp="临时卡";
				    	}else{
				    		$is_temp="会员卡";
				    }
				    if($behavior_type==1){
						$result['owner_type']=$is_temp;$result['enable_in']='不能入场';$result['err_msg']='未按正常顺序出闸机';				    	
				    	$this->ajax_success("成功",$result);	
				    }
					
					if($interface['status']!=1){
						$result['owner_type']=$is_temp;$result['enable_in']='不能入场';$result['err_msg']='会员状态无效';				    	
				    	$this->ajax_success("成功",$result);	
					}
					$now=date('Y-m-d H:i:s');
					if($now<$interface['start_date']){
						$result['owner_type']=$is_temp;$result['enable_in']='不能入场';$result['err_msg']='卡的有效起始时间为：'.$interface['start_date'];				    	
				    	$this->ajax_success("成功",$result);	
					}
					
					if($now>$interface['effect_date']){
						$result['owner_type']=$is_temp;$result['enable_in']='不能入场';$result['err_msg']='卡的截至时间为：'.$interface['effect_date'];				    	
				    	$this->ajax_success("成功",$result);	
					}
					
					if($interface['in_varifycation']==1){//需要指纹验证
						if($interface['free_finger']==1){//设置了免指纹
							if($interface['free_finger_varify']!=1){//还未前台验证
								$result['owner_type']=$is_temp;$result['enable_in']='不能入场';$result['err_msg']='免指纹卡，还未前台验证';				    	
				    			$this->ajax_success("成功",$result);			
							}
						}
					}
					
					if($interface['in_surplus_limt']==1){//次卡
						if($interface['surplus']<1){//次数不足
							$result['owner_type']=$is_temp;$result['enable_in']='不能入场';$result['err_msg']='可用次数 为0 次';				    	
				    		$this->ajax_success("成功",$result);		
						}
					}
					$this->ajax_success("成功",$result);	
				}else{//员工卡
					$result['owner_type']='员工卡';$result['enable_in']='能入场';$result['err_msg']='';
					$this->ajax_success("成功",$result);	
				}
			}
		}
	}
	///////////////////////////////////////////////////////////////////////////////////
	/**
	 * 权限菜单
	 **/
	function before_index(){
		$auth_group=sp_get_auth_menu_3("10041001");
		$this->assign("menus",$auth_group);
	}
	
	/*获取卡数据*/
	private function _ajax_index(){
		$query=array(
					 'card_no'=>array("field" =>"card_no","operator" =>"like"),
					 'surplus'=>array("field" =>"surplus","operator" =>"in"),
					 );
		$draw=input("draw",0)+1;//请求时间		 
		$where_and=join(" and ",sp_get_param_sql(request()->isPost(),$query));
		$where_and.=" and is_temp=1 ";
		$total=db("interface")->where($where_and)->fetchsql(FALSE)->count(1);
		$list=db("interface")->where($where_and)->order($this->get_order())->limit($this->get_limit())->fetchsql(FALSE)->select();
		return array('pageData'=>$list,'total'=>$total,"draw"=>$draw);
	}
}
