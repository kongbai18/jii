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
    <form action="/jiimadeeee/index.php/Admin/Category/add.html" method="post" name="theForm" enctype="multipart/form-data">
        <table width="100%" id="general-table">
            <tr>
                <td class="label">分类名称:</td>
                <td>
                    <input type='text' name='name' maxlength="20" value='' size='27' /> <font color="red">*</font>
                </td>
            </tr>
            <tr>
                <td class="label">英文名称:</td>
                <td>
                    <input type='text' name='name_en' maxlength="20" value='' size='27' />
                </td>
            </tr>
            <tr>
                <td class="label">上级分类:</td>
                <td>
                    <select name="parent_id">
                        <option value="0">顶级分类</option>
                        <?php foreach($data as $k => $v): ?>
                        <?php echo '<option value="'.$v['id'].'">'.str_repeat('-',4*$v['level']).$v['name'].'</option>'; ?>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label">首页所在区块:</td>
                <td>
                    <select name="index_block">
                        <option value="0">首页不展示</option>
                        <option value="1">图品加商品</option>
                        <option value="2">商品小图</option>
                        <option value="3">商品大图</option>
                        <option value="4">只展示图品</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label">排序:</td>
                <td>
                    <input type="text" name='order_id'  value="100" size="15" />
                </td>
            </tr>
            <tr>
                <td class="label">是否显示:</td>
                <td>
                    <input type="radio" name="is_index" value="1"  checked="true"/> 是
                    <input type="radio" name="is_index" value="0"  /> 否
                </td>
            </tr>
            <tr>
                <td class="label">分类展示图</td>
                <td>
                    <input type="file" name="img_src" accept="image/*" id="logo" size="45"><br/>
                    <span class="notice-span" style="display:block"  id="warn_brandlogo">如作为顶级分类且显示请上传展示图</span>
                </td>
            </tr>
        </table>
        <div class="button-div">
            <input type="submit" value=" 确定 " />
            <input type="reset" value=" 重置 " />
        </div>
    </form>
</div>


<div id="footer">
版权所有 &copy; 2018 宁波几和网络科技有限公司，并保留所有权利。</div>
</body>
</html>