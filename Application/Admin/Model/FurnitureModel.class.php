<?php
namespace Admin\Model;
use Think\Model;
class FurnitureModel extends Model {
    //添加类别时允许接收的表单
    protected $insertFields = array('fur_name','cate_id','is_index','sort_id');
    //修改类别时允许接收的字段
    protected $updateFields = array('id','fur_name','cate_id','is_index','sort_id');
    //验证码规则
    protected $_validate = array(
           array('fur_name','require','家具类型名称不能为空！',1),
           array('cate_id','require','家具分类不能为空！',1),
    );
    //添加之前
    public function _before_insert(&$data,$option){
        $attrName = I('post.attr_name');
        $attrVal = I('post.attr_val');
        $extAttrCat = I('post.ext_attr_cat');
        $attr = array();

        foreach ($attrName as $k => $v){
            if($v === ''){
                continue;
            }else{
                foreach ($attrVal[$k] as $v1){
                    if ($v1 === ''){
                        continue 2;
                    }
                }
                $attr[$v] = array($extAttrCat[$k] => $attrVal[$k]);
            }
        }

        $data['attribute'] = json_encode($attr);
        /*************处理IMG*********************/
        if($_FILES['img_src']['error']==0){
            $file = $_FILES['img_src']['tmp_name'];
            $key = 'jiimade/view/images/furniture/'.date("Y/m/d").'/'.rand();
            $ret = qiniu_img_upload($key,$file);
            $data['img_src'] = $ret['img'];
        }
    }
    //修改之前
    public function _before_update(&$data,$option){
        $id = I('post.id');
        $data['id'] = $id;
        $attrName = I('post.attr_name');
        $attrVal = I('post.attr_val');
        $extAttrCat = I('post.ext_attr_cat');
        $attr = array();
        foreach ($attrName as $k => $v){
            if($v === ''){
                continue;
            }else{
                foreach ($attrVal[$k] as $v1){
                    if ($v1 === ''){
                        continue 2;
                    }
                }
                $attr[$v] = array($extAttrCat[$k] => $attrVal[$k]);
            }
        }
        $data['attribute'] = json_encode($attr);
        $attribute = $this->field('attribute')->find($id);

        if($attribute['attribute'] !== $data['attribute']){
            $furQuoModel = D('furniture_quote');

            $oldFurQuoImg =  $furQuoModel->field('img_src')->where(array('fur_id'=>array('eq',$id)))->select();
            foreach ($oldFurQuoImg as $v1){
                if(!empty($v1)){
                    $key = rtrim($v1['img_src'],'?');
                    $key = substr_replace($key,'',0,33);
                    qiniu_img_delete($key);
                }
            }
            $furQuoModel->where(array('fur_id'=>array('eq',$id)))->delete();
        }
        /*************处理IMG*********************/
        if($_FILES['img_src']['error']==0){
            $file = $_FILES['img_src']['tmp_name'];
            $key = 'jiimade/view/images/furniture/'.date("Y/m/d").'/'.rand();
            $ret = qiniu_img_upload($key,$file);
            $data['img_src'] = $ret['img'];

            //获取LOGO路径
            $oldImg = $this->field('img_src')->find($data['id']);
            //从七牛云删除
            if(!empty($oldImg)){
                $key = rtrim($oldImg['img_src'],'?');
                $key = substr_replace($key,'',0,33);
                qiniu_img_delete($key);
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