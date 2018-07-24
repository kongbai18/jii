<?php
namespace Admin\Model;
use Think\Model;
class ThemeModel extends Model {
    //添加类别时允许接收的表单
    protected $insertFields = array('theme_name','sort_id');
    //修改类别时允许接收的字段
    protected $updateFields = array('id','theme_name','sort_id');
    //验证码规则
    protected $_validate = array(
           array('theme_name','require','主题名称不能为空！',1),
           array('sort_id','number','排序必须为数字类型！',1),
    );
    //搜索品牌信息
    public function search($perPage){
    	$where = array();
    	//类别名搜索
    	$colorName = I('get.color_name');
    	if($colorName){
    		$where['color_name'] = array('like',"%$colorName%");
    	}
    	/*************翻页************************/
    	//获取总记录数
    	$count = $this->where($where)->count();
    	//生成翻页对象
    	$pageObj = new \Think\Page($count,$perPage);
    	//设置样式
        $pageObj->setConfig('prev','上一页');
        $pageObj->setConfig('next','下一页');
    	//获取翻页字符串
    	$pageString = $pageObj->show();
    	/**************取某一页数据********************/
    	$data = $this->where($where)->limit($pageObj->firstRow.','.$pageObj->listRows)->select();
    	return array(
    	    'data' => $data,
    	    'page' => $pageString,
    	);
    }
    //添加之前
    public function _before_insert(&$data,$option){

    }
    //修改之前
    public function _before_update(&$data,$option){

    }
    //删除之前
    public function _before_delete($option){

    }
    	
}