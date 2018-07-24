<?php
namespace Admin\Controller;
use Think\Controller;
class ThemeController extends BaseController {
	//角色列表
    public function lst(){
      $model = D('theme');
      $data = $model->order('sort_id asc')->select();
      //数据assign到页面中
      $this->assign(array(
           'data'  => $data,
           'title' => '主题列表',
           'btn_name' => '添加主题',
           'btn_url' => U('add')
      ));
      $this->display();
    }
    //角色添加
    public function add(){
      $model = D('theme');
      //判断是否接收了表单
      if(IS_POST){
      	//判断数据是否验证成功
      	if($model->create(I('post.'),1)){
      		//判断数据是否添加成功
      		if($model->add()){
      			$this->success('主题添加成功！',U('lst'));
      			exit;
      		}
      	}
      	$this->error($model->getError());
      }

      //数据assign到页面中
      $this->assign(array(
           'title' => '添加主题',
           'btn_name' => '主题列表',
           'btn_url' => U('lst')
      ));
      $this->display();
    }
    //角色修改
    public function edit(){
      //获取要修改角色的ID
      $id = I('get.id');
      //取出该角色信息
      $model = D('theme');
      $data = $model->find($id);
      //判断是否提交数据
      if(IS_POST){
      	 //判断是否通过验证
      	 if($model->create(I('post.'),2)){
      	 	//判断是否修改成功
      	 	if(FALSE !== $model->save()){
      	 		$this->success('主题修改成功！',U('lst'));
      	 	}
      	 }
      	 $this->error($model->getError());
      }

      //数据assign到页面中
      $this->assign(array(
           'data' => $data,
           'title' => '主题修改',
           'btn_name' => '主题列表',
           'btn_url' => U('lst')
      ));
      $this->display();
    }
    //角色删除
    public function delete(){
      //获取要删除角色的ID
      $id = I('get.id');
      $model = D('theme');
      //判断是否删除成功
     if($model->delete($id)){
     	$this->success('主题删除成功！',U('lst'));
     }
      $this->error($model->getError());
    }
}