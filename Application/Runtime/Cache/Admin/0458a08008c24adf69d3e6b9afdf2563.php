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
    .attr-list{
        margin-top:5px;
    }
    .attr-list li{
        list-style:none;
        display: inline-block;
        margin-right: 20px;
    }
</style>
<div class="main-div">
    <form method="post" action="/jiimadeeee/index.php/Admin/Furniture/add.html"enctype="multipart/form-data" >
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">家具类型名称：</td>
                <td>
                    <input type="text" name="fur_name" maxlength="60" />
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">家具类型分类：</td>
                <td >
                    <select name="cate_id">
                        <option value="1">柜体</option>
                        <option value="2">门板</option>
                        <option value="3">饰面</option>
                    </select>
                    <span class="require-field">*</span>
                </td>
            </tr>
            <tr>
                <td class="label">封面图：</td>
                <td >
                    <input type="file" name="img_src" accept="image/*" >
                </td>
            </tr>
            <tr>
                <td class="label">扩展属性：</td>
                <td id="attr">
                    <div>
                        <input type="radio" name="attr-cate" value="1" checked>单选
                        <input type="radio" name="attr-cate" value="2">加减
                        <input type="button" value="扩展一个属性" onclick="addAttr()">
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label">推荐排序：</td>
                <td>
                    <input type="text" name="sort_id" size="5" value="100"/>
                </td>
            </tr>
            <tr>
                <td class="label">是否显示：</td>
                <td>
                    <input type="radio" name="is_index" value="1" checked="checked"/> 是
                    <input type="radio" name="is_index" value="0"/> 否
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
    index = 0;
    function addAttr() {
        let attr_cate_ext = $("input[name='attr-cate']:checked").val();
        index = index + 1;
        var div = '<div id="attr-list'+index+'" class="attr-list">';
        div += '<a href="javascript:void(0)" onclick="delAttr(this)">[删除此属性]</a>';
        if(attr_cate_ext == '1'){
            div += ' 单选 ';
        }else if(attr_cate_ext == '2'){
            div += ' 加减 ';
        }
        div += '<input type="hidden" name="ext_attr_cat['+index+']" value="'+attr_cate_ext+'">';
        div += '<input type="text"  placeholder="属性名称" name="attr_name['+index+']" style="width: 80px;margin-right: 5px;">';
        div += '<li> <a href="javascript:void(0)" sort="'+index+'"  onclick="addAttrVal(this)">[+]</a>';
        div += '<input type="text" name="attr_val['+index+'][]" style="width: 80px;"></li>';
        div += '</div>';
        $('#attr').append(div);
    }
    function delAttr(a) {
        let div = $(a).parent();
        div.remove();
    }
    function addAttrVal(a) {
        let li = $(a).parent();
        var sort = $(a).attr('sort');
        var qqq = '#attr-list'+sort;
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