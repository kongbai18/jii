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


        <!-- 用户姓名 -->
        用户姓名 <input type="text" name="u_name" size="15" value="<?php echo I('get.u_name') ?>" />

        <!-- 手机号 -->
        手机号 <input type="text" name="u_phone" size="15" value="<?php echo I('get.u_phone') ?>" />
        <input type="submit" value="搜索" class="button" />
    </form>
</div>
 
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>报价单号</th>
                <th>业主姓名</th>
                <th>业主地址</th>
                <th>手机号</th>
                <th>最后操作时间</th>
                <th>操作</th>
            </tr>
            <?php foreach($data['data'] as $k => $v): ?>
            <tr class="tron">
                <td align="center"><?php echo $v['id'] ?></td>
                <td align="center"><?php echo $v['user_name'] ?></td>
                <td align="center"><?php echo $v['address'] ?></td>
                <td align="center"><?php echo $v['telephone'] ?></td>
                <td align="center"><?php if($v['update_time']){echo date("Y-m-d H:i:s", $v['update_time']);}else{echo date("Y-m-d H:i:s", $v['add_time']);} ?></td>
                <td align="center">
                <a href="<?php echo U('detail?id='.$v['id']) ?>" >报价单详情</a> |
                <a href="<?php echo U('getExcel?id='.$v['id']) ?>" >下载报价表</a> |
                <a href="<?php echo U('getPrcode?id='.$v['id']) ?>" >推送二维码</a> |
                <a href="<?php echo U('delete?id='.$v['id']) ?>" onclick="return delMol()">移除</a>
                </td>
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