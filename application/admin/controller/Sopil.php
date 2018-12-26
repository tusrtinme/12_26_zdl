<?php
namespace app\admin\controller;

class Sopil extends BaseAdmin
{
    public function index()
    {
        $list=db("sopil")->order("sort asc")->select();
        $this->assign("list",$list);
        return \view('index');
    }
    public function save(){
        $id=\input('id');
        if($id){
            $re=db('sopil')->where("id=$id")->find();
            if(!is_string(input('image'))){
                $data['image']=uploads("image");
            }
            $data['name']=input('name');
            $data['odds']=input('odds');
            $res=db('sopil')->where("id=$id")->update($data);
            if($res){
                $this->success("修改成功！");
            }else{
                $this->error("修改失败！");
            }
        }else{
            if(!is_string(input('image'))){
                $data['image']=uploads("image");
            }
     
            $data['name']=input('name');
            $data['odds']=input('odds');
            $re=db('sopil')->insert($data);
            if($re){
                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            }
        }
         
    }
    public function modifys(){
        $id=input('id');
        $re=db('sopil')->where("id=$id")->find();
        echo json_encode($re);
    }
    public function delete(){
        $id=input('id');
        $del=db("sopil")->where("id=$id")->delete();
        $this->redirect('index');
    }
    public function sort(){
        $data=input('post.');
        $lb=db('sopil');
        foreach ($data as $id => $sort){
            $lb->where(array('id' => $id ))->setField('sort' , $sort);
        }
        $this->redirect('index');
    }
    public function integ()
    {
        $re=db("score")->where("id=4")->find();
        $this->assign("re",$re);
        return \view('integ');
    }
    public function savei()
    {
        $data['integ']=\input('integ');
        $res=db("score")->where("id=4")->update($data);
        if($res){
            $this->success("保存成功");
        }else{
            $this->error("保存失败");
        }
    }
    public function lister()
    {
        $key=\input('key');
        if($key){
            $maps['phone']=array('like','%'.$key.'%');
            $re=db("user")->where($maps)->select();
            $id=array();
            if($re){
                foreach ($re as $v){
                    $id[]=$v['uid'];
                }
            }
            $map['u_id']=array("in",$id);
        }else{
            $key="";
            $map=[];
        }
        $this->assign("key",$key);
        $list=db("dial")->alias("a")->field("a.id,a.type,a.time,b.phone,c.username,c.addr,c.addrs,c.phone as phones")->where("status=0")->join("user b","a.u_id = b.uid",'left')->
        join("addr c","a.u_id=c.uid",'left')->where("a_status=1")->where($map)->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        
        $page=$list->render();
        $this->assign("page",$page);
        
        return \view('lister');
    }
    public function change()
    {
        $id=\input('id');
        $re=db("dial")->where("id=$id")->find();
        if($re['status'] == 0){
            $data['d_time']=\time();
            $data['status']=1;
            $res=db("dial")->where("id=$id")->update($data);
            $this->redirect("lister");
        }else{
            $this->redirect("lister");
        }
    }
    public function listery()
    {
        $key=\input('key');
        if($key){
            $maps['phone']=array('like','%'.$key.'%');
            $re=db("user")->where($maps)->select();
            $id=array();
            if($re){
                foreach ($re as $v){
                    $id[]=$v['uid'];
                }
            }
            $map['u_id']=array("in",$id);
        }else{
            $key="";
            $map=[];
        }
        $this->assign("key",$key);
        $list=db("dial")->alias("a")->field("a.id,a.type,a.time,a.d_time,b.phone,c.username,c.addr,c.addrs,c.phone as phones")->where("status=1")->join("user b","a.u_id = b.uid",'left')->
        join("addr c","a.u_id=c.uid",'left')->where("a_status=1")->where($map)->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
    
        $page=$list->render();
        $this->assign("page",$page);
    
        return \view('listery');
    }
    public function deletes(){
        $id=input('id');
        $del=db("dial")->where("id=$id")->delete();
        $this->redirect('listery');
    }
    
    
    
    
    
    
    
    
    
}