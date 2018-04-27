<?php
namespace Admin\Model;
use Think\Model;
class AttributeModel extends Model {
    //添加类别时允许接收的表单
    protected $insertFields = array('attr_name','attr_type','attr_option_values','type_id');
    //修改类别时允许接收的字段
    protected $updateFields = array('id','attr_name','attr_type','attr_option_values','type_id');
    //验证码规则
    protected $_validate = array(
           array('attr_name','require','类型名称不能为空！',1),
    );
    //搜索品牌信息
    public function search($perPage){
    	$where = array();
    	//属性名搜索
    	$attrName = I('get.attr_name');
    	if($attrName){
    		$where['attr_name'] = array('like',"%$attributeName%");
    	}
    	//属性类型搜索
    	$attrType = I('get.attr_type');
    	if($attrType){
    		$where['attr_type'] = array('eq',$attrType);
    	}
    	//类型搜索
    	$typeId = I('get.type_id');
    	if($typeId){
    		$where['type_id'] = array('eq',$typeId);
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
    	$data = $this->field('a.*,b.type_name')
    	->alias('a')
    	->join('LEFT JOIN __TYPE__ b ON a.type_id=b.id')
    	->where($where)
    	->limit($pageObj->firstRow.','.$pageObj->listRows)
    	->select();
    	return array(
    	    'data' => $data,
    	    'page' => $pageString,
    	);
    }
    //添加之前
    public function _before_insert(&$data,$option){
          if($_POST['attr_type'] == 3){
              $colorId = array_filter($_POST['color_id']);
              $colorId = implode(',',$colorId);
              $data['attr_option_values'] = $colorId;
          }else if($_POST['attr_type'] == 2){
              $data['attr_option_values'] = str_replace('，',',',$data['attr_option_values']);
          }else if($_POST['attr_type'] == 1){
              $data['attr_option_values'] = '';
          }
    }
    //修改之前
    public function _before_update(&$data,$option){
        if($_POST['attr_type'] == 3){
            $colorId = array_filter($_POST['color_id']);
            $colorId = implode(',',$colorId);
            $data['attr_option_values'] = $colorId;
        }else{
            $data['attr_option_values'] = str_replace('，',',',$data['attr_option_values']);
        }
    }
    //删除之前
    public function _before_delete($option){
    	
    }
    	
}