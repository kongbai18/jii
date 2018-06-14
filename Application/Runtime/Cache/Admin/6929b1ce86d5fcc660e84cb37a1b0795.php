<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>JiiHOME 管理中心 - 添加新商品 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/jiimadeeee/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/jiimadeeee/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/jiimadeeee/Public/Admin/Js/jquery-1.10.2.min.js"></script>
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo $btn_url; ?>"><?php echo $btn_name; ?></a>
    </span>
    <span class="action-span1"><a href="/jiimadeeee/index.php/Admin/index/index">JiiHOME 管理中心</a></span>
    <span id="search_id" class="action-span1"> - <?php echo $title ?> </span>
    <div style="clear:both"></div>
</h1>


<div class="form-div">
    <form action="" name="searchForm">
        <img src="/jiimadeeee/Public/Admin/Images/icon_search.gif" width="26" height="22" border="0" alt="search" />
        <!-- 状态 -->
        <select name="status">
            <option value='' <?php echo I('get.status') === ''?'selected':'' ?>>全部</option>
            <option value="0" <?php echo (I('get.status') === '0')?'selected':'' ?>>待付款</option>
            <option value="2" <?php echo I('get.status') === '2'?'selected':'' ?>>待发货</option>
            <option value="3" <?php echo I('get.status') === '3'?'selected':'' ?>>待收货</option>
            <option value="1" <?php echo I('get.status') === '1'?'selected':'' ?>>已完成</option>
            <option value="7" <?php echo I('get.status') === '7'?'selected':'' ?>>已退款</option>
        </select>
        <!-- 关键字 -->
        订单号 <input type="text" name="orderId" size="15" value="<?php echo I('get.orderId') ?>" />
        <input type="submit" value="搜索" class="button" />
    </form>
</div>

<!-- 商品列表 -->
<form method="post" action="" name="listForm" onsubmit="">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>订单号</th>
                <th>下单时间</th>
                <th>最新更新时间</th>
                <th>总价格</th>
            </tr>
            <?php foreach($data['data'] as $k => $v): ?>
            <tr class="tron">
                <td align="center"><?php echo $v['order_id'] ?></td>
                <td align="center"><?php echo date('Y-m-d',$v['add_time']) ?></td>
                <td align="center"><?php echo date('Y-m-d',$v['update_time']) ?></td>
                <td align="center"><?php echo $v['price'] ?></td>
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
<script type="text/javascript" src="/jiimadeeee/Public/Admin/Js/tron.js"></script>        



<div id="footer">
版权所有 &copy; 2018 宁波几和网络科技有限公司，并保留所有权利。</div>
</body>
</html>