<?php
namespace Admin\Model;
use Think\Model;
class PrivilegeModel extends Model {
	//添加时允许接收的字段
	protected $inserField = array('pri_name','module_name','controller_name','action_name','parent_id');
	//添加时允许接收的字段 
	protected $updateField = array('id','pri_name','module_name','controller_name','action_name','parent_id');
    //验证规则
	protected $_validate = array(
		array('pri_name', 'require', '权限名不能为空！', 1, 'regex', 3),
		array('module_name', 'require', '模型名不能为空！', 1, 'regex', 3),
		array('controller_name', 'require', '控制器名不能为空！', 1, 'regex', 3),
		array('action_name', 'require', '方法名不能为空！', 1, 'regex', 3),
		array('parent_id', 'number', '必须是一个整数！', 2, 'regex', 3),
	);
	/************获取管理员前两级权限********************/
	public function getBtns(){
		$adminId = session('id');
		if($adminId == 1){
			$priModel = D('privilege');
			$priData = $priModel->select();
		}else{
			//取出当前角色权限
			$arModel = D('admin_role');
			$priData = $arModel->alias('a')
			->field('c.*')
			->join('LEFT JOIN __ROLE_PRI__ b ON a.role_id=b.role_id
			        LEFT JOIN __PRIVILEGE__ c ON b.pri_id=c.id')
			->where(array(
			        'a.admin_id' => array('eq',$adminId),
			))->select();  
		}
		$btns =  array();
		foreach($priData as $k => $v){
			if($v['parent_id'] == 0){
			   //找子集
			   foreach($priData as $k1 => $v1){	
			       if($v1['parent_id'] == $v['id']){
						$v['children'][] = $v1;
					}
				}
				$btns[] = $v;
			}
		}
		return $btns;
	}
	//检查管理员访问权限
	public function chkPri(){
		//获取要访问的模型，控制器，方法
		$adminId = session('id');
		//如果是超级管理员直接pass
		if($adminId==1){
			return true;
		}
		$arModel = D('admin_role');
		$has = $arModel->alias('a')
		->join('LEFT JOIN __ROLE_PRI__ b ON a.role_id=b.role_id
		        LEFT JOIN __PRIVILEGE__ c ON b.pri_id=c.id')
		->where(array(
		       'a.admin_id' => array('eq',$adminId),
		       'c.module_name' => array('eq',MODULE_NAME),
		       'c.controller_name' => array('eq',CONTROLLER_NAME),
		       'c.action_name' => array('eq',ACTION_NAME),
		))->count();
		return ($has > 0);
	}
    //获取分类子ID
    public function getChildren($catId){
    	//获得所有分类数据
        $data = $this->select();
        $children = $this->_getChildren($catId,$data,true);
        $children[] = $catId;
        return $children;
    }
    private function _getChildren($catId,$data,$isClear = FALSE){
    	static $children = array();
    	if($isClear){
    		$children = array();
    	}
    	//循环从数据中找出子类
    	foreach($data as $k => $v){
    		if($v['parent_id']==$catId){
    			$children[] = $v['id'];
    			$this->_getChildren($v['id'],$data,FALSE);
    		}
    	}
    	return $children;
    }
    //无限极排序
    public function getTree(){
    	//获得所有分类数据
    	$data = $this->select();
    	return $this->_getTree($data);
    }
    private function _getTree($data,$parentId=0,$level=0){
    	static $ret =array();
    	foreach($data as $k => $v){
    		if($v['parent_id']==$parentId){
    			$v['level'] = $level;
    			$ret[] = $v;
    			//找子分类
    			$this->_getTree($data,$v['id'],$level+1);
    		}
    	}
    	return $ret;
    }
}