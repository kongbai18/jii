<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/8 0008
 * Time: 8:48
 */
namespace Admin\Controller;
class QuoteController extends BaseController {
    //模型列表
    public function lst(){
        $model = D('quote');
        $data = $model->search();
        //数据assign到页面中
        $this->assign(array(
            'data'  => $data,
            'title' => '报价单列表',
            'btn_name' => '添加报价单',
            'btn_url' => U('add')
        ));
        $this->display();
    }
    //类别增加
    public function add(){
        $model = D('quote');
        //判断是否接收表单
        if(IS_POST){
            $_POST['id'] = $model->create_unique();
            //判断是否验证成功
            if($model->create(I('post.'),1)){
                //判断是否添加成功
                if($model->add()){
                    $this->success('类型添加成功！',U('detail?id='.$_POST['id']));
                }
            }
            //添加失败
            $this->error($model->getError());
        }
        //数据assign到页面中
        $this->assign(array(
            'title' => '添加报价单',
            'btn_name' => '报价单列表',
            'btn_url' => U('lst')
        ));
        $this->display();
    }
    //类别修改
    public function edit(){
        //获取需要修改类型的ID
        $id = I('get.id');
        $model = D('type');
        //判断是否接收表单
        if(IS_POST){
            //判断是否验证成功
            if($model->create(I('post.'),2)){
                //判断是否修改成功
                if(FALSE !== $model->save()){
                    $this->success('类型修改成功！',U('lst'));
                }
            }
            //添加失败
            $this->error($model->getError());
        }
        //获取修改类型数据
        $data = $model->find($id);
        //数据assign到页面中
        $this->assign(array(
            'data' => $data,
            'title' => '修改类型',
            'btn_name' => '类型列表',
            'btn_url' => U('lst')
        ));
        $this->display();
    }
    //模型删除
    public function delete(){
        //接收要删除模型的ID
        $id = I('get.id');
        $model = D('quote');
        //判断是否删除成功
        if($model->delete($id)){
            $this->success('报价单删除成功！',U('lst'));
        }
        $this->error($model->getError());
    }
    //报价单详情
    public function detail(){
        $id = I('get.id');
        $model = D('quote');
        $data = $model->find($id);
        $moduleModel = D('module');
        $moduleData = $moduleModel->getInfo($id);
        $this->assign(array(
            'moduleData' => $moduleData,
            'data' => $data,
            'title' => '报价单详情',
            'btn_name' => '添加模块',
            'btn_url' => U('chooseModel?id='.$id)
        ));
        $this->display();
    }
    public function chooseModel(){
        $id = I('get.id');
        $model = D('model');
        $mData = $model->field('id,model_name,img_src')->order('sort_id asc')->select();
        $this->assign(array(
            'quote' => $id,
            'data' => $mData,
            'title' => '选择模型',
            'btn_name' => '返回报价单详情',
            'btn_url' => U('detail?id='.$id)
        ));
        $this->display();
    }
    public function addModule(){
        $quoteId = I('get.quoteId');
        $modelId = I('get.id');
        $mModel = D('model');
        $mData = $mModel->find($modelId);
        $goodsModel = D('goods');
        $img = $mData['img_src'];
        $material = json_decode($mData['material'],true);
        $parameter = json_decode($mData['parameter'],true);
        //判断是否接收表单
        if(IS_POST){
             $moduleModel = D('module');
            //判断是否验证成功
            if($moduleModel->create(I('post.'),1)){
                $_POST['model_id'] = $mData['id'];
                $_POST['model_cate'] = $mData['model_cate'];
                $_POST['quote_id'] = $quoteId;
                $_POST['material'] = array();
                foreach ($material as $k => $v){
                    $$k = I('post.'.$k);
                    if(empty($$k)){
                        $this->error('请选择完整的材料！');
                    }
                    foreach ($v as $k1 => $v1){
                        $_POST['material'][$k] = $$k;
                    }
                    unset($_POST[$k]);
                }

                $_POST['parameter'] = array();
                foreach ($parameter as $k => $v){
                    $$v = I('post.'.$v);
                    if(empty($$v)){
                        $this->error('请填写完整参数！');
                    }
                    $_POST['parameter'][$v] = $$v;
                    unset($_POST[$v]);
                }
                //判断是否修改成功
                if($moduleModel->add()){
                    $quoteModel = D('quote');
                    $data = array(
                      'id' => $quoteId,
                      'update_time' => time(),
                    );
                    $quoteModel->save($data);
                    $this->success('模块产品添加成功！',U('detail?id='.$quoteId));
                }
            }
            //添加失败
            $this->error($moduleModel->getError());
        }

       foreach ($material as $k => &$v){
           foreach ($v as $k1 => &$v1){
               $cateId = explode(',',$v1);
               $goodsData = $goodsModel->field('id,goods_name')->where(array('cat_id'=>array('in',$cateId),'is_quote'=>array('eq','1')))->select();
               $v1 = $goodsData;
           }
       }
        $this->assign(array(
            'img' => $img,
            'material' => $material,
            'parameter' => $parameter,
            'title' => '添加模块',
            'btn_name' => '重新选择模块',
            'btn_url' => U('chooseModel?id='.$quoteId)
        ));
        $this->display();
    }
}