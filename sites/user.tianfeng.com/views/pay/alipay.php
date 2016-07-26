<?php 
echo $this->load->view("layout/header", array(
    'title'=>'订单支付-全屋优品',
    'keywords'=>'全屋优品,家具,家具网上商城',
    'description'=>'全屋优品,家具,家具网上商城！',
    'min_footer'=>1,  //是否简易底部
	'footer_nav'=>0,  //是否显示底部导航
    'page_tag'=>'pay',  //页面标识
    'page_url'=>'/pay/index'  //页面地址
    'page_title'=>'订单支付',  //页面标题
), true);
?>
<link rel="stylesheet" type="text/css" href="/css/pay/index.css">
<div class="pay_box c w">
     <p>付款金额</p>
    <p class="c1">¥<?php echo $order['surplus_amount'];?></p>
</div>

<div class="payment w">
    <div class="pay_type bb clearfix ">
        <div class="text">支付方式</div>
        <div class="payment_img">
            <p class="fl"><img src="/img/cart/pay02.png"></p>
        </div>
    </div>
    <div class="payment_w">
        <?php echo $html;?>
    </div>
</div>
<?php echo $this->load->view("layout/footer", '', true); ?>