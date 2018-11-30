<?php
namespace app\heart\controller;
use app\common\controller\HeartApi;

/*手机端 心率apk*/
class Heart extends  HeartApi
{
	/**
	 * 测试地址
	 * http://tp5.com/heart/heart/test
	 * */
	 public function _initialize()
    {
    	$filter=array("login","register","send_msg_code","varify_msg_code","login_out");
		if(!in_array(request()->action(), $filter)){
			parent::_initialize();	
		}
    }
	/**
	 * 登录
	 * http://tp5.com/heart/heart/login/mobile/15200000000/123456
	 * */
	public function login(){
		 return h_login_varify(input('mobile'),input('psw'));
	}
	/**
	 * 注册
	 *  http://tp5.com/heart/heart/register/mobile/15202373874/psw/123/nick_name/占/open_id/saf13456/icon/www.baidu.com
	 * */
	public function register(){
		return h_register(input("mobile"),input("psw"),input("nick_name"),input("open_id"),input("icon"),input("sex"));
	}
	/**
	 * 修改密码
	 * http://tp5.com/heart/heart/modify_psw/mobile/15202373874/psw/12345
	 * */
	public function modify_psw(){
		return h_modify_psw(input("mobile"),input("psw"));
	}
	/**
	 * 短信验证码
	 * http://tp5.com/heart/heart/send_msg_code/mobile/15202373874
	 * */
	public function send_msg_code(){
		 return h_send_msg_code(input("mobile"));
	}
	/**
	 * 验证短信码
	 * http://tp5.com/heart/heart/varify_msg_code/mobile/123456
	 * */
	public function varify_msg_code(){
		return h_varify_msg_code(input("code"));
	} 
	/**
	 * 排行榜
	 * http://tp5.com/heart/heart/rangking_list/type/1
	 * */
	public function rangking_list(){
		return h_ranking_list(input("type"));
	} 
	/**
	 * 设置
	 * 最高心率 
	 * http://tp5.com/heart/heart/set_max_heart/id/19/max_heart/198
	 * */
	public function set_max_heart(){
		if(empty(input("id"))||empty(input("max_heart"))){
			return h_error("","id,max_heart 必填");
		}
		return h_set_field(array("id"=>input("id"),"max_heart"=>input("max_heart")));
	}
	/**
	 * 设置
	 * 最高年龄和性别
	 * http://tp5.com/heart/heart/set_age_sex/id/19/sex/2/age/22
	 * */
	public function set_age_sex(){
		if(empty(input("id"))||empty(input("sex"))||empty(input("age"))){
			return h_error("","id,sex,age 必填");
		}
		return h_set_field(array("id"=>input("id"),"age"=>input("age"),"sex"=>input("sex")));
	} 
	
	/**
	 * 设置
	 * 姓名昵称
	 * http://tp5.com/heart/heart/set_nick_name/id/19/nick_name/2x
	 * */
	public function set_nick_name(){
		if(empty(input("id"))||empty(input("nick_name"))){
			return h_error("","id,nick_name 必填");
		}
		return h_set_field(array("id"=>input("id"),"nick_name"=>input("nick_name")));
	} 
	
	/**
	 * 设置
	 * 性别
	 * http://tp5.com/heart/heart/set_sex
	 * */
	public function set_sex(){
		if(empty(input("id"))||empty(input("sex"))){
			return h_error("","id,sex 必填");
		}
		return h_set_field(array("id"=>input("id"),"sex"=>input("sex")));
	}
	/**
	 * 退出登录
	 * */
	public function login_out(){
		return h_login_out();
	}
	/**
	 *获取当前登录者的信息 
	 * http://tp5.com/heart/heart/get_current_user_info
	 */
	public function get_current_user_info(){
		return h_success(h_get_current_user());
	}
	/**
	 * 上传心跳数据
	 * http://tp5.com/heart/heart/upload_heart_record/avg_heart/150/cost_time/100/max_heart/220/level_1/0.1/level_2/0.2/level_3/0.3/level_4/0.2/level_5/0.2/user_id/20/mac_addr/11:22:33:44:55:66/calorie/12.12
	 * */
	 public function upload_heart_record(){
	 	return h_upload_heart_record(
	 	 input("avg_heart"),input('cost_time'),input('max_heart'),input('level_1'),input('level_2'),
	 	 input('level_3'),input('level_4'),input('level_5'),input("user_id"),input("mac_addr"), 
		 input('calorie')
		);
	 }
	/**
	 * 更具日期查询记录
	 * http://tp5.com/heart/heart/heart_record_list/date/2017-04-25
	 * */
	public function heart_record_list(){
		return h_heart_record_list(input('date'));
	}
	/**
	 * 点赞
	 * http://tp5.com/heart/heart/zan/id/2/user_id/20
	 * */
	public function zan(){
		return h_zan(input("id"),input("user_id"));
	} 
	
	/**
	 * 
	 * */
	public function test(){
		 var_dump( preg_match("/^1[345678]\d{9}$/", "15202373874"));
		 die;
	}
////////////////////////////////////////////////////////////////////////////////////
}
