<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/16 0016
 * Time: 10:12
 */
namespace Admin\Model;
use Think\Model;
class CategoryModel extends Model {
    //添加时允许接收的字段
    protected $inserField = array('name','name_en','parent_id','index_block','is_index','order_id');
    //添加时允许接收的字段
    protected $updateField = array('id','name','name_en','parent_id','index_block','is_index','order_id');
    //验证规则
    protected $_validate = array(
        array('name', 'require', '分类名不能为空！', 1, 'regex', 3),
        array('name', '1,30', '的值最长不能超过 30 个字符！', 1, 'length', 3),
        array('parent_id', 'number', '必须是一个整数！', 2, 'regex', 3),
        array('index_block','0,4','必须是一个整数！',1,'between',3)
    );
    //获取分类子ID
    public function getChildren($catId){
        //获得所有分类数据
        $data = $this->order('order_id asc')->select();
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
        $data = $this->order('order_id asc')->select();
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
    //添加之前
    public function _before_insert(&$data,$option){
        /*************处理IMG*********************/
        if($_FILES['img_src']['error']==0){
                $file = $_FILES['img_src']['tmp_name'];
                $key = 'jiimade/view/images/category/'.date("Y/m/d").'/'.rand();
                $ret = qiniu_img_upload($key,$file);
                $data['img_src'] = $ret['img'];
        }
    }
    //更新之前
    public function _before_update(&$data,$option){
        /*************处理IMG*********************/
        if($_FILES['img_src']['error']==0){
                $file = $_FILES['img_src']['tmp_name'];
                $key = 'jiimade/view/images/category/'.date("Y/m/d").'/'.rand();
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

    //小程序获取两级分类
    public function getCate(){
        $data = $this->order('order_id asc')->where(array('is_index'=>array('eq','1')))->select();
        $ret = array();
        foreach ($data as $k => &$v) {
            if($v['parent_id'] == 0){
                foreach ($data as $k1 => $v1) {
                    if ($v1['parent_id'] == $v['id']) {
                        $v['child'][] = $v1;
                    }
                }
                $ret[] = $v;
            }
        }
        return $ret;
    }
    //获取首页模块信息
    public function getBlockInfo(){
        $block = I('get.block');
        $limit = I('get.goodsNum');
        $where['index_block'] = array('eq',$block);
        $catData = $this ->where($where)->order('order_id asc')->select();
        $goodsModel = D('goods');
        foreach($catData as $k => $v){
            $goodsWhere['cat_id'] = array('eq',$v['id']);
            $goodsWhere['is_on_sale'] = array('eq','1');
            $goodsData = $goodsModel->field('a.id,a.goods_name,a.tag,max(b.goods_price) as max_goods_price,min(b.goods_price) as min_goods_price,max(b.discount_price) as max_discount_price,min(b.discount_price) as min_discount_price,max(b.img_src) as img_src')
                ->alias('a')
                ->group('a.id')
                ->join('LEFT JOIN __GOODS_NUMBER__ b ON a.id = b.goods_id')
                ->where($goodsWhere)
                ->limit($limit)
                ->select();
            foreach ($goodsData as $k1 => $v1){
                if($goodsData[$k1]['tag']){
                    $goodsData[$k1]['tag'] = explode(',',$v1['tag']);
                }
            }
            $catData[$k]['goods'] = $goodsData;
        }
        return $catData;
    }
}