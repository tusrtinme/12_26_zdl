<?php
namespace app\admin\model;

use think\Model;
use function think\select;
use function think\delete;

class Goods extends Model{
    public function addType($data){
        $re=db('type')->insert($data);
        return $re;
    }
    public function updateType($id,$data){
        $res=db('type')->where("type_id=$id")->update($data);
        return $res;
    }
    public function deleteType($id){
        $re=db('type')->where("type_id=$id")->find();
        if($re){
           $del=db('type')->where("type_id=$id")->delete();
           $res=db('goods')->where("fid=$id")->select();
           if($res){
               $dels=db('goods')->where("fid=$id")->delete();
               foreach ($res as $v){
                   $rei=db('goods_img')->where("g_id={$v['gid']}")->select();
                   if($rei){
                       $deli=db('goods_img')->where("g_id={$v['gid']}")->delete();
                   }
               }
           }
           return $del;
        }else{
            return false;
        }
    }
    public function addGoods($data){
        $rea=db('goods')->insert($data);
        return $rea;
    }
    public function updateGoods($id,$data){
        $res=db('goods')->where("gid=$id")->update($data);
        return $res;
    }
    public function findGoods($id){
        $re=db('goods')->where("gid=$id")->find();
        return $re;
    }
    /**
     * 删除一条数据
     * **/
    public function deleteGoods($id){
        $re=db('goods')->where("gid=$id")->find();
        if($re){
            $del=db('goods')->where("gid=$id")->delete();
           
            $rei=db('goods_img')->where("g_id=$id")->select();
            if($rei){
                $deli=db('goods_img')->where("g_id=$id")->delete();
            }
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
            $re=db('goods')->where("gid=$v")->find();
            if($re){
                $del=db('goods')->where("gid=$v")->delete();
                
                $rei=db('goods_img')->where("g_id=$v")->select();
                if($rei){
                    $deli=db('goods_img')->where("g_id=$v")->delete();
                }
            }else{
                return false;
            }
        }
        return $del;
    }
    public function addSpec($data){
        $re=db('goods_spec')->insert($data);
        return $re;
    }
    /**
     * 修改规格
     * **/
    public function updateSpec($id,$data){
        $res=db('goods_spec')->where("sid=$id")->update($data);
        return $res;
    }
    public function addImg($data){
        $re=db('goods_img')->insert($data);
        return $re;
    }
    public function updateImg($id,$data){
        $res=db('goods_img')->where("id=$id")->update($data);
        return $res;
    }
    
}