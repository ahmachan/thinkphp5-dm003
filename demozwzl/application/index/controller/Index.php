<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        return '<h1>欢迎使用--zwzl--</h1>';
    }
	public  function home(){
		 var_dump(db("test")->insertGetId(array("name"=>(uniqid(mt_rand(), true)))));
	} 
}
