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
    #goods-list{
        padding-top: 30px;
    }
    #goods-list li{
        list-style:none;
        display: inline-block;
        margin-right: 20px;
    }
</style>
<div class="tab-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front" >基本信息</span>
            <span class="tab-back" >文章内容</span>
            <span class="tab-back" >推荐物品</span>
        </p>
    </div>
    <div id="tabbody-div">
        <form enctype="multipart/form-data" action="/jiimadeeee/index.php/Admin/Article/add.html" method="post">
            <!--通用信息-->
            <table width="90%" class="general-table" align="center">
                <tr>
                    <td class="label">文章标题：</td>
                    <td><input type="text" name="article_name" value=""size="30" />
                        <span class="require-field">*</span></td>
                </tr>

                <tr>
                    <td class="label">是否显示：</td>
                    <td>
                        <input type="radio" name="is_index" value="1" checked="checked"/> 是
                        <input type="radio" name="is_index" value="0"/> 否
                    </td>
                </tr>
                <tr>
                    <td class="label">所属分类：</td>
                    <td>
                        <select name="cate_id">
                            <option value="">请选择...</option>
                            <?php foreach($catData as $k => $v): ?>
                            <?php echo '<option value="'.$v['id'].'">'.str_repeat('-',4*$v['level']).$v['name'].'</option>'; ?>
                            <?php endforeach; ?>
                        </select>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">推荐排序：</td>
                    <td>
                        <input type="text" name="sort_id" size="5" value="100"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">封面图：</td>
                    <td>
                        <input type="file" name="pic" accept="image/*" ><span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">文章简介：</td>
                    <td>
                        <textarea name="article_brief" id="" cols="40" rows="5"></textarea>
                    </td>
                </tr>
            </table>
            <!--文章详情-->
            <table width="90%" style="display:none" class="general-table" align="center">
                <tr>
                    <td>
                        <ul id="pic_list">
                            <li>
                                <input type="file" name="desc_pic[]" accept="image/*" multiple>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
            <!--推荐商品-->
            <table width="90%" style="display:none" class="general-table" align="center">
                <tr>
                    <td>
                        <input type="text" placeholder="根据商品名称搜索">
                        <input type="button" value="搜索" onclick="searchGoods(this)">
                    </td>
                </tr>
                <tr>
                   <td id="goods-list"></td>
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

    function searchGoods(a) {
        let key = $(a).prev().val();
        if(key != ''){
            $.ajax({
                type: "GET",
                url: "<?php echo U('ajaxGetgoods','',FALSE); ?>/keyword/" + key,
                dataType: "json",
                success: function (data) {
                    if(data.length == 0){
                        alert('搜索无此商品！');
                    }else{
                        var li = '<li>';
                        li += '<a  onclick="delGoods(this)" href="#">[-]</a>';
                        li += '<select name="goods[]"><option value="" >请选择搜索商品</option>';
                        $(data).each(function (k,v) {
                            li += '<option value="'+v.id+'" >'+v.goods_name+'</option>'
                        });
                        li += '</select></li>';
                        $('#goods-list').append(li);
                    }
                }
            });
        }
    }
    function delGoods(a) {
        if(confirm('确定要删除嘛？')){
            let li = $(a).parent();
            li.remove();
        }
    }
</script>



<div id="footer">
版权所有 &copy; 2018 宁波几和网络科技有限公司，并保留所有权利。</div>
</body>
</html>