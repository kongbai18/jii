<?php
namespace Admin\Controller;
use Think\Controller;
class AttributeController extends BaseController {
    //类别列表
    public function lst(){
      $model = D('attribute');
      $data = $model->search(10);
      //数据assign到页面中
      $this->assign(array(
           'data'  => $data,
           'title' => '属性列表',
           'btn_name' => '添加属性',
           'btn_url' => U('add?type_id='.I('get.type_id'))
      ));
      $this->display();
    }
    //类别增加
    public function add(){
      $model = D('attribute');
      //判断是否接收表单
      if(IS_POST){
      	//判断是否验证成功
      	if($model->create(I('post.'),1)){
      		//判断是否添加成功
      		if($model->add()){
      			$this->success('属性添加成功！',U('lst?type_id='.I('post.type_id')));
      		}
      	}
      	//添加失败
      	$this->error($model->getError());
      }
      //数据assign到页面中
      $this->assign(array(
           'title' => '添加属性',
           'btn_name' => '属性列表',
           'btn_url' => U('lst?type_id='.I('post.type_id')),
      ));
      $this->display();
    }
    //类别修改
    public function edit(){
      //获取需要修改属性的ID
      $id = I('get.id');
      $model = D('attribute');
      //判断是否接收表单
      if(IS_POST){
      	//判断是否验证成功
      	if($model->create(I('post.'),2)){
      		//判断是否修改成功
      		if(FALSE !== $model->save()){
      			$this->success('属性修改成功！',U('lst?type_id='.I('post.type_id')));
      		}
      	}
      	//修改失败
      	$this->error($model->getError());
      }
      //获取修改属性数据
      $data = $model->find($id);
      //数据assign到页面中
      $this->assign(array(
           'data' => $data,
           'title' => '修改属性',
           'btn_name' => '属性列表',
           'btn_url' => U('lst')
      ));
      $this->display();
    }
    //类别删除
    public function delete(){
        //接收要删除类型的ID
      $id = I('get.id');
      $model = D('attribute');
      //判断是否删除成功
      if($model->delete($id)){
      	$this->success('属性删除成功！',U('lst?type_id='.I('get.type_id')));
      }
      $this->error($model->getError());
    }
}