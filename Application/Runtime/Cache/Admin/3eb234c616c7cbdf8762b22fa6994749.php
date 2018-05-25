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
    #title{
        font-size: 24px;
        text-align: center;
        padding: 20px 0;
    }
    #quo-con{
        font-size: 14px;
        padding-bottom: 20px;
        overflow: hidden;
    }
    #quo{
        float: left;
        margin-left: 100px;
    }
    #con{
        float: right;
        margin-right: 100px;
    }
    .model-price{
        background: white;
        width: 100%;
        margin-top: 20px;
    }
    .model-price tr td {
        box-sizing: border-box;
        border:1px solid #66cccc;
        width: 25%;
        height: 40px;
        line-height: 40px;
        font-size: 18px;
        text-align: center;
    }
    .detail-price{
        background: white;
        width: 100%;
    }
    .detail-price tr td {
        box-sizing: border-box;
        border:1px solid #66cccc;
        height: 20px;
        line-height: 20px;
        font-size: 18px;
        font-weight: normal;
        text-align: center;
    }
    .module-top{
        background: white;
        width: 100%;
        margin-top: 20px;
    }
    .module-top tr td {
        box-sizing: border-box;
        border:1px solid #66cccc;
        height: 20px;
        line-height: 20px;
        font-size: 18px;
        font-weight: normal;
        text-align: center;
    }
    .module-info{
        background: yellow;
    }
    .module-form{
        background: white;
        width: 100%;
    }
    .module-form tr td {
        box-sizing: border-box;
        border:1px solid #66cccc;
        height: 20px;
        line-height: 20px;
        width: 12.5%;
        font-size: 18px;
        text-align: center;
    }
