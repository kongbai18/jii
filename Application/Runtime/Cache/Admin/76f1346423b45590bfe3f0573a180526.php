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


<style>
    .material-list li{
        list-style:none;
        display: inline-block;
        margin-right: 20px;
    }
    #parameter-list li{
        list-style:none;
        display: inline-block;
        margin-right: 20px;
    }
    #formula-list li{
        list-style:none;
        margin-right: 20px;
        margin-bottom: 10px;
    }
    #install-list li{
        list-style:none;
        margin-right: 20px;
    }
    .ext-list{
        margin-top:5px;
    }
    .ext-list li{
        list-style:none;
        display: inline-block;
        margin-right: 20px;
    }
</style>
<div class="main-div">
    <form method="post" action="/jiimadeeee/index.php/Admin/Model/add.html"enctype="multipart/form-data" >
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">模型名称：</td>
                <td>
                    <input type="text" name="model_name" maxlength="60" />
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">涉及材料类：</td>
                <td id="material">
                    <div>
                        <input type="button" value="添加一个材料类" onclick="addMaterCat()">
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label">所需参数：</td>
                <td id="parameter-list">
                    <li>
                        <a href="javascript:void(0)" onclick="addParameter(this)">[+]</a><input type="text" name="parameter[]" style="width: 80px;">
                    </li>
                </td>
            </tr>
            <tr>
                <td class="label">投影面积公式：</td>
                <td>
                    <input type="text" name="project_area" placeholder="投影面积公式">
                </td>
            </tr>
            <tr>
                <td class="label">计价公式：</td>
                <td id="formula-list">
                    <li>
                        <a href="javascript:void(0)" onclick="addFormula(this)">[+]</a>
                        <input type="text" name="formula-name[]" placeholder="计价名称" style="width: 80px;">
                        <input type="text" name="formula-num[]" placeholder="计价数量" style="width: 200px;">
                        <input type="text" name="formula-price[]" placeholder="计价单价" style="width: 80px;">
                        <input type="text" name="formula-total-price[]" placeholder="总计价" style="width: 80px;">
                        <input type="text" name="formula-unit[]" placeholder="计价单位" style="width: 80px;">
                    </li>
                </td>
            </tr>
            <tr>
                <td class="label">扩展参数：</td>
                <td id="ext">
                    <div>
                        <input type="radio" name="ext" value="1" checked>单选
                        <input type="radio" name="ext" value="2">多选
                        <input type="radio" name="ext" value="3">输入框
                        <input type="button" value="添加一个扩展" onclick="addExt()">
                    </div>
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
<script>
    index = 0;
    function addExt() {
        let ext = $("input[name='ext']:checked").val();
        index = index + 1;
        var div = '<div id="ext-list'+index+'" class="ext-list">';
        div += '<a href="javascript:void(0)" onclick="delExt(this)">[删除此扩展]</a>';
        if(ext == '1'){
            div += ' 单选 ';
        }else if(ext == '2'){
            div += ' 多选 ';
        }else if(ext == '3'){
            div += ' 输入框 ';
        }
        div += '<input type="hidden" name="ext_cat['+index+']" value="'+ext+'">';
        div += '<input type="text"  placeholder="扩展名称" name="ext_name['+index+']" style="width: 80px;margin-right: 5px;">';
        div += '<input type="text"  placeholder="扩展参数符号" name="ext_para['+index+']" style="width: 80px;margin-right: 5px;">';
        if(ext == '1' || ext == '2'){
            div += '<li> <a href="javascript:void(0)" sort="'+index+'"  onclick="addExtVal(this)">[+]</a>';
            div += '<input type="text" placeholder="扩展值名称" name="ext_val_name['+index+'][]" style="width: 80px;margin-right: 5px;">';
            div += '<input type="text" placeholder="扩展值" name="ext_val['+index+'][]" style="width: 80px;"></li>';
            div += '</div>';
        }
        $('#ext').append(div);
    }
    function delExt(a) {
        let div = $(a).parent();
        div.remove();
    }
    function addExtVal(a) {
        let li = $(a).parent();
        var sort = $(a).attr('sort');
        var qqq = '#ext-list'+sort;
        if($(a).text() == '[+]'){
            var newLi = li.clone();
            newLi.find('a').text('[-]');
            newLi.find('input').val('');
            $(qqq).append(newLi);
        }else{
            li.remove();
        }
    }
</script>



<div id="footer">
版权所有 &copy; 2018 宁波几和网络科技有限公司，并保留所有权利。</div>
</body>
</html>