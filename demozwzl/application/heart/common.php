<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/*微信登录验证*/
 function h_login_varify($mobile,$psw){
	 $psw=md5($psw);
	 $user=db("user")->where("mobile='$mobile' and psw='$psw'")->find();
	 if(empty($user)){
	 	return  h_error("","账号或密码错误！");
	 }else{
	 	session('User',$user);
		$user['PHPSESSION']=session_id();
	 	return h_success($user,"成功");
	 }
}
/**
 * 设置注册
 * @param $mobile 电话号码
 * @param $psw 密码
 * @param $nick 微信昵称
 * @param $open_id 微信id
 * @param $icon 微信图标
 * */
 function h_register($mobile,$psw,$nick,$open_id,$icon,$sex=1){
 	 $vafify=h_varify_param(array($mobile,$psw,$open_id));
	 if(!$vafify['flag']){
	 	return $vafify;
	 }
	 if(db("user")->where("mobile='$mobile' or open_id='$open_id'")->count(1)>0){
	 	return h_error("","电话号码或者open_id已经存在");
	 }
	 $user=db("user")->where("mobile='$mobile'")->find();
	 if(empty($user)){
	 	 $id=db("user")->insertGetId(array('mobile'=>$mobile,'psw'=>md5($psw),'icon'=>$icon,'nick_name'=>$nick,'open_id'=>$open_id,'sex'=>$sex));
		 db("WeekStatistic")->insert(array("user_id"=>$id,"week_date"=>h_get_first_day_of_week()));
		 $user=db("user")->find($id);
		session('User',$user);
	 	return  h_success($user,"成功！");
	 }else{
	 	return h_error($user,"电话号码已经存在");
	 }
}
/**
 * 获取手机端登陆session
 * */
function h_get_current_user() {
	return session('User');
}
/*获取session 中的验证码*/
function h_get_msg_code(){
	 return session("msg_code");
}
/**
 * 发送短信验证码
 * */
function h_send_msg_code($mobile){
	if(preg_match("/^1[345678]\d{9}$/", $mobile)){
		$code=rand(10000,99999);
		$url="http://www.ztsms.cn:8800/sendSms.do?username=xiaolijob&password=2rCXWG71&mobile=$mobile&content=您的短信验证码是".$code."！请妥善保管【中未智联】&dstime=&productid=95533&xh=";
		$result=h_get_net_data($url);
		if(strpos('1,', $result)==0){
			session('msg_code',$code);
			return h_success("","发送成功");
		}else{
			return h_error($result,"发送失败");
		}
	}else{
			return h_error("","手机号码格式错误");
	}
} 
/**
 *验证短信验证码 
 */
 function h_varify_msg_code($mobile_code){
 	 $code=h_get_msg_code();
 	 if($mobile_code!=$code){
 	 	 return h_error("","验证码错误");
 	 }else{
 	 	 return h_success();
 	 }
 }
	 
/**
 * 成功返回的模板
 * */
 function h_success($data="",$msg="成功",$code="1000"){
	return array('flag'=>TRUE,"data"=>$data,"code"=>$code,"msg"=>$msg);
} 
/**
 * 失败返回的模板
 * */
 function h_error($data="",$msg="未知错误",$code="0000"){
	return array('flag'=>FALSE,"data"=>$data,"code"=>$code,"msg"=>$msg);
}
 
/**
 * 验证参数是否为空
 * @param params=array()
 * */
function h_varify_param($params){
	 	foreach ($params as $key => $value) {
	 		if(empty($value)){
	 			return h_error("","参数错误");
	 		}
	 	}
		return h_success();
}
/*获取网络数据*/
function h_get_net_data($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		return curl_exec($ch);
}
/*获取一周第一天*/
function h_get_first_day_of_week(){
	$date=new DateTime(); 
	$date->modify('this week'); 
	return $date->format('Y-m-d'); 
	
}
/**
 * 排行榜
 * @param  $type 排序类型  0 全部 1 男生  2女生
 * */
