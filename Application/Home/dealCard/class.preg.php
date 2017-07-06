<?php
/*
 * 临时使用，正式业务用的过滤我闲下来再写。
 * 未来，过滤库是分组储存，批量读取，异步过滤 ^_^Y
 * 加我好友，提出需求吧.
 */

class preg{
//定义替换规则
public $patterns;
//定义替换符号
public $replacements = '*';
//输入内容
public $subject;
public $pattern;
//替换结果
public $result;
//自动读取实例化内容
public function __construct($subject){
  require_once('base.php');
  $this -> subject = $subject;
}
public function str() {
  $this->pattern = array_combine($this -> patterns,array_fill(0,count($this -> patterns),$this -> replacements));
  $this->result = strtr($this -> subject, $this -> pattern);
  return $this -> result;
  }
}

/*//用法:

//假如GET/POST来的句子是：“约炮ee约炮4444，加QQ啪啪啪54grfg4QQ群SEX约炮”。
$str = '约炮ee约炮4444，加QQ啪啪啪54grfg4QQ群SEX约炮';  //要过滤的词，先去除非法字符再过滤
//实例化对象
$preg = new preg($str);
//对象里的替换方法
echo $preg -> str();*/
