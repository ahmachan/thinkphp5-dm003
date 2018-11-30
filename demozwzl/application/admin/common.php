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
/**
 * 获取当前登录的管事员
 * @return obj
 */
function sp_get_current_admin(){
	return session('Admin');
}
/**
 * 获取当前权限菜单
 */
function sp_get_auth_menu(){
 	 $admin=session('Admin');
	 $menu=sp_get_all_menu();
	 if($admin['id']==1){//超级管理员
	 	  return $menu;
	 }else{//授权管理员
	 	  $auth_group=model("AuthGroup")->where("id=(select auth_group_id from m_auth where admin_id=$admin[id])")->find();
		  if(empty($auth_group)){
		  	  return null;
		  }else{
		  	  foreach ($menu as $k => $v) {//遍历第一级菜单
		  	  	  if(!in_array($k,explode(",", $auth_group['pool_1']) )){
		  	  	  	   unset($menu[$k]);
		  	  	  }else{
		  	  	  	   foreach ($v['childs'] as $kk => $vv) {//遍历第二级菜单
		  	  	  	   	     if(!in_array($kk, explode(",", $auth_group['pool_2']))){
		  	  	  	   	     	unset($menu[$k]['childs'][$kk]);
		  	  	  	   	     }
		  	  	  	   }
		  	  	  }
		  	  }
			  return $menu;
		  }
	 }
}
/**
 * 获取三级菜单（功能按钮）
 * @param $code 二级菜单code
 * @return array()
 */
function sp_get_auth_menu_3($code){
	  $auth_menu=sp_get_auth_menu();
	  //功能按钮
	  $code_1=substr($code, 0,4);//第一级菜单
	  if(isset($auth_menu[$code_1]['childs'][$code]['childs'])){
	  	 return $auth_menu[$code_1]['childs'][$code]['childs'];
	  }//功能按钮
	  return null;
}
/**
 * 获取所有菜单
 * @return array()
 */
function sp_get_all_menu(){
    return config('menu.menu');
} 
/**
 * 拼装请求参数成sql
 **/
function sp_get_param_sql($flag,$fields){
		$where_ands=array("1=1");
			if($flag){
			foreach ($fields as $param =>$val){
				if (isset($_POST[$param]) && (trim($_POST[$param])!='')) {
					$operator=$val['operator'];
					$field   =$val['field'];
					$get=$_POST[$param];
					$_GET[$param]=$get;
					if($operator=="like"){
						$get="%$get%";
					}
					if($operator=='in'){
						array_push($where_ands, "$field $operator $get");
					}else{
						array_push($where_ands, "$field $operator '$get'");	
					}
				}
			}
		}else{
			foreach ($fields as $param =>$val){
				if (isset($_GET[$param]) && (trim($_GET[$param])!='')) {
					$operator=$val['operator'];
					$field   =$val['field'];
					$get=$_GET[$param];
					if($operator=="like"){
						$get="%$get%";
					}
					if($operator=='in'){
						array_push($where_ands, "$field $operator $get");
					}else{
						array_push($where_ands, "$field $operator '$get'");	
					}
				}
			}
		}
		return $where_ands;
}
/**动态修改自定义配置文件  extra 下面的文件
 *@param file 动态配置文件名称
 *@param param 配置参数  array("key"=>key,"value"=>value)
 *@return true 成功  	  false失败 
 **/
function sp_dynamic_config($file,$param){
	if(empty($file)||empty($param)){
		return FALSE;
	}
	$cache=config($file);
	$cache=array_merge($cache,$param);
	$content = "<?php\nreturn ".var_export($cache,TRUE).";"; 
	file_put_contents("./application/extra/".$file.".php", $content);
	return TRUE;
}
/**
 * 获取表单提交的文件名称
 * $pics=xxxxx,bbbb,ccc
 * */
function sp_get_file_name($pics){
	if(empty($pics)){
		return [];
	}else{
		$pics=explode(",", $pics);
		$result="";
		foreach ($pics as $key => $value) {
			if(!empty($value)){
				$result[]=$value;
			}
		}
		return $result;
	}
}
/**
 *文件上传目录
 * 文件目录在 ./data/$filename/yyyyMMdd/  其中filename 是传入的文件名称
 **/
function sp_file_upload($filename){
	$dir="./data/$filename/".date("Ymd")."/";
	 if(!is_dir($dir)){
	 	mkdir($dir);
	}
	Vendor('uploader.uploader');
	$uploader=new \Uploader();
    $data = $uploader->upload($_FILES['files'], array(
    'limit' => 10, //Maximum Limit of files. {null, Number}
    'maxSize' => 10, //Maximum Size of files {null, Number(in MB's)}
    'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
    'required' => false, //Minimum one file is required for upload {Boolean}
    'uploadDir' => $dir, //Upload directory {String}
    'title' => array('auto'), //New file name {null, String, Array} *please read documentation in README.md
    'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
    'perms' => null, //Uploaded file permisions {null, Number}
    'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
    'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
    'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
    'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
    'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
    'onRemove' => 'onFilesRemoveCallback' //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
	));	 
	if($data['isComplete']){
        $files = $data['data'];
//		print_r($files);
        print_r(substr($files['files'][0],1));
		die;
    }

    if($data['hasErrors']){
        $errors = $data['errors'];
        print_r($errors);
		die;
    }
}
function onFilesRemoveCallback($removed_files){
    foreach($removed_files as $key=>$value){
        $file = '/uploads/' . $value;
        if(file_exists($file)){
            unlink($file);
        }
    }
    return $removed_files;
}

