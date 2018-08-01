<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/13 0013
 * Time: 9:28
 */
require (APP_PATH.'../Public/Qiniu/autoload.php');
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Processing\Operation;
/**
 * @brief 上传图片至七牛云
 */
function qiniu_img_upload($key,$file)
{
    $accessKey = 'aEY-lKi3FC2LI4Ip6HK6PNkC4t6mt30xd6ro1UQD';
    $secretKey = 'Pp1p447OMbdsI81rHiaPG2-CA6cr_0QHjyvL4_Bs';
    $auth = new Auth($accessKey, $secretKey);
    $bucket = 'daishu'; //你的七牛空间名
    // 设置put policy的其他参数
    $opts = array(
        'callbackBody' => 'name=$(fname)&hash=$(etag)'
    );
    $token = $auth->uploadToken($bucket, null, 3600, $opts);
    $uploadMgr = New UploadManager();

    list($ret, $err) = $uploadMgr->putFile($token, $key, $file);

    if ($err !== null) {
        //var_dump($err);
        $result = array(
            'flag'=> 0,
            'img' => ''
        );
    } else {
        $str=$ret['key'];
        $key = $str;
        $domain = 'p5koaz6je.bkt.clouddn.com';
        $op = New Operation($domain);
        $ops = '';
        $url = $op->buildUrl($key, $ops);
        $result = array(
            'flag'=> 1,
            'img' => $url
        );
    }
    return $result;
}
/**
 * @brief 图片从七牛云删除的方法
 */
function qiniu_img_delete($key)
{
    $accessKey = 'aEY-lKi3FC2LI4Ip6HK6PNkC4t6mt30xd6ro1UQD';
    $secretKey = 'Pp1p447OMbdsI81rHiaPG2-CA6cr_0QHjyvL4_Bs';
    $auth = new Auth($accessKey, $secretKey);
    $bucket = 'daishu'; //你的七牛空间名
    $config = new \Qiniu\Config();
    $bucketManager = new \Qiniu\Storage\BucketManager($auth, $config);
    $err = $bucketManager->delete($bucket, $key);
    /*if($err){
        print_r($err);
    }*/
}

/**
 * 制作下拉框
*/
function buildSelect($mdName,$selName,$val,$valName,$selelctVal = '',$firstName = '请选择...'){
    $model = D($mdName);
    $data = $model->field($val.','.$valName)->select();
    $select = '<select name="'.$selName.'" ><option value="">'.$firstName.'</option>';
    foreach($data as $k => $v){
        //判断是否有默认选择
        if($selelctVal && $selelctVal==$v[$val]){
            $selected = 'selected="selected"';
        }else{
            $selected = '';
        }
        $select .= '<option '.$selected.' value="'.$v[$val].'">'.$v[$valName].'</option>';
    }
    $select .= '</select>';
    echo $select;
}

    /*
     * 检测用户信息
     */
   function checkUser($userId,$thr_session){
        $model = D('Admin/user');
        $session = $model->field('thr_session')->find($userId);
        if($session['thr_session'] == $thr_session){
            return true;
        }else{
            return false;
        }
   }

    /*
      * 创建毫秒级订单号
      */
    function create_unique() {
        list($usec, $sec) = explode(" ", microtime());
        $usec = substr(str_replace('0.', '', $usec), 0 ,4);
        $str  = rand(10,99);
        return date("YmdHis").$usec.$str;
    }
/*
 * 创建微信支付单号
 */
function wxorder($orderId,$price,$openid) {

    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    $str ="";
    for ( $i = 0; $i < 32; $i++ )  {
        $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
    }
    $dataNow = strval(date('YmdHis',time()));
    $dataLat = strval(date('YmdHis',time()+60*60*24));
    $order = array(
        'appid'=>'wx6a73b5816054ba24',
        'body'=>'jiihome定制',
        'device_info'=>'WEB',
        'mch_id'=>'1501109211',
        'nonce_str'=>$str,
        'time_start'=>$dataNow,
        'time_expire'=>$dataLat,
        'notify_url'=>'http://8bj34k.natappfree.cc/jiiMarket/index.php',//接受微信异步通知地址
        'openid'=>$openid,
        'out_trade_no'=>$orderId.rand(1000,9999),//商户唯一订单号，可包含字母序
        'total_fee'=>$price,//订单金额，单位/分
        'trade_type'=>'JSAPI',
    );

    ksort($order);

    $sign = '';
    foreach ($order as $k => $v){
        $sign = $sign.$k.'='.$v.'&';
    }

    $sign = $sign.'key=JBkjkj54adDSskjKL54SDjsd35sdsJHs';
    $sign = md5($sign);
    //转大写
    $sign = strtoupper($sign);
    $order['sign'] = $sign;
    //转换成一维XML格式
    $xml = '<xml>';
    foreach($order as $k=>$v){
        $xml.='<'.$k.'>'.$v.'</'.$k.'>';
    }
    $xml.='</xml>';
    //CURL会话
    $ch = curl_init();
    // 设置curl允许执行的最长秒数
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
    // 获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    //发送一个常规的POST请求。
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, 'https://api.mch.weixin.qq.com/pay/unifiedorder');
    //要传送的所有数据
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    // 执行操作
    $response = curl_exec($ch);
    //将xml格式的$response 转成数组
    $response = json_decode( json_encode( simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA) ), true );
    //若预下单成功，return_code 和result_code为SUCCESS。
    if ( $response['return_code'] ==='SUCCESS' && $response['result_code'] ==='SUCCESS') {
        //返回trade_type和prepay_id供前端调用
        return $response['prepay_id'];
        //echo json_encode( ['trade_type'=>$response['trade_type'], 'prepay_id'=>$response['prepay_id']] );
    }else{
        //return 'false';
        return 'false';
    }
}
function wxpay($prepayId){
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    $str ="";
    for ( $i = 0; $i < 32; $i++ )  {
        $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
    }
    $data = array(

        'timeStamp'=>strval(time()),
        'appId'=>'wx6a73b5816054ba24',
        'nonceStr'=>$str,
        'signType'=>'MD5',
        'package'=>'prepay_id='.$prepayId,
    );
    ksort($data);
    $sign = '';
    foreach ($data as $k => $v){
        $sign = $sign.$k.'='.$v.'&';
    }

    $sign = $sign.'key=JBkjkj54adDSskjKL54SDjsd35sdsJHs';
    $sign = md5($sign);
    //转大写
    $paySign = strtoupper($sign);
    $data['paysign'] = $paySign;
    return $data;
}

