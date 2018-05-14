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
</style>
<div class="main-div">
    <form method="post" action="/jiimadeeee/index.php/Admin/Model/edit/id/5.html"enctype="multipart/form-data" >
        <table cellspacing="1" cellpadding="3" width="100%">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <tr>
                <td class="label">模型名称</td>
                <td>
                    <input type="text" name="model_name" maxlength="60" value="<?php echo $data['model_name']; ?>"/>
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">模型分类：</td>
                <td >
                    <select name="model_cate">
                        <option value="1" <?php echo ($data['model_cate'] == 1)?'selected':'' ?>>柜体</option>
                        <option value="2" <?php echo ($data['model_cate'] == 2)?'selected':'' ?>>饰面</option>
                        <option value="3" <?php echo ($data['model_cate'] == 3)?'selected':'' ?>>门</option>
                    </select>
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">模型图：</td>
                <td >
                    <input type="file" name="img_src" accept="image/*" >
                </td>
            </tr>
            <tr>
                <td class="label">涉及材料类：</td>
                <td id="material">
                    <div>
                        <input type="button" value="添加一个材料类" onclick="addMaterCat()">
                    </div>
                    <?php $material = json_decode($data['material'],true);$index = 1;?>
                    <?php foreach($material as $k => $v): ?>
                    <div id="material-list<?php echo $index;?>" class="material-list">
                        <a href="javascript:void(0)" onclick="delMaterial(this)">[删除此材料]</a>
                        <input type="text" name="material-price<?php echo $index;?>" value="<?php echo $k;?>" style="width: 80px;margin-right: 5px;">
                        <?php foreach($v as $k1 => $v1): $cateId = explode(',',$v1)?>
                        <input type="text" name="material-name<?php echo $index;?>" value="<?php echo $k1;?>" style="width: 80px;margin-right: 5px;">
                           <?php foreach($cateId as $k2 => $v2):?>
                              <?php if($k2 == 0): ?>
                                  <li> <a href="javascript:void(0)" sort="<?php echo $index;?>" onclick="addMaterial(this)">[+]</a>
                              <?php else: ?>
                                  <li> <a href="javascript:void(0)" sort="<?php echo $index;?>" onclick="addMaterial(this)">[-]</a>
                              <?php endif; ?>
                                    <select name="material-val['<?php echo $index;?>'][]">
                                        <option value="">请选择...</option>
                                        <?php foreach($catData as $k3 => $v3): ?>
                                        <option value="<?php echo $v3['id'] ?>" <?php echo ($v3['id']== $v2)?'selected':''?>><?php echo str_repeat('-',2*$v3['level']).$v3['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                 </li>
                           <?php  endforeach;?>
                        <?php endforeach;?>
                    </div>
                    <?php $index = $index+1; endforeach; ?>
                </td>
            </tr>
            <tr>
                <td class="label">所需参数：</td>
                <td id="parameter-list">
                    <?php $parameter = json_decode($data['parameter'],true);?>
                    <?php foreach($parameter as $k => $v):?>
                    <li>
                        <?php if($k == 0): ?>
                          <a href="javascript:void(0)" onclick="addParameter(this)">[+]</a>
                        <?php else: ?>
                          <a href="javascript:void(0)" onclick="addParameter(this)">[-]</a>
                        <?php endif; ?>
                        <input type="text" name="parameter[]" value="<?php echo $v; ?>" style="width: 80px;">
                    </li>
                    <?php  endforeach;?>
                </td>
            </tr>
            <tr>
                <td class="label">计价公式：</td>
                <td id="formula-list">
                    <?php $formula = json_decode($data['formula'],true);?>
                    <?php $foNum = 0; foreach($formula as $k => $v):?>
                    <li>
                        <?php if($foNum == 0): ?>
                          <a href="javascript:void(0)" onclick="addFormula(this)">[+]</a>
                        <?php else: ?>
                          <a href="javascript:void(0)" onclick="addFormula(this)">[-]</a>
                        <?php endif; ?>
                        <input type="text" name="formula-name[]" placeholder="计价名称" value="<?php echo $k; ?>" style="width: 80px;">
                        <input type="text" name="formula-num[]" placeholder="计价数量" value="<?php echo $v[0]; ?>" style="width: 200px;">
                        <input type="text" name="formula-price[]" placeholder="计价单价" value="<?php echo $v[1]; ?>" style="width: 80px;">
                        <input type="text" name="formula-unit[]" placeholder="计价单位" value="<?php echo $v[2]; ?>" style="width: 80px;">
                    </li>
                    <?php $foNum = $foNum +1; endforeach;?>
                </td>
            </tr>
            <tr>
                <td class="label">推荐排序：</td>
                <td>
                    <input type="text" name="sort_id" size="5" value="<?php echo $data['sort_id']; ?>"/>
                </td>
            </tr>
            <tr>
                <td class="label">是否显示：</td>
                <td>
                    <input type="radio" name="is_index" value="1"  <?php echo ($data['is_index'] == '1')?'checked="checked"':''; ?>/> 是
                    <input type="radio" name="is_index" value="0" <?php echo ($data['is_index'] == '0')?'checked="checked"':''; ?>/> 否
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
    index = <?php echo $index; ?>;
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