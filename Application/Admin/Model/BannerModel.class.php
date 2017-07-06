<?php
namespace Admin\Model;
use Think\Model;
class BannerModel extends Model{
    protected $insertFields='cate_id,banner_name,pic,sm_pic,sj_sm_pic,sj_mid_pic,mid_pic,cid,banner_des,yid';
    protected $updateFields='id,cate_id,banner_name,pic,sm_pic,sj_sm_pic,sj_mid_pic,mid_pic,cid,banner_des,yid';
    protected function _before_insert(&$data,$option){
    }
    protected function _after_insert(&$data,$option){

    }
    protected function _before_update(&$data,$option){
        $id=$option['where']['id'];


    }

    protected function _before_delete($option){
        $id=$option['where']['id'];

        //dump($option);exit;
    }

}

?>