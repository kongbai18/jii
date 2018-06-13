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


<div class="list-div" id="listDiv">
 <form method="post" action="/jiimadeeee/index.php/Admin/Furniture/furniture_quote/id/3.html" name="listForm" enctype="multipart/form-data">
       <table id="num-tal">
            <tr>
                <?php foreach($attr as $k => $v): ?>
                <th><?php echo $k; ?></th>
                <?php endforeach; ?>
                <?php if(empty($attr)){ ?>
                <th>无属性</th>
                <?php } ?>
                <th>对应计算模型</th>
                <th>模型图</th>
                <th>操作</th>
            </tr>

            <tr class="tron">
                <?php if(empty($attr)){ ?>
                <input type="hidden" name="fur_attr_id[]" value="0">
                <td align="center">默认</td>
                <?php } ?>

                <?php foreach($attr as $v): ?>
                   <td align="center">
                       <select name="fur_attr_id[]">
                           <option value="">请选择...</option>
                           <?php foreach($v as $k1 => $v1 ): ?>
                           <option value="<?php echo $k1; ?>"><?php echo $v1; ?></option>
                           <?php endforeach; ?>
                       </select>
                   </td>
                <?php endforeach; ?>
                <input type="hidden" name="id[]" class="hidden-id" value="">
                <td align="center">
                    <select name="model_id[]" >
                        <option value="0">请选择...</option>
                        <?php foreach($moData as $v): ?>
                        <option value="<?php echo $v['id']; ?>"><?php echo $v['model_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td align="center"><input type="file" accept="image/*" name="goods_img[]" ></td>
                <td align="center"><input type="button" value="添加一行" onclick="addAttr(this)"></td>
            </tr>


           <?php foreach($furQuoData as $v): $furAttrId = explode(',',$v['fur_attr_id']);?>
              <tr class="tron">
                  <?php if(empty($attr)){ ?>
                  <input type="hidden" name="goods_attr_id[]" value="0">
                  <td align="center">默认</td>
                  <?php } ?>

                  <?php $num = 0; foreach($attr as $v1): ?>
                  <td align="center">
                      <select name="fur_attr_id[]">
                          <option value="">请选择...</option>
                          <?php foreach($v1 as $k2 => $v2 ): if(strval($k2) === $furAttrId[$num]){ $select = 'selected="selected"'; }else{ $select = ''; } ?>
                          <option <?php echo $select; ?> value="<?php echo $k2; ?>"><?php echo $v2; ?></option>
                          <?php endforeach; ?>
                      </select>
                  </td>
                  <?php $num = $num +1; endforeach; ?>
                  <input type="hidden" name="id[]" class="hidden-id" value="<?php echo $v['id']; ?>">
                  <td align="center">
                      <select name="model_id[]" >
                          <option value="0">请选择...</option>
                          <?php foreach($moData as $v3): if($v3['id'] === $v['model_id']){ $select = 'selected="selected"'; }else{ $select = ''; } ?>
                          <option <?php echo $select; ?> value="<?php echo $v3['id']; ?>"><?php echo $v3['model_name']; ?></option>
                          <?php endforeach; ?>
                      </select>
                  </td>
                  <td align="center"><input type="file" accept="image/*" name="goods_img[]" ><image src="<?php echo $v['img_src']; ?>" style="width: 50px;height: 50px;"></td>
                  <td align="center"><input type="button" value="删除此行" onclick="addAttr(this)"></td>
              </tr>
           <?php endforeach; ?>
      </table>

      <div class="button-div">
          <input type="submit" value=" 确定 " class="button"/>
      </div>
 </form>
</div>
 <!--引入高亮显示-->
<script type="text/javascript" src="/jiimadeeee/Public/Admin/Js/tron.js"></script>

<script>
    function addAttr(btn) {
        let tr = $(btn).parent().parent()
        if($(btn).val() == "添加一行") {
            let newTr = tr.clone();
            newTr.find(":button").val("删除此行");
            newTr.find(":text").val("");
            newTr.find(":file").val("");
            $('#num-tal').append(newTr);
        }else{
            if(confirm('确定要删除此行嘛？')){
                var id = tr.find(".hidden-id").val();
                console.log(id);
                if(id){
                    $.ajax({
                        type: "GET",
                        url: "<?php echo U('ajaxDelNum','',FALSE); ?>/id/" + id,
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                             if(data == '1'){
                                 tr.remove();
                             }else {
                                 alert('删除失败!');
                             }
                        }
                    });
                }else {
                    tr.remove();
                }
            }
        }
    }
</script>

<div id="footer">
版权所有 &copy; 2018 宁波几和网络科技有限公司，并保留所有权利。</div>
</body>
</html>