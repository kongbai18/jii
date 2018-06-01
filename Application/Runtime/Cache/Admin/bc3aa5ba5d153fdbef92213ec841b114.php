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



<form method="post" action="" name="listForm">
 
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>家具类型名称</th>
                <th>操作</th>
            </tr>
            <tr>
                <td colspan="2">柜体</td>
            </tr>
            <?php foreach($cabinet as $k => $v): ?>
            <tr class="tron">
                <td>--<?php echo $v['fur_name'] ?></td>
                <td align="center">
                    <a href="<?php echo U('furniture_quote?id='.$v['id']) ?>" >属性模型</a> |
                    <a href="<?php echo U('edit?id='.$v['id']) ?>" >编辑</a> |
                    <a href="<?php echo U('delete?id='.$v['id']) ?>" onclick="return delMol()">移除</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2">门板</td>
            </tr>
            <?php foreach($door as $k => $v): ?>
            <tr class="tron">
                <td>--<?php echo $v['fur_name'] ?></td>
                <td align="center">
                    <a href="<?php echo U('furniture_quote?id='.$v['id']) ?>" >属性模型</a> |
                    <a href="<?php echo U('edit?id='.$v['id']) ?>" >编辑</a> |
                    <a href="<?php echo U('delete?id='.$v['id']) ?>" onclick="return delMol()">移除</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2">饰面</td>
            </tr>
            <?php foreach($front as $k => $v): ?>
            <tr class="tron">
                <td>--<?php echo $v['fur_name'] ?></td>
                <td align="center">
                    <a href="<?php echo U('furniture_quote?id='.$v['id']) ?>" >属性模型</a> |
                    <a href="<?php echo U('edit?id='.$v['id']) ?>" >编辑</a> |
                    <a href="<?php echo U('delete?id='.$v['id']) ?>" onclick="return delMol()">移除</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

 <!--引入高亮显示-->
<script type="text/javascript" src="/jiimadeeee/Public/Admin/Js/tron.js"></script>
<script>
    function delMol() {
        if(confirm("确认删除嘛？")){
            return true;
        }else {
            return false;
        }
    }
</script>


<div id="footer">
版权所有 &copy; 2018 宁波几和网络科技有限公司，并保留所有权利。</div>
</body>
</html>