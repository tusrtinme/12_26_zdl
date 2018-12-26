<?php
namespace app\admin\controller;

class Leav extends BaseAdmin
{
    public function index()
    {
        $list=\db("message")->field("id,content,a.time as time,nickname")->where("status=1")->alias("a")->join("user b","a.uid = b.uid")->order("id desc")->paginate(10);
        $this->assign("list",$list);
        
        $page=$list->render();
        $this->assign("page",$page);
        
        return \view('index');
    }
    public function look()
    {
        $id=\input('id');
        $re=db("message")->field("content")->where("id=$id")->find();
        echo \json_encode($re);
    }
    public function delete()
    {
        $id=\input('id');
        $re=db("message")->where("id=$id")->find();
        if($re){
            $del=db("message")->where("id=$id")->delete();
            $this->redirect("index");
        }else{
            $this->redirect("index");
        }
    }
    public function huifu()
    {
        $id=\input('id');
        $this->assign("id",$id);
        return \view('huifu');
    }
    public function save()
    {
        $id=\input('id');
        $re=db("message")->where("id=$id")->find();
        if($re){
            $data['uid']=$re['uid'];
            $data['content']=\input('content');
            $data['time']=\time();
            $data['status']=2;
            $rea=db("message")->insert($data);
            if($rea){
                $this->success("回复成功",\url('index'));
            }else{
                $this->error("回复失败",\url('index'));
            }
        }else{
            $this->error("系统繁忙请稍后再试",\url('index'));
        }
    }
    
    
    
    
    
    
    
    
    
}