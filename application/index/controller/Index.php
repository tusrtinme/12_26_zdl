<?php
namespace app\index\controller;
use think\Controller;
class Index extends controller
{
    public function index(){
        //首页banner
        $image = db('lb')->where('fid',1)->select();
        $this->assign('image',$image);

        //商城公告
        $radio = db('lb')->where('fid',2)->find();
        $this->assign('radio',$radio);

        //首页中部广告
        $pic = db('lb')->where('fid',3)->find();
        $this->assign('pic',$pic);

        return view('index');
    }
}
