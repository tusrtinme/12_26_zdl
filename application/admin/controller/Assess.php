<?php
namespace app\admin\controller;

class Assess extends BaseAdmin
{
    public function lister()
    {
        $list=\db("assess")->alias('a')->field("id,content,g_name,number")->where("status=0")->join("goods b","a.g_id=b.gid")->paginate(10);
        $this->assign("list",$list);
        
        $page=$list->render();
        $this->assign("page",$page);
        
        return \view('lister');
    }
    public function change()
    {
        $id=\input('id');
        $re=db("assess")->where("id=$id")->find();
        if($re){
            $res=db("assess")->where("id=$id")->setField("status",1);
            $this->redirect('lister');
        }else{
            $this->redirect('lister');
        }
    }
    public function delete()
    {
        $id=\input('id');
        $re=db("assess")->where("id=$id")->find();
        if($re){
            $res=db("assess")->where("id=$id")->delete();
            $this->redirect('lister');
        }else{
            $this->redirect('lister');
        }
    }
    public function index()
    {
        $list=\db("assess")->alias('a')->field("id,content,g_name")->where("status=1")->join("goods b","a.g_id=b.gid")->paginate(10);
        $this->assign("list",$list);
    
        $page=$list->render();
        $this->assign("page",$page);
    
        return \view('index');
    }
    public function deletes()
    {
        $id=\input('id');
        $re=db("assess")->where("id=$id")->find();
        if($re){
            $res=db("assess")->where("id=$id")->delete();
            $this->redirect('index');
        }else{
            $this->redirect('index');
        }
    }
    
    
    
    
    
    
    
}