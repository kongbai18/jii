<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/8 0008
 * Time: 8:48
 */
namespace Admin\Controller;
class OrderController extends BaseController {
    //订单列表
    public function lst(){
        $model = D('order');
        $data = $model->search(20);
        //数据assign到页面中
        $this->assign(array(
            'data' => $data,
            'title' => '订单列表',
        ));
        $this->display();
    }
    //发货单列表
    public function deli(){
        $model = D('order');
        $data = $model->search(20,2);
        //数据assign到页面中
        $this->assign(array(
            'data' => $data,
            'title' => '待发货列表',
        ));
        $this->display();
    }
    //AJAX获取订单中得商品
    public function orderGoods(){
        $model = D('order');
        $data = $model->orderGoods();
        echo json_encode($data);
    }
    //订单发货
    public function orderDeli(){
        $model = D('order');
        $data = $model->orderDeli();
        echo json_encode($data);
    }
}