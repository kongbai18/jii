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
    <form method="post" action="/jiimadeeee/index.php/Admin/Carousel/edit"enctype="multipart/form-data" >
        <table cellspacing="1" cellpadding="3" width="100%">
        <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
            <tr>
                <td class="label">颜色图片</td>
                <td>
                    <input type="file" name="img_src" accept="image/*" id="img" size="45">
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">商品分类：</td>
                <td>
                    <select name="theme_id">
                        <option value="">请选择...</option>
                        <?php foreach($themeData as $k => $v): if($v['id']==$data['theme_id']){ $select = 'selected="selected"'; }else{ $select = ""; } ?>
                        <?php echo '<option ' .$select.' value="'.$v['id'].'">'.$v['theme_name'].'</option>'; ?>
                        <?php endforeach; ?>
                    </select>
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">跳转路径</td>
                <td>
                    <input type="text" name="url" maxlength="120" style="width: 300px;" value="<?php echo $data['url'] ?>" />
                </td>
            </tr>
            <tr>
                <td class="label">排序</td>
                <td>
                    <input type="text" name="sort_id" maxlength="60" style="width: 50px;" value="<?php echo $data['sort_id'] ?>" />
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><br />
                    <input type="submit" class="button" value=" 确定 " />
                </td>
            </tr>
        </table>
    </form>
</div>


<div id="footer">
版权所有 &copy; 2018 宁波几和网络科技有限公司，并保留所有权利。</div>
</body>
</html>