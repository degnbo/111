<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2013 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Behavior;
/**
 *
 */
class GetcodingBehavior extends Behavior{
    public function run(&$params) {

        foreach ($_GET as $k=>$v){
            if(!is_array($v)){
                if (!mb_check_encoding($v, 'utf-8')){
                    $_GET[$k] = iconv('gbk', 'utf-8', $v);
                }
            }else{
                foreach ($_GET['_URL_'] as $key=>$value){
                    if (!mb_check_encoding($value, 'utf-8')){
                        $_GET['_URL_'][$key] = iconv('gbk', 'utf-8', $value);
                    }
                }
            }
        }

    }
}