<?php
namespace app\admin\controller;

use app\admin\model\Lb as LbModel;
class Lb extends BaseAdmin{
    private $model = "";
    public function _initialize(){
        parent::_initialize();
        $this->model = new LbModel;
    }
    public function place(){
        $list = Db('lb_place')->order('pl_id desc')->paginate(10);
        $this->assign("list",$list);
        return view('place');
    }
    public function save_place(){
        if($this->request->isAjax()){
            $id=$_POST['id'];
            if($id){
                $data['pl_name']=$_POST['name'];
                $res=$this->model->updatePlace("pl_id=$id",$data);
                if($res){
                    $this->success("修改成功！",url('Lb/place'));
                }else{
                    $this->error("修改失败！",url('Lb/place'));
                }
            }else{
                $data['pl_name']=$_POST['name'];
                $re=$this->model->addPlace($data);
                if($re){
                    $this->success("添加成功！",url('Lb/place'));
                }else{
                    $this->error("添加失败！",url('Lb/place'));
                } 
            }
            
        }else{
            $this->success("非法提交",url('Lb/place'));
        }
    }
    public function modify(){
        $id=input('id');
        $re=db('lb_place')->where("pl_id=$id")->find();
        echo json_encode($re);
    }
    public function lister(){
        $res=db('lb_place')->select();
        $this->assign("res",$res);
        
        $list=db('lb')->alias('a')->join('lb_place w','a.fid = w.pl_id')->order(['sort' => 'asc', 'id' => 'desc'])->paginate(10);
        $this->assign("list",$list);
        
        return view('lister');
    }
    public function save(){
        $id=\input('id');
        if($id){
           $re=db('lb')->where("id=$id")->find();
           if(!is_string(input('image'))){
               $data['image']=uploads("image");
              
           }
           
           if(input('status')){
               $data['status']=1;
           }
           $data['name']=input('name');
           $data['fid']=input('fid');
           $data['sort']=input('sort');
           $data['url']=input('url');
           $data['desc']=input('desc');
           $res=db('lb')->where("id=$id")->update($data);
           if($res){
               $this->success("修改成功！");
           }else{
               $this->error("修改失败！");
           }
        }else{
            if(!is_string(input('image'))){
                $data['image']=uploads("image");
            }
            if(input('status')){
                $data['status']=1;
            }
            $data['name']=input('name');
            $data['fid']=input('fid');
            $data['sort']=input('sort');
            $data['url']=input('url');
            $data['desc']=input('desc');
            $re=$this->model->addLb($data);
            if($re){
                $this->success("添加成功！");
            }else{
                $this->error("添加失败！");
            } 
        }
         
    }
    public function change(){
        $id=$_POST['id'];
        $res=$this->model->updateStatus($id);
        if($res){
            echo '1';
        }else{
            echo '0';
        }
    }
    public function sort(){
        $data=input('post.');
        $lb=db('lb');
        foreach ($data as $id => $sort){
            $lb->where(array('id' => $id ))->setField('sort' , $sort);
        }
        $this->redirect('lister');
    }
    public function delete(){
        $id=input('id');
        $del=$this->model->deleteLb($id);
        $this->redirect('lister');
    }
    public function delete_all(){
        $id=input('id');
        $arr=explode(",", $id);
        $del=$this->model->deleteAll($arr);
        $this->redirect('lister');
    }
    public function modifys(){
        $id=$_GET['id'];
        $re=db('lb')->where("id=$id")->find();
        echo json_encode($re);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}