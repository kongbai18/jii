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


<div class="main-div">
    <form method="post" action="/jiiMade/index.php/Admin/Admin/edit.html"enctype="multipart/form-data" >
        <table cellspacing="1" cellpadding="3" width="100%">
        <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
            <tr>
                <td class="label">用户名称 :</td>
                <td>
                    <input type="text" name="username" maxlength="60" value="<?php echo $data['username'] ?>" />
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">密码 :</td>
                <td>
                    <input type="password" name="password" maxlength="60" value="" />
                     <span> 密码为空默认为不修改</span>
                </td>
            </tr>
            <tr>
                <td class="label">确认密码 :</td>
                <td>
                    <input type="password" name="password1" maxlength="60" value="" />
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