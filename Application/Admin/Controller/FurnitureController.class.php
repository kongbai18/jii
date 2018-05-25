<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/8 0008
 * Time: 8:48
 */
namespace Admin\Controller;
class FurnitureController extends BaseController {
    //模型列表
    public function lst(){
        $model = D('furniture');
        $cabinet = $model->field('id,fur_name')->where(array('cate_id' => array('eq','1')))->order('sort_id asc')->select();
        $door = $model->field('id,fur_name')->where(array('cate_id' => array('eq','2')))->order('sort_id asc')->select();
        $front = $model->field('id,fur_name')->where(array('cate_id' => array('eq','3')))->order('sort_id asc')->select();
        //数据assign到页面中
        $this->assign(array(
            'cabinet' => $cabinet,
            'door' => $door,
            'front' => $front,
            'title' => '家具类型列表',
            'btn_name' => '添加家具类型',
            'btn_url' => U('add')
        ));
        $this->display();
    }
    //类别增加
    public function add(){
        $model = D('furniture');
        //判断是否接收表单
        if(IS_POST){
            //判断是否验证成功
            if($model->create(I('post.'),1)){

                //判断是否添加成功
                if($model->add()){
                    $this->success('家具类型添加成功！',U('lst'));
                }
            }
            //添加失败
            $this->error($model->getError());
        }
        $this->assign(array(
            'title' => '添加家具类型',
            'btn_name' => '家具类型列表',
            'btn_url' => U('lst')
        ));
        $this->display();
    }
    //类别修改
    public function edit(){
        //获取需要修改家具的ID
        $id = I('get.id');
        $model = D('furniture');
        $data = $model->find($id);
        //判断是否接收表单
        if(IS_POST){
            //判断是否验证成功
            if($model->create(I('post.'),2)){
                //判断是否修改成功
                if(FALSE !== $model->save()){
                    $this->success('家具类型修改成功！',U('lst'));
                }
            }
            //添加失败
            $this->error($model->getError());
        }

        //数据assign到页面中
        $this->assign(array(
            'data' => $data,
            'title' => '修改家具类型',
            'btn_name' => '家具类型列表',
            'btn_url' => U('lst')
        ));
        $this->display();
    }
    //模型删除
    public function delete(){
        //接收要删除模型的ID
        $id = I('get.id');
        $model = D('furniture');
        //判断是否删除成功
        if($model->delete($id)){
            $this->success('家具类型删除成功！',U('lst'));
        }
        $this->error($model->getError());
    }
    //家具属性计价模型
    public function furniture_quote(){
        $furId = I('get.id');
        $model = D('furniture');
        $moModel = D('model');
        $furQuoModel = D('furniture_quote');
        $moData = $moModel->field('id,model_name')->select();
        $attr = $model->field('attribute')->find($furId);
        $attr = json_decode($attr['attribute'],true);
        foreach ($attr as $k => $v){
            foreach($v as $k1 => $v1){
                $attr[$k] = $v1;
            }
        }
        $furQuoData = $furQuoModel->where(array('fur_id'=>array('eq',$furId)))->select();

        if(IS_POST){
            $id = I('post.id');
            $modelId = I('post.model_id');
            $faId = I('post.fur_attr_id');
            //计算家具属性和模型比例
            $faIdCount = count($faId);
            $modelIdCount = count($modelId);
            $rate = $faIdCount/$modelIdCount;


            //循环模型
            $_i = 0;
            foreach($modelId as $k => $v){
                if(!$v){
                    continue;
                }

                $_furAttrId = array();
                for($i=0;$i<$rate;$i++) {
                    if ($faId[($k*$rate)+$i] === '') {
                        continue 2;
                    }
                    $_furAttrId[] = $faId[($k*$rate)+$i];
                }

                $_furAttrId = (string)implode(',',$_furAttrId);

                if($_FILES['goods_img']['error'][$k] == '0') {
                    $file = $_FILES['goods_img']['tmp_name'][$k];
                    $key = 'jiimade/view/images/furnitureModel/' . date("Y/m/d") . '/' . rand();
                    $ret = qiniu_img_upload($key, $file);
                    if ($ret['flag'] == 1) {
                        if ($id[$k] != '') {
                            //获取旧LOGO地址
                            $oldImg = $gnModel->field('img_src')->find($id[$k]);

                            foreach ($oldImg as $v1) {
                                if ($v1 != '') {
                                    $key = rtrim($v1, '?');
                                    $key = substr_replace($key, '', 0, 33);
                                    qiniu_img_delete($key);
                                }
                            }
                        }
                        $img = $ret['img'];

                    } else {
                        $img = '';
                    }
                    if($id[$k] != ''){
                        $data=array(
                            'id' => $id[$k],
                            'fur_id' => $furId,
                            'model_id' => $modelId[$k],
                            'fur_attr_id' => $_furAttrId,
                            'img_src' => $img,
                        );
                        $furQuoModel->save($data);
                    }else{
                        $furQuoModel->add(array(
                            'fur_id' => $furId,
                            'model_id' => $modelId[$k],
                            'fur_attr_id' => $_furAttrId,
                            'img_src' => $img,
                        ));
                    }
                }else{
                    if($id[$k] != ''){
                        $data=array(
                            'id' => $id[$k],
                            'fur_id' => $furId,
                            'model_id' => $modelId[$k],
                            'fur_attr_id' => $_furAttrId,
                        );
                        $furQuoModel->save($data);
                    }else{
                        $furQuoModel->add(array(
                            'fur_id' => $furId,
                            'model_id' => $modelId[$k],
                            'fur_attr_id' => $_furAttrId,
                        ));
                    }
                }
            }
            $this->success('家具计算模型修改完成!',U('furniture_quote?id='.$furId));
        }
        $this->assign(array(
            'moData' => $moData,
            'attr' => $attr,
            'furQuoData' => $furQuoData,
            'title' => '修改模型',
            'btn_name' => '模型列表',
            'btn_url' => U('lst')
        ));
        $this->display();
    }
    //AJAX删除家具属性模型
    public function ajaxDelNum(){
        $id = I('get.id');
        $furQuoModle = D('furniture_quote');
        $oldImg = $furQuoModle->field('img_src')->find($id);
        //从七牛云删除
        $key = rtrim($oldImg['img_src'],'?');
        $key = substr_replace($key,'',0,33);
        qiniu_img_delete($key);
        //从数据库上删除
        $result = $furQuoModle->delete($id);
        if($result){
            $data = '1';
        }else{
            $data = '0';
        }
        echo json_encode($data);
    }
}