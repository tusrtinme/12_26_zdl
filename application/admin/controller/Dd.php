<?php
namespace app\admin\controller;
\header("content-type:text/html;charset=utf-8;");
class Dd extends BaseAdmin
{
    public function dai_dd()
    {
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $username=\input('username');
        $addr=\input('addr');
        $phone=\input('phone');
        if($start || $code || $username || $addr || $phone){
            if($start){
                $arrdateone = strtotime($start);
                $arrdatetwo = strtotime($end . ' 23:55:55');
                $map['time'] = array(
                    array(
                        'egt',
                        $arrdateone
                    ),
                    array(
                        'elt',
                        $arrdatetwo
                    ),
                    'AND'
                );
            }
            if($code){
                $map['code']=array('like','%'.$code.'%');
            }
            if($username){
                $maps['username']=array('like','%'.$username.'%');
                $re=db("addr")->where($maps)->select();
               // if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
               // }else{
               //     $map=[];
              //  }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
              //  if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
              //  }else{
              //      $map=[];
              //  }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
             //   if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
             //   }else{
              //      $map=[];
             //   }
            }
        }else{
            
            $start="";
            $end="";
            $username="";
            $phone="";
            $addr="";
            $code="";
            $map=[];
        }
        $this->assign("start",$start);
        $this->assign("end",$end);
        $this->assign("username",$username);
        $this->assign("phone",$phone);
        $this->assign("addr",$addr);
        $this->assign("code",$code);
        
        $list=db("car_dd")->alias('a')->where("status=0 and gid=0")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("did asc")->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        $page=$list->render();
        $this->assign("page",$page);
        
        return \view('dai_dd');
    }
    public function out(){
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $username=\input('username');
        $addr=\input('addr');
        $phone=\input('phone');
        if($start || $code || $username || $addr || $phone){
            if($start){
                $arrdateone = strtotime($start);
                $arrdatetwo = strtotime($end . ' 23:55:55');
                $map['time'] = array(
                    array(
                        'egt',
                        $arrdateone
                    ),
                    array(
                        'elt',
                        $arrdatetwo
                    ),
                    'AND'
                );
            }
            if($code){
                $map['code']=array('like','%'.$code.'%');
            }
            if($username){
                $maps['username']=array('like','%'.$username.'%');
                $re=db("addr")->where($maps)->select();
               // if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
               // }else{
              //      $map=[];
              //  }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
               // if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
//                 }else{
//                     $map=[];
//                 }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
//                 if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
//                 }else{
//                     $map=[];
//                 }
            }
        }else{
            
            $start="";
            $end="";
            $username="";
            $phone="";
            $addr="";
            $code="";
            $map=[];
        }
        $this->assign("start",$start);
        $this->assign("end",$end);
        $this->assign("username",$username);
        $this->assign("phone",$phone);
        $this->assign("addr",$addr);
        $this->assign("code",$code);
        
        $list=db("car_dd")->alias('a')->where("status=0 and gid=0")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("did asc")->select();
        // var_dump($data);exit;
        vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
    
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F");
        $arrHeader =  array("订单号","订单总金额","下单时间","收货人姓名","收货人电话","收货人地址");
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        }
        //填充表格信息
        foreach($list as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['code']);
            $objActSheet->setCellValue('B'.$k, $v['zprice']);    
            // 表格内容
            $objActSheet->setCellValue('C'.$k, \date("Y-m-d H:i:s",$v['time']));
            $objActSheet->setCellValue('D'.$k, $v['username']);
            $objActSheet->setCellValue('E'.$k, $v['phone']);
            $objActSheet->setCellValue('F'.$k, $v['addr'].$v['addrs']);

    
            // 表格高度
            $objActSheet->getRowDimension($k)->setRowHeight(20);
        }
    
        $width = array(20,20,15,10,10,30,10,15,15,15);
        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(20);
        $objActSheet->getColumnDimension('C')->setWidth(25);
        $objActSheet->getColumnDimension('D')->setWidth(25);
        $objActSheet->getColumnDimension('E')->setWidth(25);
        $objActSheet->getColumnDimension('F')->setWidth(30);
  

        if($start !=0 ){
             
            $times=($start."-".$end);
        }else{
            $times="";
        }
        $outfile = "$times"."待付款订单".".xls";
    
        $userBrowser=$_SERVER['HTTP_USER_AGENT'];
        
        if(preg_match('/MSIE/i', $userBrowser)){
            $outfile=urlencode($outfile);
           
        }else{
            $outfile= iconv("utf-8","gb2312",$outfile);;
            
        }
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outfile.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }
    public function detail()
    {
        $id=\input('id');
        $re=db("car_dd")->where("did=$id")->find();
        $pay=$re['pay'];
        $res=\explode(",", $pay);
        $arr=array();
        foreach ($res as $v){
            $arr[]=db("car_dd")->alias("a")->where("code='$v'")->join("addr b","a.a_id=b.aid","left")->find();
        }
        $this->assign("list",$arr);
        return \view("detail");
    }
    public function delete()
    {
        $id=\input('id');
        $re=db("car_dd")->where("did=$id")->find();
        if($re){
            $del=db("car_dd")->where("did=$id")->delete();
            $pay=$re['pay'];
            $res=\explode(",", $pay);
            foreach ($res as $v){
                $dels=db("car_dd")->where("code='$v'")->delete();
            }
            $this->redirect("dai_dd");
        }else{
            $this->redirect("dai_dd");
        }
    }
    public function change()
    {
        $id=\input('id');
        $re=db("car_dd")->where("did=$id")->find();
        if($re){
            $del=db("car_dd")->where("did=$id")->setField("status",1);
            $pay=$re['pay'];
            $res=\explode(",", $pay);
            foreach ($res as $v){
                $dels=db("car_dd")->where("code='$v'")->setField("status",1);
            }
            
            $this->redirect("dai_dd");
        }else{
            $this->redirect("dai_dd");
        }
    }
    public function fa_dd()
    {
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $username=\input('username');
        $addr=\input('addr');
        $phone=\input('phone');
        if($start || $code || $username || $addr || $phone){
            if($start){
                $arrdateone = strtotime($start);
                $arrdatetwo = strtotime($end . ' 23:55:55');
                $map['time'] = array(
                    array(
                        'egt',
                        $arrdateone
                    ),
                    array(
                        'elt',
                        $arrdatetwo
                    ),
                    'AND'
                );
            }
            if($code){
                $map['code']=array('like','%'.$code.'%');
            }
            if($username){
                $maps['username']=array('like','%'.$username.'%');
                $re=db("addr")->where($maps)->select();
              //  if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
//                 }else{
//                     $map=[];
//                 }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
              //  if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
             //   }else{
              //      $map=[];
              //  }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
             //   if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
              //  }else{
              //      $map=[];
             //   }
            }
        }else{
        
            $start="";
            $end="";
            $username="";
            $phone="";
            $addr="";
            $code="";
            $map=[];
        }
        $this->assign("start",$start);
        $this->assign("end",$end);
        $this->assign("username",$username);
        $this->assign("phone",$phone);
        $this->assign("addr",$addr);
        $this->assign("code",$code);
        
        $list=db("car_dd")->alias('a')->where("status=1 and gid=0")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("did asc")->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        $page=$list->render();
        $this->assign("page",$page);
        
        return \view("fa_dd");
    }
    public function outf(){
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $username=\input('username');
        $addr=\input('addr');
        $phone=\input('phone');
        if($start || $code || $username || $addr || $phone){
            if($start){
                $arrdateone = strtotime($start);
                $arrdatetwo = strtotime($end . ' 23:55:55');
                $map['time'] = array(
                    array(
                        'egt',
                        $arrdateone
                    ),
                    array(
                        'elt',
                        $arrdatetwo
                    ),
                    'AND'
                );
            }
            if($code){
                $map['code']=array('like','%'.$code.'%');
            }
            if($username){
                $maps['username']=array('like','%'.$username.'%');
                $re=db("addr")->where($maps)->select();
               // if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
              //  }else{
             //       $map=[];
             //   }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
              //  if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
              //  }else{
              //      $map=[];
              //  }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
               // if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
               // }else{
               //     $map=[];
               // }
            }
        }else{
    
            $start="";
            $end="";
            $username="";
            $phone="";
            $addr="";
            $code="";
            $map=[];
        }
        $this->assign("start",$start);
        $this->assign("end",$end);
        $this->assign("username",$username);
        $this->assign("phone",$phone);
        $this->assign("addr",$addr);
        $this->assign("code",$code);
    
        $list=db("car_dd")->alias('a')->where("status=1 and gid=0")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("did asc")->select();
        // var_dump($data);exit;
        vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
    
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F");
        $arrHeader =  array("订单号","订单总金额","下单时间","收货人姓名","收货人电话","收货人地址");
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        }
        //填充表格信息
        foreach($list as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['code']);
            $objActSheet->setCellValue('B'.$k, $v['zprice']);
            // 表格内容
            $objActSheet->setCellValue('C'.$k, \date("Y-m-d H:i:s",$v['time']));
            $objActSheet->setCellValue('D'.$k, $v['username']);
            $objActSheet->setCellValue('E'.$k, $v['phone']);
            $objActSheet->setCellValue('F'.$k, $v['addr'].$v['addrs']);
    
    
            // 表格高度
            $objActSheet->getRowDimension($k)->setRowHeight(20);
        }
    
        $width = array(20,20,15,10,10,30,10,15,15,15);
        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(20);
        $objActSheet->getColumnDimension('C')->setWidth(25);
        $objActSheet->getColumnDimension('D')->setWidth(25);
        $objActSheet->getColumnDimension('E')->setWidth(25);
        $objActSheet->getColumnDimension('F')->setWidth(30);
    
    
        if($start !=0 ){
             
            $times=($start."-".$end);
        }else{
            $times="";
        }
        $outfile = "$times"."待发货订单".".xls";
    
        $userBrowser=$_SERVER['HTTP_USER_AGENT'];
    
        if(preg_match('/MSIE/i', $userBrowser)){
            $outfile=urlencode($outfile);
             
        }else{
            $outfile= iconv("utf-8","gb2312",$outfile);;
    
        }
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outfile.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }
    public function save()
    {
        $did=\input('did');
        $re=db("car_dd")->where("did=$did")->find();
        if($re){
           if($re['status'] == 1){
               $data['status']=2;
               $data['f_time']=\time();
               $res=db("car_dd")->where("did=$did")->update($data);
               
               $pay=$re['pay'];
               $arr=\explode(",", $pay);
               foreach ($arr as $v){
                   $ress=db("car_dd")->where("code='$v'")->update($data);
               }
               //增加积分
              /*  $uid=$re['uid'];
               $user=db("user")->where("uid=$uid")->find();
               if($user){
                   $money=$re['zprice'];
                   $resu=db("user")->where("uid=$uid")->setInc("integ",$money);
               
                   //增加积分日志
                   $arrs['uid']=$uid;
                   $arrs['type']="购物增加积分".$money;
                   $arrs['time']=\time();
                   $arrs['money']=$money;
                   $arrs['status']=1;
                   db("ji_log")->insert($arrs);
               
               } */
               
               $this->redirect('fa_dd');
           }else{
               $this->redirect('fa_dd');
           }
        }else{
            $this->redirect('fa_dd');
        }
    }
    public function shou_dd()
    {
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $username=\input('username');
        $addr=\input('addr');
        $phone=\input('phone');
        if($start || $code || $username || $addr || $phone){
            if($start){
                $arrdateone = strtotime($start);
                $arrdatetwo = strtotime($end . ' 23:55:55');
                $map['time'] = array(
                    array(
                        'egt',
                        $arrdateone
                    ),
                    array(
                        'elt',
                        $arrdatetwo
                    ),
                    'AND'
                );
            }
            if($code){
                $map['code']=array('like','%'.$code.'%');
            }
            if($username){
                $maps['username']=array('like','%'.$username.'%');
                $re=db("addr")->where($maps)->select();
              //  if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
             //   }else{
              //      $map=[];
             //   }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
          //      if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
           //     }else{
            //        $map=[];
            //    }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
           //     if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
          //      }else{
          //          $map=[];
           //     }
            }
        }else{
        
            $start="";
            $end="";
            $username="";
            $phone="";
            $addr="";
            $code="";
            $map=[];
        }
        $this->assign("start",$start);
        $this->assign("end",$end);
        $this->assign("username",$username);
        $this->assign("phone",$phone);
        $this->assign("addr",$addr);
        $this->assign("code",$code);
        
        $list=db("car_dd")->alias('a')->where("status=2 and gid=0")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("did asc")->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        $page=$list->render();
        $this->assign("page",$page);
        
        return \view("shou_dd");
    }
    public function outh(){
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $username=\input('username');
        $addr=\input('addr');
        $phone=\input('phone');
        if($start || $code || $username || $addr || $phone){
            if($start){
                $arrdateone = strtotime($start);
                $arrdatetwo = strtotime($end . ' 23:55:55');
                $map['time'] = array(
                    array(
                        'egt',
                        $arrdateone
                    ),
                    array(
                        'elt',
                        $arrdatetwo
                    ),
                    'AND'
                );
            }
            if($code){
                $map['code']=array('like','%'.$code.'%');
            }
            if($username){
                $maps['username']=array('like','%'.$username.'%');
                $re=db("addr")->where($maps)->select();
           //     if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
            //    }else{
            //        $map=[];
             //   }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
            //    if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
           //     }else{
           //         $map=[];
           //     }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
          //      if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
         //       }else{
         //           $map=[];
         //       }
            }
        }else{
    
            $start="";
            $end="";
            $username="";
            $phone="";
            $addr="";
            $code="";
            $map=[];
        }
        $this->assign("start",$start);
        $this->assign("end",$end);
        $this->assign("username",$username);
        $this->assign("phone",$phone);
        $this->assign("addr",$addr);
        $this->assign("code",$code);
    
        $list=db("car_dd")->alias('a')->where("status=2 and gid=0")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("did asc")->select();
        // var_dump($data);exit;
        vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
    
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F");
        $arrHeader =  array("订单号","订单总金额","下单时间","收货人姓名","收货人电话","收货人地址");
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        }
        //填充表格信息
        foreach($list as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['code']);
            $objActSheet->setCellValue('B'.$k, $v['zprice']);
            // 表格内容
            $objActSheet->setCellValue('C'.$k, \date("Y-m-d H:i:s",$v['time']));
            $objActSheet->setCellValue('D'.$k, $v['username']);
            $objActSheet->setCellValue('E'.$k, $v['phone']);
            $objActSheet->setCellValue('F'.$k, $v['addr'].$v['addrs']);
    
    
            // 表格高度
            $objActSheet->getRowDimension($k)->setRowHeight(20);
        }
    
        $width = array(20,20,15,10,10,30,10,15,15,15);
        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(20);
        $objActSheet->getColumnDimension('C')->setWidth(25);
        $objActSheet->getColumnDimension('D')->setWidth(25);
        $objActSheet->getColumnDimension('E')->setWidth(25);
        $objActSheet->getColumnDimension('F')->setWidth(30);
    
    
        if($start !=0 ){
             
            $times=($start."-".$end);
        }else{
            $times="";
        }
        $outfile = "$times"."待收货订单".".xls";
    
        $userBrowser=$_SERVER['HTTP_USER_AGENT'];
    
        if(preg_match('/MSIE/i', $userBrowser)){
            $outfile=urlencode($outfile);
             
        }else{
            $outfile= iconv("utf-8","gb2312",$outfile);;
    
        }
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outfile.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }
    public function ping_dd()
    {
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $username=\input('username');
        $addr=\input('addr');
        $phone=\input('phone');
        if($start || $code || $username || $addr || $phone){
            if($start){
                $arrdateone = strtotime($start);
                $arrdatetwo = strtotime($end . ' 23:55:55');
                $map['time'] = array(
                    array(
                        'egt',
                        $arrdateone
                    ),
                    array(
                        'elt',
                        $arrdatetwo
                    ),
                    'AND'
                );
            }
            if($code){
                $map['code']=array('like','%'.$code.'%');
            }
            if($username){
                $maps['username']=array('like','%'.$username.'%');
                $re=db("addr")->where($maps)->select();
           //     if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
          //      }else{
          //          $map=[];
           //     }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
         //       if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
         //       }else{
         //           $map=[];
         //       }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
          //      if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
           //     }else{
           //         $map=[];
           //     }
            }
        }else{
    
            $start="";
            $end="";
            $username="";
            $phone="";
            $addr="";
            $code="";
            $map=[];
        }
        $this->assign("start",$start);
        $this->assign("end",$end);
        $this->assign("username",$username);
        $this->assign("phone",$phone);
        $this->assign("addr",$addr);
        $this->assign("code",$code);
    
        $list=db("car_dd")->alias('a')->where("status=3 and gid=0")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("did asc")->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        $page=$list->render();
        $this->assign("page",$page);
    
        return \view("ping_dd");
    }
    public function outp(){
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $username=\input('username');
        $addr=\input('addr');
        $phone=\input('phone');
        if($start || $code || $username || $addr || $phone){
            if($start){
                $arrdateone = strtotime($start);
                $arrdatetwo = strtotime($end . ' 23:55:55');
                $map['time'] = array(
                    array(
                        'egt',
                        $arrdateone
                    ),
                    array(
                        'elt',
                        $arrdatetwo
                    ),
                    'AND'
                );
            }
            if($code){
                $map['code']=array('like','%'.$code.'%');
            }
            if($username){
                $maps['username']=array('like','%'.$username.'%');
                $re=db("addr")->where($maps)->select();
            //    if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
            //    }else{
            //        $map=[];
            //    }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
              //  if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
              //  }else{
              //      $map=[];
              //  }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
              //  if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
              //  }else{
             //       $map=[];
             //   }
            }
        }else{
    
            $start="";
            $end="";
            $username="";
            $phone="";
            $addr="";
            $code="";
            $map=[];
        }
        $this->assign("start",$start);
        $this->assign("end",$end);
        $this->assign("username",$username);
        $this->assign("phone",$phone);
        $this->assign("addr",$addr);
        $this->assign("code",$code);
    
        $list=db("car_dd")->alias('a')->where("status=3 and gid=0")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("did asc")->select();
        // var_dump($data);exit;
        vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
    
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F");
        $arrHeader =  array("订单号","订单总金额","下单时间","收货人姓名","收货人电话","收货人地址");
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        }
        //填充表格信息
        foreach($list as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['code']);
            $objActSheet->setCellValue('B'.$k, $v['zprice']);
            // 表格内容
            $objActSheet->setCellValue('C'.$k, \date("Y-m-d H:i:s",$v['time']));
            $objActSheet->setCellValue('D'.$k, $v['username']);
            $objActSheet->setCellValue('E'.$k, $v['phone']);
            $objActSheet->setCellValue('F'.$k, $v['addr'].$v['addrs']);
    
    
            // 表格高度
            $objActSheet->getRowDimension($k)->setRowHeight(20);
        }
    
        $width = array(20,20,15,10,10,30,10,15,15,15);
        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(20);
        $objActSheet->getColumnDimension('C')->setWidth(25);
        $objActSheet->getColumnDimension('D')->setWidth(25);
        $objActSheet->getColumnDimension('E')->setWidth(25);
        $objActSheet->getColumnDimension('F')->setWidth(30);
    
    
        if($start !=0 ){
             
            $times=($start."-".$end);
        }else{
            $times="";
        }
        $outfile = "$times"."待评价订单".".xls";
    
        $userBrowser=$_SERVER['HTTP_USER_AGENT'];
    
        if(preg_match('/MSIE/i', $userBrowser)){
            $outfile=urlencode($outfile);
             
        }else{
            $outfile= iconv("utf-8","gb2312",$outfile);;
    
        }
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outfile.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }
    public function wan_dd()
    {
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $username=\input('username');
        $addr=\input('addr');
        $phone=\input('phone');
        if($start || $code || $username || $addr || $phone){
            if($start){
                $arrdateone = strtotime($start);
                $arrdatetwo = strtotime($end . ' 23:55:55');
                $map['time'] = array(
                    array(
                        'egt',
                        $arrdateone
                    ),
                    array(
                        'elt',
                        $arrdatetwo
                    ),
                    'AND'
                );
            }
            if($code){
                $map['code']=array('like','%'.$code.'%');
            }
            if($username){
                $maps['username']=array('like','%'.$username.'%');
                $re=db("addr")->where($maps)->select();
           //     if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
            //    }else{
            //        $map=[];
             //   }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
            //    if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
            //    }else{
            //        $map=[];
             //   }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
              //  if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
             //   }else{
             //       $map=[];
             //   }
            }
        }else{
    
            $start="";
            $end="";
            $username="";
            $phone="";
            $addr="";
            $code="";
            $map=[];
        }
        $this->assign("start",$start);
        $this->assign("end",$end);
        $this->assign("username",$username);
        $this->assign("phone",$phone);
        $this->assign("addr",$addr);
        $this->assign("code",$code);
    
        $list=db("car_dd")->alias('a')->where("status=4 and gid=0")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("did asc")->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        $page=$list->render();
        $this->assign("page",$page);
    
        return \view("wan_dd");
    }
    public function outw(){
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $username=\input('username');
        $addr=\input('addr');
        $phone=\input('phone');
        if($start || $code || $username || $addr || $phone){
            if($start){
                $arrdateone = strtotime($start);
                $arrdatetwo = strtotime($end . ' 23:55:55');
                $map['time'] = array(
                    array(
                        'egt',
                        $arrdateone
                    ),
                    array(
                        'elt',
                        $arrdatetwo
                    ),
                    'AND'
                );
            }
            if($code){
                $map['code']=array('like','%'.$code.'%');
            }
            if($username){
                $maps['username']=array('like','%'.$username.'%');
                $re=db("addr")->where($maps)->select();
             //   if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
             //   }else{
             //       $map=[];
             //   }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
             //   if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
             //   }else{
              //      $map=[];
             //   }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
             //   if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
             //   }else{
             //       $map=[];
             //   }
            }
        }else{
    
            $start="";
            $end="";
            $username="";
            $phone="";
            $addr="";
            $code="";
            $map=[];
        }
        $this->assign("start",$start);
        $this->assign("end",$end);
        $this->assign("username",$username);
        $this->assign("phone",$phone);
        $this->assign("addr",$addr);
        $this->assign("code",$code);
    
        $list=db("car_dd")->alias('a')->where("status=4 and gid=0")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("did asc")->select();
        // var_dump($data);exit;
        vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
    
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F");
        $arrHeader =  array("订单号","订单总金额","下单时间","收货人姓名","收货人电话","收货人地址");
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        }
        //填充表格信息
        foreach($list as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['code']);
            $objActSheet->setCellValue('B'.$k, $v['zprice']);
            // 表格内容
            $objActSheet->setCellValue('C'.$k, \date("Y-m-d H:i:s",$v['time']));
            $objActSheet->setCellValue('D'.$k, $v['username']);
            $objActSheet->setCellValue('E'.$k, $v['phone']);
            $objActSheet->setCellValue('F'.$k, $v['addr'].$v['addrs']);
    
    
            // 表格高度
            $objActSheet->getRowDimension($k)->setRowHeight(20);
        }
    
        $width = array(20,20,15,10,10,30,10,15,15,15);
        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(20);
        $objActSheet->getColumnDimension('C')->setWidth(25);
        $objActSheet->getColumnDimension('D')->setWidth(25);
        $objActSheet->getColumnDimension('E')->setWidth(25);
        $objActSheet->getColumnDimension('F')->setWidth(30);
    
    
        if($start !=0 ){
             
            $times=($start."-".$end);
        }else{
            $times="";
        }
        $outfile = "$times"."已完成订单".".xls";
    
        $userBrowser=$_SERVER['HTTP_USER_AGENT'];
    
        if(preg_match('/MSIE/i', $userBrowser)){
            $outfile=urlencode($outfile);
             
        }else{
            $outfile= iconv("utf-8","gb2312",$outfile);;
    
        }
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outfile.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }
    public function tui_dd()
    {
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $username=\input('username');
        $addr=\input('addr');
        $phone=\input('phone');
        if($start || $code || $username || $addr || $phone){
            if($start){
                $arrdateone = strtotime($start);
                $arrdatetwo = strtotime($end . ' 23:55:55');
                $map['time'] = array(
                    array(
                        'egt',
                        $arrdateone
                    ),
                    array(
                        'elt',
                        $arrdatetwo
                    ),
                    'AND'
                );
            }
            if($code){
                $map['code']=array('like','%'.$code.'%');
            }
            if($username){
                $maps['username']=array('like','%'.$username.'%');
                $re=db("addr")->where($maps)->select();
           //     if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
            //    }else{
            //        $map=[];
            //    }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
           //     if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
           //     }else{
           //         $map=[];
            //    }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
          //      if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
          //      }else{
          //          $map=[];
          //      }
            }
        }else{
    
            $start="";
            $end="";
            $username="";
            $phone="";
            $addr="";
            $code="";
            $map=[];
        }
        $this->assign("start",$start);
        $this->assign("end",$end);
        $this->assign("username",$username);
        $this->assign("phone",$phone);
        $this->assign("addr",$addr);
        $this->assign("code",$code);
    
        $list=db("car_dd")->alias('a')->where("status=5 and gid=0")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("did asc")->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        $page=$list->render();
        $this->assign("page",$page);
    
        return \view("tui_dd");
    }
    public function ytui_dd()
    {
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $username=\input('username');
        $addr=\input('addr');
        $phone=\input('phone');
        if($start || $code || $username || $addr || $phone){
            if($start){
                $arrdateone = strtotime($start);
                $arrdatetwo = strtotime($end . ' 23:55:55');
                $map['time'] = array(
                    array(
                        'egt',
                        $arrdateone
                    ),
                    array(
                        'elt',
                        $arrdatetwo
                    ),
                    'AND'
                );
            }
            if($code){
                $map['code']=array('like','%'.$code.'%');
            }
            if($username){
                $maps['username']=array('like','%'.$username.'%');
                $re=db("addr")->where($maps)->select();
            //    if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
           //     }else{
           //         $map=[];
            //    }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
            //    if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
            //    }else{
            //        $map=[];
            //    }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
            //    if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
            //    }else{
            //        $map=[];
            //    }
            }
        }else{
    
            $start="";
            $end="";
            $username="";
            $phone="";
            $addr="";
            $code="";
            $map=[];
        }
        $this->assign("start",$start);
        $this->assign("end",$end);
        $this->assign("username",$username);
        $this->assign("phone",$phone);
        $this->assign("addr",$addr);
        $this->assign("code",$code);
    
        $list=db("car_dd")->alias('a')->where("status=6 and gid=0")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("did asc")->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        $page=$list->render();
        $this->assign("page",$page);
    
        return \view("ytui_dd");
    }
    public function tong()
    {
        $did=\input('id');
        $re=db("car_dd")->where("did=$did")->find();
        if($re['status'] == 5){
            $res=db("car_dd")->where("did=$did")->setField("status",6);
            $pay=$re['pay'];
            $pays=\explode(",", $pay);
            foreach ($pays as $v){
                db("car_dd")->where("code='$v'")->setField("status",6);
            }
            $this->redirect("tui_dd");
        }else{
            $this->redirect("tui_dd");
        }
    }
    public function bo()
    {
        $did=\input('id');
        $re=db("car_dd")->where("did=$did")->find();
        if($re['status'] == 5){
            $res=db("car_dd")->where("did=$did")->setField("status",1);
            $pay=$re['pay'];
            $pays=\explode(",", $pay);
            foreach ($pays as $v){
                db("car_dd")->where("code='$v'")->setField("status",1);
            }
            $this->redirect("tui_dd");
        }else{
            $this->redirect("tui_dd");
        }
    }
    public function outs(){
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $username=\input('username');
        $addr=\input('addr');
        $phone=\input('phone');
        if($start || $code || $username || $addr || $phone){
            if($start){
                $arrdateone = strtotime($start);
                $arrdatetwo = strtotime($end . ' 23:55:55');
                $map['time'] = array(
                    array(
                        'egt',
                        $arrdateone
                    ),
                    array(
                        'elt',
                        $arrdatetwo
                    ),
                    'AND'
                );
            }
            if($code){
                $map['code']=array('like','%'.$code.'%');
            }
            if($username){
                $maps['username']=array('like','%'.$username.'%');
                $re=db("addr")->where($maps)->select();
           //     if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
            //    }else{
            //        $map=[];
            //    }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
           //     if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
           //     }else{
            //        $map=[];
            //    }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
            //    if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
            //    }else{
            //        $map=[];
             //   }
            }
        }else{
    
            $start="";
            $end="";
            $username="";
            $phone="";
            $addr="";
            $code="";
            $map=[];
        }
        $this->assign("start",$start);
        $this->assign("end",$end);
        $this->assign("username",$username);
        $this->assign("phone",$phone);
        $this->assign("addr",$addr);
        $this->assign("code",$code);
    
        $list=db("car_dd")->alias('a')->where("status=5 and gid=0")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("did asc")->select();
        // var_dump($data);exit;
        vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
    
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G,H");
        $arrHeader =  array("订单号","订单总金额","下单时间","收货人姓名","收货人电话","收货人地址","申请退货时间","退货原因");
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        }
        //填充表格信息
        foreach($list as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['code']);
            $objActSheet->setCellValue('B'.$k, $v['zprice']);
            // 表格内容
            $objActSheet->setCellValue('C'.$k, \date("Y-m-d H:i:s",$v['time']));
            $objActSheet->setCellValue('D'.$k, $v['username']);
            $objActSheet->setCellValue('E'.$k, $v['phone']);
            $objActSheet->setCellValue('F'.$k, $v['addr'].$v['addrs']);
            $objActSheet->setCellValue('G'.$k, \date("Y-m-d H:i:s",$v['t_time']));
            $objActSheet->setCellValue('H'.$k, $v['cencal']);
    
    
            // 表格高度
            $objActSheet->getRowDimension($k)->setRowHeight(20);
        }
    
        $width = array(20,20,15,10,10,30,10,15,15,15);
        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(20);
        $objActSheet->getColumnDimension('C')->setWidth(25);
        $objActSheet->getColumnDimension('D')->setWidth(25);
        $objActSheet->getColumnDimension('E')->setWidth(25);
        $objActSheet->getColumnDimension('F')->setWidth(30);
        $objActSheet->getColumnDimension('G')->setWidth(25);
        $objActSheet->getColumnDimension('H')->setWidth(30);
    
        if($start !=0 ){
             
            $times=($start."-".$end);
        }else{
            $times="";
        }
        $outfile = "$times"."申请退货订单".".xls";
    
        $userBrowser=$_SERVER['HTTP_USER_AGENT'];
    
        if(preg_match('/MSIE/i', $userBrowser)){
            $outfile=urlencode($outfile);
             
        }else{
            $outfile= iconv("utf-8","gb2312",$outfile);;
    
        }
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outfile.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }
    
    public function outsy(){
        $start=input('start');
        $end=input('end');
        $code=\input('code');
        $username=\input('username');
        $addr=\input('addr');
        $phone=\input('phone');
        if($start || $code || $username || $addr || $phone){
            if($start){
                $arrdateone = strtotime($start);
                $arrdatetwo = strtotime($end . ' 23:55:55');
                $map['time'] = array(
                    array(
                        'egt',
                        $arrdateone
                    ),
                    array(
                        'elt',
                        $arrdatetwo
                    ),
                    'AND'
                );
            }
            if($code){
                $map['code']=array('like','%'.$code.'%');
            }
            if($username){
                $maps['username']=array('like','%'.$username.'%');
                $re=db("addr")->where($maps)->select();
              //  if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
             //   }else{
            //        $map=[];
             //   }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
           //     if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
            //    }else{
            //        $map=[];
            //    }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
            //    if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
            //    }else{
            //        $map=[];
            //    }
            }
        }else{
    
            $start="";
            $end="";
            $username="";
            $phone="";
            $addr="";
            $code="";
            $map=[];
        }
        $this->assign("start",$start);
        $this->assign("end",$end);
        $this->assign("username",$username);
        $this->assign("phone",$phone);
        $this->assign("addr",$addr);
        $this->assign("code",$code);
    
        $list=db("car_dd")->alias('a')->where("status=6 and gid=0")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("did asc")->select();
        // var_dump($data);exit;
        vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
    
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G,H");
        $arrHeader =  array("订单号","订单总金额","下单时间","收货人姓名","收货人电话","收货人地址","申请退货时间","退货原因");
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        }
        //填充表格信息
        foreach($list as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['code']);
            $objActSheet->setCellValue('B'.$k, $v['zprice']);
            // 表格内容
            $objActSheet->setCellValue('C'.$k, \date("Y-m-d H:i:s",$v['time']));
            $objActSheet->setCellValue('D'.$k, $v['username']);
            $objActSheet->setCellValue('E'.$k, $v['phone']);
            $objActSheet->setCellValue('F'.$k, $v['addr'].$v['addrs']);
            $objActSheet->setCellValue('G'.$k, \date("Y-m-d H:i:s",$v['t_time']));
            $objActSheet->setCellValue('H'.$k, $v['cencal']);
    
    
            // 表格高度
            $objActSheet->getRowDimension($k)->setRowHeight(20);
        }
    
        $width = array(20,20,15,10,10,30,10,15,15,15);
        //设置表格的宽度
        $objActSheet->getColumnDimension('A')->setWidth(20);
        $objActSheet->getColumnDimension('B')->setWidth(20);
        $objActSheet->getColumnDimension('C')->setWidth(25);
        $objActSheet->getColumnDimension('D')->setWidth(25);
        $objActSheet->getColumnDimension('E')->setWidth(25);
        $objActSheet->getColumnDimension('F')->setWidth(30);
        $objActSheet->getColumnDimension('G')->setWidth(25);
        $objActSheet->getColumnDimension('H')->setWidth(30);
    
        if($start !=0 ){
             
            $times=($start."-".$end);
        }else{
            $times="";
        }
        $outfile = "$times"."已退货订单".".xls";
    
        $userBrowser=$_SERVER['HTTP_USER_AGENT'];
    
        if(preg_match('/MSIE/i', $userBrowser)){
            $outfile=urlencode($outfile);
             
        }else{
            $outfile= iconv("utf-8","gb2312",$outfile);;
    
        }
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outfile.'"');
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }
    public function updates()
    {
        $id=\input('id');
        $re=db("car_dd")->where("did=$id")->find();
        $this->assign("re",$re);
        return \view("updates");
    }
    public function saved()
    {
        $did=input('did');
        $data=\input('post.');
        $re=db("car_dd")->where("did=$did")->find();
        if($re){
            $res=db("car_dd")->where("did=$did")->update($data);
            if($res){
                $this->success("修改成功",url('dai_dd'));
            }else{
                $this->error("修改失败",url('dai_dd'));
            }
        }else{
            $this->error("非法操作",url('dai_dd'));
        }
    }
    
    
    
    
    
    
    
    
    
    
}