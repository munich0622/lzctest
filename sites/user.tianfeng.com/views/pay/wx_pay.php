<?php 
echo $this->load->view("head", array(), true);
?>
<script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				if(res.err_msg == 'get_brand_wcpay_request:fail'){
					location.href = "/pay/pay_fail?pay_id=<?php echo $pay_id; ?>" ;
				}else if(res.err_msg == 'get_brand_wcpay_request:ok'){
					location.href = "/pay/pay_success?pay_id=<?php echo $pay_id; ?>";
				}
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	</script>
<link rel="stylesheet" type="text/css" href="/pulic/css/pay.css">
<div class="pay_box c w">
     <p>付款金额</p>
    <p class="c1">¥<?php echo $pay_money;?></p>
</div>
<div class="payment w">
    <h3 class="bb p20 text" style="text-align: center;">支付方式</h3>
    <div class="payment_w">
        <div class="clearfix payment_img " style="margin-top: 0.3rem;height:1.25rem;">
            <div class="fl"><img src="/pulic/img/pay01.png" ></div>
            <div  style="float:right;"><img src="/pulic/img/checked_icon_wx.png" ></div>
        </div>
        <button type="button" onclick="callpay()">立即支付</button>
    </div>
</div>