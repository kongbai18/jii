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
    <form method="post" action="/jiimadeeee/index.php/Admin/Quote/add.html"enctype="multipart/form-data" >
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">用户姓名：</td>
                <td>
                    <input type="text" name="user_name" maxlength="60" />
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">用户地址：</td>
                <td>
                    <input type="text" name="address" maxlength="60" />
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">用户手机号：</td>
                <td >
                    <input type="text" name="telephone" maxlength="60" />
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
    function addMaterial(a) {
        let li = $(a).parent();
        var sort = $(a).attr('sort');
        var qqq = '#material-list'+sort;
        if($(a).text() == '[+]'){
            var newLi = li.clone();
            console.log(newLi);
            newLi.find('a').text('[-]');
            newLi.find('select').val('');
            $(qqq).append(newLi);
        }else{
            li.remove();
        }
    }
    function addParameter(a) {
        let li = $(a).parent();
        if($(a).text() == '[+]'){
            var newLi = li.clone();
            newLi.find('a').text('[-]');
            newLi.find('input').val('');
            $('#parameter-list').append(newLi);
        }else{
            li.remove();
        }
    }
    function addFormula(a) {
        let li = $(a).parent();
        if($(a).text() == '[+]'){
            var newLi = li.clone();
            newLi.find('a').text('[-]');
            newLi.find('input').val('');
            $('#formula-list').append(newLi);
        }else{
            li.remove();
        }
    }
    function delMaterial(a) {
        let div = $(a).parent();
        div.remove();
    }
    index = 0;
    function addMaterCat() {
        index = index + 1;
        var catData = <?php echo json_encode($catData);?>;
        var div = '<div id="material-list'+index+'" class="material-list">';
            div += '<a href="javascript:void(0)" onclick="delMaterial(this)">[删除此材料]</a>';
            div += '<input type="text" name="material-price['+index+']" placeholder="价格参数如p1" style="width: 80px;margin-right: 5px;">';
            div += '<input type="text" name="material-name['+index+']" placeholder="材料类名称" style="width: 80px;margin-right: 5px;">';
            div += '<li> <a href="javascript:void(0)" sort="'+index+'" onclick="addMaterial(this)">[+]</a>';
            div += '<select name="material-val['+index+'][]">';
            div += '<option value="">请选择...</option>';
        $(catData).each(function(k,v){
            console.log(v.name);
            div += '<option value="'+v.id+'">'+repeat("-",2*v.level)+v.name+'</option>';
        });
            div += '</select> </li>';
            div += '</div>';
        $('#material').append(div);
    }
    function repeat(str, num){
        return new Array( num + 1 ).join( str );
    }
</script>



<div id="footer">
版权所有 &copy; 2018 宁波几和网络科技有限公司，并保留所有权利。</div>
</body>
</html>