<?php
namespace app\admin\controller;

class Message extends BaseAdmin
{
    public function index()
    {
        $list=\db("message")->alias('a')->field("id,content,a.time as time,nickname")->where("status=0")->join("user b","a.uid=b.uid")->order("id desc")->paginate(10);
        $this->assign("list",$list);
        
        $page=$list->render();
        $this->assign("page",$page);
        
        return view('index');
    }
    public function deletes()
    {
        $id=\input('id');
        $re=db("message")->where("id=$id")->find();
        if($re){
            $res=db("message")->where("id=$id")->delete();
            $this->redirect('index');
        }else{
            $this->redirect('index');
        }
    }
}