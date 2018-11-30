<?php
namespace app\api\controller;
use app\common\controller\BaseApi;

/*业务接口表api*/
class InterfaceApi extends  BaseApi
{
	/**
	 * 测试地址
	 * http://zwzl.com/api/InterfaceApi/add/v/1/t/1/key/zwzl_admin/sign/5de093255ad3e78341dbe00309c9436c
	 * */
	 public function _initialize()
    {
    	
    	if(!in_array(request()->action(),array("upload","upload_finger_img"))){
    		parent::_initialize();	
    	}
    }
	public function test(){
		var_dump("111");
	}
	/**
	 * 业务接口插入
	 * http://zwzl.com/api/InterfaceApi/add/v/1/t/1/key/zwzl_admin/sign/5de093255ad3e78341dbe00309c9436c
	 * */
	public  function add(){		
		$data=input('');
		$flag=$this->validate($data,'card');
		if($flag!==true){//参数验证错误
			return $this->ajax_error('',"0003",$flag);
		}
		//新增会员
		$flag=$this->validate($data,'InterfaceApi.add');
		if($flag!==TRUE){
			return $this->ajax_error('','0003',$flag);
		}else{
			if (!empty($_FILES)) {
			   //上传处理类
			  $file = request()->file('files');
			  $url="";
			  $file_name="";
		    // 移动到框架应用根目录/public/uploads/ 目录下
		    $info = $file->move(ROOT_PATH . 'data' . DS . 'header');
		    if($info){
			    	$url="./data/header/".$info->getSaveName();
					$data['icon']=$url; 
		    	}
			}
			model('InterfaceApi')->create($data,TRUE);
			return $this->ajax_success();
		}
	}
	
	/**
	 * 业务接口修改
	 * http://zwzl.com/api/InterfaceApi/update/v/1/t/1/key/zwzl_admin/sign/5de093255ad3e78341dbe00309c9436c
	 * */
	 public function update(){
	 	 $id_number=input('id_number/s');
		 
		 //卡表需要的数据
		 $interface=db('Interface')->where("id_number='$id_number'")->fetchsql(FALSE)->find();
		 if(empty($interface)){//id_number 不存在
		 	return $this->ajax_error('','0003','参数错误！（id_number 不存在）');
		 }
		 
		//////////////////////////////////////////////////////////////////////// 
	 	 //会员接口表需要修改的字段
		 //需要修改字段的值
		  if(!empty(input('start_date'))){
		 	$flag=$this->validate(input(''),'InterfaceApi.start_date');
			if($flag!==TRUE){
				return $this->ajax_error('','0003',$flag);
			} 
			$interface['start_date']=input('start_date');
		 }
		 
		 
		 if(!empty(input('effect_date'))){
		 	$flag=$this->validate(input(''),'InterfaceApi.effect_date');
			if($flag!==TRUE){
				return $this->ajax_error('','0003',$flag);
			} 
			$interface['effect_date']=input('effect_date');
		 }
		 
		 if(input('surplus')!==null){
		 	$flag=$this->validate(input(''),'InterfaceApi.surplus');
			if($flag!==TRUE){
				return $this->ajax_error('','0003',$flag);
			} 
			$interface['surplus']=input('surplus');
		 }
		 
		 if(input('in_varifycation')!==null){
		 	$flag=$this->validate(input(''),'InterfaceApi.in_varifycation');
			if($flag!==TRUE){
				return $this->ajax_error('','0003',$flag);
			} 
			$interface['in_varifycation']=input('in_varifycation');
		 }
		 
		  if(input('out_varifycation')!==null){
		 	$flag=$this->validate(input(''),'InterfaceApi.out_varifycation');
			if($flag!==TRUE){
				return $this->ajax_error('','0003',$flag);
			}else{
				$interface['out_varifycation']=input('out_varifycation');
			}
		  }
		  if(!empty(input('mobile'))){
		  	$interface['mobile']=input('mobile');
		  }
			
		 if(!empty(input('name'))){
		  	$interface['name']=input('name');
		  }	
		 
		  if(input('in_surplus_limt')!==null){
		 	$flag=$this->validate(input(''),'InterfaceApi.in_surplus_limt');
			if($flag!==TRUE){
				return $this->ajax_error('','0003',$flag);
			} 
			$interface['in_surplus_limt']=input('in_surplus_limt');
		 }
		  
		 if(input('in_surplus_limt')!==null){
		 	$flag=$this->validate(input(''),'InterfaceApi.in_surplus_sub');
			if($flag!==TRUE){
				return $this->ajax_error('','0003',$flag);
			} 
			$interface['in_surplus_sub']=input('in_surplus_sub');
		 }
		 
		if(input('status')!==null){
			$interface['status']=input('status');
		 }
	 	db('interface')->update($interface);
		 return  $this->ajax_success();
	 }
	
	/*批量修改会员状态*/
	function update_batch(){
		$data=htmlspecialchars_decode(input('data'));
		if(empty($data)){
			return $this->ajax_error('','0003',config('err_code.0003'));
		}else{
			$data=json_decode($data,TRUE);
			foreach ($data as $key => $value) {
				 $id_number=$value['id_number'];
				 db("interface")->where("id_number='$id_number'")->setField(array('status'=>$value['status']));
			}
			return $this->ajax_success();
		}
	}

	/**
	 * 图片上传
	 * zwzl.com/api/InterfaceApi/upload/
	 * */
	public function upload_finger_img(){
		if (!empty($_FILES)) {
			   //上传处理类
			  $file = request()->file('files');
			  $url="";
			  $file_name="";
		    // 移动到框架应用根目录/public/uploads/ 目录下
		    $info = $file->move(ROOT_PATH . 'data' . DS . 'finger');
		    if($info){
			    	$url="./data/finger/".$info->getSaveName();
					$data['icon']=$url; 
		    	}
			return $this->ajax_success("成功");
		}else{
			return $this->ajax_error("图片为空");
		}
	}
}
