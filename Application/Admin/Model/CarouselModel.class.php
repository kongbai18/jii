<?php
namespace Admin\Model;
use Think\Model;
class CarouselModel extends Model {
    //添加类别时允许接收的表单
    protected $insertFields = array('url','sort_id','theme_id');
    //修改类别时允许接收的字段
    protected $updateFields = array('id','url','sort_id','theme_id');
    //验证码规则
    protected $_validate = array(
        array('theme_id','require','主题不能为空！',1),
        array('sort_id','number','排序必须为数字类型！',1),
    );
//搜索信息
    public function search($perPage){
        $where = array();
        //类别名搜索
        $themeId = I('get.theme_id');
        if($themeId){
            $where['theme_id'] = array('eq',"$themeId");
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
        $data = $this->field('a.*,b.theme_name')
            ->alias('a')
            ->join('LEFT JOIN __THEME__ b ON a.theme_id=b.id')
            ->where($where)
            ->limit($pageObj->firstRow.','.$pageObj->listRows)->select();
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
            $key = 'jiimade/view/images/carousel/'.date("Y/m/d").'/'.rand();
            $ret = qiniu_img_upload($key,$file);
            $data['img_src'] = $ret['img'];
        }
    }
    //修改之前
    public function _before_update(&$data,$option){
        /*************处理IMG*********************/
        if($_FILES['img_src']['error']==0){
            $file = $_FILES['img_src']['tmp_name'];
            $key = 'jiimade/view/images/carousel/'.date("Y/m/d").'/'.rand();
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
    //根据主题获取轮播图
    public function getCarousel(){
        $themeId = I('get.themeId');
        $data = $this->where(array('theme_id'=>array('eq',$themeId)))->order('sort_id asc')->select();
        return $data;
    }
    	
}