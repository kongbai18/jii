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
    #ch-model{
        padding: 30px 50px;
        background: white;
    }
    .model{
        width: 300px;
        height: 350px;
        background: #f3f3f3;
        margin: 20px 50px;
        display: inline-block;
    }
    .model-img{
        box-sizing: border-box;
        padding: 10px;
    }
    .model-img img{
        width: 280px;
        height: 280px;
    }
    .model-name{
        box-sizing: border-box;
        height: 50px;
        width: 300px;
        line-height: 50px;
        font-size: 24px;
        text-align: center;
    }
</style>
<div id="ch-model">
    <?php foreach($data as $k => $v): ?>
        <div class="model">
          <a href="<?php echo U('addModule?id='.$v['id'].'&quoteId='.$quote) ?>">
            <div class="model-img">
                <img src="<?php echo $v['img_src'] ?>" alt="<?php echo $v['model_name'] ?>">
            </div>
            <div class="model-name"><?php echo $v['model_name'] ?></div>
          </a>
        </div>
    <?php endforeach; ?>
</div>

<div id="footer">
版权所有 &copy; 2018 宁波几和网络科技有限公司，并保留所有权利。</div>
</body>
</html>