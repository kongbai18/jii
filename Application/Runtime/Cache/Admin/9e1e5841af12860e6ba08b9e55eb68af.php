<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>JiiHOME 管理中心 - 添加新商品 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/jiiMade/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/jiiMade/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/jiiMade/Public/Admin/Js/jquery-1.10.2.min.js"></script>
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo $btn_url; ?>"><?php echo $btn_name; ?></a>
    </span>
    <span class="action-span1"><a href="/jiiMade/index.php/Admin/index/index">JiiHOME 管理中心</a></span>
    <span id="search_id" class="action-span1"> - <?php echo $title ?> </span>
    <div style="clear:both"></div>
</h1>


<div class="form-div">
    <form action="/jiiMade/index.php/Admin/Type/lst.html" name="searchForm">
    <img src="/jiiMade/Public/Admin/Images/icon_search.gif" width="26" height="22" border="0" alt="search" />
    <input type="text" name="type_name" size="15" value="<?php echo I('get.type_name'); ?>" />
    <input type="submit" value=" 搜索 " class="button" />
    </form>
</div>
<form method="post" action="" name="listForm">
 
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>类型编号</th>
                <th>类型名称</th>
                <th>操作</th>
            </tr>
            <?php foreach($data['data'] as $k => $v): ?>
            <tr class="tron">
                <td align="center"><?php echo $v['id'] ?></td>
                <td align="center"><?php echo $v['type_name'] ?></td>
                <td align="center">
                <a href="<?php echo U('attribute/lst?type_id='.$v['id']) ?>">查看属性</a> |
                <a href="<?php echo U('edit?id='.$v['id']) ?>" >编辑</a> |
                <a href="<?php echo U('delete?id='.$v['id']) ?>" onclick="return delTyp()">移除</a>
                </td>
            </tr>
            <?php endforeach; ?>
 <!-- 分页开始 -->
        <table id="page-table" cellspacing="0">
            <tr>
                <td width="80%">&nbsp;</td>
                <td align="center" nowrap="true">
                    <?php echo $data['page'] ?>
                </td>
            </tr>
        </table>
 <!--引入高亮显示-->
<script type="text/javascript" src="/jiiMade/Public/Admin/Js/tron.js"></script>
<script>
    function delTyp() {
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