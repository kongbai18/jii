<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    //微信小程序登陆
    public function login(){
        $userModel = D('Admin/User');
        echo json_encode($userModel->login());
    }
    //获取特定分类下特定数量商品
    public function getCatGoods(){
        $model = D('Admin/goods');
        echo json_encode($model->getCatGoods());
    }
    //获取首页特定模块展示
    public function getBlockInfo(){
        $model = D('Admin/category');
        //var_dump($model->getBlockInfo());
        echo json_encode($model->getBlockInfo());
    }
    //获取两级
    public function getCate(){
        $model = D('Admin/category');
        echo json_encode($model->getCate());
    }
    //获取设计文章
    public function getArticle(){
        $model = D('Admin/article');
        echo json_encode($model->getArticle());
    }
    //获取单篇文章
    public function getOneArt(){
        $model = D('Admin/article');
        echo json_encode($model->getOneArt());
    }
    //获取设计文章详情
    public function getArtiDesc(){
        $model = D('Admin/article');
        echo json_encode($model->getArtiDesc());
    }
    //搜索商品
    public function goodsSearch(){
        $goodsModel = D('Admin/Goods');
        echo json_encode($goodsModel->goodsSearch());
    }
    //获取商品详情
    public function getGoodsDetail(){
        $model = D('Admin/goods');
        //var_dump($model->getGoodsDetail());
        echo json_encode($model->getGoodsDetail());
    }
    //选择商品属性
    public function changeAttr(){
        $model = D('Admin/goods');
        //var_dump($model->getGoodsDetail());
        echo json_encode($model->changeAttr());
    }
    //添加商品到购物车
    public function addCart(){
        $cartModel = D('Admin/Cart');
        echo json_encode($cartModel->addCart());
    }
    //购物车列表
    public function listCart(){
        $cartModel = D('Admin/Cart');
        echo json_encode($cartModel->listCart());
    }
    //删除购物车中物品
    public function delCart(){
        $cartModel = D('Admin/Cart');
        echo json_encode($cartModel->delCart());
    }
    //添加地址
    public function addAddress(){
        $addressModel = D('Admin/Address');
        echo json_encode($addressModel->addAddress());
    }
    //地址列表
    public function addressList(){
        $addressModel = D('Admin/Address');
        echo json_encode($addressModel->addressList());
    }
    //修改默认地址
    public function editDefault(){
        $addressModel = D('Admin/Address');
        echo json_encode($addressModel->editDefault());
    }
    //获取地址信息
    public function addrInfo(){
        $addressModel = D('Admin/Address');
        echo json_encode($addressModel->addrInfo());
    }
    //删除地址信息
    public function delAddr(){
        $addressModel = D('Admin/Address');
        echo json_encode($addressModel->delAddr());
    }
    //获取默认地址
    public function addressDef(){
        $addressModel = D('Admin/Address');
        echo json_encode($addressModel->addressDef());
    }
    //生成订单
    public function addOrder(){
        $orderModel = D('Admin/Order');
        echo json_encode($orderModel->addOrder());
    }
    //订单信息
    public function userOrder(){
        $orderModel = D('Admin/Order');
        echo json_encode($orderModel->userOrder());
    }
    //订单状态
    public function orderState(){
        $orderModel = D('Admin/Order');
        echo json_encode($orderModel->orderState());
    }
    //取消订单
    public function removeOrder(){
        $orderModel = D('Admin/Order');
        echo json_encode($orderModel->removeOrder());
    }
    //完成支付
    public function comPay(){
        $orderModel = D('Admin/Order');
        echo json_encode($orderModel->comPay());
    }
    //支付
    public function payOrder(){
        $orderModel = D('Admin/Order');
        echo json_encode($orderModel->payOrder());
    }
    //完成订单
    public function comOrder(){
        $orderModel = D('Admin/Order');
        echo json_encode($orderModel->comOrder());
    }
    //获取文章二维码
    public function get_artcode(){
        $model = D('Admin/Article');
        echo json_encode($model->get_artcode());
    }
    //获取商品二维码
    public function get_prcode(){
        $model = D('Admin/Goods');
        echo json_encode($model->get_prcode());
    }
    //展示文章封面
    public function showArtImg(){
        $model = D('Admin/Article');
        echo $model->showImg();
    }
    //展示商品封面
    public function showPrImg(){
        $model = D('Admin/Goods');
        echo $model->showImg();
    }
    //获取手机号
    public function getPhone(){
        $model = D('Admin/user');
        echo json_encode($model->getPhone());
    }
    //选择家具
    public function chooseFurniture(){
        $model = D('furniture');
        $furData = $model->field('id,fur_name,img_src')->order('sort_id desc')->select();
        echo json_encode($furData);
    }
    //获取家具扩展属性
    public function getFurAttr(){
        $model = D('furniture');
        $furId = I('get.furId');
        $furData = $model->find($furId);
        $attribute = json_decode($furData['attribute'],true);
        $data = array(
          'attribute' => $attribute,
          'furData' => $furData,
        );
        echo json_encode($data);
    }
    public function test(){
        $model = D('Admin/Model');
        var_dump($model->getModel());
    }

}