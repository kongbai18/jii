<?php
namespace Admin\Model;
use Think\Model;
class ModelModel extends Model {
    //添加类别时允许接收的表单
    protected $insertFields = array('model_name','model_cate','is_index','sort_id');
    //修改类别时允许接收的字段
    protected $updateFields = array('id','type_name');
    //验证码规则
    protected $_validate = array(
           array('model_name','require','模型名称不能为空！',1),
           array('model_cate','require','模型分类不能为空！',1),
    );
    //添加之前
    public function _before_insert(&$data,$option){
        $materialPrice = I('post.material-price');
        $materialName = I('post.material-name');
        $materialVal = I('post.material-val');
        $parameter = I('post.parameter');
        $formulaName = I('post.formula-name');
        $formulaNum = I('post.formula-num');
        $formulaPrice = I('post.formula-price');
        $formulaUnit = I('post.formula-unit');
        $material = array();
        foreach ($materialPrice as $k => $v){
            $maVal = array_unique($materialVal[$k]);
            $maVal = implode(',',$maVal);
            $material[$v] = array($materialName[$k] => $maVal);
        }
        $formula = array();
        foreach ($formulaName as $k => $v){
            $formula[$v] = array($formulaNum[$k],$formulaPrice[$k],$formulaUnit[$k]);
        }
        $material = json_encode($material);
        $formula = json_encode($formula);
        $parameter = json_encode($parameter);
        $data['material'] = $material;
        $data['parameter'] = $parameter;
        $data['formula'] = $formula;
        /*************处理IMG*********************/
        if($_FILES['img_src']['error']==0){
            $file = $_FILES['img_src']['tmp_name'];
            $key = 'jiimade/view/images/category/'.date("Y/m/d").'/'.rand();
            $ret = qiniu_img_upload($key,$file);
            $data['img_src'] = $ret['img'];
        }
    }
    //修改之前
    public function _before_update(&$data,$option){
    	
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