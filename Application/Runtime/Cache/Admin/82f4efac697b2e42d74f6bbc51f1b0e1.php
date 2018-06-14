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


<div class="main-div">
    <form method="post" action="/jiimadeeee/index.php/Admin/Admin/add.html"enctype="multipart/form-data" >
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">角色列表 :</td>
                <td>
                    <?php foreach($rData as $k => $v): ?>
                    <input type="checkbox" name="role_id[]" value="<?php echo $v['id'] ?>"><?php echo $v['role_name'] ?>
                    <?php endforeach; ?>
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">用户名称 :</td>
                <td>
                    <input type="text" name="username" maxlength="60" value="" />
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">密码 :</td>
                <td>
                    <input type="password" name="password" maxlength="60" value="" />
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">确认密码 :</td>
                <td>
                    <input type="password" name="password1" maxlength="60" value="" />
                    <span class="require-field">*</span>
                </td>
            </tr>
        </table>
        <div class="button-div">
                    <input type="submit" class="button" value=" 确定 " />
                    <input type="reset" class="button" value=" 重置 " />
        </div>
    </form>
</div>



<div id="footer">
版权所有 &copy; 2018 宁波几和网络科技有限公司，并保留所有权利。</div>
</body>
</html>