function h_ranking_list($type=0){
	$first_day_of_week=h_get_first_day_of_week();
	if($type==0){
		$where="a.week_date='$first_day_of_week'";
	}else{
		$where=" a.week_date='$first_day_of_week' and  b.sex=$type ";
	}
	$user_id=h_get_current_user()['id'];
	$list=db("WeekStatistic")->alias("a")->field("a.id,b.nick_name,a.cost_time,a.zan,a.user_id")->join([['m_user b',"a.user_id=b.id"]])->where($where)->order("a.cost_time desc,a.id desc")->page(1,10)->select();
	$my_week_statistic=db("WeekStatistic")->where("user_id=$user_id and week_date='$first_day_of_week'")->fetchsql(FALSE)->find();
	$ranking=db("WeekStatistic")->where("week_date='$first_day_of_week' and cost_time>".$my_week_statistic['cost_time'])->count(1);
	$my_week_statistic['ranking']=$ranking+1;
	foreach ($list as $key => $value) {
		$list[$key]['ranking']=$key+1;
		if($value['user_id']==$user_id){
			$my_week_statistic['ranking']=$key+1;;
		}
	}
	return h_success(array('list'=>$list,'my_ranking'=>$my_week_statistic));	
}
/*设置年龄 最高心率  性别*/
function h_set_field($data){
	db("user")->update($data);
	return h_success();
}
/*退出登录 清除缓存*/
function h_login_out(){
	session(null);
	return h_success();
}
/*修改密码*/
function h_modify_psw($mobile,$psw){
	$varify=h_varify_param(array($mobile,$psw));
	if($varify['flag']){
		db("user")->where("mobile='$mobile'")->setField(array("psw"=>md5($psw)));
		return h_success();
	}else{
		return $varify;
	}
}
/***
 * 上传心率数据
 * @param $avg_heart 平均心率
 * @param $cost_time  运动时长 单位分钟
 * @param $max_heart 最大心率
 * @param $level_1 热身占比
 * @param $level_2 减脂占比
 * @param $level_3 糖元消耗占比
 * @param $level_4  乳酸堆积
 * @param $level_5 极限运动占比
 * @param $user_id 用户id
 * @param $mac_addr 心率带 mac_addr
 * @param $calorie 消耗卡路里
 */		
function h_upload_heart_record($avg_heart,$cost_time,$max_heart,$level_1,$level_2,$level_3,$level_4,$level_5,$user_id,$mac_addr,$calorie){
	$varify=h_varify_param(array($user_id,$mac_addr));
	if($varify['flag']){
		$equipment=db("equipment")->where("mac_addr='$mac_addr'")->find();
		if(empty($equipment)){
			db("equipment")->insert(array("mac_addr"=>$mac_addr,"user_id"=>$user_id));
		}else{
			$equipment['user_id']=$user_id;
			db("equipment")->update($equipment);
		}
		db("record")->insert(array("avg_heart"=>$avg_heart,"cost_time"=>$cost_time,"calorie"=>$calorie,"max_heart"=>$max_heart,
								   "level_1"=>$level_1,"level_2"=>$level_2,"level_3"=>$level_3,"level_4"=>$level_4,"level_5"=>$level_5,
								   "user_id"=>$user_id,"mac_addr"=>$mac_addr,"calorie"=>$calorie
								   ));
		 $first_week_of_day=h_get_first_day_of_week();						   
		 db("WeekStatistic")->where("user_id=$user_id and week_date='$first_week_of_day'")->setInc("cost_time",$cost_time);						   
		 return h_success();						   
	}else{
		return $varify;
	}
}
/*心率记录列表*/
function h_heart_record_list($date){
	if(empty($date)){
		$date=date("Y-m-d");
	}
	$user_id=h_get_current_user()['id'];
	$list=db("record")->where("user_id=$user_id and date_format(create_time,'%Y-%m-%d')='$date'")->fetchsql(FALSE)->select();
	return h_success($list);
}
/*点赞*/
function h_zan($id,$user_id){
	$varify=h_varify_param(array($id,$user_id));
	if($varify['flag']){
		$count=db("zan")->where("week_statistic_id=$id and user_id=$user_id")->count(1);
		if($count>0){
			return h_error("","您已经点过赞啦");
		}
		db("WeekStatistic")->where("id=$id")->setInc("zan");
		db("zan")->insert(array("week_statistic_id"=>$id,"user_id"=>$user_id));
		return h_success();
	}else{
		return $varify;
	}
}

