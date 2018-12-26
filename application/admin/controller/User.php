<?php
namespace app\admin\controller;

class User extends BaseAdmin
{
    public function index()
    {
        $phone=\input('phone');
        if($phone){
            $map['phone']=array("like",'%'.$phone.'%');
        }else{
            $phone="";
            $map=[];
        }
        $this->assign("phone",$phone);
        $list=db("user")->order("time desc")->where($map)->paginate(10,false,['query'=>request()->param()]); 
        $this->assign("list",$list);
        
        $page=$list->render();
        $this->assign("page",$page);
        
        return \view('index');
    }
    //增加用户待返积分
    public function add_integz()
    {
       if($this->request->isAjax()){
           $uid=\input('id');
           $integz=\input("integz");
           $re=db("user")->where("uid=$uid")->find();
           if($re){
               $res=db("user")->where("uid=$uid")->setInc("integz",$integz);
               
               $data['uid']=$uid;
               $data['type']="系统赠送待返积分".$integz;
               $data['integ']=$integz;
               $data['time']=\time();
               $data['status']=0;
               db("ji_log")->insert($data);
               if($res){
                   echo '1';
               }else{
                   echo '2';
               }
           }else{
               echo '3';
           }
       }else{
           echo '0';
       }
       
    }
    //增加用户每日释放积分
    public function add_integf()
    {
        if($this->request->isAjax()){
            $uid=\input('id');
            $integz=\input("integz");
            $re=db("user")->where("uid=$uid")->find();
            if($re){
                $res=db("user")->where("uid=$uid")->setInc("integf",$integz);
             
                if($res){
                    echo '1';
                }else{
                    echo '2';
                }
            }else{
                echo '3';
            }
        }else{
            echo '0';
        }
         
    }
    //增加用户积分
    public function add_integ()
    {
        if($this->request->isAjax()){
            $uid=\input('id');
            $integz=\input("integz");
            $re=db("user")->where("uid=$uid")->find();
            if($re){
                $res=db("user")->where("uid=$uid")->setInc("integ",$integz);
                 
                $data['uid']=$uid;
                $data['type']="系统赠送积分".$integz;
                $data['money']=$integz;
                $data['time']=\time();
                $data['status']=1;
                db("ji_log")->insert($data);
                if($res){
                    echo '1';
                }else{
                    echo '2';
                }
            }else{
                echo '3';
            }
        }else{
            echo '0';
        }
         
    }
    //减少用户积分
    public function jian_integ()
    {
        if($this->request->isAjax()){
            $uid=\input('id');
            $integz=\input("integz");
            $re=db("user")->where("uid=$uid")->find();
            $integ=$re['integ'];
            if($integz <= $integ){
                if($re){
                    $res=db("user")->where("uid=$uid")->setDec("integ",$integz);
                     
                    $data['uid']=$uid;
                    $data['type']="系统减少积分".$integz;
                    $data['money']=$integz;
                    $data['time']=\time();
                    $data['status']=1;
                    db("ji_log")->insert($data);
                    if($res){
                        echo '1';
                    }else{
                        echo '2';
                    }
                }else{
                    echo '3';
                }
            }else{
                echo '4';
            }
            
        }else{
            echo '0';
        }
         
    }
    //积分明细
    public function detail()
    {
        $id=\input('id');
        $list=db("ji_log")->where("uid=$id")->order("id desc")->paginate(10,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        
        $page=$list->render();
        $this->assign("page",$page);
        
        return \view('detail');
    }
    //下级代理
    public function lister()
    {
        $phone=\input('phone');
        if($phone){
            $map['phone']=array("like",'%'.$phone.'%');
        }else{
            $phone="";
            $map=[];
        }
        $this->assign("phone",$phone);
        $id=\input('id');
        $list=db("user")->order("uid desc")->where("fid=$id")->where($map)->paginate(10,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        
        $page=$list->render();
        $this->assign("page",$page);
        
        return \view("lister");
    }
    //删除会员
    public function delete()
    {
        $id=\input('id');
        $re=db("user")->where("uid=$id")->find();
        if($re){
            $del=db("user")->where("uid=$id")->delete();
           
            $this->redirect('index');
        }else{
            $this->redirect('index');
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
}