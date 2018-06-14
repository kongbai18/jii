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
    <form method="post" action="/jiimadeeee/index.php/Admin/Role/edit/id/2.html"enctype="multipart/form-data" >
        <table cellspacing="1" cellpadding="3" width="100%">
        <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
            <tr>
                <td class="label">角色名称 :</td>
                <td>
                    <input type="text" name="role_name" maxlength="60" value="<?php echo $data['role_name'] ?>" />
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">权限列表 :</td>
                <td>
                    <?php foreach($priData as $k => $v): if(in_array($v['id'],$rpData)){ $check = 'checked="checked"'; }else{ $check = ''; } ?>
                    <?php echo str_repeat('-',4*$v['level']) ?>
                    <input level_id="<?php echo $v['level'] ?>" type="checkbox" name="pri_id[]" <?php echo $check; ?> value="<?php echo $v['id'] ?>"><?php echo $v['pri_name'] ?></br>
                    <?php endforeach; ?>
                </td>
            </tr>
        </table>
        <div class="button-div">
                    <input type="submit" class="button" value=" 确定 " />
                    <input type="reset" class="button" value=" 重置 " />
        </div>
    </form>
</div>
<script>
//所有的复选框绑定事件
$(":checkbox").click(function(){
	//获取级别ID
	var tmp_level_id = level_id = $(this).attr("level_id");
	//判断是否选中
	if($(this).prop("checked")){
		//所有子权限选中
		$(this).nextAll(":checkbox").each(function(k,v){
			if($(v).attr("level_id") > level_id){
				$(v).prop("checked","checked");
			}else{
				return false;
			}
		});
		//所有上级权限选中
		$(this).prevAll(":checkbox").each(function(k,v){
			if($(v).attr("level_id") < tmp_level_id){
				$(v).prop("checked","checked");
				tmp_level_id--;
			}
		});
	}else{
		//所有子权限取消
		$(this).nextAll(":checkbox").each(function(k,v){
			if($(v).attr("level_id") > level_id){
				$(v).removeAttr("checked");
			}else{
				return false;
			}
		});
		//所有上级权限取消
		/*$(this).prevAll(":checkbox").each(function(k,v){
			if($(v).attr("level_id") < tmp_level_id){
				$(v).removeAttr("checked");
				tmp_level_id--;
			}
		});*/
	}
});
</script>


<div id="footer">
版权所有 &copy; 2018 宁波几和网络科技有限公司，并保留所有权利。</div>
</body>
</html>