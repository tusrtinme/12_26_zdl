<?php
namespace app\admin\controller;

class Rush extends BaseAdmin
{
    public function lister()
    {
        $re=db("rush")->where("id=1")->find();
        $this->assign("re",$re);
        return \view('lister');
    }
    public function save()
    {
        $data['end_time']=\strtotime(\input('end_time'));
        $res=db("rush")->where("id=1")->update($data);
        if($res){
            $this->success("保存成功");
        }else{
            $this->error("保存失败");
        }
    }
    public function index()
    {
        $re=db("rush")->where("id=2")->find();
        $this->assign("re",$re);
        return \view('index');
    }
    public function saves()
    {
        $data['money']=\input('money');
        $res=db("rush")->where("id=2")->update($data);
        if($res){
            $this->success("保存成功");
        }else{
            $this->error("保存失败");
        }
    }
}