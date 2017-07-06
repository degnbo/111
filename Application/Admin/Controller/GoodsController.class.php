<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class GoodsController extends BaseController {
    public function index(){
        $this->display();
    }
    public function add(){
        header('content-type:text/html;charset=utf8');
        $model=D('Goods');
        $tmodel=M('Type');
        $tlist=$tmodel->select();
        $this->tlist=$tlist;
        $pmodel = M('Province');
        $cmodel=D('Category');
        $this->clist=$cmodel->getTree();
        $this->plist = $model->sheng();
        if(IS_POST){
            //dump($_POST);exit;
            if($model->create($_POST)){
                if($lastId=$model->add()){
                    $this->redirect('Goods/xuanzuo',array('id'=>$lastId));
                    exit;
                }else{
                    $this->error($model->getError());
                }
            }else{
                //echo $model->getlastSql();exit;
                $this->error($model->getError());
            }
        }
        $this->dlist=$model->select();
        //dump($this->dlist);
        $this->display();

    }
    public function lis(){
        $model=D('Goods');
        $data=$model->search(8);
        $this->dlist=$data['data'];
        $this->page=$data['page'];
		$cmodel=D('Category');
        $this->clist=$cmodel->getTree();
        //dump($this->dlist);exit;
        $this->display();
    }
    public function edit(){
        $id=I('get.id');
        $p=I('get.p');
        $model=D('Goods');
        if(IS_POST){
            if($model->create()){
                if($model->where(array('id'=>$id))->save()!==false){
                    $this->success('修改成功',U('Goods/edit',array('id'=>$id,'p'=>$p)),1);
                    exit;
                }else{
                    $this->error($model->getError());
                }
            }else{
                $this->error($model->getError());
            }
        }
        //dump($this->dlist);
        //省份
        $this->plist = $model->sheng();
        $vo=$model->alias('a')->field('a.*,b.cate_id')->
        join("left join beidou_goods_cate b on a.id=b.goods_id")->
        where(array('a.id'=>$id))->find();
        $this->vo=$vo;
		$url=$vo['url'];
        if($url){
            $urls=explode('|',$url);
        }else{
            $urls=$url;
        }
        $this->urls=$urls;
        $tmodel=M('Type');
        $tlist=$tmodel->select();
        $this->tlist=$tlist;
        $pmodel = M('Province');
        $cmodel=D('Category');
        $this->clist=$cmodel->getTree();
		
        $this->display();
    }
    public function delete(){
        $id=I('get.id');
        $model=D('Goods');
        $model->where(array(
            'id'=>$id,
        ))->save(array(
                'is_show'=>'0',
            ));
        $this->success('删除成功',U('Goods/lis'),1);
    }
    public function delete1(){
        $id=I('get.id');
        $model=D('Goods');
        //dump($id);exit;
        //$cid=$model->getChildren($id);
        //false表示sql出错，0表示没有删除任何数据
        $result=$model->delete($id);
        if(false!==$result){
            $this->success('删除成功',U('Goods/lis'),1);
            exit;
        }else {
            $this->error($model->getError(),'',1);
        }
        $this->display();
    }
    public function ajaxChange(){
    $mid=I('post.mid');
    $val=I('post.val');
    $model=M('Goods');
		if($val==1){
			$model->where(array(
				'id'=>$mid
			))->save(array(
				'is_on_sale'=>'0'));
			exit;
		}else{
			$model->where(array(
				'id'=>$mid
			))->save(array(
				'is_on_sale'=>'1'));
			exit;
		}
    }
	public function ajaxChange1(){
    $mid=I('post.mid');
    $val=I('post.val');
    $model=M('Goods');
		if($val==1){
			$model->where(array(
				'id'=>$mid
			))->save(array(
				'is_rec'=>'0'));
			exit;
		}else{
			$model->where(array(
				'id'=>$mid
			))->save(array(
				'is_rec'=>'1'));
			exit;
		}
    }
    public function xuanzuo(){
        $id=I('get.id');
        //where(array('id'=>array('elt',65))
        $model=D('Goods');
        $data=$model->getWeizitotal($id);
        $this->wzarr=$data['wzarr'];
        $this->dlist=$data['arr'];
        $this->display();
    }
    public function yuliu(){
        $id=I('get.id');
        //where(array('id'=>array('elt',65))
        $model=D('Goods');
        $data=$model->getWeizitotal($id);
        $this->wzarr=$data['wzarr'];
        //$this->dzfarr=$data['dzfarr'];
        $this->dlist=$data['arr'];
        $this->ylarr=$data['ylarr'];
        //dump($data['kxarr']);exit;
        $this->ymarr=$data['ymarr'];
        $this->kxarr=$data['kxarr'];
        //exit;
        $this->display();
    }
    public function yuliu1(){
        $model=M('Zuowei');
        $id=I('get.id');
        //where(array('id'=>array('elt',65))
        $data=$model->select();
        $gmodel=M('Goods');
        $list=$gmodel->where(array('id'=>$id))->getField('wz_total');
        $wzarr=explode(',',$list);
        $this->wzarr=$wzarr;
        $arr=array();
        foreach($data as $k=>$val){
            if(!$val){
                continue;
            }
            $v=explode('_',$val['hang_zwh']);
            $arr[$v[0]]['weizi'][]=$v[1];
        }
        $this->dlist=$arr;
        //exit;
        $this->display();
    }
    //选择的总座位
    public function ajaxaddzw(){
        $data=I('post.wz_total');
        $id=I('post.id');
        $data=explode(',',$data);
        //$arr表示总的座位
        $arr=array();
        foreach($data as $val){
            if($val) {
                $arr[] = $val;
            }
        }
        file_put_contents('d:/m.txt',print_r($arr,true));
        $total=$arr;
        $model=M('Goods');
        $wz= $model->where(array('id'=>$id))->find();
        $ylwz=$wz['wz_yl'];
        $ymwz=$wz['wz_ym'];
        if($ymwz){
            $ymwz=explode(',',$ymwz);
        }
        $flage=true;
        file_put_contents('d:/n.txt',print_r($ymwz,true));
        if($ymwz){
            foreach($ymwz as $kk=>$vv){
                if(!in_array($vv,$arr)){
                    $flage=false;
                    break;
                }
            }
        }
        if(!$flage){
            echo 0;
        }else{
            $ylwz=explode(',',$ylwz);
            foreach($ylwz as $key=>$val){
                if(!in_array($val,$arr)){
                    unset($ylwz[$key]);
                }else{
                    $ke=array_search($val,$total);
                    if($ke!==false){
                        unset($total[$ke]);
                    }
                }
            }
            if($ymwz){
                foreach($ymwz as $k3=>$v3){
                    $k4=array_search($v3,$total);
                    if($k4!==false){
                        unset($total[$k4]);
                    }
                }
            }
            $kxwz=implode(',',$total);
            $ylwz=implode(',',$ylwz);
            $arr=implode(',',$arr);
            $model->where('id='.$id)->save(array(
                'wz_total'=>$arr,
                'wz_yl'=>$ylwz,
                'wz_kx'=>$kxwz,
            ));
            file_put_contents('d:/a.txt',$model->getLastSql());
            $list=explode(',',$arr);
            echo 1;
            exit;
        }

    }
    //对预留的座位进行修改
    public function ajaxaddylzw(){
        $id=I('post.id');
        $data=I('post.wz_yl');
        $data=explode(',',$data);
        $arr=array();
        foreach($data as $val) {
            if ($val) {
                $arr[] = $val;
            }
        }
        $model=M('Goods');
        $dlist= $model->where(array('id'=>$id))->find();
        $total=$dlist['wz_total'];
        $ym=$dlist['wz_ym'];
        file_put_contents('d:/n.txt',print_r($ym,true));
        if($total){
            $total=explode(',',$total);
        }
        if($ym){
            $ym=explode(',',$ym);
        }
        $yl=$arr;
        foreach($yl as $k=>$val){
            $key=array_search($val,$total);
            if($key!==false){
                unset($total[$key]);
            }
        }
        foreach($ym as $k1=>$v1){
            $k2=array_search($v1,$total);
            $k3=array_search($v1,$yl);
            if($k2!==false){
                unset($total[$k2]);
            }
            if($k3!==false){
                unset($yl[$k3]);
            }

        }
        $yl=array_unique($yl);
        $yl=implode(',',$yl);
        $total=array_unique($total);
        $kx=implode(',',$total);
        $model->where('id='.$id)->save(array(
                'wz_yl'=>$yl,
                'wz_kx'=>$kx
        ));
        $list=explode(',',$yl);
        die(json_encode($list));

    }
	//添加图集
    public function add_pic(){
        $id=I('get.id');
        //echo $id;die;
        $p=I('get.p');
        $model=M('Goods');
        $url=$model->where(array('id'=>$id))->find();
        $info=$this->upload2(2,"shangchuan/");
        //dump($info);die;
        if($url['url']){
            $urls=explode('|',$url['url']);
            $urls[]="/Public/".$info['myfile1']['savepath'].$info['myfile1']['savename'];
            $lj=implode('|',$urls);
        }else{
            $urls="/Public/".$info['myfile1']['savepath'].$info['myfile1']['savename'];
            $lj=$urls;
        }
        $result=$model->where(array('id'=>$id))->save(array('url'=>$lj));
        if($result!==false){
            $this->success('上传成功',U('Goods/edit',array('id'=>$id,'p'=>$p)),1);
            exit;
        }else{
            $this->error($model->getError());
        }
    }
    //上传
    public function upload2($size=2,$lanmu='shangchuan/'){
        $config=array(
            'maxSize'    =>    $size*1024*1024,
            'savePath'   =>$lanmu,
            'rootPath'=>'./Public/',
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd')
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        if($_FILES['myfile1']['error']==0) {
            $info = $upload->upload();
            if ($info) {
                return $info;
            }
        }else {
            return false;
        }
    }
	 public function ajax_edit(){
        //dump($_FILES);
        $tp=I('post.tp');
        $id=I('post.id');
        $p=I('get.p');
        //echo $id;
        //echo $tp;die;
        $model=M('Goods');
        $url=$model->where(array('id'=>$id))->find();
        //echo $tp;die;
        $info=$this->upload2(2,"shangchuan/");
        $urls=explode('|',$url['url']);
        foreach($urls as $k=>$v){
            if($tp==$v){
                $v="/Public/".$info['myfile2']['savepath'].$info['myfile2']['savename'];
                unlink(".".$tp);
            }
            $urls[$k]=$v;
        }
        $lj=implode('|',$urls);
        $result=$model->where(array('id'=>$id))->setField('url',$lj);
        if($result!==false){
            $this->success('图片修改成功',U('Goods/edit',array('id'=>$id,'p'=>$p)),1);
            exit;
        }else{
            $this->error($model->getError());
        }
    }
    public function ajax_del(){
        $tp=I('post.tp');
        $id=I('post.id');
        $model=M('Goods');
        $url=$model->where(array('id'=>$id))->find();
        $urls=explode('|',$url['url']);
        foreach($urls as $k=>$v){
            if($tp==$v){
                unset($urls[$k]);
                unlink(".".$tp);
                //unlink(__ROOT__.$tp);
            }
        }
        $lj=implode('|',$urls);
        $url=$model->where(array('id'=>$id))->setField('url',$lj);
        echo 1;die;
    }
    function upload1() {
        //echo 1;die;
        import('ORG.Net.UploadFile');
        $upload = new \UploadFile();// 实例化上传类
        $upload->maxSize  = 1024*1024*2 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $savepath='./Public/shangchuan/'.date('Ymd').'/';
        if (!file_exists($savepath)){
            mkdir($savepath);
        }
        $upload->savePath =  $savepath;// 设置附件上传目录
        if(!$upload->upload()) {// 上传错误提示错误信息
            $this->error($upload->getErrorMsg());
        }else{// 上传成功 获取上传文件信息
            $info =  $upload->getUploadFileInfo();
        }
        //file_put_contents("d:/a.txt",print_r($info,true));
        //print_r("".$info[0]['savepath'].'/'.$info[0]['savename']);die;//以后就用这个
        print_r($this->J(__ROOT__.'/'.$info[0]['savepath'].'/'.$info[0]['savename']));die;
    }
    function J($str){
        return str_replace('./', '', str_replace('//', '/', $str));
    }
    function del() {
        $src=$_GET['src'];
        //file_put_contents("d:/a.txt",$src);
        if (file_exists(".".$src)){
            unlink(".".$src);
        }
        print_r($_GET['src']);
        exit();
    }

}