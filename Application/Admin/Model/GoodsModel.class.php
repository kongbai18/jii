<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/18 0018
 * Time: 10:20
 */
namespace Admin\Model;
use Think\Model;
class GoodsModel extends Model {
    //添加商品时接收的字段
    protected $insertFields = array('goods_name', 'cat_id', 'type_id', 'is_on_sale', 'is_new', 'is_hot','is_quote','sort_id','tag');
    protected $updateFields = array('id','goods_name', 'cat_id', 'type_id', 'is_on_sale', 'is_new', 'is_hot','is_quote', 'sort_id','tag');
    protected $_validate = array(
        array('goods_name', 'require', '商品名称不能为空！', 1),
        array('cat_id', 'require', '请选择商品分类！', 1),
    );

    //添加之前
    public function _before_insert(&$data,$option){
        $data['tag'] = str_replace('，',',',$data['tag']);
        //添加添加时间
        $data['add_time'] = time();
    }

    //添加之后
    public function _after_insert($data, $option)
    {
        /***************处理商品属性************************/
        //接收表单数据
        $attrVal = I('post.attr_value');
        $gaModel = D('goods_attr');
        foreach ($attrVal as $k => $v) {
            foreach ($v as $k1 => $v1) {
                if ($v1) {
                    $gaModel->add(array(
                        'attr_id' => $k,
                        'attr_value' => $v1,
                        'goods_id' => $data['id'],
                    ));
                }
            }
        }

        /***************处理扩展分类***********************/
        //接收表单数据
        $catId = I('post.ext_cat_id');
        //实例化商品分类扩展
        $_catId = array($data['cat_id']);
        $gcModel = D('goods_cat');
        foreach ($catId as $k => $v) {
            if (empty($v) || in_array($v, $_catId)) {
                continue;
            } else {
                $gcModel->add(array(
                    'cat_id' => $v,
                    'goods_id' => $data['id'],
                ));
                $_catId[] = $v;
            }
        }
        /***************处理商品描述***********************/
        $imgs = array();
        foreach ($_FILES['pic']['name'] as $k => $v) {
            $imgs[$k]['name'] = $_FILES['pic']['name'][$k];
            $imgs[$k]['type'] = $_FILES['pic']['type'][$k];
            $imgs[$k]['tmp_name'] = $_FILES['pic']['tmp_name'][$k];
            $imgs[$k]['error'] = $_FILES['pic']['error'][$k];
            $imgs[$k]['size'] = $_FILES['pic']['size'][$k];
        }
        $goodsDescModel = D('goods_desc');
        foreach ($imgs as $k => $v) {
            if ($v['error'] == 0) {
                $file = $v['tmp_name'];
                $key = 'jiimade/view/images/goodsdesc/' . date("Y/m/d") . '/' . rand();
                $ret = qiniu_img_upload($key, $file);
                if ($ret['flag'] == 1) {
                    $goodsDescModel->add(array(
                        'img_src' => $ret['img'],
                        'goods_id' => $data['id'],
                    ));
                }
            }
        }
        /***************处理商品轮播图***********************/
        $lunImgs = array();
        foreach ($_FILES['lun_pic']['name'] as $k => $v) {
            $lunImgs[$k]['name'] = $_FILES['lun_pic']['name'][$k];
            $lunImgs[$k]['type'] = $_FILES['lun_pic']['type'][$k];
            $lunImgs[$k]['tmp_name'] = $_FILES['lun_pic']['tmp_name'][$k];
            $lunImgs[$k]['error'] = $_FILES['lun_pic']['error'][$k];
            $lunImgs[$k]['size'] = $_FILES['lun_pic']['size'][$k];
        }
        $goodsImgModel = D('goods_img');
        foreach ($lunImgs as $k => $v) {
            if ($v['error'] == 0) {
                $file = $v['tmp_name'];
                $key = 'jiimade/view/images/goodslun/' . date("Y/m/d") . '/' . rand();
                $ret = qiniu_img_upload($key, $file);
                if ($ret['flag'] == 1) {
                    $goodsImgModel->add(array(
                        'img_src' => $ret['img'],
                        'goods_id' => $data['id'],
                    ));
                }
            }
        }
    }

