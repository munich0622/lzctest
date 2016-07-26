<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 支付宝即时到账支付
 */
class Payment_alipay extends Payment_Driver {
    
    public function init(){
        require_once("alipay/alipay_submit.class.php");
        $this->_config = unserialize($this->_config);
        $this->_config['gateway']        = "https://mapi.alipay.com/gateway.do?";
        //字符编码格式 目前支持 GBK 或 utf-8
        $this->_config['_input_charset'] = "utf-8";
        //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        $this->_config['transport']      = "https";
        //交易过程中服务器通知的页面 要用 http://格式的完整路径，不允许加?id=123这类自定义参数
		$this->_config['notify_url']     = "http://".$_SERVER['HTTP_HOST']."/api/alipay_async";
        //付完款后跳转的页面 要用 http://格式的完整路径，不允许加?id=123这类自定义参数
        $this->_config['return_url']     = "http://".$_SERVER['HTTP_HOST']."/api/alipay";
        //网站商品的展示地址，不允许加?id=123这类自定义参数
        $this->_config['show_url']       = "http://".$_SERVER['HTTP_HOST'];
        //加密方式 不需修改
        $this->_config['sign_type']      = "MD5";
        $this->_config['paymethod']      = 'directPay';
        $this->_config['defaultbank']    = '';
        //防钓鱼功能开关，'0'表示该功能关闭，'1'表示该功能开启。默认为关闭
        //一旦开启，就无法关闭，根据商家自身网站情况请慎重选择是否开启。
        //申请开通方法：联系我们的客户经理或拨打商户服务电话0571-88158090，帮忙申请开通。
        //开启防钓鱼功能后，服务器、本机电脑必须支持远程XML解析，请配置好该环境。
        //若要使用防钓鱼功能，建议使用POST方式请求数据，且请打开class文件夹中alipay_function.php文件，找到该文件最下方的query_timestamp函数
        $this->_config['antiphishing']   = "0";
        $this->_config['anti_phishing_key'] = '';
        $this->_config['exter_invoke_ip'] = '';
        $this->_config['cacert']    = dirname(__FILE__).'/alipay/cacert.pem';
        if(!isset($this->_config['partner'])) {
            //合作身份者ID
            $this->_config['partner'] = '2088221241608422';
        }
        if(!isset($this->_config['key'])) {
            //安全检验码
            $this->_config['key'] = 'ahhrci5mhlsbyzf0lwcpbas25veewrgo';
        }
        if(!isset($this->_config['seller_email'])) {
            //签约支付宝账号或卖家支付宝帐户
            $this->_config['seller_email'] = 'zhifubao@quanwuyoupin.com';
        }
        if(!isset($this->_config['mainname'])) {
            //收款方名称，如：公司名称、网站名称、收款人姓名等
            $this->_config['mainname'] = '全屋优品';
        }
    }
    

    public function getCode($order) {
        $parameter = array(
                "service"         => "create_direct_pay_by_user",   //接口名称，不需要修改
                "payment_type"    => "1",                           //交易类型，不需要修改
                //获取配置文件(alipay_config.php)中的值
                "partner"         => $this->_config['partner'],
                "seller_email"    => $this->_config['seller_email'],
                "return_url"      => $this->_config['return_url'],
                "notify_url"      => $this->_config['notify_url'],
                "_input_charset"  => $this->_config['_input_charset'],
                "show_url"        => $this->_config['show_url'],
                //从订单数据中动态获取到的必填参数
                "out_trade_no"    => $order['order_sn'],
                "subject"         => '全屋优品订单支付'.($order['type']==1 ? '-定金' : ''),
                "body"            => '全屋优品订单',
                "total_fee"       => $order['total'],
                //扩展功能参数——网银提前
                "paymethod"       => $this->_config['paymethod']
                //"extra_common_param" => $order['add_time']
        );
        $merchant_url    = "http://".$_SERVER['HTTP_HOST']."/pay?oid=".$order['order_id'];
        $req_data = '<direct_trade_create_req><notify_url>' . $this->_config['notify_url'] .
         '</notify_url><call_back_url>' . $this->_config['return_url'] . 
         '</call_back_url><seller_account_name>' . $this->_config['seller_email'] . 
         '</seller_account_name><out_trade_no>' . $order['order_id'] . 
         '</out_trade_no><subject>全屋优品在线支付</subject><total_fee>' . $order['total'] . 
         '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
        $format = "xml";
        $req_id = date('Ymdhis');
        $para_token = array(
                "service" => "alipay.wap.trade.create.direct",
                "partner" => trim($this->_config['partner']),
                "sec_id" => trim($this->_config['sign_type']),
                "format"    => $format,
                "v" => "2.0",
                "req_id"    => $req_id,
                "req_data"  => $req_data,
                "_input_charset"    => trim(strtolower($this->_config['_input_charset']))
        );
        $alipaySubmit = new AlipaySubmit($this->_config);
        // $html_text = $alipaySubmit->buildRequestForm($para_token,"get", "确认");
        $html_text = $alipaySubmit->buildRequestHttp($para_token);
        $html_text = urldecode($html_text);
        // echo $html_text;exit();
        $para_html_text = $alipaySubmit->parseResponse($html_text);
        $request_token = $para_html_text['request_token'];
        $req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
        $parameter = array(
                "service" => "alipay.wap.auth.authAndExecute",
                "partner" => trim($this->_config['partner']),
                "sec_id" => trim($this->_config['sign_type']),
                "format"    => $format,
                "v" => "2.0",
                "req_id"    => $req_id,
                "req_data"  => $req_data,
                "_input_charset"    => trim(strtolower($this->_config['_input_charset']))
        );
        $html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');
        return $html_text;
    }

    public function getOptions($data) {
        return $data;
    }

    public function response($callback) {
        require_once("alipay/alipay_notify.class.php");
        $this->is_log = true;
        $alipayNotify = new AlipayNotify($this->_config);
        
        if(empty($_POST)) {
            $verify_result = $alipayNotify->verifyReturn();
        } else {
            $verify_result = $alipayNotify->verifyNotify();
        }
        
        if($verify_result) {
            //商户订单号
            if(empty($_POST)) {
                $response['out_trade_no'] = $_GET['out_trade_no'];
                $response['trade_no'] = $_GET['trade_no'];
                //交易状态
                $trade_status = $_GET['result'];
                $amount = $_GET['total_fee'];
            } else {
                $notify_data = $_POST['notify_data'];
                
                $doc = new DOMDocument();
                $doc->loadXML($notify_data);
                if( empty($doc->getElementsByTagName( "notify" )->item(0)->nodeValue) ) {
                    return false;
                }
                $out_trade_no = $doc->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;

                $response['out_trade_no'] = $out_trade_no;
                //支付宝交易号
                $response['trade_no'] = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
                $trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;
                $amount = $doc->getElementsByTagName( "total_fee" )->item(0)->nodeValue;
            }
            //交易状态
            $response['trade_status'] = $trade_status;
            $response['total_fee'] = $amount;
            $response['pay_id'] = 1;
            $response['pay_name'] = '支付宝';
            if(is_array($callback)) {
                return $callback[0]->$callback[1]($response);
            } else {
                return $callback($response);
            }
            
        }
        return false;
        
    }
}



