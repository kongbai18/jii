<?php
namespace Admin\Model;
use Think\Model;
class ModuleModel extends Model {
    //添加类别时允许接收的表单
    protected $insertFields = array('agio','space','type');
    //修改类别时允许接收的字段
    protected $updateFields = array('id','type_name');
    //验证码规则
    protected $_validate = array(
           array('space','require','产品位置不能为空！',1),
           array('type','require','产品类型不能为空！',1),
           array('agio','0.8,1','折扣只能在0.8-1之间！',1,'between'),
    );
    //添加之前
    public function _before_insert(&$data,$option){
        $quoteId = I('post.quote_id');
        $modelCate = I('post.model_cate');
        $data['quote_id'] = $quoteId;
        $data['model_cate'] = $modelCate;
        $data['model_id'] = I('post.model_id');
        $data['material'] = json_encode(I('post.material'));
        $data['parameter'] = json_encode(I('post.parameter'));
        $num = $this->field('max(sort_id) as num')->where(array('quote_id'=>array('eq',$quoteId),'model_cate'=>array('eq',$modelCate)))->group('quote_id')->select();
        if(empty($num)){
            $data['sort_id'] = 1;
        }else{
            $data['sort_id'] = $num[0]['num']+1;
        }
    }
    //修改之前
    public function _before_update(&$data,$option){
    	
    }
    //删除之前
    public function _before_delete($option){

    }
    //报价单获取包含模块商品
    public function getInfo($quoteId){
        $cabinetFee = 0;
        $cabinetAgioFee = 0;
        $doorFee = 0;
        $doorAgioFee = 0;
        $frontFee = 0;
        $frontAgioFee = 0;
        $cabinetDetailFee = array();
        $doorDetailFee = array();
        $frontDetailFee = array();
        $cabinetData = array();
        $doorData = array();
        $frontData = array();
        //获取所有模块商品
        $data = $this->field('a.*,b.formula')
            ->alias('a')
            ->join('LEFT JOIN __MODEL__ b ON a.model_id = b.id')
            ->where(array(
                'quote_id' => array('eq',$quoteId)
            ))
            ->select();
        foreach ($data as $k => $v){
            $parameter = json_decode($v['parameter'],true);
           foreach ($parameter as $k1 => $v1){
               $$k1 = $v1;
           }
            foreach (json_decode($v['material'],true) as $k2 => $v2){
               $goodsModel = D('Admin/goods');
               $goodsData = $goodsModel->field('a.goods_name,max(b.goods_price) as price')
                   ->alias('a')
                   ->group('b.goods_id')
                   ->where(array('a.id'=>array('eq',$v2)))
                   ->join('LEFT JOIN __GOODS_NUMBER__ b ON a.id=b.goods_id')
                   ->find();
               $$k2 = $goodsData['price'];
               $goodsName[ltrim($k2,'$')] = $goodsData['goods_name'];
            }
            $module = array();
            foreach (json_decode($v['formula'],true) as $k3 => $v3){
               $num = eval("return $v3[0];");
               $price = eval("return $v3[1];");
               $fee = $num * $price;
               $agioFee = $fee * $v['agio'];
               if($v['agio'] === '1.00'){
                   $v['agio'] = 1;
               }
               if($v['model_cate'] == '1'){
                   $cabinetFee = $cabinetFee + $fee;
                   $cabinetAgioFee = $cabinetAgioFee + $agioFee;
                   $hold = false;
                   foreach ($cabinetDetailFee as $k4 => $v4){
                       if($k3 === $k4){
                           $cabinetDetailFee[$k3]['fee'] = $cabinetDetailFee[$k3]['fee'] + $fee;
                           $cabinetDetailFee[$k3]['agioFee'] = $cabinetDetailFee[$k3]['agioFee'] + $agioFee;
                           $hold = true;
                       }
                   }
                   if(!$hold){
                       $cabinetDetailFee[$k3]['fee'] = $fee;
                       $cabinetDetailFee[$k3]['agioFee'] = $agioFee;
                   }
               }elseif ($v['model_cate'] == '2'){
                   $frontFee = $frontFee + $fee;
                   $frontAgioFee = $frontAgioFee + $agioFee;
                   $hold = false;
                   foreach ($cabinetDetailFee as $k4 => $v4){
                       if($k3 === $k4){
                           $frontDetailFee[$k3]['fee'] = $frontDetailFee[$k3]['fee'] + $fee;
                           $frontDetailFee[$k3]['agioFee'] = $frontDetailFee[$k3]['agioFee'] + $agioFee;
                           $hold = true;
                       }
                   }
                   if(!$hold){
                       $frontDetailFee[$k3]['fee'] = $fee;
                       $frontDetailFee[$k3]['agioFee'] = $agioFee;
                   }
               }elseif ($v['model_cate'] == '3'){
                   $doorFee = $doorFee + $fee;
                   $doorAgioFee = $doorAgioFee + $agioFee;
                   $hold = false;
                   foreach ($cabinetDetailFee as $k4 => $v4){
                       if($k3 === $k4){
                           $doorDetailFee[$k3]['fee'] = $doorDetailFee[$k3]['fee'] + $fee;
                           $doorDetailFee[$k3]['agioFee'] = $doorDetailFee[$k3]['agioFee'] + $agioFee;
                           $hold = true;
                       }
                   }
                   if(!$hold){
                       $doorDetailFee[$k3]['fee'] = $fee;
                       $doorDetailFee[$k3]['agioFee'] = $agioFee;
                   }
               }
                $module[] = array(
                 'name' => $k3,
                 'unit' => $v3[2],
                 'num'  => $num,
                 'price'=> $price,
                 'agio' => $v['agio'],
                 'agioFee' => $agioFee,
                 'remarkes' => $goodsName[ltrim($v3[1],'$')],

               );
            }
            if($v['model_cate'] == '1'){
                $cabinetData['module'][$k]['formula'] = $module;
                $cabinetData['module'][$k]['space'] = $v['space'];
                $cabinetData['module'][$k]['type'] = $v['type'];
                $cabinetData['module'][$k]['sort_id'] = $v['sort_id'];
                $cabinetData['module'][$k]['parameter'] = $parameter;
            }elseif ($v['model_cate'] == '2'){
                $frontData['module'][$k]['formula'] = $module;
                $frontData['module'][$k]['space'] = $v['space'];
                $frontData['module'][$k]['type'] = $v['type'];
                $frontData['module'][$k]['sort_id'] = $v['sort_id'];
                $frontData['module'][$k]['parameter'] = $parameter;
            }elseif ($v['model_cate'] == '3'){
                $doorData['module'][$k]['formula'] = $module;
                $doorData['module'][$k]['space'] = $v['space'];
                $doorData['module'][$k]['type'] = $v['type'];
                $doorData['module'][$k]['sort_id'] = $v['sort_id'];
                $doorData['module'][$k]['parameter'] = $parameter;
            }
        }
        $cabinetData['fee'] = $cabinetFee;
        $cabinetData['agioFee'] = $cabinetAgioFee;
        $cabinetData['detailFee'] = $cabinetDetailFee;
        $doorData['fee'] = $doorFee;
        $doorData['agioFee'] = $doorAgioFee;
        $doorData['detailFee'] = $doorDetailFee;
        $frontData['fee'] = $frontFee;
        $frontData['agioFee'] = $frontAgioFee;
        $frontData['detailFee'] = $frontDetailFee;
        unset($data);
        $moduledata = array(
            'cabinet' => $cabinetData,
            'door'    => $doorData,
            'front'   => $frontData,
        );
        return $moduledata;
    }
}