</style>
<div class="tab-div">
    <div id="title"><?php echo $data['address'] ?>报价单</div>
    <div id="quo-con"><span id="quo">报价单号：<?php echo $data['id'] ?></span><span id="con">联系方式：<?php echo $data['telephone'] ?></span></div>
    <div>
        <div id="tabbar-div">
            <p>
                <span class="tab-front" >柜体报价</span>
                <span class="tab-back" >门控报价</span>
                <span class="tab-back" >饰面报价</span>
            </p>
        </div>
    </div>
    <div>
        <div width="90%" class="general-table" >
            <table class="model-price" cellspacing="0" cellpadding="0">
                <tr>
                    <td>总价：</td>
                    <td><?php echo $moduleData['cabinet']['fee']; ?></td>
                    <td>折后价：</td>
                    <td><?php echo $moduleData['cabinet']['agioFee']; ?></td>
                </tr>
            </table>
            <table class="detail-price" cellspacing="0" cellpadding="0">
            <?php $caFeeNum = count($moduleData['cabinet']['detailFee']);?>
                <tr></tr>
                <?php foreach($moduleData['cabinet']['detailFee'] as $k => $v): ?>
                   <td style="width:<?php echo 50/$caFeeNum.'%'; ?>" ><?php echo $k; ?></td>
                   <td style="width:<?php echo 50/$caFeeNum.'%'; ?>"><?php echo $v['fee']; ?></td>
                <?php endforeach; ?>
                </tr>
                <tr></tr>
                <?php foreach($moduleData['cabinet']['detailFee'] as $k => $v): ?>
                <td style="width:<?php echo 50/$num.'%'; ?>" >折后</td>
                <td style="width:<?php echo 50/$num.'%'; ?>"><?php echo $v['agioFee']; ?></td>
                <?php endforeach; ?>
                </tr>
            </table>
            <?php foreach($moduleData['cabinet']['module'] as $k => $v): ?>
            <table class="module-top" cellspacing="0" cellpadding="0">
                <?php $caParNum = count($v['parameter']); $caPaWidth = 100/(4+$caParNum).'%'; ?>
                <tr class="module-info">
                    <td rowspan="2" style="width:<?php echo $caPaWidth; ?>">产品信息</td>
                    <td style="width:<?php echo $caPaWidth; ?>">序号</td>
                    <td style="width:<?php echo $caPaWidth; ?>">产品位置</td>
                    <td style="width:<?php echo $caPaWidth; ?>">家具类型</td>
                    <?php foreach($v['parameter'] as $k1 => $v1): ?>
                    <td style="width:<?php echo $caPaWidth; ?>">参数:<?php echo $k1; ?></td>
                    <?php endforeach; ?>
                </tr>
                <tr class="module-info">
                    <td style="width:<?php echo $caPaWidth; ?>">G<?php echo $v['sort_id']; ?></td>
                    <td style="width:<?php echo $caPaWidth; ?>"><?php echo $v['space']; ?></td>
                    <td style="width:<?php echo $caPaWidth; ?>"><?php echo $v['fur_name']; ?></td>
                    <?php foreach($v['parameter'] as $k1 => $v1): ?>
                    <td style="width:<?php echo $caPaWidth; ?>"><?php echo $v1; ?></td>
                    <?php endforeach; ?>
                </tr>
            </table>
            <table class="module-form" cellspacing="0" cellpadding="0">
                <tr>
                    <td>项目</td>
                    <td>单位</td>
                    <td>数量</td>
                    <td>单价(元)</td>
                    <td>折扣</td>
                    <td>金额(元)</td>
                    <td style="width: 25%;">备注</td>
                </tr>
                <?php $tolFee = 0; foreach($v['formula'] as $k2 => $v2): $tolFee = $tolFee + $v2['agioFee'];?>
                <tr>
                    <td><?php echo $v2['name']; ?></td>
                    <td><?php echo $v2['unit']; ?></td>
                    <td><?php echo $v2['num']; ?></td>
                    <td><?php echo $v2['price']; ?></td>
                    <td><?php echo $v2['agio']; ?></td>
                    <td><?php echo $v2['agioFee']; ?></td>
                    <td><?php echo $v2['remarkes']; ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td>合计：</td>
                    <td colspan="6"><?php echo $tolFee; ?></td>
                </tr>
            </table>
            <?php endforeach; ?>
        </div>

        <div width="90%" style="display:none" class="general-table">
            <table class="model-price" cellspacing="0" cellpadding="0">
                <tr>
                    <td>总价：</td>
                    <td><?php echo $moduleData['door']['fee']; ?></td>
                    <td>折后价：</td>
                    <td><?php echo $moduleData['door']['agioFee']; ?></td>
                </tr>
            </table>
            <table class="detail-price" cellspacing="0" cellpadding="0">
                <?php $doFeeNum = count($moduleData['door']['detailFee']);?>
                <tr></tr>
                <?php foreach($moduleData['door']['detailFee'] as $k => $v): ?>
                <td style="width:<?php echo 50/$doFeeNum.'%'; ?>" ><?php echo $k; ?></td>
                <td style="width:<?php echo 50/$doFeeNum.'%'; ?>"><?php echo $v['fee']; ?></td>
                <?php endforeach; ?>
                </tr>
                <tr></tr>
                <?php foreach($moduleData['door']['detailFee'] as $k => $v): ?>
                <td style="width:<?php echo 50/$doFeeNum.'%'; ?>" >折后</td>
                <td style="width:<?php echo 50/$doFeeNum.'%'; ?>"><?php echo $v['agioFee']; ?></td>
                <?php endforeach; ?>
                </tr>
            </table>
            <?php foreach($moduleData['door']['module'] as $k => $v): ?>
            <table class="module-top" cellspacing="0" cellpadding="0">
                <?php $doParNum = count($v['parameter']); $caPaWidth = 100/(4+$caParNum).'%'; ?>
                <tr class="module-info">
                    <td rowspan="2" style="width:<?php echo $caPaWidth; ?>">产品信息</td>
                    <td style="width:<?php echo $doParNum; ?>">序号</td>
                    <td style="width:<?php echo $doParNum; ?>">产品位置</td>
                    <td style="width:<?php echo $doParNum; ?>">家具类型</td>
                    <?php foreach($v['parameter'] as $k1 => $v1): ?>
                    <td style="width:<?php echo $doParNum; ?>">参数:<?php echo $k1; ?></td>
                    <?php endforeach; ?>
                </tr>
                <tr class="module-info">
                    <td style="width:<?php echo $doParNum; ?>">D<?php echo $v['sort_id']; ?></td>
                    <td style="width:<?php echo $doParNum; ?>"><?php echo $v['space']; ?></td>
                    <td style="width:<?php echo $doParNum; ?>"><?php echo $v['fur_name']; ?></td>
                    <?php foreach($v['parameter'] as $k1 => $v1): ?>
                    <td style="width:<?php echo $doParNum; ?>"><?php echo $v1; ?></td>
                    <?php endforeach; ?>
                </tr>
            </table>
            <table class="module-form" cellspacing="0" cellpadding="0">
                <tr>
                    <td>项目</td>
                    <td>单位</td>
                    <td>数量</td>
                    <td>单价(元)</td>
                    <td>折扣</td>
                    <td>金额(元)</td>
                    <td style="width: 25%;">备注</td>
                </tr>
                <?php $tolFee = 0; foreach($v['formula'] as $k2 => $v2): $tolFee = $tolFee + $v2['agioFee'];?>
                <tr>
                    <td><?php echo $v2['name']; ?></td>
                    <td><?php echo $v2['unit']; ?></td>
                    <td><?php echo $v2['num']; ?></td>
                    <td><?php echo $v2['price']; ?></td>
                    <td><?php echo $v2['agio']; ?></td>
                    <td><?php echo $v2['agioFee']; ?></td>
                    <td><?php echo $v2['remarkes']; ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td>合计：</td>
                    <td colspan="6"><?php echo $tolFee; ?></td>
                </tr>
            </table>
            <?php endforeach; ?>
        </div>

        <div width="90%" style="display:none" class="general-table">
            <table class="model-price" cellspacing="0" cellpadding="0">
                <tr>
                    <td>总价：</td>
                    <td><?php echo $moduleData['front']['fee']; ?></td>
                    <td>折后价：</td>
                    <td><?php echo $moduleData['front']['agioFee']; ?></td>
                </tr>
            </table>
            <table class="detail-price" cellspacing="0" cellpadding="0">
                <?php $frFeeNum = count($moduleData['front']['detailFee']);?>
                <tr></tr>
                <?php foreach($moduleData['front']['detailFee'] as $k => $v): ?>
                <td style="width:<?php echo 50/$frFeeNum.'%'; ?>" ><?php echo $k; ?></td>
                <td style="width:<?php echo 50/$frFeeNum.'%'; ?>"><?php echo $v['fee']; ?></td>
                <?php endforeach; ?>
                </tr>
                <tr></tr>
                <?php foreach($moduleData['front']['detailFee'] as $k => $v): ?>
                <td style="width:<?php echo 50/$frFeeNum.'%'; ?>" >折后</td>
                <td style="width:<?php echo 50/$frFeeNum.'%'; ?>"><?php echo $v['agioFee']; ?></td>
                <?php endforeach; ?>
                </tr>
            </table>
            <?php foreach($moduleData['front']['module'] as $k => $v): ?>
            <table class="module-top" cellspacing="0" cellpadding="0">
                <?php $frParNum = count($v['parameter']); $caPaWidth = 100/(4+$caParNum).'%'; ?>
                <tr class="module-info">
                    <td rowspan="2" style="width:<?php echo $caPaWidth; ?>">产品信息</td>
                    <td style="width:<?php echo $frParNum; ?>">序号</td>
                    <td style="width:<?php echo $frParNum; ?>">产品位置</td>
                    <td style="width:<?php echo $frParNum; ?>">家具类型</td>
                    <?php foreach($v['parameter'] as $k1 => $v1): ?>
                    <td style="width:<?php echo $frParNum; ?>">参数:<?php echo $k1; ?></td>
                    <?php endforeach; ?>
                </tr>
                <tr class="module-info">
                    <td style="width:<?php echo $frParNum; ?>">S<?php echo $v['sort_id']; ?></td>
                    <td style="width:<?php echo $frParNum; ?>"><?php echo $v['space']; ?></td>
                    <td style="width:<?php echo $frParNum; ?>"><?php echo $v['fur_name']; ?></td>
                    <?php foreach($v['parameter'] as $k1 => $v1): ?>
                    <td style="width:<?php echo $frParNum; ?>"><?php echo $v1; ?></td>
                    <?php endforeach; ?>
                </tr>
            </table>
            <table class="module-form" cellspacing="0" cellpadding="0">
                <tr>
                    <td>项目</td>
                    <td>单位</td>
                    <td>数量</td>
                    <td>单价(元)</td>
                    <td>折扣</td>
                    <td>金额(元)</td>
                    <td style="width: 25%;">备注</td>
                </tr>
                <?php $tolFee = 0; foreach($v['formula'] as $k2 => $v2): $tolFee = $tolFee + $v2['agioFee'];?>
                <tr>
                    <td><?php echo $v2['name']; ?></td>
                    <td><?php echo $v2['unit']; ?></td>
                    <td><?php echo $v2['num']; ?></td>
                    <td><?php echo $v2['price']; ?></td>
                    <td><?php echo $v2['agio']; ?></td>
                    <td><?php echo $v2['agioFee']; ?></td>
                    <td><?php echo $v2['remarkes']; ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td>合计：</td>
                    <td colspan="6"><?php echo $tolFee; ?></td>
                </tr>
            </table>
            <?php endforeach; ?>
        </div>
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


<div id="footer">
版权所有 &copy; 2018 宁波几和网络科技有限公司，并保留所有权利。</div>
</body>
</html>