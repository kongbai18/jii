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
        $extCat = I('post.ext_cat');
        $extName = I('post.ext_name');
        $extPara = I('post.ext_para');
        $extValName = I('post.ext_val_name');
        $extVal = I('post.ext_val');

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

        $extend = array();
        $index = 0;
        foreach ($extCat as $k => $v){
            $extend[$index][$v][0] = $extName[$k];
            $extend[$index][$v][1] = $extPara[$k];
            if($v == '1' || $v == '2'){
                foreach ($extValName[$k] as $k1 => $v1){
                    $extend[$index][$v][2][$v1] = $extVal[$k][$k1];
                }
            }
            $index = $index + 1;
        }

        $material = json_encode($material);
        $formula = json_encode($formula);
        $parameter = json_encode($parameter);
        $extend = json_encode($extend);
        $data['material'] = $material;
        $data['parameter'] = $parameter;
        $data['formula'] = $formula;
        $data['ext'] = $extend;



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
        $extCat = I('post.ext_cat');
        $extName = I('post.ext_name');
        $extPara = I('post.ext_para');
        $extValName = I('post.ext_val_name');
        $extVal = I('post.ext_val');

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

        $extend = array();
        $index = 0;
        foreach ($extCat as $k => $v){
            $extend[$index][$v][0] = $extName[$k];
            $extend[$index][$v][1] = $extPara[$k];
            if($v == '1' || $v == '2'){
                foreach ($extValName[$k] as $k1 => $v1){
                    $extend[$index][$v][2][$v1] = $extVal[$k][$k1];
                }
            }
            $index = $index + 1;
        }

        $material = json_encode($material);
        $formula = json_encode($formula);
        $parameter = json_encode($parameter);
        $extend = json_encode($extend);
        $data['material'] = $material;
        $data['parameter'] = $parameter;
        $data['formula'] = $formula;
        $data['ext'] = $extend;
    }
    //删除之前
    public function _before_delete($option){

    }
    //小程序获取计价模型
    public function getFurModel(){
        $gatAttr = I('get.gatAttr');
        $furId = I('get.furId');
        $gatAttr = rtrim($gatAttr,',');
        $furQuoModel = D('furniture_quote');
        $furQuoData = $furQuoModel->field('model_id')->where(array(
            'fur_attr_id' => array('eq',$gatAttr),
            'fur_id' => array('eq',$furId)
        ))->select();
        if(empty($furQuoData)){
            return false; //此属性不存在对应计价模型
        }else{
            $modelData = $this->find($furQuoData[0]['model_id']);
            $material = json_decode($modelData['material'],true);
            $goodsModel = D('goods');
            foreach ($material as $k => &$v){
                foreach ($v as $k1 => &$v1){
                    $cateId = explode(',',$v1);
                    $goodsData = $goodsModel->field('a.id,a.goods_name,max(b.img_src) as img_src')
                        ->alias('a')
                        ->join('LEFT JOIN __GOODS_NUMBER__ b ON a.id=b.goods_id')
                        ->where(array('a.cat_id'=>array('in',$cateId),'a.is_quote'=>array('eq','1')))
                        ->group('a.id')
                        ->select();
                    $v1 = $goodsData;
                }
            }
            $parameter = json_decode($modelData['parameter'],true);
            $ext = json_decode($modelData['ext'],true);
            unset($modelData['material']);
            unset($modelData['model_name']);
            unset($modelData['formula']);
            unset($modelData['parameter']);
            $data = array(
                'modelData' => $modelData,
                'material' => $material,
                'parameter' => $parameter,
                'ext' => $ext,
            );
            return $data;
        }
    }

    public function test(){
        getAddress(120.1860233868,30.3548291689);
    }
}