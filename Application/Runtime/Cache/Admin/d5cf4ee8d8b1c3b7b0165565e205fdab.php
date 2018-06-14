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
    <form action="/jiimadeeee/index.php/Admin/Privilege/edit" method="post" name="theForm" enctype="multipart/form-data">
        <table width="100%" id="general-table">
        <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
            <tr>
                <td class="label">分类名称:</td>
                <td>
                    <input type='text' name='pri_name' maxlength="20" value="<?php echo $data['pri_name'] ?>" size='27' /> <font color="red">*</font>
                </td>
            </tr>
            <tr>
                <td class="label">上级分类:</td>
               <td>
                 <select name="parent_id">
                    <option value="0">顶级分类</option>
                    <?php foreach($tree as $k => $v): if($v['id']==$data['parent_id']){ $select = 'selected="selected"'; }else{ $select = ''; } ?>
                    <?php if(in_array($v['id'],$children)){ continue; }else{ echo '<option '.$select.' value="'.$v['id'].'">'.str_repeat('-',4*$v['level']).$v['pri_name'].'</option>'; } ?>
                    <?php endforeach; ?>
                 </select>
            </tr>
             <tr>
                <td class="label">模型名称:</td>
                <td>
                    <input type='text' name='module_name' maxlength="20" value='<?php echo $data['module_name'] ?>' size='27' /> <font color="red">*</font>
                </td>
            </tr>
            <tr>
                <td class="label">控制器名称:</td>
                <td>
                    <input type='text' name='controller_name' maxlength="20" value='<?php echo $data['controller_name'] ?>' size='27' /> <font color="red">*</font>
                </td>
            </tr>
            <tr>
                <td class="label">方法名称:</td>
                <td>
                    <input type='text' name='action_name' maxlength="20" value='<?php echo $data['action_name'] ?>' size='27' /> <font color="red">*</font>
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