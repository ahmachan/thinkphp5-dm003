<?php
namespace app\common\validate;
use think\Validate;
class InterfaceApi extends Validate
{
	protected $rule = [
        'id_number' =>  'require|unique:interface',
        'effect_date'=>'require|date',
        'start_date'=>'require|date',
        'status'=>'require|in:0,1',
        'start_date'=>'require|date',
        'surplus'=>'require|number',
        'in_varifycation'=>'require|in:0,1',
        'out_varifycation'=>'require|in:0,1',
        'in_surplus_limt'=>'require|in:0,1',
        'in_surplus_sub'=>'require|in:0,1',
        //'in_statistic'=>'require|number',
    ];
	protected $message=[
		'id_number.require'=>'会员唯一标识必须填写',
		'id_number.unique'=>'会员唯一标识已经存在',
		'surplus.require'=>'剩余次数必填',
		'status.require'=>'status 必填 ',
		'status.in'=>'status值域 0,1',
		'surplus.number'=>'剩余次数必须是数值',
		'in_varifycation.require'=>'是否进场验证必填',
		'in_varifycation.in'=>'进场验证的值必须是  0 或者 1',
		'out_varifycation.require'=>'是否出场验证必填',
		'out_varifycation.in'=>'出场验证的值必须是  0 或者 1',
		'in_surplus_limt.require'=>'进场是否限制必填',
		'in_surplus_limt.in'=>'进场是否限制必须是  0 或者 1',
		'in_surplus_sub.require'=>'进场后剩余次数减一必填',
		'in_surplus_sub.in'=>'进场后剩余次数减一 必须是  0 或者 1',
		//'in_statistic.require'=>'进场次数统计必填',
		//'in_statistic.number'=>'进场次数统计必填是数值',
	];
	protected $scene=[
		'add'=>['id_number','start_date','status','effect_date','surplus','in_varifycation','out_varifycation','in_surplus_limt','in_surplus_sub'],
		'effect_date'=>['effect_date'],
		'start_date'=>['start_date'],
		'stauts'=>['stauts'],
		'surplus'=>['surplus'],
		'in_varifycation'=>['in_varifycation'],
		'out_varifycation'=>['out_varifycation'],
		'in_surplus_limt'=>['in_surplus_limt'],
		'in_surplus_sub'=>['in_surplus_sub'],
	];
}
