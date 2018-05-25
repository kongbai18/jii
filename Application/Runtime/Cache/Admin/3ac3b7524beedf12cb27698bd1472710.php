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
    #main-box{
        background: white;
        overflow: hidden;
    }
    #main-box img{
        float: left;
        box-sizing: border-box;
        background: #f3f3f3;
        width: 340px;
        height: 340px;
        padding: 20px;
        margin: 20px;
    }
    #mou-tal{
        float: left;
        margin: 40px 100px;
    }
    .button-div{
        margin-top: 30px;
    }
</style>
<div id="main-box">
    <img id="fur-img" src="<?php echo $furData['img_src']; ?>" alt="">
    <?php $attr = json_decode($furData['attribute'],true); if(empty($attr)){ $gatherattr = '0'; } foreach ($attr as $k => $v){ foreach($v as $k1 => $v1){ $attr[$k] = $v1; } } ?>
    <div id="mou-tal">
        <form method="post" action="/jiimadeeee/index.php/Admin/Quote/addModule/id/1/quoteId/20180522154224089586.html"enctype="multipart/form-data" >
            <table cellspacing="1" cellpadding="3" width="100%">
                <?php $index = 1; foreach($attr as $k => $v): ?>
                <tr>
                    <td class="label"><?php echo $k; ?>：</td>
                    <td>
                        <?php foreach($v as $k1 => $v1): ?>
                        <input type="radio" class="radio" name="attr<?php echo $index; ?>" value="<?php echo $k1; ?>"><?php echo $v1; ?>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <?php $index++; endforeach; ?>
            </table>

            <table id="mater-par" cellspacing="1" cellpadding="3" width="100%">
            </table>
            <div class="button-div">
                <input type="submit" value=" 确定 " class="button"/>
                <input type="reset" value=" 重置 " class="button" />
            </div>
        </form>
    </div>

</div>
<script>
    var num = <?php echo count($attr); ?>;

    $("input:radio").change(function(){
        var att = true;
        var gatattr = '';
        for(var i=1;i<=num;i++){
           var attr = $('input[name="attr'+i+'"]:checked').val();
            console.log(attr);
           if(attr == undefined){
              att = false;
           }else{
               gatattr = gatattr+attr+',';
           }
        }
        if(att){
            var furId = <?php echo $furId; ?>;
            $.ajax({
                type : "GET",
                url : "<?php echo U('ajaxGetModel','',FALSE); ?>",
                data : {attr:gatattr,furId:furId},
                dataType : "json",
                success : function(data){
                    if(data.length != 0){
                        var div = '<input type="hidden" name="fur-quo" value="'+data[0].id+'">';
                        for(var i in data[0].material){
                            for(var p in data[0].material[i]){
                                div += '<tr><td class="label">'+p+'：</td><td>';
                                $(data[0].material[i][p]).each(function (k,v) {
                                    div += '<input type="radio" name="'+i+'" value="'+v.id+'">'+v.goods_name;
                                });
                                div += '</td></tr>';
                            }
                        }
                        var arr1 = $.parseJSON( data[0].parameter );
                        div += '<tr><td class="label">根据图示输入参数：</td><td>';
                        $.each(arr1, function(i,val){
                            div += '<input type="text" name="'+val+'" placeholder="'+val+'" style="width: 60px;margin-right: 5px;">';
                        });
                        div += '</td></tr>';
                        div += '<tr><td class="label">柜体开放面积：</td><td ><input type="text" name="open" placeholder="㎡" maxlength="60" style="width: 80px;" /></td></tr>';
                        div += '<tr><td class="label">家具所在位置：</td><td ><input type="text" name="space" placeholder="例如:主卧,次卧,客厅等" /></td></tr>';
                        div += '<tr><td class="label">折扣：</td><td ><input type="text" name="agio" value="1" maxlength="60" style="width: 80px;" /></td></tr>';

                        console.log(div);
                        $("#mater-par").html(div);
                    }else{
                        $("#mater-par").html('');
                        alert('暂不支持该配置！');
                    }
                }
            });
        }else{
            $("#mater-par").html();
        }
    });
</script>
<script>
    var gatherattr = <?php echo $gatherattr; ?>;
    if(gatherattr === 0){
        var furId = <?php echo $furId; ?>;
        $.ajax({
            type : "GET",
            url : "<?php echo U('ajaxGetModel','',FALSE); ?>",
            data : {attr:'0',furId:furId},
            dataType : "json",
            success : function(data){
                if(data.length != 0){
                    var div = '<input type="hidden" name="fur-quo" value="'+data[0].id+'">';
                    for(var i in data[0].material){
                        for(var p in data[0].material[i]){
                            div += '<tr><td class="label">'+p+'：</td><td>';
                            $(data[0].material[i][p]).each(function (k,v) {
                                div += '<input type="radio" name="'+i+'" value="'+v.id+'">'+v.goods_name;
                            });
                            div += '</td></tr>';
                        }
                    }
                    var arr1 = $.parseJSON( data[0].parameter );
                    div += '<tr><td class="label">根据图示输入参数：</td><td>';
                    $.each(arr1, function(i,val){
                        div += '<input type="text" name="'+val+'" placeholder="'+val+'" style="width: 60px;margin-right: 5px;">';
                    });
                    div += '</td></tr>';
                    div += '<tr><td class="label">柜体开放面积：</td><td ><input type="text" name="open" placeholder="㎡" maxlength="60" style="width: 80px;" /></td></tr>';
                    div += '<tr><td class="label">家具所在位置：</td><td ><input type="text" name="space" placeholder="例如:主卧,次卧,客厅等" /></td></tr>';
                    div += '<tr><td class="label">折扣：</td><td ><input type="text" name="agio" value="1" maxlength="60" style="width: 80px;" /></td></tr>';
                    $("#mater-par").html(div);
                }else{
                    $("#mater-par").html('');
                    alert('暂不支持该配置！');
                }
            }
        });
    }
</script>

<div id="footer">
版权所有 &copy; 2018 宁波几和网络科技有限公司，并保留所有权利。</div>
</body>
</html>