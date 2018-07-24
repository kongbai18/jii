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
        <!-- 分类 -->
        <select name="cate_id">
            <option value="">所有分类</option>
            <?php foreach($catData as $k => $v): if($v['id'] == I('get.cate_id')){ $select = "selected"; }else{ $select = ""; } ?>
            <?php echo '<option value="'.$v['id'].'" '.$select.' >'.str_repeat('-',4*$v['level']).$v['name'].'</option>'; ?>
            <?php endforeach; ?>
        </select>
        <!-- 关键字 -->
        关键字 <input type="text" name="keyword" size="15" value="<?php echo I('get.keyword') ?>" />
        <input type="submit" value="搜索" class="button" />
    </form>
</div>

<!-- 商品列表 -->
<form method="post" action="" name="listForm" onsubmit="">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>编号</th>
                <th>文章名称</th>
                <th>所属分类</th>
                <th>文章简介</th>
                <th>是否显示</th>
                <th>推荐排序</th>
                <th>操作</th>
            </tr>
            <?php foreach($data['data'] as $k => $v): ?>
            <tr class="tron">
                <td align="center"><?php echo $v['id'] ?></td>
                <td align="center"><?php echo $v['article_name'] ?></td>
                <td align="center"><?php echo $v['cate_name'] ?></td>
                <td align="center"><?php echo $v['article_brief'] ?></td>
                <td align="center"><img src="<?php echo ($v['is_index'] == 1)?'/jiimadeeee/Public/Admin/Images/yes.gif':'/jiimadeeee/Public/Admin/Images/no.gif'; ?>"/></td>
                <td align="center"><?php echo $v['sort_id'] ?></td>
                <td align="center">
                <a href="<?php echo U('edit?id='.$v['id']) ?>" >编辑</a> |
                <a href="<?php echo U('delete?id='.$v['id']) ?>" onclick="return delArt()">移除</a></td>
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
<script>
    function delArt() {
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