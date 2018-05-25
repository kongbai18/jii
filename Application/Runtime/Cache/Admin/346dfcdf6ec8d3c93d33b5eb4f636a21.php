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
    #pic_list li{float:left;list-style-type:none;}
    #lun_pic_list li{float:left;list-style-type:none;}
    #attr_list{
        margin-top:20px ;
    }
    .attr{
        margin: 5px 0;
    }
    .attr li{
        list-style:none;
        display: inline-block;
        margin-right: 20px;
    }
    #cat_list li{
        list-style:none;
        display: inline-block;
        margin-right: 20px;
    }
</style>
<div class="tab-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front" >通用信息</span>
            <span class="tab-back" >商品属性</span>
            <span class="tab-back" >商品描述</span>
            <span class="tab-back" >商品轮播图</span>
        </p>
    </div>
    <div id="tabbody-div">
        <form enctype="multipart/form-data" action="/jiimadeeee/index.php/Admin/Goods/add.html" method="post">
            <!--通用信息-->
            <table width="90%" class="general-table" align="center">
                <tr>
                    <td class="label">商品名称：</td>
                    <td><input type="text" name="goods_name" value=""size="30" />
                        <span class="require-field">*</span></td>
                </tr>
                <tr>
                    <td class="label">商品分类：</td>
                    <td>
                        <select name="cat_id">
                            <option value="">请选择...</option>
                            <?php foreach($catData as $k => $v): ?>
                            <?php echo '<option value="'.$v['id'].'">'.str_repeat('-',4*$v['level']).$v['name'].'</option>'; ?>
                            <?php endforeach; ?>
                        </select>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">扩展分类：</td>
                    <td id="cat_list">
                        <li>
                            <a href="javascript:void(0)" onclick="addCat(this)">[+]</a>
                            <select name="ext_cat_id[]">
                                <option value="">请选择...</option>
                                <?php foreach($catData as $k => $v): ?>
                                <?php echo '<option value="'.$v['id'].'">'.str_repeat('-',4*$v['level']).$v['name'].'</option>'; ?>
                                <?php endforeach; ?>
                            </select>
                        </li>
                    </td>
                </tr>
                <tr>
                    <td class="label">是否上架：</td>
                    <td>
                        <input type="radio" name="is_on_sale" value="1" checked="checked"/> 是
                        <input type="radio" name="is_on_sale" value="0"/> 否
                    </td>
                </tr>
                <tr>
                    <td class="label">是否报价系统中商品：</td>
                    <td>
                        <input type="radio" name="is_quote" value="1" /> 是
                        <input type="radio" name="is_quote" value="0" checked="checked"/> 否
                    </td>
                </tr>
                <tr>
                    <td class="label">加入推荐：</td>
                    <td>
                        <input type="checkbox" name="is_new" value="1" /> 新品
                        <input type="checkbox" name="is_hot" value="1" /> 热销
                    </td>
                </tr>
                <tr>
                    <td class="label">商品标签：</td>
                    <td>
                        <input type="text" name="tag"  /> (多种标签属性用逗号‘,’隔开，建议不超过两个)
                    </td>
                </tr>
                <tr>
                    <td class="label">推荐排序：</td>
                    <td>
                        <input type="text" name="order_id" size="5" value="100"/>
                    </td>
                </tr>
            </table>
            <!--商品属性-->
            <table width="90%" style="display:none" class="general-table" align="center">
                <tr>
                    <td class="label">商品类型：</td>
                    <td>
                        <?php buildSelect('type','type_id','id','type_name') ?>
                        <div id='attr_list'></div>
                    </td>
                </tr>
            </table>
            <!--商品描述-->
            <table width="90%" style="display:none" class="general-table" align="center">
                <tr>
                    <td>
                        <ul id="pic_list">
                            <li>
                                <input type="file" name="pic[]" accept="image/*" multiple>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <!--商品轮播图-->
            <table width="90%" style="display:none" class="general-table" align="center">
                <tr>
                    <td>
                        <ul id="lun_pic_list">
                            <li>
                                <input type="file" name="lun_pic[]" accept="image/*" multiple>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <div class="button-div">
                <input type="submit" value=" 确定 " class="button"/>
                <input type="reset" value=" 重置 " class="button" />
            </div>
        </form>
    </div>
