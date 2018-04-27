<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/16 0016
 * Time: 9:37
 */
namespace Admin\Controller;
use Think\Controller;
class CategoryController extends BaseController {
    //分类列表
    public function lst(){
        $model = D('category');
        $data=$model->getTree();
        //数据assign到页面中
        $this->assign(array(
            'data' => $data,
            'title' => '分类列表',
            'btn_name' => '添加分类',
            'btn_url' => U('add')
        ));
        $this->display();
    }
    //分类添加
    public function add(){
        $model = D('category');
        if(IS_POST) {
            if(empty($_FILES)){
                $this->error('请上传2M以下照片！');
            }
            //判断是否验证成功
            if ($model->create(I('post.'), 1)) {
                //判断是否添加成功
                if ($model->add()) {
                    $this->success('分类添加成功！', U('lst'));
                    exit;
                }
            }
        }
        $data=$model->getTree();
        //数据assign到页面中
        $this->assign(array(
            'data' => $data,
            'title' => '添加分类',
            'btn_name' => '分类列表',
            'btn_url' => U('lst')
        ));
        $this->display();
    }
    //分类修改
    public function edit(){
        //获得需要修改的分类ID
        $id = I('get.id');
        $model = D('category');
        //判断是否接收表单
        if(IS_POST){
            if(empty($_FILES)){
                $this->error('请上传2M以下照片！');
            }
            //判断是否验证成功
            if($model->create(I('post.'),2)){
                //判断是否添加成功
                if(FALSE !== $model->save()){
                    $this->success('分类添加成功！',U('lst'));
                    exit;
                }
            }
            //添加失败
            $this->error($model->getError());
        }
        //获取该ID的数据
        $idData = $model->find($id);
        //获取分类的树形结构
        $data=$model->getTree();
        //获取本身和子类ID
        $children = $model->getChildren($id);
        //数据assign到页面中
        $this->assign(array(
            'children' => $children,
            'idData' => $idData,
            'data' => $data,
            'title' => '修改分类',
            'btn_name' => '分类列表',
            'btn_url' => U('lst')
        ));
        $this->display();
    }
    //分类删除
    public function del(){
        //获取要删除分类的ID
        $id = I('get.id');
        $model = D('category');
        //判断是否删除成功
        if($model->delete($id)){
            $this->success('删除成功！',U('lst'));
            exit;
        }
        $this->error($model->getError());
    }
}