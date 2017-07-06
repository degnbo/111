<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller {
    public function _initialize(){
        $msg=array('model'=>CONTROLLER_NAME,'name'=>session('admin_us'),'action'=>ACTION_NAME);
        tp_log($msg);
        header('content-type:text/html;charset=utf-8');
        if(!session("?admin_id")) {
            $this->success('你还没有登录呢！',U('Login/login'),1);
            exit;
        }
        $model=D('Admin');
        if(!$model->checkqx()){
            $this->success('你没权访问',U('Index/index'),1);
            exit;
        }
    }
    public function _empty(){
        $this->redirect("Index/index");
    }
    public function getTree($model="Category",$px='sort_num',$sx="desc"){
        //$data=$this->where(array('pid'=>0))->order('sort_num asc')->select();
        $data=M($model)->order($px." ".$sx)->select();
        //dump($data);die;
        return $this->resort($data);
    }
    public function resort($data,$pid=0,$level=0,$clear=true){
        static $arr=array();
        if($clear){
            $arr=array();
        }
        foreach($data as $key=>$val){
            if($val['pid']==$pid){
                $val['level']=$level;
                $arr[]=$val;
                $this->resort($data,$val['id'],$level+1,false);
            }
        }
        return $arr;
    }
    public function search($cid='',$pageSize=10,$model='Article'){
        /************************************* 翻页 ****************************************/
        $model=M($model);
        if($cid){
            $where['cate_id']=$cid;
        }
        $goodsName = bian(trim(I('get.keyword')));
        if($goodsName){
            $data['des'] = array('like', "%$goodsName%");
            $data['title'] = array('like', "%$goodsName%");
            $data['keywords'] = array('like', "%$goodsName%");
            $data['content'] = array('like', "%$goodsName%");
            //$data['goods_desc'] = array('like', "%$goodsName%");
            $data['_logic']='or';
            $where['_complex'] = $data;
        }
        $tid=I('get.type_id','');
        if($tid){
            $where['type_id']=$tid;
        }
        /*$tp = I('get.tp'); //总价格
        if($tp){
            $where['total_price'] = $tp;
        }
        $orderby = 'id';  // 默认排序的字段
        $orderway = 'asc';  // 默认排序方式
        $odby = I('get.orderby');
        if($odby == 'id_asc')
            $orderway = 'asc';
        if($odby == 'id_desc')
            $orderway = 'desc';*/
        $count = $model->where($where)->count();
        //echo $this->getLastSql();
        //echo $count;exit;
        if(!$count){
            $data['page']='无查询数据!';
            //$data['data'] = $this->alias('a')->where($where)->group('a.id')->select();
        }else{
            $page = new \Think\Page($count, $pageSize);
            $page->rollPage=6;
            $page->setConfig('theme','%HEADER% %FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span>当前是第%NOW_PAGE%页 总共%TOTAL_PAGE%页</span>');
            // 配置翻页的样式
            $page->setConfig('prev', '上一页');
            $page->setConfig('next', '下一页');
            $data['page'] = $page->show();
            /************************************** 取数据 ******************************************/
            $data['data'] = $model->where($where)->order("sort_num desc")->limit($page->firstRow.','.$page->listRows)->select();
            ///echo $this->getLastSql();exit;
        }
        return $data;

        //$pageObj->setConfig('prev','上一页');
        //$pageObj->setConfig('next','下一页');
        // 生成翻页字符串
    }
    /*public function a_name($name){
        echo 'hello,欢迎'.$name;
    }*/

}