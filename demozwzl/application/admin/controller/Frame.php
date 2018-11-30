<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;

/*用于提供公共hearders 和 bottoms 和left 部分*/
class Frame extends AdminBase
{
	/*头部*/
    public function headers()
    {
    	return $this->fetch();
    }
	/*尾部*/
	public function bottoms()
    {
    	return $this->fetch();
    }
	/*左边菜单*/
	public function left(){
		return $this->fetch();
	}
	/*顶部*/
	 public function top(){
	 	return $this->fetch();
	 }
	 /*内容部分*/
	 public function content(){
	 	return $this->fetch();
	 }
	 /*公共脚本部分*/
	 public function scripts(){
	 	return $this->fetch();
	 }
	 ///////////////////////////////////////////
	 //测试数据
	public function test(){
		var_dump(sp_upload_file("uplad"));
	}
	
	public function getstatistic(){
		$result=array('div1'=>0,'div2'=>0,'div3'=>0,'div4'=>0);
		$data=db("interface")->field("owner_type,count(1) as count")->group("owner_type")->select();
		if(empty($data)){
			$this->ajax_success($data);		
		}else{
			foreach($data as $key =>$value){
				if($value['owner_type']==1){
					$result['div4']=$value['count'];
				}else if($value['owner_type']==2){
					$result['div3']=$value['count'];					
				}
			}
		}
		
		$data=date('Y-m-d');
		$result1=db('BehaviorLog')->field("card_no,count(1) as count")->where("behavior_status=1 and behavior_type=1 and date_format(create_time,'%Y-%m-%d')='$data' ")->group("card_no")->select();
		$result2=db('BehaviorLog')->where("behavior_status=1 and behavior_type=1 and date_format(create_time,'%Y-%m-%d')='$data' ")->count(1);
		if(!empty($result1)){
			$result['div2']=count($result1);		
		}
		if(!empty($result2)){
			$result['div1']=$result2;		
		}
		return $this->ajax_success($result);						
	}
	/*获取性别*/
	public function get_sex_info(){
		$sex=db("user")->group("sex")->field("sex,count(1) as count")->select();
		$total=db("user")->count(1);
		$result=array("0"=>array("count"=>0,"rate"=>0),"1"=>array("count"=>0,"rate"=>0),"2"=>array("count"=>0,"rate"=>0));
		foreach ($sex as $key => $value) {
			$result[$value['sex']]=array("count"=>$value['count'],"rate"=>100*$value['count']/$total);
		}
		$this->ajax($result);
	}
	/*获取上周注册情况*/
	public function get_register_info(){
		$date=date('Y-m-d');  //当前日期
        $first=1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        $w=date('w',strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $now_start=date('Y-m-d',strtotime("$date -".($w ? $w - $first : 6).' days')); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $now_end=date('Y-m-d',strtotime("$now_start +6 days"));  //本周结束日期
        $last_start=date('Y-m-d',strtotime("$now_start - 7 days"));  //上周开始日期
        $last_end=date('Y-m-d',strtotime("$now_start - 1 days"));  //上周结束日期
        
       $make_week=$this->_make_week($last_start);
        $fields="DATE_FORMAT(create_time,'%Y-%m-%d') as create_time,count(1) as count";
       	$infos=db('user')->field($fields)->where("DATE_FORMAT(create_time,'%Y-%m-%d')>='$last_start' and DATE_FORMAT(create_time,'%Y-%m-%d')<='$last_end'")->group("DATE_FORMAT(create_time,'%Y-%m-%d')")->fetchsql(FALSE)->order("create_time desc")->select();
		foreach ($infos as $key => $value) {
			$make_week[$value['create_time']]=$value['count'];
		}
		$result='';
		$i=1;
		foreach ($make_week as $key => $value) {
			$result[]=[$i++,$value];
		}
	   	$this->ajax($result);
	}
	
	/*生成一周7天日期数据*/
	public function _make_week($last_start){
		$result='';
		for ($i=0; $i <7 ; $i++) {
			$temp=date("Y-m-d",strtotime("+$i day",strtotime($last_start))); 
			$result[$temp]=0;
		}
		return $result;
	}
}
