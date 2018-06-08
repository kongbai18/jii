<?php
namespace Admin\Model;
use Think\Model;
class CarouselModel extends Model {
    //添加类别时允许接收的表单
    protected $insertFields = array('url','sort_id');
    //修改类别时允许接收的字段
    protected $updateFields = array('id','url','sort_id');
    //验证码规则
    protected $_validate = array(

    );

    //添加之前
    public function _before_insert(&$data,$option){
        /*************处理IMG*********************/
        if($_FILES['img_src']['error']==0){
            $file = $_FILES['img_src']['tmp_name'];
            $key = 'jiimade/view/images/carousel/'.date("Y/m/d").'/'.rand();
            $ret = qiniu_img_upload($key,$file);
            $data['img_src'] = $ret['img'];
        }
    }
    //修改之前
    public function _before_update(&$data,$option){
        /*************处理IMG*********************/
        if($_FILES['img_src']['error']==0){
            $file = $_FILES['img_src']['tmp_name'];
            $key = 'jiimade/view/images/carousel/'.date("Y/m/d").'/'.rand();
            $ret = qiniu_img_upload($key,$file);

            if($ret['flag'] == 1){
                //获取旧LOGO地址
                $oldImg = $this->field('img_src')->find($option['where']['id']);
                foreach($oldImg as $v){
                    $key = rtrim($v,'?');
                    $key = substr_replace($key,'',0,33);
                    qiniu_img_delete($key);
                }
                $data['img_src'] = $ret['img'];
            }
        }
    }
    //删除之前
    public function _before_delete($option){
        //获取LOGO路径
        $oldImg = $this->field('img_src')->find($option['where']['id']);
        //从七牛云删除
        if(!empty($oldImg)){
            $key = rtrim($oldImg['img_src'],'?');
            $key = substr_replace($key,'',0,33);
            qiniu_img_delete($key);
        }
    }
    	
}