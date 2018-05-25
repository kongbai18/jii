<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/8 0008
 * Time: 8:48
 */
namespace Admin\Controller;
class ModelController extends BaseController {
    //模型列表
    public function lst(){
        $model = D('model');
        $data = $model->field('id,model_name')->select();
        //数据assign到页面中
        $this->assign(array(
            'data'  => $data,
            'title' => '模型列表',
            'btn_name' => '添加模型',
            'btn_url' => U('add')
        ));
        $this->display();
    }
    //类别增加
    public function add(){
        $model = D('model');
        $catModel = D('category');
        //判断是否接收表单
        if(IS_POST){
            //判断是否验证成功
            if($model->create(I('post.'),1)){
                //判断是否添加成功
                if($model->add()){
                    $this->success('类型添加成功！',U('lst'));
                }
            }
            //添加失败
            $this->error($model->getError());
        }
        $catData = $catModel->getTree();
        //数据assign到页面中
        $this->assign(array(
            'catData' => $catData,
            'title' => '添加模型',
            'btn_name' => '模型列表',
            'btn_url' => U('lst')
        ));
        $this->display();
    }
    //类别修改
    public function edit(){
        //获取需要修改类型的ID
        $id = I('get.id');
        $model = D('model');
        $catModel = D('category');
        $data = $model->find($id);
        //判断是否接收表单
        if(IS_POST){
            //判断是否验证成功
            if($model->create(I('post.'),2)){
                //判断是否修改成功
                if(FALSE !== $model->save()){
                    $this->success('模型修改成功！',U('lst'));
                }
            }
            //添加失败
            $this->error($model->getError());
        }
        $catData = $catModel->getTree();
        //数据assign到页面中
        $this->assign(array(
            'data' => $data,
            'catData' => $catData,
            'title' => '修改模型',
            'btn_name' => '模型列表',
            'btn_url' => U('lst')
        ));
        $this->display();
    }
    //模型删除
    public function delete(){
        //接收要删除模型的ID
        $id = I('get.id');
        $model = D('model');
        //判断是否删除成功
        if($model->delete($id)){
            $this->success('模型删除成功！',U('lst'));
        }
        $this->error($model->getError());
    }
}