    //修改之前
    public function _before_update(&$data,$option)
    {
        $data['tag'] = str_replace('，',',',$data['tag']);
        if (!$data['is_new']) {
            $data['is_new'] = '0';
        }
        if (!$data['is_hot']) {
            $data['is_hot'] = '0';
        }
        /***************处理商品属性******************/
        $gaid = I('post.goods_attr_id');
        $typeId = I('post.type_id');
        $attrValue = I('post.attr_value');
        $gaModel = D('goods_attr');
        $goodsModel = D('goods');
        $oldTpye = $goodsModel->field('type_id')->find($option['where']['id']);
        if($oldTpye['type_id'] == $typeId){
            $_i = 0;
            foreach($attrValue as $k => $v){
                foreach($v as $k1 => $v1){
                    if($gaid[$_i]){
                        if($v1 == ''){
                            //删除
                            $gaModel->delete($gaid[$_i]);
                            /*
                             * 库存处理
                             */
                            $gnModel = D('goods_number');
                            $gnData = $gnModel->where(array('goods_id'=>$option['where']['id']));
                            foreach($gnData as $v){
                                $goodsAttrId = explode(',',$v['goods_attr_id']);
                                if(in_array($gaid[$_i],$goodsAttrId)){
                                    $key = rtrim($v['img_src'], '?');
                                    $key = substr_replace($key,'',0,33);
                                    qiniu_img_delete($key);
                                    $gnModel->delete($v['id']);
                                }
                            }
                        }else{
                            //修改
                            $gaModel->setField(array(
                                'id' => $gaid[$_i],
                                'attr_value' => $v1,
                            ));
                        }
                    }else{
                        //添加
                        if($v1){
                            $gaModel->add(array(
                                'attr_value' => $v1,
                                'attr_id' => $k,
                                'goods_id' => $option['where']['id'],
                            ));
                        }
                    }
                    $_i++;
                }
            }
        }else{
            $gaModel->where(array(
                'goods_id' => array('eq',$option['where']['id'])
            ))->delete();
            $attrVal = I('post.attr_value');
            $gaModel = D('goods_attr');
            foreach ($attrVal as $k => $v) {
                foreach ($v as $k1 => $v1) {
                    if ($v1) {
                        $gaModel->add(array(
                            'attr_id' => $k,
                            'attr_value' => $v1,
                            'goods_id' => $option['where']['id'],
                        ));
                    }
                }
            }
        }

        /***************处理扩展分类***************/
        //删除原分类
        $gcModel = D('goods_cat');
        $gcModel->where(array(
            'goods_id' => array('eq', $option['where']['id'])
        ))->delete();
    }

