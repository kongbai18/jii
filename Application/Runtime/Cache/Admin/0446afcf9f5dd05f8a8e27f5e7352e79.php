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
    <form method="post" action="/jiimadeeee/index.php/Admin/Admin/setIntegration.html"enctype="multipart/form-data" >
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">注册奖励 :</td>
                <td>
                    <input type="text" name="integration[]" maxlength="60" value="<?php echo $data[0]['integration'] ?>" />
                </td>
            </tr>
            <tr>
                <td class="label">绑定手机奖励 :</td>
                <td>
                    <input type="text" name="integration[]" maxlength="60" value="<?php echo $data[1]['integration'] ?>" />
                </td>
            </tr>
            <tr>
                <td class="label">第一次报价奖励 :</td>
                <td>
                    <input type="text" name="integration[]" maxlength="60" value="<?php echo $data[2]['integration'] ?>" />
                </td>
            </tr>
            <tr>
                <td class="label">订单奖励积分 :</td>
                <td>
                    <input type="text" name="integration[]" maxlength="60" value="<?php echo $data[3]['integration'] ?>" />
                    <span class="require-field">此项为订单金额百分比</span>
                </td>
            </tr>
            <tr>
                <td class="label">订单返利 :</td>
                <td>
                    <input type="text" name="integration[]" maxlength="60" value="<?php echo $data[4]['integration'] ?>" />
                    <span class="require-field">此项为订单金额百分比</span>
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