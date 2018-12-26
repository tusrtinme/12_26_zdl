<?php
namespace app\admin\model;

use think\Model;

class Lb extends Model{
    /**
     * 新增广告位
     * **/
    public function addPlace($data){
        $re=db('lb_place')->insert($data);
        return $re;
    }
    /**
     * 修改广告位
     * */
    public function updatePlace($where,$data){
        $res=db('lb_place')->where("$where")->update($data);
        return $res;
    }
    /**
     * 添加广告
     * */
    public function addLb($data){
        $re=db('lb')->insert($data);
        return $re;
    }
    /**
     * 修改状态
     * */
    public function updateStatus($id){
        $re=db("lb")->where("id=$id")->find();
        if($re){
            if($re['status'] == 1){
                $res=db('lb')->where("id=$id")->setField("status",0);
                return $res;
            }elseif($re['status'] == 0){
                $res=db('lb')->where("id=$id")->setField("status",1);
                return $res;
            }
        }else{
            return false;
        }
    }
    /**
     * 删除一条数据
     * **/
    public function deleteLb($id){
        $re=db('lb')->where("id=$id")->find();
        if($re){
            $del=db('lb')->where("id=$id")->delete();
            return $del;
        }else{
            return false;
        }
    }
    /**
     * 删除多条数据
     * */
    public function deleteAll($arr){
        foreach ($arr as $v){
            $re=db('lb')->where("id=$v")->find();
            if($re){
                $del=db('lb')->where("id=$v")->delete();
                
            }else{
                return false;
            }
        }
        return $del;
    }
    
    
}