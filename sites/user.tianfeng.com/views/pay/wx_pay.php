<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>支付页面</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="format-detection" content="telephone=no,email=no">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta HTTP-EQUIV="Pragma" name="no-cache">
    <link rel="stylesheet" href="<?php   echo base_url('public/css/shop_common.css');?>">
    <link rel="stylesheet" href="<?php   echo base_url('public/css/pay.css');?>">
    <script type="text/javascript" src="<?php echo base_url('public/js/resize.js') ;?>" ></script> 
</head>
<body>
<script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				if(res.err_msg == 'get_brand_wcpay_request:fail'){
					alert(res.err_code+res.err_desc+res.err_msg);
					return false;
					location.href = "/user/pay_result?res=fail&pay_id=<?php echo $pay_id; ?>" ;
				}else if(res.err_msg == 'get_brand_wcpay_request:ok'){
					location.href = "/user/pay_result?res=success&pay_id=<?php echo $pay_id; ?>";
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
<div style="width:16rem;overflow-x: hidden;margin:0 auto;position: relative">
    <header id="header">
        <table>
            <tbody>
            <tr>
                <td class="icon"><a href="javascript:;" class="return"></a></td>
                <td class="c">支付(支付到公司平台由公司平台统一打款到对应上级)</td>
                <td class="icon"><a href="javascript:;" id="JS_category_menu" class="category"></a></td>
            </tr>
            </tbody>
        </table>
    </header>
    <div class="pay_box c w">
         <p>付款金额</p>
        <p class="c1">¥<?php echo $pay_money;?></p>
    </div>
    <div class="payment w">
        <h3 class="bb p20 text">支付方式</h3>
        <div class="payment_w">
            <div class="payment_img clearfix bb">
                <p class="fl"><img src="/public/img/pay01.png"></p>
                <p class="check_box  is_checked fr"><input type="checkbox" class="" name=""></p>
            </div>
            <button type="button" onclick="callpay()">立即支付</button>
        </div>
    </div>
</div>
</body>
</html>