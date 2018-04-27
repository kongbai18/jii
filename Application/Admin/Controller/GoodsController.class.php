<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/18 0018
 * Time: 9:29
 */
namespace Admin\Controller;
use Think\Controller;
class GoodsController extends BaseController {
    //商品列表
    public function lst(){
        $model = D('goods');
        $data = $model->search(15);
        //数据assign到页面中
        $this->assign(array(
            'data' => $data,
            'title' => '商品列表',
            'btn_name' => '添加商品',
            'btn_url' => U('add')
        ));
        $this->display();
    }
    //添加商品
    public function add(){
        //判断是否接收表单
        if(IS_POST){
            if (empty($_FILES)){
                $this->error('上传图片超出2M，请分次上传！');
            }
            $model = D('goods');
            //判断表单是否验证成功
            if($model->create(I('post.'),1)){
                //判断数据是否添加成功
                if($model->add()){
                    $this->success('商品添加成功！',U('lst'));
                    exit;
                }
            }
            //添加失败
            $this->error($model->getError());
        }
        //获取分类数据
        $catModel = D('category');
        $catData = $catModel->getTree();
        //数据assign到页面中
        $this->assign(array(
            'catData' => $catData,
            'title' => '添加商品',
            'btn_name' => '商品列表',
            'btn_url' => U('lst')
        ));
        $this->display();
    }
    //修改列表
    public function edit(){
        //获取要修改商品的ID并取出商品信息
        $id = I('get.id');
        $model = D('goods');
        $data = $model->find($id);
        //判断是否接收表单
        if(IS_POST){
            $model = D('goods');
            //判断表单是否验证成功
            if($model->create(I('post.'),2)){
                //判断是否修改成功
                if(FALSE !== $model->save()){
                    $this->success('修改成功！',U('lst'));
                }
            }
            $this->error($model->getError());
        }
        //获取商品图片
        $descModel = D('goods_desc');
        $descData = $descModel->where(array(
            'goods_id' => array('eq',$id),
        ))->select();
        //获取商品轮播图
        $giModel = D('goods_img');
        $imgData = $giModel->where(array(
            'goods_id' => array('eq',$id),
        ))->select();
        //获取分类数据
        $catModel = D('category');
        $catData = $catModel->getTree();
        //获取扩展分类数据
        $gcModel = D('goods_cat');
        $gcData = $gcModel->where(array(
            'goods_id' => array('eq',$id)
        ))->select();
        //获取类型属性
        $attModel = D('attribute');
        $attData = $attModel->alias('a')
            ->field('a.id attr_id,a.attr_name,a.attr_type,a.attr_option_values,b.id,b.attr_value')
            ->join('LEFT JOIN __GOODS_ATTR__ b ON a.id=b.attr_id AND b.goods_id='.$data['id'])
            ->where(array(
                'a.type_id' => array('eq',$data['type_id']),
            ))->select();
        $_attData = array();
        foreach ($attData as $v){
            if($v['attr_type'] == 3){
                $colorId = explode(',',$v['attr_option_values']);
                $colModel = D('color');
                $colData = array();
                foreach ($colorId as $v1){
                    $colName = $colModel->field('color_name')->find($v1);
                    $colData[$v1] = $colName['color_name'];
                }
                $v['attr_option_values'] = $colData;
            }
            $_attData[$v['attr_id']][] = $v;
        }
        //var_dump($_attData);die;
        //数据assign到页面中
        $this->assign(array(
            'imgData' => $imgData,
            'descData' => $descData,
            'attData' => $_attData,
            'gcData' => $gcData,
            'catData' => $catData,
            'data' => $data,
            'title' => '修改商品',
            'btn_name' => '商品列表',
            'btn_url' => U('lst')
        ));
        $this->display();
    }
    //删除列表
    public function delete(){
        $id = I('get.id');
        $model = D('goods');
        //判断是否删除成功
        if($model->delete($id)){
            $this->success('商品删除成功！',U('lst'));
        }
        $this->error($model->getError());
    }
    //AJAX获取颜色值
    public function ajaxGetcolor(){
        $colorId = I('get.colorId');
        $colorId = explode(',',$colorId);
        $colModel = D('color');
        $data = array();
        foreach ($colorId as $v){
            $colName = $colModel->field('color_name')->find($v);
            $data[] = $colName['color_name'];
        }
        echo json_encode($data);
    }
    //AJAX获取属性值
    public function ajaxGetAttr(){
        $typeId = I('get.type_id');
        $attrModel = D('attribute');
        $attrData = $attrModel->where(array(
            'type_id' => array('eq',$typeId),
        ))->select();
        echo json_encode($attrData);
    }
    //AJAX删除商品图片
    public function ajaxDelPic(){
        //获得商品图片ID
        $id = I('get.picid');
        //获得旧图片路径
        $gpiModel = D('goods_img');
        $oldImg = $gpiModel->field('img_src')->where(array(
            'id' => array('eq',$id),
        ))->select();
        //从七牛云删除
        foreach($oldImg as  $v){
            $key = rtrim($v['img_src'],'?');
            $key = substr_replace($key,'',0,33);
            qiniu_img_delete($key);
        }
        //从数据库上删除
        $gpiModel->where(array(
            'id' => array('eq',$id),
        ))->delete();
    }
    //库存量
    public function goods_number(){
        //获取商品ID
        $goodsId = I('get.id');
        $gnModel = D('goods_number');
        //取出商品所有可选属性值
        $gaModel = D('goods_attr');
        $gaData = $gaModel->alias('a')
            ->field('a.*,b.attr_name,b.attr_type,b.attr_option_values,b.type_id')
            ->join('LEFT JOIN __ATTRIBUTE__ b ON a.attr_id=b.id')
            ->where(array(
                'a.goods_id' => array('eq',$goodsId),
                'b.attr_type' => array('in',array(2,3)),
            ))->select();
        $colModel = D('color');
        foreach ($gaData as $v){
            if($v['attr_type']  == '3'){
                $colName = $colModel->field('color_name')->find($v['attr_value']);
                $glData[$v['attr_id']]['attr_id'] = $v['attr_id'];
                $glData[$v['attr_id']]['attr_name'] = $v['attr_name'];
                $glData[$v['attr_id']]['id'][] = $v['id'];
                $glData[$v['attr_id']]['attr_value'][] = $colName['color_name'];
            }else{
                $glData[$v['attr_id']]['attr_id'] = $v['attr_id'];
                $glData[$v['attr_id']]['attr_name'] = $v['attr_name'];
                $glData[$v['attr_id']]['id'][] = $v['id'];
                $glData[$v['attr_id']]['attr_value'][] = $v['attr_value'];
            }
        }
        //判断是否接收表单
        if(IS_POST){
            $id = I('post.id');
            $gaid = I('post.goods_attr_id');
            $gn = I('post.goods_number');
            $gp = I('post.goods_price');
            $dp = I('post.discount_price');
            //计算商品属性和库存量比例
            $gaidCount = count($gaid);
            $gnCount = count($gn);
            $rate = $gaidCount/$gnCount;
            //循环库存量
            //var_dump($_POST);die;
            $_i = 0;
            foreach($gn as $k => $v){
                $_goodsAttrId = array();
                for($i=0;$i<$rate;$i++) {
                    if ($gaid[($k*$rate)+$i] === '') {
                        continue 2;
                    }
                    $_goodsAttrId[] = $gaid[($k*$rate)+$i];
                }
                if($gp[$k] == ''){
                    continue;
                }
                if($dp[$k] == ''){
                    $dp[$k] = 0;
                }
                sort($_goodsAttrId,SORT_NUMERIC);//以数字形式升序
                $_goodsAttrId = (string)implode(',',$_goodsAttrId);

                if($_FILES['goods_img']['error'][$k] == '0'){
                    $file = $_FILES['goods_img']['tmp_name'][$k];
                    $key = 'jiimade/view/images/goodsNumImg/'.date("Y/m/d").'/'.rand().$_FILES['goods_img']['name'][$k];
                    $ret = qiniu_img_upload($key,$file);
                    if($ret['flag'] == 1){
                        if($id[$k] != ''){
                            //获取旧LOGO地址
                            $oldImg = $gnModel->field('img_src')->find($id[$k]);
                            //var_dump($oldImg);die;
                            foreach($oldImg as $v1) {
                                if ($v1 != '') {
                                    $key = rtrim($v1, '?');
                                    $key = substr_replace($key,'',0,33);
                                    qiniu_img_delete($key);
                                }
                            }
                        }
                        $img = $ret['img'];
                    }else{
                        $img = '';
                    }
                    if($id[$k] != ''){
                        $data=array(
                            'id' => $id[$k],
                            'goods_id' => $goodsId,
                            'goods_attr_id' => $_goodsAttrId,
                            'goods_number' => $v,
                            'goods_price' => $gp[$k],
                            'discount_price' => $dp[$k],
                            'img_src' => $img,
                        );
                        $gnModel->save($data);
                    }else{
                        $gnModel->add(array(
                            'goods_id' => $goodsId,
                            'goods_attr_id' => $_goodsAttrId,
                            'goods_number' => $v,
                            'goods_price' => $gp[$k],
                            'discount_price' => $dp[$k],
                            'img_src' => $img,
                        ));
                    }
                }else{
                    if($id[$k] != ''){
                        $data=array(
                            'id' => $id[$k],
                            'goods_id' => $goodsId,
                            'goods_attr_id' => $_goodsAttrId,
                            'goods_number' => $v,
                            'goods_price' => $gp[$k],
                            'discount_price' => $dp[$k],
                        );
                        $gnModel->save($data);
                    }else{
                        $gnModel->add(array(
                            'goods_id' => $goodsId,
                            'goods_attr_id' => $_goodsAttrId,
                            'goods_number' => $v,
                            'goods_price' => $gp[$k],
                            'discount_price' => $dp[$k],
                        ));
                    }
                }
            }
            echo "<script language=\"JavaScript\">alert(\"库存修改完成!\");</script>";
        }
        //获取商品库存
        $gnData = $gnModel->where(array(
            'goods_id' => array('eq',$goodsId),
        ))->select();
        //var_dump($gnData);die;
        $this->assign(array(
            'gnData' => $gnData,
            'glData' => $glData,
            'title' => '商品库存',
            'btn_name' => '商品列表',
            'btn_url' => U('lst')
        ));
        $this->display();
    }
    //AJAX删除库存
    public function ajaxDelNum(){
        $id = I('get.id');
        $gnModle = D('goods_number');
        $oldImg = $gnModle->field('img_src')->find($id);
        //从七牛云删除
        $key = rtrim($oldImg['img_src'],'?');
        $key = substr_replace($key,'',0,33);
        qiniu_img_delete($key);
        //从数据库上删除
        $result = $gnModle->delete($id);
        if($result){
            $data = '1';
        }else{
            $data = '0';
        }
        echo json_encode($data);
    }
    //AJAX删除描述图片
    public function ajaxDelDesc(){
        //获得描述图片ID
        $id = I('get.descid');
        //获得旧图片路径
        $descModel = D('goods_desc');
        $oldImg = $descModel->field('img_src')->where(array(
            'id' => array('eq',$id),
        ))->select();
        //从七牛云删除
        foreach($oldImg as  $v){
            $key = rtrim($v['img_src'],'?');
            $key = substr_replace($key,'',0,33);
            qiniu_img_delete($key);
        }
        //从数据库上删除
        $descModel->where(array(
            'id' => array('eq',$id),
        ))->delete();
    }
    //AJAX删除轮播图片
    public function ajaxDelImg(){
        //获得描述图片ID
        $id = I('get.descid');
        //获得旧图片路径
        $giModel = D('goods_img');
        $oldImg = $giModel->field('img_src')->where(array(
            'id' => array('eq',$id),
        ))->select();
        //从七牛云删除
        foreach($oldImg as  $v){
            $key = rtrim($v['img_src'],'?');
            $key = substr_replace($key,'',0,33);
            qiniu_img_delete($key);
        }
        //从数据库上删除
        $giModel->where(array(
            'id' => array('eq',$id),
        ))->delete();
    }
    //AJAX删除属性和相关库存
    public function ajaxDelAttr(){
        $goodsId = I('get.goods_id');
        $gaid = I('get.gaid');
        $gaModel = D('goods_attr');
        $gaModel->delete($gaid);
        //库存
        /*$gnModel = D('goods_number');
        $gnModel->where(array(
            'goods_id' => array('EXP',"=$goodsId AND FIND_IN_SET($gaid,goods_attr_id)"),
        ))->delete();*/
    }
    //AJAX改变商品排序
    public function ajaxChangeSort(){
        $id = I('get.did');
        $sort = I('get.sort');
        $model = D('goods_desc');
        $result = $model->save(array(
                'id' => $id,
                'sort_id' => $sort,
            ));
        if($result !== false){
            $data = true;
        }else{
            $data = false;
        }
        echo json_encode($data);
    }
}