    //修改后
    public function _after_update($data,$option)
    {
        /***************处理扩展分类***********************/
        //接收表单数据
        $catId = I('post.ext_cat_id');
        //实例化商品分类扩展
        $_catId = array($data['cat_id']);
        $gcModel = D('goods_cat');
        foreach ($catId as $k => $v) {
            if (empty($v) || in_array($v, $_catId)) {
                continue;
            } else {
                $gcModel->add(array(
                    'cat_id' => $v,
                    'goods_id' => $data['id'],
                ));
                $_catId[] = $v;
            }
        }
        /***************处理商品描述***********************/
        $imgs = array();
        foreach ($_FILES['pic']['name'] as $k => $v) {
            $imgs[$k]['name'] = $_FILES['pic']['name'][$k];
            $imgs[$k]['type'] = $_FILES['pic']['type'][$k];
            $imgs[$k]['tmp_name'] = $_FILES['pic']['tmp_name'][$k];
            $imgs[$k]['error'] = $_FILES['pic']['error'][$k];
            $imgs[$k]['size'] = $_FILES['pic']['size'][$k];
        }
        $goodsDescModel = D('goods_desc');
        foreach ($imgs as $k => $v) {
            if ($v['error'] == 0) {
                $file = $v['tmp_name'];
                $key = 'jiimade/view/images/goodsdesc/' . date("Y/m/d") . '/' . rand();
                $ret = qiniu_img_upload($key, $file);
                if ($ret['flag'] == 1) {
                    $goodsDescModel->add(array(
                        'img_src' => $ret['img'],
                        'goods_id' => $data['id'],
                    ));
                }
            }
        }
        /***************处理商品轮播图***********************/
        $lunImgs = array();
        foreach ($_FILES['lun_pic']['name'] as $k => $v) {
            $lunImgs[$k]['name'] = $_FILES['lun_pic']['name'][$k];
            $lunImgs[$k]['type'] = $_FILES['lun_pic']['type'][$k];
            $lunImgs[$k]['tmp_name'] = $_FILES['lun_pic']['tmp_name'][$k];
            $lunImgs[$k]['error'] = $_FILES['lun_pic']['error'][$k];
            $lunImgs[$k]['size'] = $_FILES['lun_pic']['size'][$k];
        }
        $goodsImgModel = D('goods_img');
        foreach ($lunImgs as $k => $v) {
            if ($v['error'] == 0) {
                $file = $v['tmp_name'];
                $key = 'jiimade/view/images/goodslun/' . date("Y/m/d") . '/' . rand();
                $ret = qiniu_img_upload($key, $file);
                if ($ret['flag'] == 1) {
                    $goodsImgModel->add(array(
                        'img_src' => $ret['img'],
                        'goods_id' => $data['id'],
                    ));
                }
            }
        }
    }
    //删除之前
    public function _before_delete($option){
        $gnModel = D('goods_number');
        $gdModel = D('goods_desc');
        $gcModel = D('goods_cat');
        $gaModel = D('goods_attr');
        $giModel = D('goods_img');
        //处理库存
        $gnData = $gnModel->where(array('goods_id'=>$option['where']['id']))->select();
        foreach($gnData as $v){
            $key = rtrim($v['img_src'],'?');
            $key = substr_replace($key,'',0,33);
            qiniu_img_delete($key);
            $gnModel->delete($v['id']);
        }
        //处理商品描述；
        $gdData = $gdModel->where(array('goods_id'=>$option['where']['id']))->select();
        foreach($gdData as $v){
            $key = rtrim($v['img_src'],'?');
            $key = substr_replace($key,'',0,33);
            qiniu_img_delete($key);
            $gdModel->delete($v['id']);
        }
        //处理商品轮播；
        $giData = $giModel->where(array('goods_id'=>$option['where']['id']))->select();
        foreach($giData as $v){
            $key = rtrim($v['img_src'],'?');
            $key = substr_replace($key,'',0,33);
            qiniu_img_delete($key);
            $giModel->delete($v['id']);
        }
        //处理扩展分类
        $gcModel->where(array('goods_id'=>$option['where']['id']))->delete();
        //处理商品属性
        $gaModel->where(array('goods_id'=>$option['where']['id']))->delete();
    }
    //搜索商品信息
    public function search($perpage){
        $where = array();
        //商品名称搜索
        $keyword = I('get.keyword');
        if($keyword){
            $where['goods_name'] = array('like',"%$keyword%");
        }
        //品牌搜索
        $brandId = I('get.brand_id');
        if($brandId){
            $where['brand_id'] = array('eq',$brandId);
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
        $data = $this->field('a.*,c.name as cat_name')
            ->alias('a')
            ->join('LEFT JOIN __CATEGORY__ c ON a.cat_id=c.id')
            ->where($where)
            ->order('order_id asc')
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();
        return  array(
            'data' => $data,
            'page' => $pageString,
        );
    }
    //获取特定分类下特定数量商品
   public function getCatGoods(){
        $kind = I('get.kind');
        $catId = I('get.catId');
        $limit = I('get.goodsNum');
        if($kind == '' || $kind == 'zong'){
            $order = 'a.sort_id asc';
        }elseif($kind == 'new'){
            $order = 'a.add_time desc';
        }elseif($kind == 'up'){
            $order = 'min_goods_price asc';
        }elseif($kind == 'down'){
            $order = 'min_goods_price desc';
        }
        $where['cat_id'] = array('eq',$catId);
        $where['is_on_sale'] = array('eq','1');
        $goodsData = $this->field('a.id,a.goods_name,a.tag,max(b.goods_price) as max_goods_price,min(b.goods_price) as min_goods_price,max(b.discount_price) as max_discount_price,min(b.discount_price) as min_discount_price,max(b.img_src) as img_src')
                     ->alias('a')
                     ->group('a.id')
                     ->order($order)
                     ->join('LEFT JOIN __GOODS_NUMBER__ b ON a.id = b.goods_id')
                     ->where($where)
                     ->limit($limit)
                     ->select();
        foreach ($goodsData as $k => $v){
            if($goodsData[$k]['tag']){
                $goodsData[$k]['tag'] = explode(',',$v['tag']);
            }else{
                $goodsData[$k]['tag'] = '';
            }
        }
        return $goodsData;
   }
    //APP搜索商品
    public function goodsSearch(){
        $keyword = I('get.keyword');
        if ($keyword) {
            $where['goods_name'] = array('like', "%$keyword%");
        }
        $goodsData = $this->field('a.id,a.goods_name,a.tag,max(b.goods_price) as max_goods_price,min(b.goods_price) as min_goods_price,max(b.discount_price) as max_discount_price,min(b.discount_price) as min_discount_price,max(b.img_src) as img_src')
            ->alias('a')
            ->group('a.id')
            ->order('a.sort_id asc')
            ->join('LEFT JOIN __GOODS_NUMBER__ b ON a.id = b.goods_id')
            ->where($where)
            ->select();
        foreach ($goodsData as $k => $v){
            if($goodsData[$k]['tag']){
                $goodsData[$k]['tag'] = explode(',',$v['tag']);
            }else{
                $goodsData[$k]['tag'] = '';
            }
        }
        return $goodsData;
    }
    //获取商品详情
    public function getGoodsDetail(){
        $id = I('get.id');
        //基本信息
        $where['a.id'] = array('eq',$id);
        $goodsData = $this->field('a.id,a.goods_name,a.tag,a.type_id,b.id as cat_id,b.name as cat_name')
                     ->alias('a')
                     ->join('LEFT JOIN __CATEGORY__ b ON a.cat_id = b.id')
                     ->where($where)
                     ->find();
        if($goodsData['tag']){
            $goodsData['tag'] = explode(',',$goodsData['tag']);
        }else{
            $goodsData['tag'] = '';
        }

        //价格
        $gnModel = D('goods_number');
        $goodsPrice = $gnModel->field('min(goods_attr_id) as goods_attr_id,max(goods_price) as max_goods_price,min(goods_price) as min_goods_price,max(discount_price) as max_discount_price,min(discount_price) as min_discount_price,max(img_src) as img_src')
                              ->group('goods_id')
                              ->where(array('goods_id' => array('eq',$id)))
                              ->find();
        $gnData = $gnModel->field('goods_attr_id,goods_number')
                            ->where(array(
                                'goods_id' => array('eq',$id),
                                'goods_attr_id' => array('eq',$goodsPrice['goods_attr_id'])
                            ))
                            ->find();
        //轮播图
        $giModel = D('goods_img');
        $goodsPic = $giModel->field('img_src')
            ->where(array('goods_id' => array('eq',$id)))
            ->select();
        //属性
        $attrModel = D('goods_attr');
        $attrWhere['goods_id'] = array('eq',$id);
            $attrData = $attrModel->field('a.*,b.attr_name,b.attr_type')
            ->alias('a')
            ->join('LEFT JOIN __ATTRIBUTE__ b ON a.attr_id=b.id')
            ->where($attrWhere)
            ->select();
        $colModel = D('color');
        foreach ($attrData as $v){
            if($v['attr_type'] == '2'){
                $attrNouniData[$v['attr_id']][] = $v;
            }elseif($v['attr_type'] == '3'){
                $color = $colModel->field('img_src')->find($v['attr_value']);
                $v['img_src'] = $color['img_src'];
                $attrColData[$v['attr_id']][] = $v;
            }elseif($v['attr_type'] == '1'){
                $attrUniData[$v['attr_id']][] = $v;
            }
        }
        if((count($attrNouniData) + count($attrColData)) == 1){
            if(count($attrNouniData) == 1){
                foreach ($attrNouniData as $k => $v){
                    foreach ($v as $k1 => $v1){
                        $gn = $gnModel->field('goods_number')->where(array('goods_attr_id'=>$v1['id'],'goods_id'=>$id))->select();
                        if(!$gn[0]['goods_number']){
                            $attrNouniData[$k][$k1]['num'] = 'false';
                        }
                    }
                }
            }else{
                foreach ($attrColData as $k => $v){
                    foreach ($v as $k1 => $v1){
                        $gn = $gnModel->field('goods_number')->where(array('goods_attr_id'=>$v1['id'],'goods_id'=>$id))->select();
                        if(!$gn[0]['goods_number']){
                            $attrColData[$k][$k1]['num'] = 'false';
                        }
                    }
                }
            }
        }
        //商品描述
        $gdModel = D('goods_desc');
        $goodsdescData = $gdModel->field('img_src')
                         ->order('sort_id asc')
                         ->where(array('goods_id' => array('eq',$id)))
                         ->select();
        $data = array(
            'gnData' => $gnData,
            'goodsData' => $goodsData,
            'goodsPrice' => $goodsPrice,
            'goodsPic' => $goodsPic,
            'goodsdescData' => $goodsdescData,
            'attrUniData' => $attrUniData,
            'attrColData' => $attrColData,
            'attrNouniData' => $attrNouniData,
            'gn' => $gn,
        );

        return $data;
    }
    //选择商品属性
    public function changeAttr(){
        $goodsId = I('get.goodsId');
        $goodsAttrId = I('get.goodsAttrId');
        $goodsAttrId = explode(',',$goodsAttrId);
        foreach ($goodsAttrId as $v){
            if($v != '' && $v != 'undefined'){
                $attr[] = $v;
            }
        }
        sort($attr,SORT_NUMERIC);//以数字形式升序
        $attrStr = (string)implode(',',$attr);
        $gnModel = D('goods_number');
        $gnData = $gnModel->field('goods_price,discount_price,goods_number,goods_attr_id,img_src')
                    ->where(array(
                        'goods_id' => array('eq',$goodsId),
                        'goods_attr_id' =>array('eq',$attrStr)
                    ))->find();
        if(empty($gnData)){
            $gnData = '';
        }
        //获取所有库存不为0的属性
        $gnAllData = $gnModel->field('goods_attr_id,discount_price,goods_price')
            ->where(array(
                'goods_id' => array('eq',$goodsId),
                'goods_number' => array('gt','0')
            ))
            ->select();

        $attrModel = D('goods_attr');
        $attrWhere['a.goods_id'] = array('eq',$goodsId);
        $attrWhere['b.attr_type'] = array('in',array(2,3));
        $attData = $attrModel->field('a.*,b.attr_name,b.attr_type')
            ->alias('a')
            ->join('LEFT JOIN __ATTRIBUTE__ b ON a.attr_id=b.id')
            ->where($attrWhere)
            ->select();
        foreach ($attData as $v){
            $attrData[$v['attr_id']][] = $v;
        }
        foreach ($attr as $v){
            foreach ($attrData as &$v1){
                foreach ($v1 as &$v2){
                    if($v2['id']==$v ){
                        $v1[0]['attr'] = $v;
                        $v2['attr'] = $v;
                    }
                }
            }
        }

        //获取商品所有可选属性
        $att = array();
        $max = count($attrData);


        foreach ($attrData as $k => $v){
            foreach ($v as $v3){
                $att[$k][] = $v3['id'];
            }
        }
        $n = count($attr);
        $sub_array = array();
        if($n<=$max && $n > 1){
            for ($i = 0; $i < $n; $i++) {
                $sub_attr = $attr;
                unset($sub_attr[$i]);
                $sub_array[] = $sub_attr;
            }
            if($n < $max){
                $sub_array[] = $attr;
            }
        }else if($n=1){
            $sub_array[] = $attr;
        }

        if($max > 1){
            if($n>=1){
                foreach ($sub_array as $k4 => $v4) {
                    $haAttr = array();
                    foreach ($v4 as $k5 => $v5) {
                        foreach ($att as $k6 => $v6) {
                            if (in_array($v5, $v6)) {
                                $haAttr[] = $k6;
                            }
                        }
                    }
                    foreach ($att as $k7 => $v7) {
                        if (!in_array($k7, $haAttr)) {
                            foreach ($att[$k7] as $k8 => $v8) {
                                $num = 0;
                                foreach ($gnAllData as $k9 => $v9) {
                                    $zuhe = explode(',', $v9['goods_attr_id']);
                                    if ($v4 == array_intersect($v4, $zuhe) && in_array($v8, $zuhe)) {
                                        $num = $num + 1;
                                    }
                                }
                                if ($num == 0) {
                                    foreach ($attrData[$k7] as $k10 => $v10) {
                                        if ($v10['id'] == $v8) {
                                            $attrData[$k7][$k10]['num'] = 'false';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if($max == 1){
            foreach ($attrData as $k3 => $v3){
                foreach ($v as $k4 => $v4){
                    $gn = $gnModel->field('goods_number')->where(array('goods_attr_id'=>$v4['id'],'goods_id'=>$goodsId))->select();
                    if(!$gn[0]['goods_number']){
                        $attrData[$k3][$k4]['num'] = 'false';
                    }
                }
            }
        }
        $attrNouniData = array();
        $attrColData = array();
        $colModel = D('color');
        foreach ($attrData as $k => $v){
            if($v[0]['attr_type'] == '2'){
                $attrNouniData[$v[0]['attr_id']] = $v;
            }else{
                foreach($v as &$v1){
                    $color = $colModel->field('img_src')->find($v1['attr_value']);
                    $v1['img_src'] = $color['img_src'];
                }
                $attrColData[$v[0]['attr_id']] = $v;
            }
        }
        $data = array(
            'gnData' => $gnData,
            'attrNouniData' => $attrNouniData,
            'attrColData' => $attrColData
        );
        return $data;
    }
    //获得商品二维码
    public function get_prcode() {
        $access = json_decode(get_access_token(),true);
        $access_token= $access['access_token'];
        $id = I('get.id');
        $path="pages/product/product?goodsId=".$id;
        $width=430;
        $post_data='{"path":"'.$path.'","width":'.$width.'}';
        $url="https://api.weixin.qq.com/wxa/getwxacode?access_token=".$access_token;
        $result = get_http_array($url,$post_data);
        return $result;
    }
    //展示商品图片
    public function showImg(){
        $id = I('get.id');
        $gnModel = D('goods_number');
        $data = $gnModel->field('max(img_src) as img_src')->group('goods_id')->where(array('goods_id'=>$id))->find();
        return showImg($data['img_src']);
    }
}