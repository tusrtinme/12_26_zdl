<?php
namespace app\admin\controller;

class Info extends BaseAdmin
{
    public function index()
    {
        $list=\db("info")->alias('a')->where("status=1")->join('user b','a.u_id=b.uid')->order("id desc")->paginate(10);
        $this->assign("list",$list);
        
        $page=$list->render();
        $this->assign("page",$page);
        
        return \view('index');
    }
    public function deletes()
    {
        $id=\input('id');
        $re=db("info")->where("id=$id")->find();
        if($re){
            $res=db("info")->where("id=$id")->delete();
            $this->redirect('index');
        }else{
            $this->redirect('index');
        }
    }
}