/**
 * 导出excle
 * $titles_des 标题描述
 * $titles_field 标题字段
 * $title excel 名称
 * $titles_des=array('姓名','年龄','班级','职位');
		$titles_field=array('A','B','C','D');
		$data=array(array('A'=>'张数1','B'=>'18','C'=>'3班','D'=>'经理')
				,array('A'=>'张数2','B'=>'20','C'=>'4班','D'=>'动手')
		);
		$title="测试demo";
 */
function export_excel($titles_des,$titles_field,$data,$title){
	//$title=iconv("utf-8", "gb2312", $title);
	if(is_array($titles_des)&&is_array($titles_field)){
		$result=result_template(true);
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('RPC');
		
		if (PHP_SAPI == 'cli')
			die('This example should only be run from a Web Browser');
		
		/** Include PHPExcel */
		Vendor('phpoffice.phpexcel.Classes.PHPExcel');
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		// Set document properties
		$objPHPExcel->getProperties()->setCreator(C('CompanyName'))
									 ->setLastModifiedBy(C('CompanyName'))
									 ->setTitle("Office 2007 XLSX  Document")
									 ->setSubject("Office 2007 XLSX  Document")
									 ->setDescription("The document for Office 2007 XLSX, generated using PHP classes.".C('CompanyName'))
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("result file");
		
		
		/*26个英文字母*/
		for($i=65;$i<91;$i++){
			$res[]=strtoupper(chr($i));
     	}
		$AZ=$res;
		// Add title
		foreach($titles_des as $key=>$value){
			$row=$AZ[$key]."1";
			$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValueExplicit($row,$value);
		}
		// Add data
		foreach($data as $key=>$value){
			foreach($titles_field as $k=>$v){
				$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValueExplicit($AZ[$k].($key+2),$value[$v]);
			}
		}
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle($title);
		
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8');
		
		$ua = $_SERVER["HTTP_USER_AGENT"];
		if (strpos($ua,'MSIE')!==false || strpos($ua,'rv:11.0')) {  
			header('Content-Disposition: attachment; filename="' . urlencode($title) . '"'.".xlsx");
		} else {  
			header('Content-Disposition: attachment;filename='.$title.".xlsx");
		}
		
	//	header('Content-Disposition: attachment;filename='.$title.".xlsx");
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit();
	}else{
		return result_template(FALSE,'$titles_des 和 $titles_field 不是数组');
	}
}
/**
 * @param $title_  excle 第一行的标题
 * @param $start_ 数据开始行数 
 * @param $start_col_  数据开始列
 **/
function import_excel($title_,$start_=2,$start_col_){
		$result=array();
		if((is_array($title_)==false)||count($title_)<1){
			return result_template(FALSE,'标题列不是数组!或者为空');
		}
		if((is_numeric($start_)==false)||$start_<0){
			return result_template(FALSE,'开始行小于0');
		}
		if((is_numeric($start_col_)==false)||$start_col_<0){
			return result_template(FALSE,'开始列小于0');
		}
		if (!empty($_FILES)) {
			   //上传处理类
			  $file = request()->file('files');
			  $url="";
			  $file_name="";
		    // 移动到框架应用根目录/public/uploads/ 目录下
		     $info = $file->move(ROOT_PATH . 'data' . DS . 'upload'.DS .'excel');
		    if($info){
		    	$url="./data/upload/excel/".$info->getSaveName();
				$file_name=$info->getFilename(); 
		        // 成功上传后 获取上传信息
		        // 输出 jpg
		       // echo $info->getExtension();
		        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
		       // echo $info->getSaveName();
		        // 输出 42a79759f284b767dfcb2a0197904287.jpg
		        //echo $info->getFilename(); 
				//$url="./data/upload/excel/".$first['savename'];
		    }else{
		        // 上传失败获取错误信息
		        return result_template(FALSE,$file->getError(),$result);
//		        echo $file->getError();
		    }
			Vendor('phpoffice.phpexcel.Classes.PHPExcel');
			Vendor("PHPExcel.IOFactory");
			if(strpos($url,'.xlsx')){
				$objReader = \PHPExcel_IOFactory::createReader('Excel2007');
			}else{
				$objReader = \PHPExcel_IOFactory::createReader('Excel5');
			}
			
			$path=$url;
			$objPHPExcel = $objReader->load($path, $encode = 'utf-8');
			/*读取第0个工作表格*/
			$sheet = $objPHPExcel -> getSheet(0);
			/*取得总行数*/ 
			$highestRow = $sheet -> getHighestRow();
			/*取得列数*/
			$highestColumn = $sheet -> getHighestColumn();
			
			if($start_>$highestRow){
				return result_template(FALSE,'开始行大于总行数');
			}
			$AZ=getAZ();
				/* 获取excel  标题*/
				if($start_==2){
						foreach ($title_ as $key => $field) {
							$cell_date=$objPHPExcel->getActiveSheet()->getCell($AZ[$key]."1")->getCalculatedValue();
							if(!in_array(trim($cell_date),$title_)){
								return result_template(FALSE,'模板错误');
							}
					}
				}
			for ($i = $start_; $i <= $highestRow; $i++) {
				$data=array();
				foreach ($title_ as $key => $field) {
					$cell_date=$objPHPExcel->getActiveSheet()->getCell($AZ[$key+$start_col_].$i)->getCalculatedValue();
					if(empty($cell_date)){
						if($cell_date==0){
							$data[trimall($field)]=0;
						}else{
							$data[trimall($field)]='';	
						}
					}else{
						$data[trimall($field)]=trimall($cell_date);
					}
				}
				$result[]=$data;
			}
			return result_template(TRUE,'成功',$result);
		} else {
			return result_template(FALSE,'请选择文件',$result);
		}
}

