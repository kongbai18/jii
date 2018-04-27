<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>JiiHOME 管理中心 - 添加新商品 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/jiimade/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/jiimade/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/jiimade/Public/Admin/Js/jquery-1.10.2.min.js"></script>
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo $btn_url; ?>"><?php echo $btn_name; ?></a>
    </span>
    <span class="action-span1"><a href="/jiimade/index.php/Admin/index/index">JiiHOME 管理中心</a></span>
    <span id="search_id" class="action-span1"> - <?php echo $title ?> </span>
    <div style="clear:both"></div>
</h1>


<style>
    #color li{
        list-style:none;
        display: inline-block;
        margin-right: 20px;
    }
</style>
<div class="main-div">
    <form method="post" action="/jiimade/index.php/Admin/Attribute/edit/id/7.html"enctype="multipart/form-data" >
        <table cellspacing="1" cellpadding="3" width="100%">
        <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
            <tr>
                <td class="label">属性名称</td>
                <td>
                    <input type="text" name="attr_name" maxlength="60" value="<?php echo $data['attr_name'] ?>" />
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">属性类型</td>
                <td>
                    <input type="radio" name="attr_type" <?php if($data['attr_type'] == 1)echo 'checked="checked"'; ?> value="1">唯一
                    <input type="radio" name="attr_type" <?php if($data['attr_type'] == 2)echo 'checked="checked"'; ?> value="2">可选
                    <input type="radio" name="attr_type" <?php if($data['attr_type'] == 3)echo 'checked="checked"'; ?> value="3">颜色库
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">属性可选值</td>
                <td>

                    <div id="attr_unique" <?php echo ($data['attr_type'] == 1)?'':'style="display:none;"'; ?>>
                        (属性唯一不需要填写属性可选值)
                    </div>
                    <div id="attr_val" <?php echo ($data['attr_type'] == 2)?'':'style="display:none;"'; ?>>
                        <input type="text" name="attr_option_values" maxlength="60" value="<?php echo $data['attr_option_values'] ?>" />（请使用 ， 将可选值隔开）
                    </div>
                    <div id="color" <?php echo ($data['attr_type'] == 3)?'':'style="display:none;"'; ?>>
                        <?php $colorId = explode(',',$data['attr_option_values']); foreach($colorId as $k =>$v){ ?>
                        <li>
                            <a href="javascript:void(0)" onclick="addColor(this)"><?php echo ($k == 0)?'[+]':'[-]'; ?></a><?php buildSelect('color','color_id[]','id','color_name',$v,'请选择颜色') ?>
                        </li>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label">所属类型</td>
                <td>
                    <?php buildSelect('type','type_id','id','type_name',$data['type_id']) ?>
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><br />
                    <input type="submit" class="button" value=" 确定 " />
                    <input type="reset" class="button" value=" 重置 " />
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    $('input:radio[name="attr_type"]').change( function(){
        var a = $("input[name='attr_type']:checked").val();
        if(a == 3){
            $('#color').css('display','inline');
            $('#attr_val').css('display','none');
            $('#attr_unique').css('display','none');
        }else if(a == 2){
            $('#attr_val').css('display','inline');
            $('#color').css('display','none');
            $('#attr_unique').css('display','none');
        }else if(a == 1){
            $('#attr_val').css('display','none');
            $('#color').css('display','none');
            $('#attr_unique').css('display','inline');
        }
    });
    function addColor(a) {
        let li = $(a).parent();
        if($(a).text() == '[+]'){
            var newLi = li.clone();
            newLi.find('a').text('[-]');
            newLi.find('select').val('');
            $('#color').append(newLi);
        }else{
            li.remove();
        }
    }
</script>


<div id="footer">
版权所有 &copy; 2018 宁波几和网络科技有限公司，并保留所有权利。</div>
</body>
</html>