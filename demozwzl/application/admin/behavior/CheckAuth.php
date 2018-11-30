<?php
namespace app\admin\behavior;
use think\Log;
use think\Hook;
class CheckAuth
{
	public function moduleInit(){
//		Log::write("moduleInit","error");
//		Hook::listen('actionInit');
	}
    public function actionBegin(&$params)
    {
    	
		//todo
//  	$test=input("test");    	
//		Log::write("actionBegin".$test,"error");
    }
	
	public function responseEnd(&$params){
		//todo
//		Log::write("response_end".json_encode($params,TRUE),"error");
	}
	/*自定义行为标签*/
	public function actionInit(&$params){
		//todo
//		Log::write("actionInit".json_encode($params,TRUE),"error");
	}
	
}
