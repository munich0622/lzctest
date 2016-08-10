<!DOCTYPE html>
<html lang="zh-CN"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>首页</title>
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/bootstrap.min.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/font-awesome.min.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/ectouch.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/user.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/photoswipe.css');?>">
</head>

<body>
<div class="con">
<?php 
echo $this->load->view("head", array(), true);
?>
<section class="container-fluid user-nav">
  <ul class="row ect-row-nav text-center">
    <a href="/user/display_user_level">
    <li class="col-sm-3 col-xs-3"> <i class="glyphicon glyphicon-credit-card"></i>
      <p class="text-center">升级</p>
    </li>
    </a> <a href="<?php echo site_url('user/create_qrcode');?>">
    <li class="col-sm-3 col-xs-3"> <i class="fa fa-file-text"></i>
      <p class="text-center">查看我的二维码</p>
    </li>
    </a>
    <a href="<?php echo site_url('user/zijin_manage');?>">
    <li class="col-sm-3 col-xs-3"><i class="glyphicon glyphicon-usd"></i>
      <p class="text-center">资金管理</p>
    </li>
    </a> 
    <a href="/user/index">
    <li class="col-sm-3 col-xs-3"><i class="fa fa-user"></i>
      <p class="text-center">我的资料</p>
    </li>
    </a> 
    <a href="<?php echo site_url('user/update_pass');?>">
    <li class="col-sm-3 col-xs-3"><i class="fa fa-shield"></i>
      <p class="text-center">修改密码</p>
    </li>
    </a>
    <a href="<?php echo site_url('user/frame');?>">
    <li class="col-sm-3 col-xs-3"><i class="fa fa-shield"></i>
      <p class="text-center">查看框架</p>
    </li>
    </a>
  </ul>
</section>
</div>
<div class="search" style="display:none;">
  <div class="ect-bg">
    <header class="ect-header ect-margin-tb ect-margin-lr text-center"><span>搜索</span><a href="javascript:;" onclick="closeSearch();"><i class="icon-close pull-right"></i></a></header>
  </div>
  <div class="ect-padding-lr">
     <form action="" method="post" id="searchForm" name="searchForm">
      <div class="input-search"> <span>
        <input name="keywords" type="search" placeholder="请输入搜索关键词！" id="keywordBox">
        </span>
        <button type="submit" value="搜索" onclick="return check('keywordBox')"><i class="glyphicon glyphicon-search"></i></button>
      </div>
    </form>
        <div class="hot-search">
      <p>
      </p><h4 class="title"><b>热门搜索：</b></h4>
      <p></p>
       
      <a href="">玛卡</a> 
       
      <a href="">黑枸杞</a> 
    </div>
     
  </div>
</div>
</body>
</html>