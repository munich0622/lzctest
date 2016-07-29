<?php
echo $this->load->view("layout/header", array(
    'title'=>'订单支付成功-全屋优品',
    'keywords'=>'全屋优品,家具,家具网上商城',
    'description'=>'全屋优品,家具,家具网上商城！',
    'min_footer'=>1,  //是否简易底部
    'footer_nav'=>0,  //是否显示底部导航
    'page_tag'=>'pay',  //页面标识
    'page_url'=>'/pay/pay_fail' ,//页面地址
    'page_title'=>'支付成功'//页面标题
), true);
?>
<link rel="stylesheet" href="/css/pay/index.css?<?=$static_version;?>">

<div class="page_success">
    <div class="failure">
        <div class="failure_t">
            <p class="failure_img"><img src="/img/common/yes02.png" width="45" height="45"></p>
            <p>支付成功，我们将会尽快为您发货！ </p>
            <p>订单号：<em><?php echo I('get.order_sn/i');?></em>您的订单正在处理中...</>
            <div class="clearfix btns_box succ_box">
                <a href="/user/order_list" class="back_order_list btns">查看订单</a>
            </div>
        </div>
    </div>
</div>
<?php echo $this->load->view("layout/footer", '', true); ?>
