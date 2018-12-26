<?php
namespace app\admin\controller;

class Mall extends BaseAdmin
{
    public function lister()
    {
        $list=db("mall")->order("mid desc")->paginate(10);
        $this->assign("list",$list);
        
        $page=$list->render();
        $this->assign("page",$page);
        
        return \view('lister');
    }
    public function add()
    {
        return \view('add');
    }
    public function save(){
        $data=input('post.');
        if(!is_string(input('mimage'))){
            $data['mimage']=uploads('mimage');
        }
        $re=db("mall")->insert($data);
        if($re){
            $this->success("添加成功");
        }else{
            $this->error("添加失败");
        }
    }
    public function changes(){
        $id=input('id');
        $re=db('mall')->where("mid=$id")->find();
        if($re){
            if($re['mup'] == 0){
                $res=db('mall')->where("mid=$id")->setField("mup",1);
            }
            if($re['mup'] == 1){
                $res=db('mall')->where("mid=$id")->setField("mup",0);
    
            }
            echo '0';
        }else{
            echo '1';
        }
    }
    public function modifys()
    {
        $id=\input('id');
        $re=db("mall")->where("mid=$id")->find();
        $this->assign("re",$re);
        return \view('modifys');
    }
    public function usave(){
        $id=input('mid');
        $data=input('post.');
        $re=db("mall")->where("mid=$id")->find();
    
        if($re){
            if(!is_string(input('mimage'))){
                $data['mimage']=uploads('mimage');
            }else{
                $data['mimage']=$re['mimage'];
            }
          
    
            $res=db("mall")->where("mid=$id")->update($data);
            if($res){
                $this->success("修改成功",url('lister'));
            }else{
                $this->error("修改失败");
            }
        }else{
            $this->error("参数错误");
        }
    
    }
    public function delete(){
        $id=input('id');
        $re=db("mall")->where("mid=$id")->find();
        if($re){
            $del=db("mall")->where("mid=$id")->delete();
            $this->redirect('lister');
        }else{
            $this->redirect('lister');
        }
        
    }
    public function dd()
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
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
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
        
        $list=db("mall_dd")->alias('a')->where("status=0")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("id asc")->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        $page=$list->render();
        $this->assign("page",$page);
        
