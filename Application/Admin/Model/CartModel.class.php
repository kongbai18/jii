<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/28 0028
 * Time: 8:55
 */
namespace Admin\Model;
use Think\Model;
class CartModel extends Model {
    public function addCart(){
        $goodsId = I('get.goodsId');
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $attrId = I('get.attrId');
        $goodsNum = I('get.goodsNum');
        $attrId = explode(',',$attrId);
        foreach ($attrId as $v){
            if($v != ''){
                $attr[] = $v;
            }
        }
        sort($attr,SORT_NUMERIC);//以数字形式升序
        $attr = (string)implode(',',$attr);

        $user = checkUser($userId,$thr_session);
        if (!$user){
            $data = array(
                'flag' => '0',//登陆状态错误
            );
        }else{
            $gnModel = D('goods_number');
            $gnData = $gnModel->field('id')
                ->where(array(
                    'goods_id' => array('eq',$goodsId),
                    'goods_attr_id' =>array('eq',$attr),
                    'goods_number' => array('gt',$goodsNum-1)
                ))->select();
            if(!empty($gnData)){
                $cart = $this->field('cart_number')->where(array(
                    'user_id' => $userId,
                    'goods_id' => $goodsId,
                    'goods_attr_id' => $attr
                ))->select();
                if(empty($cart)){        //判断购物车是否已经有了此商品
                    $cartData = array(
                        'user_id' => $userId,
                        'goods_id' => $goodsId,
                        'goods_attr_id' => $attr,
                        'cart_number' => $goodsNum,
                    );
                    $result = $this->add($cartData);
                    if($result){
                        $data = array(
                            'flag' => '11', //添加购物车成功
                        );
                    }else{
                        $data = array(
                            'flag' => '10', //添加购物车失败
                        );
                    }
                }else{
                    $data = array(
                        'flag' => '20',  //购物车已有此商品
                    );
                }
            }else{
                $data = array(
                    'flag' => '00',  //商品库存不足
                );
            }
        }
        return $data;
    }
    public function listCart(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');

        $cartData = $this->where(array(
            'user_id' => array('eq',$userId)
        ))->select();
        $gnModel = D('goods_number');
        $gaModel = D('goods_attr');
        $goodsModel = D('goods');
        $colModel = D('color');

        $user = checkUser($userId,$thr_session);
        if (!$user){
            $data = array(
                'flag' => '0',
            );
        }else{
            $cartModuleModel = D('cart_module');
            foreach ($cartData as $k => $v){
                if($v['goods_id'] == 0){
                    $cartModuleData = $cartModuleModel->find($v['goods_attr_id']);
                    $result = $this->modulePrice($cartModuleData);

                    $cartData[$k]['goods_name'] = $cartModuleData['space_fur_name'];
                    $cartData[$k]['goods_attr'] = array($cartModuleData['attr']);
                    $cartData[$k]['goods_price'] = $result['price'];
                    $cartData[$k]['img_src'] = $result['img_src'];
                    $cartData[$k]['max_num'] = 10;
                    $cartData[$k]['deduction'] = 0;
                }else{
                    $gnData = $gnModel->field('goods_price,discount_price,goods_number,img_src,deduction')->where(array(
                        'goods_id' => array('eq',$v['goods_id']),
                        'goods_attr_id' => array('eq',$v['goods_attr_id']),
                        'goods_number' => array('gt','0')
                    ))->find();
                    if(empty($gnData)){
                        unset($cartData[$k]);
                    }else{
                        $goodsData = $goodsModel->field('is_on_sale,goods_name')->find($v['goods_id']);
                        if($goodsData['is_on_sale'] == 0){
                            unset($cartData[$k]);
                        }else{
                            if($v['cart_number'] > $gnData['goods_number']){
                                $cartData[$k]['cart_number'] = '1';
                            }
                            $attrId = explode(',',$v['goods_attr_id']);
                            $cartDa = array();
                            foreach ($attrId as $v1){
                                $attrData = $gaModel->field('a.attr_value,b.attr_type')
                                    ->alias('a')
                                    ->where(array('a.id'=>$v1))
                                    ->join('LEFT JOIN __ATTRIBUTE__ b ON a.attr_id = b.id')
                                    ->find();
                                if(!empty($attrData)){
                                    if($attrData['attr_type'] == '2'){
                                        $cartDa[] = $attrData['attr_value'];
                                    }elseif ($attrData['attr_type'] == '3'){
                                        $color = $colModel->field('color_name')->find($attrData['attr_value']);
                                        $cartDa[] = $color['color_name'];
                                    }
                                }else{
                                    $cartDa[] = '默认';
                                }
                            }
                            if($gnData['discount_price'] > 0){
                                $price = $gnData['discount_price'];
                            }else{
                                $price = $gnData['goods_price'];
                            }
                            $cartData[$k]['goods_name'] = $goodsData['goods_name'];
                            $cartData[$k]['goods_attr'] = $cartDa;
                            $cartData[$k]['goods_price'] = $price;
                            $cartData[$k]['img_src'] = $gnData['img_src'];
                            $cartData[$k]['max_num'] = $gnData['goods_number'];
                            $cartData[$k]['deduction'] = $gnData['deduction'];
                        }
                    }
                }
            }
            $data = array(
                'flag' => '1',
                'cartData' => $cartData,
            );
        }
        return $data;
    }
    public function delCart(){
        $goodsId = I('get.goodsId');
        $attrId = I('get.attrId');
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $user = checkUser($userId,$thr_session);
        if ($user){
            $result = $this->where(array(
                'goods_id' => array('eq',$goodsId),
                'goods_attr_id' => array('eq',$attrId),
                'user_id' => array('eq',$userId),
            ))->delete();
            if ($result){
                return '1';
            }else{
                return '0';
            }
        }else{
            return '00';
        }
    }
    //计算报价模型价格
    public function modulePrice($data){
        $furnitureQuoteModel = D('furniture_quote');
        $furData = $furnitureQuoteModel->field('a.fur_attr_id,a.img_src,b.formula,b.project_area')
            ->alias('a')
            ->join('LEFT JOIN __MODEL__ b ON a.model_id = b.id')
            ->where(array('a.id'=>array('eq',$data['fur_quo_id'])))
            ->find();

        $extend = json_decode($data['ext'],true);
        foreach ($extend as $k => $v){
            $$k = $v;
        }

        $parameter = json_decode($data['parameter'],true);
        foreach ($parameter as $k1 => $v1){
            $$k1 = $v1;
        }

        $material = json_decode($data['material'],true);
        foreach ($material as $k2 => $v2){
            $goodsModel = D('goods');
            if(is_array($v2)){
                if($v2[1]){
                    $goodsData = $goodsModel->field('a.goods_name,b.goods_price as price')
                        ->alias('a')
                        ->where(array('a.id'=>array('eq',$v2[0])))
                        ->join('LEFT JOIN __GOODS_NUMBER__ b ON a.id=b.goods_id AND b.id='.$v2[1])
                        ->find();
                }else{
                    $goodsData = $goodsModel->field('a.goods_name,max(b.goods_price) as price')
                        ->alias('a')
                        ->group('b.goods_id')
                        ->where(array('a.id'=>array('eq',$v2[0])))
                        ->join('LEFT JOIN __GOODS_NUMBER__ b ON a.id=b.goods_id')
                        ->find();
                }
            }else{
                $goodsData = $goodsModel->field('a.goods_name,max(b.img_src) as img_src,max(goods_price) as price')
                    ->alias('a')
                    ->group('b.goods_id')
                    ->where(array('a.id'=>array('eq',$v2)))
                    ->join('LEFT JOIN __GOODS_NUMBER__ b ON a.id=b.goods_id')
                    ->find();
                $goodsNumModel = D('goods_number');
                $goodsNumData = $goodsNumModel->field('goods_price,img_src')->where(array('goods_id'=>array('eq',$v2)))->select();
                foreach ($goodsNumData as $k3 => $v3){
                    if($v3['img_src'] == $goodsData['img_src']){
                        $goodsData['price'] = $v3['goods_price'];
                    }
                }
            }
            $$k2 = $goodsData['price'];
            $goodsName[$k2] = $goodsData['goods_name'];
        }

        $furAttrId = explode(',',$furData['fur_attr_id']);
        $mult = $furAttrId[1];

        $formula = json_decode($furData['formula'],true);
        $totalPrice = 0;
        foreach ($formula as $k3 => $v3) {
            $num = eval($v3[0]);
            $price = eval($v3[1]);
            $fee = eval($v3[3]);
            $totalPrice = $totalPrice + $fee;
        }
        $result = array(
            'price' => $totalPrice,
            'img_src' => $furData['img_src']
        );
        return $result;
    }
}