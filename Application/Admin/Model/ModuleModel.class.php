<?php
namespace Admin\Model;
use Think\Model;
class ModuleModel extends Model {
    //添加类别时允许接收的表单
    protected $insertFields = array('quote_id','model_id','cate_id','agio','space','fur_name');
    //修改类别时允许接收的字段
    protected $updateFields = array('id','type_name');
    //验证码规则
    protected $_validate = array(
           array('space','require','家具所在位置位置不能为空！',1),
           array('agio','0.8,1','折扣只能在0.8-1之间！',1,'between'),
    );
    public function check_number($open){
        $v = is_numeric ($open) ? true : false;
        return $v;
    }
    //添加之前
    public function _before_insert(&$data,$option){
        $quoteId = I('post.quote_id');
        $cateId = I('post.cate_id');
        $data['material'] = json_encode(I('post.material'));
        $data['parameter'] = json_encode(I('post.parameter'));
        $data['ext'] = json_encode(I('post.ext'));
        $num = $this->field('max(sort_id) as num')->where(array('quote_id'=>array('eq',$quoteId),'cate_id'=>array('eq',$cateId)))->group('quote_id')->select();
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
            ->order('a.sort_id asc')
            ->join('LEFT JOIN __MODEL__ b ON a.model_id = b.id')
            ->where(array(
                'a.quote_id' => array('eq',$quoteId)
            ))
            ->select();
        foreach ($data as $k => $v){
            $extend = json_decode($v['ext'],true);
            foreach ($extend as $k0 => $v0){
                $$k0 = $v0;
            }

           $parameter = json_decode($v['parameter'],true);
            $parStr = '';
           foreach ($parameter as $k1 => $v1){
               $$k1 = $v1;
               $parStr = $parStr.$k1.':'.$v1.'; ';
           }
           $material = json_decode($v['material'],true);

            foreach ($material as $k2 => $v2){
               $goodsModel = D('goods');
               $goodsData = $goodsModel->field('a.goods_name,max(b.goods_price) as price')
                   ->alias('a')
                   ->group('b.goods_id')
                   ->where(array('a.id'=>array('eq',$v2)))
                   ->join('LEFT JOIN __GOODS_NUMBER__ b ON a.id=b.goods_id')
                   ->find();
               $$k2 = $goodsData['price'];
               $goodsName[$k2] = $goodsData['goods_name'];
            }
            $module = array();
            $formula = json_decode($v['formula'],true);

            $totalModulePrice = 0;
            foreach ($formula as $k3 => $v3){
               $num = eval($v3[0]);
               $price = eval("return $v3[1];");
               $fee = $num * $price;
               $agioFee = $fee * $v['agio'];
               if($v['agio'] === '1.00'){
                   $v['agio'] = 1;
               }
               if($v['cate_id'] == '1'){
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
               }elseif ($v['cate_id'] == '3'){
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
               }elseif ($v['cate_id'] == '2'){
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
               $totalModulePrice = $totalModulePrice + $agioFee;
            }
            if($v['cate_id'] == '1'){
                $cabinetData['module'][$k]['formula'] = $module;
                $cabinetData['module'][$k]['totalModulePrice'] = $totalModulePrice;
                $cabinetData['module'][$k]['space'] = $v['space'];
                $cabinetData['module'][$k]['fur_name'] = $v['fur_name'];
                $cabinetData['module'][$k]['sort_id'] = $v['sort_id'];
                $cabinetData['module'][$k]['parameter'] = $parameter;
                $cabinetData['module'][$k]['parStr'] = $parStr;
            }elseif ($v['cate_id'] == '3'){
                $frontData['module'][$k]['formula'] = $module;
                $frontData['module'][$k]['totalModulePrice'] = $totalModulePrice;
                $frontData['module'][$k]['space'] = $v['space'];
                $frontData['module'][$k]['fur_name'] = $v['fur_name'];
                $frontData['module'][$k]['sort_id'] = $v['sort_id'];
                $frontData['module'][$k]['parameter'] = $parameter;
                $frontData['module'][$k]['parStr'] = $parStr;
            }elseif ($v['cate_id'] == '2'){
                $doorData['module'][$k]['formula'] = $module;
                $doorData['module'][$k]['totalModulePrice'] = $totalModulePrice;
                $doorData['module'][$k]['space'] = $v['space'];
                $doorData['module'][$k]['fur_name'] = $v['fur_name'];
                $doorData['module'][$k]['sort_id'] = $v['sort_id'];
                $doorData['module'][$k]['parameter'] = $parameter;
                $doorData['module'][$k]['parStr'] = $parStr;
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
    //小程序添加新家具模块
    public function addWxModule(){
        $furId = I('get.furId');
        $modelId = I('get.modelId');
        $extData = I('get.extData');
        $parData = I('get.parData');
        $chooseGoods = I('get.chooseGoods');
        $space = I('get.space');
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $user = checkUser($userId,$thr_session);
        $extData = str_replace('&quot;','"',$extData);
        $parData = str_replace('&quot;','"',$parData);
        $chooseGoods = str_replace('&quot;','"',$chooseGoods);
        $extData = json_decode($extData,true);
        $parData = json_decode($parData,true);
        $chooseGoods = json_decode($chooseGoods,true);


        if($user){
            $quoteModel = D('quote');
            $furModel = D('furniture');
            $quoteId = $quoteModel->field('id')->where(array('user_id'=>array('eq',$userId)))->find();
            $furData = $furModel->field('fur_name,cate_id')->find($furId);


            $ext = array();
            foreach ($extData as $k => $v){
                $total = 0;
                foreach ($v as $k1 => $v1){
                    $total = $total + $v1;
                }
                $ext[$k] = $total;
            }

            $data = array(
                'quote_id' => $quoteId['id'],
                'model_id' => $modelId,
                'cate_id' => $furData['cate_id'],
                'space' => $space,
                'fur_name' => $furData['fur_name'],
            );


            $_POST['quote_id'] = $quoteId['id'];
            $_POST['cate_id'] = $furData['cate_id'];
            $_POST['material'] = $chooseGoods;
            $_POST['parameter'] = $parData;
            $_POST['ext'] = $ext;


            $result = $this->add($data);
            if($result){
                return true;
            }else{
                return false;
            }

        }
    }
    //获取个人家具模块
    public function getModule(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $user = checkUser($userId,$thr_session);
        if($user){
            $quoteModel = D('quote');
            $quoteId = $quoteModel->field('id')->where(array('user_id'=>array('eq',$userId)))->find();
            $data = $this->getInfo($quoteId['id']);
            return $data;
        }
    }

    //删除模块
    public function delModule(){
        $sortId = I('get.sort');
        $cateId = I('get.cat');
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $user = checkUser($userId,$thr_session);
        if($user){
            $quoteModel = D('quote');
            $quoteId = $quoteModel->field('id')->where(array('user_id'=>array('eq',$userId)))->find();
            $result = $this->where(array(
                'quote_id' => array('eq',$quoteId['id']),
                'cate_id' => array('eq',$cateId),
                'sort_id' => array('eq',$sortId),
            ))->delete();
            if($result){
                $data = $this->where(array(
                    'quote_id' => array('eq',$quoteId['id']),
                    'cate_id' => array('eq',$cateId),
                ))->select();
                foreach ($data as $k => $v){
                    $saveData = array(
                        'sort_id' => $k+1,
                    );
                    $where = array(
                        'quote_id' => array('eq',$v['quote_id']),
                        'cate_id' => array('eq',$v['cate_id']),
                        'sort_id' => array('eq',$v['sort_id']),
                    );
                    $this->where($where)->save($saveData);
                }
                return true;
            }

        }
    }
}