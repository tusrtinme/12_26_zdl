<?php
namespace app\admin\controller;

class Sys extends BaseAdmin{
    function seting(){
        $re=db('sys')->where("id=1")->find();
        $this->assign("re",$re);
        return view('seting');
    }
    public function save(){
       if($this->request->isAjax()){

          if(!is_string(input('pclogo'))){

              $data['pclogo']=uploads("pclogo");
          }
          if(!is_string(input('waplogo'))){
              $data['waplogo']=uploads("waplogo");
          }
          if(!is_string(input('qrcode'))){
              $data['qrcode']=uploads("qrcode");
          }
          if(!is_string(input('wx'))){
              $data['wx']=uploads("wx");
          }
          
           $data['name']=input('name');
           $data['username']=input('username');
           $data['url']=input('url');
           $data['qq']=input('qq');
           $data['icp']=input('icp');
           $data['email']=input('email');
           $data['phone']=input('phone');
           $data['tel']=input('tel');
           $data['longs']=input('longs');
           $data['lats']=input('lats');
           $data['addr']=input('addr');
           $data['content']=input('content');
           $data['fax']=input('fax');
           $data['telphone']=input('telphone');
           
           $re=db('sys')->where("id=1")->update($data);
           if($re){
               $this->success("修改成功！");
           }else{
               $this->error("修改失败！");
           }
           
       }else{
           $this->error("非法提交");
       }
        
    }
    function seo(){
        $re=db('seo')->where("id=1")->find();
        $this->assign("re",$re);
        return view('seo');
    }
    function saves(){
        if($this->request->isAjax()){
            $data=input('post.');
            $res=db('seo')->where("id=1")->update($data);
            if($res){
                $this->success("修改成功！",url('Sys/seo'));
            }else{
                $this->error("修改失败！");
            }
            
        }else{
            $this->error("非法操作");
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}