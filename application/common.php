<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function uploads($image){
    $file = request()->file("$image");
    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
    $pa=$info->getSaveName();
    $path=str_replace("\\", "/", $pa);
    $paths='/uploads/'.$path;
    $images=\think\Image::open(ROOT_PATH.'/public'.$paths);
    $images->save(ROOT_PATH.'/public'.$paths,null,60,true);
    return $paths;
}
