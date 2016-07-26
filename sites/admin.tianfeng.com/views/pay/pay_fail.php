<?php 
echo $this->load->view("layout/header", array(
    'title'=>'订单支付失败-全屋优品',
    'keywords'=>'全屋优品,家具,家具网上商城',
    'description'=>'全屋优品,家具,家具网上商城！',
    'min_footer'=>1,  //是否简易底部
	'footer_nav'=>0,  //是否显示底部导航
    'page_tag'=>'pay',  //页面标识
    'page_url'=>'/pay/pay_fail' ,//页面地址
    'page_title'=>'支付失败'//页面标题
), true);
?>
<link rel="stylesheet" href="/css/pay/index.css?<?=$static_version;?>">

<div class="page_fail">
    <div class="failure">
        <div class="failure_t">
            <p class="failure_img"><img src="/img/common/fail.png" width="45" height="45"></p>
            <p>付款失败</p>
            <p>请在<span>3天</span>内完成付款,否则订单会 被系统取消</p>
            <div class="clearfix btns_box">
                <a href="/user/order_list" class="back_order_list btns">查看订单</a>
                <a href="/pay?oid=<?php echo I('get.order_id/i');?>" class="pay_again btns">重新付款</a>
            </div>
        </div>
    </div>
</div>
<?php echo $this->load->view("layout/footer", '', true); ?>
