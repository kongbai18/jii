<?php
namespace Admin\Model;
use Think\Model;
class RoleModel extends Model {
	//添加时允许接收的字段
	protected $inserField = array('role_name');
	//添加时允许接收的字段 
	protected $updateField = array('id','role_name');
    //验证规则
	protected $_validate = array(
		array('role_name', 'require', '角色名不能为空！', 1, 'regex', 3),
	);
	//搜索方法
	public function search(){
		$data = $this->alias('a')
		->field('a.*,GROUP_CONCAT(c.pri_name) pri_name')
		->join('LEFT JOIN __ROLE_PRI__ b ON a.id=b.role_id
		        LEFT JOIN __PRIVILEGE__ c ON b.pri_id=c.id')
		->group('a.id')
		->select();
		return $data;
	}
	//更新之前
	public function _before_update($data,$option){
		/**********删除原权限***********/
		$rpModel = D('role_pri');
		$rpModel->where(array(
		     'role_id' => array('eq',$option['where']['id']),
		))->delete();
		/**********添加新权限**************/
		//获取权限ID
		$priId = I('post.pri_id');
		foreach($priId as $k => $v){
			$rpModel->add(array(
			   'pri_id' => $v,
			   'role_id' => $option['where']['id'],
			));
		}
	}
	//添加之后
	public function _after_insert($data,$option){
		//获取权限ID
		$priId = I('post.pri_id');
		$rpModel = D('role_pri');
		foreach($priId as $k => $v){
			$rpModel->add(array(
			   'pri_id' => $v,
			   'role_id' => $data['id'],
			));
		}
	}
}