        return \view('dd');
    }
    
    public function change()
    {
        $id=\input('id');
        $re=db("mall_dd")->where("id=$id")->find();
        if($re){
            $del=db("mall_dd")->where("id=$id")->setField("status",1);
           
    
            $this->redirect("dd");
        }else{
            $this->redirect("dd");
        }
    }
    public function change_f()
    {
        $id=\input('id');
        $re=db("mall_dd")->where("id=$id")->find();
        if($re){
            $del=db("mall_dd")->where("id=$id")->setField("status",2);
             
    
            $this->redirect("f_dd");
        }else{
            $this->redirect("f_dd");
        }
    }
    public function delete_dd()
    {
        $id=\input('id');
        $re=db("mall_dd")->where("id=$id")->find();
        if($re){
            $del=db("mall_dd")->where("id=$id")->delete();
            $uid=$re['uid'];
            $integ=$re['minteg'];
            $res=db("user")->where("uid=$uid")->setInc("integ",$integ);
            
            //增加积分日志
            $data['uid']=$uid;
            $data['type']="取消兑换订单退回".$integ;
            $data['integ']=$integ;
            $data['time']=\time();
            $data['status']=1;
            db("ji_log")->insert($data);
           
            $this->redirect("dd");
        }else{
            $this->redirect("dd");
        }
    }
    public function delete_f()
    {
        $id=\input('id');
        $re=db("mall_dd")->where("id=$id")->find();
        if($re){
            $del=db("mall_dd")->where("id=$id")->delete();
  
             
            $this->redirect("w_dd");
        }else{
            $this->redirect("w_dd");
        }
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
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
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
    
        $list=db("mall_dd")->alias('a')->where("status=0")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("id asc")->select();
        // var_dump($data);exit;
        vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
    
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G");
        $arrHeader =  array("订单号","金额","积分","下单时间","收货人姓名","收货人电话","收货人地址");
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        }
        //填充表格信息
        foreach($list as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['code']);
            $objActSheet->setCellValue('B'.$k, $v['mprice']);
            // 表格内容
            $objActSheet->setCellValue('C'.$k, $v['minteg']);
            $objActSheet->setCellValue('D'.$k, \date("Y-m-d H:i:s",$v['time']));
            $objActSheet->setCellValue('E'.$k, $v['username']);
            $objActSheet->setCellValue('F'.$k, $v['phone']);
            $objActSheet->setCellValue('G'.$k, $v['addr'].$v['addrs']);
    
    
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
        $objActSheet->getColumnDimension('G')->setWidth(30);
    
        if($start !=0 ){
             
            $times=($start."-".$end);
        }else{
            $times="";
        }
        $outfile = "$times"."兑换商品待付款订单".".xls";
    
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
    public function f_dd()
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
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
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
    
        $list=db("mall_dd")->alias('a')->where("status=1")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("id asc")->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        $page=$list->render();
        $this->assign("page",$page);
    
        return \view('f_dd');
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
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
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
    
        $list=db("mall_dd")->alias('a')->where("status=1")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("id asc")->select();
        // var_dump($data);exit;
        vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
    
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G");
        $arrHeader =  array("订单号","商品名称","积分","下单时间","收货人姓名","收货人电话","收货人地址");
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        }
        //填充表格信息
        foreach($list as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['code']);
            $objActSheet->setCellValue('B'.$k, $v['mname']);
            // 表格内容
            $objActSheet->setCellValue('C'.$k, $v['minteg']);
            $objActSheet->setCellValue('D'.$k, \date("Y-m-d H:i:s",$v['time']));
            $objActSheet->setCellValue('E'.$k, $v['username']);
            $objActSheet->setCellValue('F'.$k, $v['phone']);
            $objActSheet->setCellValue('G'.$k, $v['addr'].$v['addrs']);
    
    
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
        $objActSheet->getColumnDimension('G')->setWidth(30);
    
        if($start !=0 ){
             
            $times=($start."-".$end);
        }else{
            $times="";
        }
        $outfile = "$times"."兑换商品待发货订单".".xls";
    
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
    public function w_dd()
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
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
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
    
        $list=db("mall_dd")->alias('a')->where("status=2")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("id asc")->paginate(20,false,['query'=>request()->param()]);
        $this->assign("list",$list);
        $page=$list->render();
        $this->assign("page",$page);
    
        return \view('w_dd');
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
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
            }
            if($phone){
                $maps['phone']=array('like','%'.$phone.'%');
                $re=db("addr")->where($maps)->select();
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
            }
            if($addr){
                $maps['addr|addrs']=array('like','%'.$addr.'%');
                $re=db("addr")->where($maps)->select();
                if($re){
                    $id=array();
                    foreach ($re as $v){
                        $id[]=$v['aid'];
                    }
                    $map['a_id']=array("in",$id);
                }
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
    
        $list=db("mall_dd")->alias('a')->where("status=2")->where($map)->join("addr b","a.a_id = b.aid","LEFT")->order("id asc")->select();
        // var_dump($data);exit;
        vendor('PHPExcel.PHPExcel');//调用类库,路径是基于vendor文件夹的
        vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
        vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        $objExcel = new \PHPExcel();
        //set document Property
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
    
        $objActSheet = $objExcel->getActiveSheet();
        $key = ord("A");
        $letter =explode(',',"A,B,C,D,E,F,G");
        $arrHeader =  array("订单号","商品名称","积分","下单时间","收货人姓名","收货人电话","收货人地址");
        //填充表头信息
        $lenth =  count($arrHeader);
        for($i = 0;$i < $lenth;$i++) {
            $objActSheet->setCellValue("$letter[$i]1","$arrHeader[$i]");
        }
        //填充表格信息
        foreach($list as $k=>$v){
            $k +=2;
            $objActSheet->setCellValue('A'.$k,$v['code']);
            $objActSheet->setCellValue('B'.$k, $v['mname']);
            // 表格内容
            $objActSheet->setCellValue('C'.$k, $v['minteg']);
            $objActSheet->setCellValue('D'.$k, \date("Y-m-d H:i:s",$v['time']));
            $objActSheet->setCellValue('E'.$k, $v['username']);
            $objActSheet->setCellValue('F'.$k, $v['phone']);
            $objActSheet->setCellValue('G'.$k, $v['addr'].$v['addrs']);
    
    
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
        $objActSheet->getColumnDimension('G')->setWidth(30);
    
        if($start !=0 ){
             
            $times=($start."-".$end);
        }else{
            $times="";
        }
        $outfile = "$times"."兑换商品已完成订单".".xls";
    
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
    
    
    
    
    
    
    
    
    
}