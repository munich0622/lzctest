<?php 
echo $this->load->view("layout/header", array(
    'title'=>'订单支付-全屋优品',
    'keywords'=>'全屋优品,家具,家具网上商城',
    'description'=>'全屋优品,家具,家具网上商城！',
    'min_footer'=>1,  //是否简易底部
	'footer_nav'=>0,  //是否显示底部导航
    'page_tag'=>'pay',  //页面标识
    'page_url'=>'/pay/index',  //页面地址
    'page_title'=>'订单支付',  //页面标题
), true);
?>
<link rel="stylesheet" type="text/css" href="/css/pay/index.css">
<div class="pay_box c w">
    <p style="font-size:0.5rem">订单号:<?php echo $order['order_sn'];?></p>
    <p class="c1">付款金额:¥<?php echo $order['surplus_amount'];?></p>
</div>
<div class="payment w">
    <div class="pay_type bb clearfix ">
        <div class="text">支付方式</div>
        <?php if($is_weixin):?>
        <div class="payment_img">
            <p class=""><img src="/img/cart/pay01.png"></p>
        </div>
        
        <?php else:?>
        <div class="payment_img">
            <p class="fl"><img src="/img/cart/pay02.png"></p>
            
        </div>
        <?php endif;?>
    </div>
    <div class="payment_w">
        <?php if(!$is_weixin):?>
        <a href="javascript:void(0);" id="alipay_pay"><button>去支付</button></a>
        <div class="_submit_btn" style="display:none;">
        </div>
        <?php else:?>
        <a href="/pay/wxpay/?oid=<?php echo $order['id'];?>"><button>去支付</button></a>
        <?php endif;?>
    </div>
</div>
<?php echo $this->load->view("layout/footer", '', true); ?>
<script type="text/javascript">

$(document).ready(function($) {
    $("._submit_btn").empty();
    
    $('#alipay_pay').click(function(){
    	pay.select(function(result){
            $("._submit_btn").html(result.d);
        });
    });
});
function Pay() {
    this.select = function(callback) {
    	var order_id = "<?php echo $order['id'];?>";
        $.post("/pay/alipay",{order_id:order_id},function(result){
            if(result.s == 1) {
                callback?callback(result):"";
            } else {
                alert(result.m);
            }
        },"json");
    };
}
var pay = new Pay();
</script>