//微信支付订单查询
function inquery($orderId){
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    $str ="";
    for ( $i = 0; $i < 32; $i++ )  {
        $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
    }
    $order = array(
        'appid'=>'wx6a73b5816054ba24',
        'mch_id'=>'1501109211',
        'nonce_str'=>$str,
        'out_trade_no'=>$orderId,//商户唯一订单号，可包含字母序
    );

    ksort($order);

    $sign = '';
    foreach ($order as $k => $v){
        $sign = $sign.$k.'='.$v.'&';
    }

    $sign = $sign.'key=JBkjkj54adDSskjKL54SDjsd35sdsJHs';
    $sign = md5($sign);
    //转大写
    $sign = strtoupper($sign);
    $order['sign'] = $sign;
    //转换成一维XML格式
    $xml = '<xml>';
    foreach($order as $k=>$v){
        $xml.='<'.$k.'>'.$v.'</'.$k.'>';
    }
    $xml.='</xml>';
    //CURL会话
    $ch = curl_init();
    // 设置curl允许执行的最长秒数
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
    // 获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    //发送一个常规的POST请求。
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, 'https://api.mch.weixin.qq.com/pay/orderquery');
    //要传送的所有数据
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    // 执行操作
    $response = curl_exec($ch);
    //将xml格式的$response 转成数组
    $response = json_decode( json_encode( simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA) ), true );
    //若预下单成功，return_code 和result_code为SUCCESS。
    if ( $response['return_code'] ==='SUCCESS' && $response['result_code'] ==='SUCCESS' && $response['trade_state'] ==='SUCCESS') {
        //返回trade_type和prepay_id供前端调用
        return 'success';
        //echo json_encode( ['trade_type'=>$response['trade_type'], 'prepay_id'=>$response['prepay_id']] );
    }else{
        //return 'false';
        return 'false';
    }
}
//退款
function refund($orderId,$price,$aHeader=array())
{
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    $str = "";
    for ($i = 0; $i < 32; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    $dataNow = strval(date('YmdHis', time()));
    $dataLat = strval(date('YmdHis', time() + 60 * 60 * 24));
    $order = array(
        'appid' => 'wx6a73b5816054ba24',
        'mch_id' => '1501109211',
        'nonce_str' => $str,
        'out_trade_no' => $orderId,//商户唯一订单号，可包含字母序
        'out_refund_no' => $orderId,
        'total_fee' => $price,//订单金额，单位/分
        'refund_fee' => $price,
    );

    ksort($order);

    $sign = '';
    foreach ($order as $k => $v) {
        $sign = $sign . $k . '=' . $v . '&';
    }

    $sign = $sign . 'key=JBkjkj54adDSskjKL54SDjsd35sdsJHs';
    $sign = md5($sign);
    //转大写
    $sign = strtoupper($sign);
    $order['sign'] = $sign;
    //转换成一维XML格式
    $xml = '<xml>';
    foreach ($order as $k => $v) {
        $xml .= '<' . $k . '>' . $v . '</' . $k . '>';
    }
    $xml .= '</xml>';

    $ch = curl_init();
    //超时时间
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //这里设置代理，如果有的话
    //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
    //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
    curl_setopt($ch, CURLOPT_URL, 'https://api.mch.weixin.qq.com/secapi/pay/refund');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    //以下两种方式需选择一种

    //第一种方法，cert 与 key 分别属于两个.pem文件
    //默认格式为PEM，可以注释
    curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
    curl_setopt($ch, CURLOPT_SSLCERT, 'D:\wamp\wamp64\www\jiiMarket\Public\cert\apiclient_cert.pem');
    //默认格式为PEM，可以注释
    curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
    curl_setopt($ch, CURLOPT_SSLKEY, 'D:\wamp\wamp64\www\jiiMarket\Public\cert\apiclient_key.pem');

    //第二种方式，两个文件合成一个.pem文件
    //curl_setopt($ch,CURLOPT_SSLCERT,'http://www.jiixcx.com/Public/cert/apiclient_key.pem');

    if (count($aHeader) >= 1) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
    }

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    $data = curl_exec($ch);
    //将xml格式的$response 转成数组
    $data = json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    if ($data['return_code'] === 'SUCCESS' && $data['result_code'] === 'SUCCESS') {
        curl_close($ch);
        return 'success';
    } else {
        //$error = curl_errno($ch);
        //echo "call faild, errorCode:$error\n";
        curl_close($ch);
        return 'false';
    }
}
//退款
function transfers($openId,$price)
{
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    $str = "";
    for ($i = 0; $i < 32; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }

    $orderId = date("YmdHis");

    $data = array(
        'mch_appid' => 'wx6a73b5816054ba24',
        'mchid' => '1501109211',
        'nonce_str' => $str,
        'partner_trade_no' => $orderId.rand(1000,9999),//商户唯一订单号，可包含字母序
        'openid' => $openId,
        'check_name' => 'NO_CHECK',//订单金额，单位/分
        'amount' => $price,
        'desc' => '用户奖励',
        'spbill_create_ip' => $_SERVER['SERVER_ADDR'],
    );

    ksort($data);

    $sign = '';
    foreach ($data as $k => $v) {
        $sign = $sign . $k . '=' . $v . '&';
    }

    $sign = $sign . 'key=JBkjkj54adDSskjKL54SDjsd35sdsJHs';
    $sign = md5($sign);
    //转大写
    $sign = strtoupper($sign);
    $data['sign'] = $sign;
    //转换成一维XML格式
    $xml = '<xml>';
    foreach ($data as $k => $v) {
        $xml .= '<' . $k . '>' . $v . '</' . $k . '>';
    }
    $xml .= '</xml>';

    $ch = curl_init();
    //超时时间
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //这里设置代理，如果有的话
    //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
    //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
    curl_setopt($ch, CURLOPT_URL, 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    //以下两种方式需选择一种

    //第一种方法，cert 与 key 分别属于两个.pem文件
    //默认格式为PEM，可以注释
    curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
    curl_setopt($ch, CURLOPT_SSLCERT, 'D:\wamp\wamp64\www\jiiMarket\Public\cert\apiclient_cert.pem');
    //默认格式为PEM，可以注释
    curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
    curl_setopt($ch, CURLOPT_SSLKEY, 'D:\wamp\wamp64\www\jiiMarket\Public\cert\apiclient_key.pem');

    //第二种方式，两个文件合成一个.pem文件
    //curl_setopt($ch,CURLOPT_SSLCERT,'http://www.jiixcx.com/Public/cert/apiclient_key.pem');

    if (count($aHeader) >= 1) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
    }

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    $data = curl_exec($ch);

    //将xml格式的$response 转成数组
    $data = json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

    if ($data['return_code'] === 'SUCCESS' && $data['result_code'] === 'SUCCESS') {
        curl_close($ch);
        return 'success';
    } else {
        //$error = curl_errno($ch);
        //echo "call faild, errorCode:$error\n";
        curl_close($ch);
        return 'false';
    }
}
//获取access_token
function get_access_token(){
    $appid = 'wx6a73b5816054ba24';
    $secret = '143b572cbecbae4bb6c138643ac7f6e8';
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    return $data;
}
function get_http_array($url,$post_data) {
    header('content-type:image/gif');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   //没有这个会自动输出，不用print_r();也会在后面多个1
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $output = curl_exec($ch);
    curl_close($ch);
    $out = json_decode($output);
    return $out;
}
function showImg($img){
    header('content-type: image/gif');
    return file_get_contents($img);
}
/*
 * 微信获取手机号解密
 */
function decryptData( $encryptedData, $iv,$sessionKey, &$data )
{
    if (strlen($sessionKey) != 24) {
        return false;
    }
    $appid = 'wx6a73b5816054ba24';
    $aesKey=base64_decode($sessionKey);


    if (strlen($iv) != 24) {
        return false;
    }
    $aesIV=base64_decode($iv);

    $aesCipher=base64_decode($encryptedData);

    $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

    $dataObj=json_decode( $result );
    if( $dataObj  == NULL )
    {
        return false;
    }
    if( $dataObj->watermark->appid != $appid )
    {
        return false;
    }
    $data = $result;
    return true;
}
/*
 * 百度API根据经纬度获取地址
 */
function getAddress($longitude,$latitude){
    $longitude=$longitude;//用户当前定位的经度

    $latitude=$latitude;//用户当前定位的纬度
    $place_url='http://api.map.baidu.com/geocoder/v2/?location='.$latitude.','.$longitude.'&output=json&ak=G2Mt3eb7ZM7ajoRIQwXdYY27w9DVYhvl';
    $json_place=file_get_contents($place_url);
    $place_arr=json_decode($json_place,true);
    $address=$place_arr['result']['formatted_address'];
    return $address;
}