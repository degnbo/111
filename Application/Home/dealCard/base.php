<?php
$data=array();
$myfile=fopen("card.txt",'r');
while(!feof($myfile)) {
    $str=fgets($myfile);
    //echo gettype($str);
    if(!mb_check_encoding($str, 'utf-8')){
        $str = iconv('gbk', 'utf-8', $str);
    }
    $data[]=trim($str);
}
fclose($myfile);
//var_dump($data);
$this->patterns =$data;
/*
 * 名称：过滤库
 * 用法：单引号把词汇括起来！
 * 说明：这个过滤库只能临时救急，不能用来做正式业务，由于不完善，就不写名字了。
 *      有空时改进一下，加入“防注入过滤”，“识图过滤”和“违禁词分类过滤”，
 *      加我好友，提出需求，会很快添加上去，各位等等吧。
 * 备注：不删除注释是个好习惯
 */

/*'SEX',
'啪啪啪',
'同城约',
'约炮',*/

