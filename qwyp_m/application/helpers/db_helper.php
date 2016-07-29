<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*-----与数据库操作相关的常用函数-----*/	

	//单个广告
	function ad($position_id, $bgcolor=false){
		$html = '';
		$CI = &get_instance();
		L("m.ad_model");
		$ad = $CI->ad_model->ad_info($position_id);
		if($ad) {
			$target = $ad['target']?"_blank":"_self";
			$html = "<a alt='{$ad['ad_name']}' href='/ad/?id={$ad['id']}&link=".urlencode($ad['link_url'])."' bg-color='{$ad['bg_color']}' ".($bgcolor?"style='background-color:{$ad['bg_color']}'":'')." target='{$target}' class='click_count' adid='{$ad['id']}'>
						<img src='".IMG_URL."{$ad['img_url']}?{$ad['uptime']}' width='{$ad['width']}' height='{$ad['height']}'>
				   	</a>";
		}
		echo $html;
	}
	
	//多个广告 
	function ads($position_ids, $contain_tag=''){
		$html = '';
		$CI = &get_instance();
		L("m.ad_model");
		$ads = $CI->ad_model->ads_info($position_ids);
		foreach ($ads as $ad) {
			$target = $ad['target']?"_blank":"_self";
			$h = "<a alt='{$ad['ad_name']}' href='/ad/?id={$ad['id']}&link=".urlencode($ad['link_url'])."' bg-color='{$ad['bg_color']}' target='{$target}' class='click_count' ad_id='{$ad['id']}'>
					<img src='".IMG_URL."{$ad['img_url']}?{$ad['uptime']}' width='{$ad['width']}' height='{$ad['height']}'>
				 </a>";
			if($contain_tag) {
				$h = "<{$contain_tag}>{$h}</{$contain_tag}>";
			}
			$html .= $h;
		}
		return array('html'=>$html, 'num'=>count($ads));
	}
	
	//商品信息
	function get_goods_info($item_id, $goods_id=0){
		$CI = &get_instance();
		L("m.goods_model");
		return $CI->goods_model->get_goods_info($item_id, $goods_id);
	}
	
	//预售商品
	function group_rev_goods($item_id, $goods_id){
		$CI = &get_instance();
		L("m.group_model");
		return $CI->group_model->group_rev_base_info($item_id, $goods_id);
	}
	
	//团购信息
	function group_direct_info($id){
		$CI = &get_instance();
		L("m.group_model");
		return $CI->group_model->group_direct_info2($id);
	}
	
	//由goods_id获取商品
	function get_goods_by_ids($goods_ids, $ob='', $img_format='120X80'){
		$CI = &get_instance();
		L("m.common_model");
		$rows = $CI->common_model->get_goods_by_ids($goods_ids, $ob);
		$rows2 = array();
		foreach ($rows as $k=>$r) {
			$r['img'] = get_goods_img_url($r, $img_format);
			$r['url'] = get_goods_url($r);
			$rows2[$r['goods_id']] = $r;
		}
		return $rows2;
	}
	
	//由item_id获取商品
	function get_goods_by_item_ids($item_ids, $img_format='120X80'){
		$CI = &get_instance();
		L("m.common_model");
		$rows = $CI->common_model->get_goods_by_item_ids($item_ids);
		$rows2 = array();
		foreach ($rows as $k=>$r) {
			$r['img'] = get_goods_img_url($r, $img_format);
			$r['url'] = get_goods_url($r);
			$rows2[$r['item_id']] = $r;
		}
		return $rows2;
	}
	
	//由item_id获取秒杀商品
	function get_seckill_goods($item_ids, $img_format='120X80'){
		$CI = &get_instance();
		L("m.common_model");
		$rows = $CI->common_model->get_seckill_goods($item_ids);
		foreach ($rows as $k=>$r) {
			$rows[$k]['img'] = get_goods_img_url($r, $img_format);
			$rows[$k]['url'] = get_goods_url($r);
		}
		return $rows;
	}
	
	function get_goods_decorate($cat_id,$limit){
	    $CI = &get_instance();
	    L("m.decorate_model");
	    $rows = $CI->decorate_model->get_decorate_list($cat_id,$limit);
	    return $rows;
	}
	
	//获取点赞商品
	function praise_goods($item_ids, $limit, $img_format='120X80'){
		$CI = &get_instance();
		L("m.goods_model");
		$rows = $CI->goods_model->praise_goods($limit, $item_ids);
		foreach ($rows as $k=>$r) {
			$rows[$k]['img'] = get_goods_img_url($r, $img_format);
			$rows[$k]['url'] = get_goods_url($r);
		}
		return $rows;
	}
	
	//商品子分类
	function get_child_cats($parent_id, $limit=15){
		$CI = &get_instance();
		L("m.category_model");
		return $CI->category_model->get_child_cats($parent_id, $limit);
	}
	
	//地区名
	function region_name($id){
		$CI = &get_instance();
		L("m.region_model");
		return $CI->region_model->region_name($id);
	}

	function get_goods_img_url($data, $size = '120X80') {
		return IMG_URL . "/goods/{$data['join_id']}/{$data['goods_id']}/{$data['default_img_id']}_{$size}.jpg";
	}
	
	function get_goods_url($goods) {
		return "/goods/".$goods['item_id'].'/'.$goods['goods_id'];
	}
	
	//获取详情图
	function get_desc_img($desc_info, $update_times){
		if(empty($desc_info)) {
			return '';
		}
		//延迟加载
		$desc_info = str_replace('src="/item_desc', 'data-src="'.IMG_URL.'/item_desc', $desc_info);
		//加版本号
		$desc_info = preg_replace('/\.(jpg|jpeg|gif|png)/iU', ".$1?".$update_times, $desc_info);
		return str_replace('&nbsp;', '', $desc_info);
	}
	
	
	//订单列表商品链接
	function get_goods_url2($goods,$empty = array()) {
		return "/goods/".$goods['item_id']."/".$goods['goods_id'];
	}
	
	function is_login() {
		return isset($_SESSION['user']) && isset($_SESSION['user']['id']) && $_SESSION['user']['id'] > 0;
	}

    function get_user_face($user_id) {
        if(file_exists(dirname(BASEPATH) . '/public/upload/qwyp/img/user/' . md5($user_id.'_face') . '_thumb.jpg')) {
            return '/upload/tmp/' . md5($user_id.'_face') . '_thumb.jpg';
        }
        return '/upload/tmp/_thumb.jpg';
    }

    function get_goods_sku($attr_key, $sku) {
        $attr_key = unserialize($attr_key);
        $sku = explode("_", $sku);
        $skus = array();
        foreach ($sku as $k => $v) {
            foreach ($attr_key as $vk => $vv) {
                if(in_array($v, $vv['value'])) {
                    $skus[$vv['name']] = $v;
                    unset($attr_key[$vk]);
                    break ;
                }
            }
        }
        return $skus;
    }
    
    //获取商品的佣金信息
    function goods_reward($goods) {
    	$CI = &get_instance();
    	L('m.goods_model', 'goods');
    	return $CI->goods->goods_reward($goods);
    }
    
    function get_setting($key) {
        $CI = &get_instance();
        L('m.common_model', 'common');
        return $CI->common->get_setting($key);
    }
    
    function website_config($key) {
        $CI = &get_instance();
        L('m.common_model', 'common');
        return $CI->common->website_config($key);
    }
    
	//将数组键值转化为指定字段作为键, 指定键值
    function id_key($rows, $colum='id', $value='') {
    	if (empty($rows)) return array();
    	$list = array();
    	foreach($rows as $k=>$r) {
    		$list[$r[$colum]] = !empty($value) ? $r[$value] : $r;
    	}
    	return $list;
    }
    
    /**
     * 获取指定字段形成数组
     * @param array $arr
     * @param str $columns 如id|province_id,city_id
     */
    function ids($arr, $id_columns='id'){
    	if (empty($arr)) return array();
    
    	$id_columns = explode(',', $id_columns);
    	$ids = array();
    	foreach ($id_columns as $id) {
    		$arr2 = $arr;
    		$first = array_shift($arr2);
    		if(is_array($first)) {
    			foreach ($arr as $r) {
    				$ids[] = $r[$id];
    			}
    		}
    		else{
    			$ids[] = $arr[$id];
    		}
    	}
    	$ids = array_unique($ids);
    	sort($ids);
    
    	return $ids;
    }
	
	
    