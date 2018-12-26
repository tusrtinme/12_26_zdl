<?php
namespace app\admin\controller;


class Login extends Common
{
    public function  index(){
        return view('index');
    }
    public function check(){
        $unm=input('post.username');
        $pwd=input('post.password');
        $re=db("Admin")->where(array('username'=>$unm,'pwd'=>$pwd))->find();
        if($re){
            session('uid',$re['Id']);
            $time = date('Y-m-d H:i:s',time());
            $re_pre = db("Admin")->where("Id=1")->find();
            $pretime = $re_pre['curtime'];
            $repre = db("Admin")->where("Id=1")->update(array('pretime'=>$pretime));
            $re = db("Admin")->where("Id=1")->update(array('curtime'=>$time));
            $this->success('登陆成功 ^_^',url('Index/index'));
        }else{
            $this->error('登录失败：用户名或密码错误。',url('Login/index'));
        }
    }
    public function logout(){
        session("uid",null);
        $this->redirect('Login/index');
    }
    function modify(){
        if (! defined('CONTROLLER_NAME')) {
            define('CONTROLLER_NAME', $this->request->controller());
        }
        if (! defined('ACTION_NAME')) {
            define('ACTION_NAME', $this->request->action());
        }
        $re=db("Admin")->where("id=1")->find();
        $this->assign("re",$re);
        return view('modify');
    }
    function save(){
        $ob=db("Admin");
        $data=input('post.');
        $res=$ob->where("Id=1")->update($data);
        if($res){
            $this->success("修改成功！");
        }else{
            $this->error("修改失败！");
        }
    }
}