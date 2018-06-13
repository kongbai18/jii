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


<style>
    .hide{
        display: none;
    }
</style>
<form method="post" action="" name="listForm">
    <div class="list-div" id="listDiv">
        <table width="100%" cellspacing="1" cellpadding="2" id="list-table">
            <tr>
                <th>分类名称</th>
                <th>是否显示</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
            <?php foreach($data as $k=> $v): ?>
                <tr class="tron par<?php echo $v['parent_id'] ?>  <?php echo ($v['level'] != 0)?'hide': '';?>" dat="<?php echo $v['id'] ?>">
                    <td ><?php echo str_repeat('-',4*$v['level']).$v['name'] ?></td>
                    <td align="center"><?php echo ($v['is_index']==1)?'显示':'不显示'; ?></td>
                    <td align="center" ><?php echo $v['order_id'] ?></td>
                    <td align="center" >
                        <a href="<?php echo U('edit?id='.$v['id']) ?>">编辑</a> |
                        <a href="<?php echo U('del?id='.$v['id']) ?>" title="移除" onclick="return delCat()">移除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</form>
<!--引入高亮显示-->
<script type="text/javascript" src="/jiimadeeee/Public/Admin/Js/tron.js"></script>
<script type="text/javascript">
        $('.tron').click(function() {
            var pa = $(this).attr("dat");
            var sub = '.par'+pa;
            $(sub).toggleClass('hide');
        });
        function delCat() {
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