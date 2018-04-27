<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>JiiHOME 管理中心 - 添加新商品 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/jiimade/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/jiimade/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/jiimade/Public/Admin/Js/jquery-1.10.2.min.js"></script>
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo $btn_url; ?>"><?php echo $btn_name; ?></a>
    </span>
    <span class="action-span1"><a href="/jiimade/index.php/Admin/index/index">JiiHOME 管理中心</a></span>
    <span id="search_id" class="action-span1"> - <?php echo $title ?> </span>
    <div style="clear:both"></div>
</h1>


<style>
    #goods-list{
        position: fixed;
        width: 50%;
        height: 500px;
        left: 20%;
        top:100px;
        border: 1px solid black;
        background: white;
        display: none;
    }
    #deli{
        position: absolute;
        bottom: 50px;
        left: 25%;
        width: 50%;
    }
    #close{
        height: 20px;
        text-align: right;
        padding: 20px;
    }
</style>
<div id="goods-list" >
    <div id="close"><input type="button" value="关闭" onclick="closeList()"></div>
   <div class="list-div" id="order-goods">
   </div>
    <div id="deli">
        快递单号：<input type="text" id="express" style="margin-right: 50px;">
        <input type="button" value="确认发货" onclick="comDeli()">
    </div>
</div>

<div class="form-div">
    <form action="" name="searchForm">
        <img src="/jiimade/Public/Admin/Images/icon_search.gif" width="26" height="22" border="0" alt="search" />
        <!-- 关键字 -->
        订单号 <input type="text" name="orderId" size="15" value="<?php echo I('get.orderId') ?>" />
        <input type="submit" value="搜索" class="button"  />
    </form>
</div>
<!-- 待发货列表 -->
<form method="post" action="" name="listForm" onsubmit="">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>订单号</th>
                <th>收件人信息</th>
                <th>下单时间</th>
                <th>付款时间</th>
                <th>总价格</th>
                <th>操作</th>
            </tr>
            <?php foreach($data['data'] as $k => $v): ?>
            <tr class="tron">
                <td align="center"><?php echo $v['order_id'] ?></td>
                <td align="center"><?php echo $v['address'] ?></td>
                <td align="center"><?php echo date('Y-m-d',$v['add_time']) ?></td>
                <td align="center"><?php echo date('Y-m-d',$v['update_time']) ?></td>
                <td align="center"><?php echo $v['price'] ?></td>
                <td align="center"><a href="javascript:void(0)" onclick="orderGoods(&apos;<?php echo $v['order_id'] ?>&apos;)">订单商品</a></td>
            </tr>
            <?php endforeach; ?>
        </table>

    <!-- 分页开始 -->
        <table id="page-table" cellspacing="0">
            <tr>
                <td width="80%">&nbsp;</td>
                <td align="center" nowrap="true">
                    <?php echo $data['page'] ?>
                </td>
            </tr>
        </table>
    <!-- 分页结束 -->
    </div>
</form>
 <!--引入高亮显示-->
<script type="text/javascript" src="/jiimade/Public/Admin/Js/tron.js"></script>
<script>
    function orderGoods(id){
        $.ajax({
            type: 'post',
            url: "<?php echo U('Order/orderGoods') ?>",
            dataType: 'json',
            data: {orderId:id},
            success: function(data){
               var  tr = "<input type='hidden' id='choose-order' value='"+id+"'><table cellpadding='3' cellspacing='1'><tr><th>商品图片</th><th>商品名称</th><th>商品规格</th><th>购买数量</th></tr>";
               $(data).each(function (k,v) {
                   tr += "<tr class='tron'>";
                   tr += "<td align='center'><image src="+v.img_src+" width='50px' height='50px'></td>";
                   tr += "<td align='center'>"+v.goods_name+"</td>";
                   tr += "<td align='center'>"+v.goods_attr_val+"</td>";
                   tr += "<td align='center'>"+v.cart_number+"</td>";
                   tr += "</tr>";
               });
               tr += "</table>";
               $('#order-goods').html(tr);
               $('#goods-list').css('display','inline');
            }
        });
    }
    function closeList() {
        $('#goods-list').css('display','none');
    }
    function comDeli() {
        express = $('#express').val();
        orderId = $('#choose-order').val();
        $.ajax({
            type: 'post',
            url: "<?php echo U('Order/orderDeli') ?>",
            dataType: 'json',
            data: {express:express,orderId:orderId},
            success: function(data) {
                if (data == 'success'){
                    $('#goods-list').css('display','none');
                    alert('发货成功!');
                }else{
                    alert('发货失败！');
                }
            }
        });
    }
</script>


<div id="footer">
版权所有 &copy; 2018 宁波几和网络科技有限公司，并保留所有权利。</div>
</body>
</html>