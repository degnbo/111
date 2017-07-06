<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class TestController extends Controller {
    public function index(){
        $a="goods/20160307/sm_56dd52e382386.jpg";
        $b=str_replace('sm_','',$a);
        echo $b;
       /* $a=634;
        $b=456;
        $c=floor($a/$b);
        echo $c;*/
       /* $a=array(array(
            'name'=>'a',
        ),array(
            'name'=>'b',
        ));
        static $k=7;
        foreach($a as $v){
            $c[]=array_fill($k,1,$v['name']);
            $k++;
        }
        dump($c);*/

        /*$arr=array();
        foreach($a as $k=>$v){
            $arr[$k+1]=$v;
        }
        $v1= array("color" => "blue",
            "size"  => "medium",
            "shape" => "sphere");
        extract($v1);
        echo $size;*/

        //dump($arr);
    }
}