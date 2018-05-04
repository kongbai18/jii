<?php
namespace Admin\Model;
use Think\Model;
class ArticleModel extends Model {
    //添加类别时允许接收的表单
    protected $insertFields = array('article_name','is_index','sort_id','article_brief','goods');
    //修改类别时允许接收的字段
    protected $updateFields = array('id','article_name','is_index','sort_id','article_brief','goods');
    //验证码规则
    protected $_validate = array(
           array('article_name','require','文章标题不能为空！',1),
    );
    //搜索品牌信息
    public function search($perPage){
    	$where = array();
    	//类别名搜索
    	$articleName = I('get.article_name');
    	if($typeName){
    		$where['article_name'] = array('like',"%$articleName%");
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
    	$data = $this->where($where)->order('sort_id asc')->limit($pageObj->firstRow.','.$pageObj->listRows)->select();
    	return array(
    	    'data' => $data,
    	    'page' => $pageString,
    	);
    }
    //添加之前
    public function _before_insert(&$data,$option){
        if($data['goods']){
            $data['goods'] = array_filter($data['goods']);
            $data['goods'] = implode(',',$data['goods']);
        }
        $data['add_time'] = time();
        $file = $_FILES['pic']['tmp_name'];
        $key = 'jiimade/view/images/article/' . date("Y/m/d") . '/' . rand();
        $ret = qiniu_img_upload($key, $file);
        if ($ret['flag'] == 1) {
            $data['img_src'] = $ret['img'];
        }
    }
    //添加之后
    public function _after_insert($data,$option){
        /***************处理文章内容***********************/
        $imgs = array();
        foreach ($_FILES['desc_pic']['name'] as $k => $v) {
            $imgs[$k]['name'] = $_FILES['desc_pic']['name'][$k];
            $imgs[$k]['type'] = $_FILES['desc_pic']['type'][$k];
            $imgs[$k]['tmp_name'] = $_FILES['desc_pic']['tmp_name'][$k];
            $imgs[$k]['error'] = $_FILES['desc_pic']['error'][$k];
            $imgs[$k]['size'] = $_FILES['desc_pic']['size'][$k];
        }
        $articleDescModel = D('article_desc');
        foreach ($imgs as $k => $v) {
            if ($v['error'] == 0) {
                $file = $v['tmp_name'];
                $key = 'jiimade/view/images/articledesc/' . date("Y/m/d") . '/' . rand();
                $ret = qiniu_img_upload($key, $file);
                if ($ret['flag'] == 1) {
                    $articleDescModel->add(array(
                        'img_src' => $ret['img'],
                        'article_id' => $data['id'],
                    ));
                }
            }
        }
    }
    //修改之前
    public function _before_update(&$data,$option){
        if($data['goods']){
            $data['goods'] = array_filter($data['goods']);
            $data['goods'] = implode(',',$data['goods']);
        }
        if($_FILES['pic']['error'] == '0'){
            //获取LOGO路径
            $oldImg = $this->field('img_src')->find($option['where']['id']);
            //从七牛云删除
            if(!empty($oldImg)){
                foreach($oldImg as  $v){
                    $key = rtrim($v,'?');
                    $key = substr_replace($key,'',0,33);
                    qiniu_img_delete($key);
                }
            }

            $file = $_FILES['pic']['tmp_name'];
            $key = 'jiimade/view/images/article/' . date("Y/m/d") . '/' . rand();
            $ret = qiniu_img_upload($key, $file);
            if ($ret['flag'] == 1) {
                $data['img_src'] = $ret['img'];
            }
        }

        /***************处理文章内容***********************/
        $imgs = array();
        foreach ($_FILES['desc_pic']['name'] as $k => $v) {
            $imgs[$k]['name'] = $_FILES['desc_pic']['name'][$k];
            $imgs[$k]['type'] = $_FILES['desc_pic']['type'][$k];
            $imgs[$k]['tmp_name'] = $_FILES['desc_pic']['tmp_name'][$k];
            $imgs[$k]['error'] = $_FILES['desc_pic']['error'][$k];
            $imgs[$k]['size'] = $_FILES['desc_pic']['size'][$k];
        }
        $articleDescModel = D('article_desc');
        foreach ($imgs as $k => $v) {
            if ($v['error'] == 0) {
                $file = $v['tmp_name'];
                $key = 'jiimade/view/images/articledesc/' . date("Y/m/d") . '/' . rand();
                $ret = qiniu_img_upload($key, $file);
                if ($ret['flag'] == 1) {
                    $articleDescModel->add(array(
                        'img_src' => $ret['img'],
                        'article_id' => $option['where']['id'],
                    ));
                }
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

        $artDescModel = D('article_desc');
        $oldDescImg = $artDescModel->where(array('article_id'=>$option['where']['id']))->select();
        if(!empty($oldDescImg)){
            foreach($oldDescImg as  $v){
                $key = rtrim($v['img_src'],'?');
                $key = substr_replace($key,'',0,33);
                qiniu_img_delete($key);
            }
        }
    }
    //APP获取文章列表
    public function getArticle(){
        $limit = I('get.num');
        $where['is_index'] = array('eq','1');
        $data = $this->where($where)->order('sort_id asc')->limit($limit)->select();
        return $data;
    }
    //获取单篇文章
    public function getOneArt(){
        $id = I('get.artId');
        $data = $this->find($id);
        return $data;
    }
    //APP获取文章详情
    public function getArtiDesc(){
         $artId = I('get.artId');
         $goodsId = $this->field('goods')->find($artId);
         $goodsId = explode(',',$goodsId['goods']);
         $goodsModel = D('goods');
         $goodsData = $goodsModel->field('a.id,a.goods_name,a.tag,max(b.goods_price) as max_goods_price,min(b.goods_price) as min_goods_price,max(b.discount_price) as max_discount_price,min(b.discount_price) as min_discount_price,max(b.img_src) as img_src')
                      ->alias('a')
                      ->group('a.id')
                      ->where(array('a.id'=>array('in',$goodsId)))
                      ->join('LEFT JOIN __GOODS_NUMBER__ b ON a.id = b.goods_id')
                      ->select();
        foreach ($goodsData as $k => $v){
            $goodsData[$k]['tag'] = explode(',',$v['tag']);
        }
         $artDescModel = D('article_desc');
         $artDescData = $artDescModel->field('img_src')->order('sort_id asc')->where(array('article_id'=>array('eq',$artId)))->select();
         $data = array(
             'goodsData' => $goodsData,
             'artDescData' => $artDescData
         );
         return $data;
    }

    //获得文章二维码
    public function get_artcode() {
        $access = json_decode(get_access_token(),true);
        $access_token= $access['access_token'];
        $id = I('get.artId');
        $path="pages/art/article/article?id=".$id;
        $width=430;
        $post_data='{"path":"'.$path.'","width":'.$width.'}';
        $url="https://api.weixin.qq.com/wxa/getwxacode?access_token=".$access_token;
        $result = get_http_array($url,$post_data);
        return $result;
    }
    public function showImg(){
        $id = I('get.artId');
        $img = $this->field('img_src')->find($id);
        return showImg($img['img_src']);
    }
}