/**
 *结果模板 
 * flag 标志位 ，msg 提示信息，data返回的数据
 * ['flag'=>'','msg'=>'','data'=>'']
 */
function result_template($flag=false,$msg='',$data=array()){
	return array('flag'=>$flag,'msg'=>$msg,'data'=>$data);
}

/*获取excle标题 和标题对应的数据  第一行是标题   从第二行开始为数据*/
function get_excle_title_data(){
		$excle_titles='';
		$excle_datas='';
		$result='';
		if (!empty($_FILES)) {
			   //上传处理类
            $config=array(
            		'rootPath' => './data/upload/excel/',
            		'savePath' => '',
            		'maxSize' => 11048576,
            		'saveName'   =>    array('uniqid',''),
            		'exts'       =>    array('xls','xlsx'),
            		'autoSub'    =>    false,
            );
			$upload = new \Think\Upload($config);
			$file_name='';
			$info=$upload->upload();
			if (!$info) {
				 //上传失败，返回错误
				 return result_template(FALSE,$upload->getError());
            } else {
                $first=array_shift($info);
                if(!empty($first['url'])){
                	$url=$first['url'];
                }else{
                	/*文件保存路径*/
                	$url="./data/upload/excel/".$first['savename'];
                }
					/*文件名称*/
                $file_name=$first['name'];
            }
			Vendor("PHPExcel");
			Vendor("PHPExcel.IOFactory");
			if(strpos($url,'.xlsx')){
				$objReader = \PHPExcel_IOFactory::createReader('Excel2007');
			}else{
				$objReader = \PHPExcel_IOFactory::createReader('Excel5');
			}
			
			$path=$url;
			$objPHPExcel = $objReader->load($path, $encode = 'utf-8');
			/*读取第0个工作表格*/
			$sheet = $objPHPExcel -> getSheet(0);
			/*取得总行数*/ 
			$highestRow = $sheet -> getHighestRow();
			/*取得列数*/
			$highestColumn = $sheet -> getHighestColumn();
			
			if($start_>$highestRow){
				return result_template(FALSE,'开始行大于总行数');
			}
			$AZ=getAZ();
				/* 获取excel  标题*/
				for ($key=0;$key<26;$key++) {
							$cell_date=$objPHPExcel->getActiveSheet()->getCell($AZ[$key]."1")->getCalculatedValue();
							if(empty($cell_date)==FALSE){
								$excle_titles[]=trimall($cell_date."");
							}else{
								break;
							}
					}
			
			for ($i = 2; $i <= $highestRow; $i++) {
				$data=array();
				foreach ($excle_titles as $key => $field) {
					$cell_date=$objPHPExcel->getActiveSheet()->getCell($AZ[$key+0].$i)->getCalculatedValue();
					if(empty($cell_date)){
						$data[trimall($field)]='';
					}else{
						$data[trimall($field)]=trimall($cell_date);
					}
				}
				$excle_datas[]=$data;
				//$data['remark'] = $objPHPExcel -> getActiveSheet() -> getCell("P" . $i) -> ;
			}
			return result_template(TRUE,'成功',array("name"=>$file_name,"datas"=>$excle_datas,"titles"=>$excle_titles));
		} else {
			return result_template(FALSE,'请选择文件',$result);
		}
	
}

/*获取A-Z*/
function getAZ(){
	for($i=65;$i<91;$i++){
		$res[]=strtoupper(chr($i));
	}
	return $res;
}


/*去掉所有空格*/
function trimall($str)//删除空格
{
    $qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");
    return str_replace($qian,$hou,$str);    
}