</div>
<script>
    $('#tabbar-div p span').click(function(){
//获取点击的哪个
        var i = $(this).index();
//隐藏所有table
        $('.general-table').hide();
//显示点击的
        $('.general-table').eq(i).show();
//改变所有
        $('.tab-front').removeClass('tab-front').addClass('tab-back');
        $(this).removeClass('tab-back').addClass('tab-front');
    });
</script>
<script>
    function addCat(a) {
        let li = $(a).parent();
        if($(a).text() == '[+]'){
            var newLi = li.clone();
            newLi.find('a').text('[-]');
            newLi.find('select').val('');
            $('#cat_list').append(newLi);
        }else{
            li.remove();
        }
    }
</script>
<!--根据商品类型获取商品属性-->
<script>
    $("select[name=type_id]").change(function(){
        var typeId = $(this).val();
        //如果选择了类型就执行AJAX
        if(typeId > 0){
            $.ajax({
                type : "GET",
                url : "<?php echo U('ajaxGetAttr','',FALSE); ?>/type_id/"+typeId,
                dataType : "json",
                success : function(data){
                    /*******把服务器返还的属性循环拼成LI字符串********/
                    let li = '';
                    console.log(data);
                    $(data).each(function(k,v){
                        if(v.attr_type == '2'){
                            li += '<div class="attr"><li>';
                            li += '<a  onclick="addNewAttr(this)" href="javascript:void(0)">[+]</a>';
                            li += v.attr_name+':';
                            li += '<select name="attr_value['+v.id+'][]"><option value="" >请选择...</option>';
                            //把可选值转换为数组
                            var _attr = v.attr_option_values.split(',');
                            //循环每个值制作option
                            for(var i=0;i<_attr.length;i++){
                                li += '<option value="'+_attr[i]+'">'+_attr[i]+'</option>';
                            }
                            li += '</select>';
                            li += '</li></div>';
                        }
                   });
                    $(data).each(function(k,v){
                        if(v.attr_type == '3'){
                            console.log(v.attr_option_values);
                            $.ajax({
                                type : "GET",
                                url : "<?php echo U('ajaxGetcolor','',FALSE); ?>",
                                data : {colorId:v.attr_option_values},
                                async: false,
                                dataType : "json",
                                success : function(dat){
                                    li += '<div class="attr"><li>';
                                    li += '<a  onclick="addNewAttr(this)" href="#">[+]</a>';
                                    li += v.attr_name+':';
                                    li += '<select name="attr_value['+v.id+'][]"><option value="" >请选择...</option>';
                                    //把可选值转换为数组
                                    var _attr = v.attr_option_values.split(',');
                                    for(var i=0;i<dat.length;i++){
                                        li += '<option value="'+_attr[i]+'">'+dat[i]+'</option>';
                                    }
                                    li += '</select>';
                                    li += '</li></div>';
                                }
                            });
                        }
                    });
                    $(data).each(function(k,v){
                        if(v.attr_type == '1'){
                            li += '<div class="attr"><li>';
                            li += v.attr_name+':';
                            li += '<input type="text" name="attr_value['+v.id+'][]" />';
                            li += '</li></div>';
                        }
                    });
                    //把拼好的LI放到页面中
                    $('#attr_list').html(li);
                }
            });
        }else{
            $('#attr_list').html('');
        }
    });
    //点击属性的【+】号
    function addNewAttr(a){
        var li = $(a).parent();
        if($(a).text() == '[+]'){
            var newLi = li.clone();
            newLi.find('a').text('[-]')
            li.parent().append(newLi);
        }else{
            li.remove();
        }
    }
</script>


<div id="footer">
版权所有 &copy; 2018 宁波几和网络科技有限公司，并保留所有权利。</div>
</body>
</html>