<?php
namespace Admin\Model;
use Think\Model;
class ModelModel extends Model {
    //添加类别时允许接收的表单
    protected $insertFields = array('model_name');
    //修改类别时允许接收的字段
    protected $updateFields = array('id','model_name');
    //验证码规则
    protected $_validate = array(
           array('model_name','require','模型名称不能为空！',1),
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
    }
    //修改之前
    public function _before_update(&$data,$option){
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
    }
    //删除之前
    public function _before_delete($option){

    }
}