<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/30 0030
 * Time: 16:16
 */
namespace Admin\Model;
use Think\Model;
class OrderModel extends Model {
    //搜索订单信息
    public function search($perpage,$state=''){
        $where = array();
        //订单编号搜索
        $orderId = I('get.orderId');
        if($orderId){
            $where['order_id'] = array('eq',$orderId);
        }
        //订单状态搜索
        $status = I('get.status');
        if($status != ''){
            $where['status'] = array('eq',$status);
        }
        if($state != ''){
            $where['status'] = array('eq',$state);
        }
        /*****************翻页*************************/
        //获取总记录数
        $count = $this->where($where)->count();
        //生成翻页对象类
        $pageObj = new \Think\Page($count,$perpage);
        //设置样式
        $pageObj->setConfig('next','下一页');
        $pageObj->setConfig('prev','上一页');
        //获取翻页字符串
        $pageString = $pageObj->show();
        /****************取一页的数据******************/
        $data = $this
            ->where($where)
            ->order('add_time desc')
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();
        return  array(
            'data' => $data,
            'page' => $pageString,
        );
    }
    //获取订单中包含的商品
    public function orderGoods(){
        $orderId = I('post.orderId');
        $orGoodsModel = D('order_goods');
        $gaModel = D('goods_attr');
        $colModel = D('color');
        $goodsData = $orGoodsModel->field('a.*,b.goods_name,c.img_src')
                    ->alias('a')
                    ->where(array('a.order_id'=>$orderId))
                    ->join('LEFT JOIN __GOODS__ b ON a.goods_id = b.id
                            LEFT JOIN __GOODS_NUMBER__ c ON a.goods_attr_id = c.goods_attr_id')
                    ->select();
        foreach ($goodsData as $k => &$v) {
            $attr = explode(',', $v['goods_attr_id']);
            $goods_attr_val = '';
            if($v['goods_attr_id'] == '0'){
                $goods_attr_val = '默认';
            }else{
                foreach ($attr as $k1 => $v1) {
                    $val = $gaModel->field('a.attr_value,b.attr_type')
                        ->alias('a')
                        ->join('LEFT JOIN __ATTRIBUTE__ b ON a.attr_id = b.id')
                        ->where(array('a.id' => $v1, 'a.goods_id' => $v['goods_id']))
                        ->find();
                    if($val['attr_type'] == '3'){
                        $color = $colModel->field('color_name')->find($val['attr_value']);
                        $goods_attr_val = $goods_attr_val . $color['color_name'] . ',';
                    }else{
                        $goods_attr_val = $goods_attr_val . $val['attr_value'] . ',';
                    }
                }
            }
            $v['goods_attr_val'] = rtrim($goods_attr_val, ',');
        }
        return $goodsData;
    }
    //发货
    public function orderDeli(){
        $express = I('post.express');
        $orderId = I('post.orderId');
        $date = time();
        $result = $this->save(array(
            'order_id' => $orderId,
            'express' => $express,
            'status' => '3',
            'update_time' => $date
        ));
        if($result){
            return 'success';
        }else{
            return 'false';
        }
    }
    //下单
    public function addOrder(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $mes = I('get.mes');
        $address = I('get.address');
        $goods = $_GET['goods'];
        $user = checkUser($userId,$thr_session);
        if($user){
            $fp = fopen('./lockOrd.text','r');
            flock($fp,LOCK_EX);         //锁机制

            $trans = M();
            $trans->startTrans();   // 开启事务

            $goods = json_decode($goods,true);
            $gnModel = D('goods_number');
            $orgoodsModel = D('order_goods');
            $cartModel = D('cart');
            $totalPrice = 0;
            $orderId = create_unique();
            foreach ($goods as $k => $v){
                $gnData = $gnModel->field('goods_number,goods_price,discount_price')
                    ->where(array(
                        'goods_id' => array('eq',$v['goods_id']),
                        'goods_attr_id' =>array('eq',$v['goods_attr_id']),
                    ))->select();
                if($gnData[0]['goods_number'] >= $v['cart_number']){
                    $num = $gnData[0]['goods_number'] - $v['cart_number'];

                    $gnModel->where(array(
                        'goods_id' => array('eq',$v['goods_id']),
                        'goods_attr_id' =>array('eq',$v['goods_attr_id']),
                    ))->save(array('goods_number' => $num));

                    $orgoodsModel->add(array(
                        'order_id' => $orderId,
                        'goods_id' => $v['goods_id'],
                        'goods_attr_id' => $v['goods_attr_id'],
                        'price' => $v['goods_price'],
                        'cart_number' => $v['cart_number']
                    ));

                    $cartModel->where(array(
                        'user_id' => array('eq',$userId),
                        'goods_id' => array('eq',$v['goods_id']) ,
                        'goods_attr_id' => array('eq',$v['goods_attr_id']),
                    ))->delete();
                    if($gnData[0]['discount_price'] == 0){
                        $totalPrice = $totalPrice + $gnData[0]['goods_price'] * $v['cart_number'];
                    }else{
                        $totalPrice = $totalPrice + $gnData[0]['discount_price'] * $v['cart_number'];
                    }

                }else{
                    $trans->rollback();
                    flock($fp,LOCK_UN);
                    fclose($fp);
                    return array(
                        'flag' => '10',
                        'mes' => $v['goods_name'].'库存不足！',
                    );
                }
            }
            $data = array(
                'order_id' => $orderId,
                'user_id' => $userId,
                'message' => $mes,
                'address' => $address,
                'price' => $totalPrice,
                'add_time' => time(),
                'status' => '0'
            );
            $result = $this->add($data);
            if($result){
                flock($fp,LOCK_UN);
                fclose($fp);
                //开启微信支付
                $userModel = D('user');
                $openId = $userModel->field('openid')->find($userId);
                $totalPrice = $totalPrice*100;
                $prepay_id = wxorder($orderId,$totalPrice,$openId['openid']);

                if($prepay_id != 'false'){
                    $trans->commit();
                    $this->save(array(
                        'order_id' => $orderId,
                        'prepay_id' => $prepay_id,
                    ));
                    $payData = wxpay($prepay_id);
                    return array(
                        'flag' => '11', //支付成功
                        'payData' => $payData,
                        'orderId' => $orderId,
                    );
                }else{
                    $trans->rollback();
                    return array(
                        'flag' => '10',
                        'mes' => '创建订单失败！',
                    );
                }
            }else{
                $trans->rollback();
                flock($fp,LOCK_UN);
                fclose($fp);
                return array(
                    'flag' => '10',
                    'mes' => '创建订单失败！',
                );
            }
        }else{
           return array('flag' => '0');
        }
    }
    public function userOrder(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');

        $user = checkUser($userId,$thr_session);
        $status = I('get.status');

        if($user){
            $orderData = $this->alias('a')
                ->where(array(
                    'user_id' => array('eq',$userId),
                    'status' => array('eq',$status)
                ))->select();
            $date = time();
            $orGoodsModel = D('order_goods');
            $goodsNuModel = D('goods_number');
            $gaModel = D('goods_attr');
            foreach ($orderData as $k => &$v){
                if($status == '0'){
                     if($date-$v['add_time'] > 60*60*24*2){
                         $this->delete(array(
                             'order_id' => $v['order_id']
                         ));
                         unset($orderData[$k]);
                         
                         $orGoods = $orGoodsModel->where(array(
                             'order_id' => array('eq',$v['order_id'])
                         ))->select();

                         $fp = fopen('./lockOrd.text','r');
                         flock($fp,LOCK_EX);         //锁机制
                         foreach ($orGoods as $k1 => $v1){
                             $goodsNuData = $goodsNuModel->where(array(
                                 'goods_id' => array('eq',$v1['goods_id']),
                                 'goods_attr_id' => array('eq',$v1['goods_attr_id'])
                             ))->find();
                             $goodsNuModel->where(array(
                                 'goods_id' => array('eq',$v1['goods_id']),
                                 'goods_attr_id' => array('eq',$v1['goods_attr_id'])
                             ))->save(array('goods_number' => $goodsNuData['goods_number']+$v1['cart_number']));
                         }
                         flock($fp,LOCK_UN);
                         fclose($fp);
                         continue;
                     }
                }elseif($status == '3'){
                    if($date-$v['update_time'] > 60*60*24*9){
                        $date = time();
                       $this->save(array(
                           'order_id' => $v['order_id'],
                           'status' => '1',
                           'update_time' => $date
                        ));
                       unset($orderData[$k]);
                       continue;
                    }
                }
                $orGoods = $orGoodsModel->field('a.*,b.img_src,c.goods_name')
                    ->alias('a')
                    ->where(array(
                    'a.order_id' => array('eq',$v['order_id'])
                    ))
                    ->join('LEFT JOIN __GOODS_NUMBER__ b ON a.goods_id = b.goods_id AND a.goods_attr_id = b.goods_attr_id
                            LEFT JOIN __GOODS__ c ON a.goods_id = c.id')
                    ->select();
                $colModel = D('color');
                foreach ($orGoods as $k2 => &$v2){
                    $attr = explode(',',$v2['goods_attr_id']);
                    $attV = array();
                    foreach ($attr as $k3 => $v3){
                        if($v3 == '0'){
                            $attV[] = '默认';
                        }else{
                            $attrValue = $gaModel->field('a.attr_value,b.attr_type')
                                ->alias('a')
                                ->join('LEFT JOIN __ATTRIBUTE__ b ON a.attr_id = b.id')
                                ->where(array(
                                    'a.id' => array('eq',$v3),
                                    'a.goods_id' => array('eq',$v2['goods_id'])
                                ))->find();
                            if($attrValue['attr_type'] == '3'){
                                $colData = $colModel->field('color_name')->find($attrValue['attr_value']);
                                $attV[] = $colData['color_name'];
                            }else{
                                $attV[] = $attrValue['attr_value'];
                            }
                        }
                    }
                    $v2['goods_attr_value'] = $attV;
                }
              $v['goods'] =  $orGoods;
            }
        }
        return $orderData;
    }
    public function removeOrder(){
        $orderId = I('get.orderId');
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');

        $user = checkUser($userId,$thr_session);
        if($user){
            $orderData = $this->find($orderId);
            if($orderData['status'] == '0' && $orderData['user_id'] == $userId){
                $orGoodsModel = D('order_goods');
                $goodsNuModel = D('goods_number');
                $orGoods = $orGoodsModel->where(array(
                    'order_id' => array('eq',$orderId)
                ))->select();
                $trans = M();
                $trans->startTrans();   // 开启事务

                $fp = fopen('./lockOrd.text','r');
                flock($fp,LOCK_EX);         //锁机制
                foreach ($orGoods as $k1 => $v1){
                    $goodsNuData = $goodsNuModel->where(array(
                        'goods_id' => array('eq',$v1['goods_id']),
                        'goods_attr_id' => array('eq',$v1['goods_attr_id'])
                    ))->find();
                    $goodsNuModel->where(array(
                        'goods_id' => array('eq',$v1['goods_id']),
                        'goods_attr_id' => array('eq',$v1['goods_attr_id'])
                    ))->save(array('goods_number' => $goodsNuData['goods_number']+$v1['cart_number']));
                }
                flock($fp,LOCK_UN);
                fclose($fp);
                $result = $this->delete($orderId);
                if ($result){
                    $trans->commit();
                    $data = '1';
                }else{
                    $trans->rollback();
                    $data = '10';
                }
            }elseif ($orderData['status'] == '2' && $orderData['user_id'] == $userId){
                $orGoodsModel = D('order_goods');
                $goodsNuModel = D('goods_number');
                $orGoods = $orGoodsModel->where(array(
                    'order_id' => array('eq',$orderId)
                ))->select();

                $trans = M();
                $trans->startTrans();   // 开启事务

                $fp = fopen('./lockOrd.text','r');
                flock($fp,LOCK_EX);         //锁机制
                foreach ($orGoods as $k1 => $v1){
                    $goodsNuData = $goodsNuModel->where(array(
                        'goods_id' => array('eq',$v1['goods_id']),
                        'goods_attr_id' => array('eq',$v1['goods_attr_id'])
                    ))->find();
                    $goodsNuModel->where(array(
                        'goods_id' => array('eq',$v1['goods_id']),
                        'goods_attr_id' => array('eq',$v1['goods_attr_id'])
                    ))->save(array('goods_number' => $goodsNuData['goods_number']+$v1['cart_number']));
                }
                flock($fp,LOCK_UN);
                fclose($fp);
                $date = time();
                $result = $this->save(array(
                    'order_id' => $orderId,
                    'status' => '7',
                    'update_time' => $date
                ));
                if ($result){
                    $orderId = strval($orderId);
                    //执行退款
                    $rufund = refund($orderId,$orderData['price']*100);
                    if($rufund == 'success'){
                        $trans->commit();
                        $data = '11';   //退款成功
                    }else{
                        $trans->rollback();
                        $data = '10';   //退款失败
                    }
                }else{
                    $trans->rollback();
                    $data = '10';
                }
            }else{
                $data = '0';
            }
        }else{
            $data = '0';
        }
        return $data;
    }
    public function payOrder(){
        $prepay = I('get.prepay');
        $data = wxpay($prepay);
        return $data;
    }
    //完成支付
    public function comPay(){
        $orderId = I('get.orderId');
        $pay = inquery(strval($orderId));
        if($pay == "success"){
            $date = time();
            $result = $this->save(array(
                'order_id' => $orderId,
                'status' => '2',
                'update_time' => $date
            ));
            if ($result){
                $data = '1';
            }else {
                $data = '0';
            }
        }else {
            $data = '0';
        }
        return $data;
    }
    //订单状态
    public function orderState(){
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $user = checkUser($userId,$thr_session);
        if($user){
            $orderData = $this->where(array(
                'user_id' => array('eq',$userId)
            ))->select();
            $date = time();
            $staData = array();
            $staData['wait_pay'] = 0;  //待支付
            $staData['wait_deli'] = 0;  //待发货
            $staData['wait_take'] = 0;  //待收货
            foreach ($orderData as $v){
                if($v['status'] == '0' && $date - $v['add_time'] < 60*60*24){
                    $staData['wait_pay'] =  $staData['wait_pay'] + 1;
                }else if($v['status'] == '2'){
                    $staData['wait_deli'] = $staData['wait_deli'] + 1;
                }else if($v['status'] == '3' && $date - $v['update_time'] < 60*60*24){
                    $staData['wait_take'] = $staData['wait_take'] +1;
                }
            }
            return $staData;
        }else{
            return 'false';
        }
    }
    //确认收获
    public function comOrder(){
        $orderId = I('get.orderId');
        $userId = I('get.userId');
        $thr_session = I('get.thr_session');
        $user = checkUser($userId,$thr_session);
        if($user){
            $oederData = $this->find($orderId);
            if($oederData['status'] == '3' && $oederData['user_id'] == $userId){
                $date = time();
                $result = $this->save(array(
                    'order_id' => $orderId,
                    'status' => '1',
                    'update_time' => $date
                ));
                if($result){
                    return '1';
                }
            }
        }else{
            return '0';
        }
    }
    public function test(){
        return refund('20180425144744968261','100');
    }
}
