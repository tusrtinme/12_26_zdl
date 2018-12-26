<?php
namespace app\index\controller;
use think\Controller;
class Index extends controller
{
    public function index(){
        //首页banner
        $image = db('lb')->where('fid',1)->select();
        $this->assign('image',$image);
        return view('index');
    }
}
