<?php
namespace app\common\validate;
use think\Validate;
class Brackemachine extends Validate
{
	protected $rule = [
        'brackemachine_no' =>  'require|unique:brackemachine',
        'addr'=>'require|unique:brackemachine',
    ];
	protected $message=[
		'brackemachine_no.require'=>'编号必须填写',
		'addr.require'=>'编号必须填写',
		'addr.unique'=>'物理地址已经存在',
		'brackemachine_no.require'=>'设备编号已经存在',
	];
	protected $scene=[
		'add'=>['brackemachine_no','addr'],
		'addr'=>['addr'=>'require'],
	];
}
