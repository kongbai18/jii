<?php
namespace Admin\Model;
use Think\Model;
class ColorModel extends Model {
    //添加类别时允许接收的表单
    protected $insertFields = array('color_name');
    //修改类别时允许接收的字段
    protected $updateFields = array('id','color_name');
    //验证码规则
    protected $_validate = array(
           array('color_name','require','颜色名称不能为空！',1),
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
        /*************处理IMG*********************/
        if($_FILES['img_src']['error']==0){
            $file = $_FILES['img_src']['tmp_name'];
            $key = 'jiimade/view/images/color/'.date("Y/m/d").'/'.rand();
            $ret = qiniu_img_upload($key,$file);
            $data['img_src'] = $ret['img'];
        }
    }
    //修改之前
    public function _before_update(&$data,$option){
        /*************处理IMG*********************/
        if($_FILES['img_src']['error']==0){
            $file = $_FILES['img_src']['tmp_name'];
            $key = 'jiimade/view/images/color/'.date("Y/m/d").'/'.rand();
            $ret = qiniu_img_upload($key,$file);

            if($ret['flag'] == 1){
                //获取旧LOGO地址
                $oldImg = $this->field('img_src')->find($option['where']['id']);
                foreach($oldImg as $v){
                    $key = rtrim($v,'?');
                    $key = substr_replace($key,'',0,33);
                    qiniu_img_delete($key);
                }
                $data['img_src'] = $ret['img'];
            }
        }
    }
    //删除之前
    public function _before_delete($option){
        //获取LOGO路径
        $oldImg = $this->field('img_src')->find($option['where']['id']);
        //从七牛云删除
        if(!empty($oldImg)){
            $key = rtrim($oldImg['img_src'],'?');
            $key = substr_replace($key,'',0,33);
            qiniu_img_delete($key);
        }
    }
    	
}