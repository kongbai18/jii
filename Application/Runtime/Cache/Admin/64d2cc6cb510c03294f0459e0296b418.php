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





    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>主题名称</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
            <?php foreach($data as $k => $v): ?>
            <tr class="tron">
                <td align="center"><?php echo $v['theme_name'] ?></td>
                <td align="center"><?php echo $v['sort_id'] ?></td>
                <td align="center">
                <a href="<?php echo U('edit?id='.$v['id']) ?>" >编辑</a> |
                <a href="<?php echo U('delete?id='.$v['id']) ?>" onclick="return delCol()">移除</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
<script>
    function delCol() {
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