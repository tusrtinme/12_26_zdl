<?php
namespace app\admin\controller;

use think\Controller;

class Common extends Controller{
    function _initialize(){
        $sys=db('sys')->where("id=1")->find();
        $this->assign("sys",$sys);
        
         
    }
}