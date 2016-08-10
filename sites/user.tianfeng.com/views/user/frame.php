<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>资金管理</title>
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/bootstrap.min.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/font-awesome.min.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/ectouch.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/user.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/photoswipe.css');?>">
</head>

<body>
<div class="con">
<div class="ect-bg">
  <header class="ect-header ect-margin-tb ect-margin-lr text-center ect-bg icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history"></a> <span>资金管理</span></header>
  <nav class="ect-nav ect-nav-list" style="display:none;">
    <ul class="ect-diaplay-box text-center">
      
    </ul>
  </nav>
</div>
 <div class="user-account-detail">
    <ul class=" ect-bg-colorf">
         </ul>
    <p class="pull-right count">您的上级是：
    <b class="ect-colory">
        <?php 
        if(!empty($up_info)){
            echo $up_info['uname'];
        }else{
            echo '暂无上级';
        }
        ?>
    </b>
    <p class="pull-right count">您的下级是：
    <b class="ect-colory">
        <?php 
        if(!empty($son_info)){
            foreach ($son_info as $key=>$val){
                echo $val['uname']."<br />";
            }
        }else{
            echo '暂无上级';
        }
        ?>
    </b>
    <p class="pull-right count">您的下下级是：
    <b class="ect-colory">
        <?php 
        if(!empty($son_son_info)){
            foreach ($son_son_info as $key=>$val){
                echo $val['uname']."<br />";
            }
        }else{
            echo '暂无下下级';
        }
        ?>
    </b>
    <p class="pull-right count">您的下级是：
    <b class="ect-colory">
        <?php 
        if(!empty($son_son_son_info)){
            foreach ($son_son_son_info as $key=>$val){
                echo $val['uname']."<br />";
            }
        }else{
            echo '暂无下下下级';
        }
        ?>
    </b>
    </p>
  </div>
</div>

<a id="scrollUp" href="#top" style="position: fixed; z-index: 10;"><i class="fa fa-angle-up"></i></a>
<style>
#scrollUp {
  border-radius:100%;
  background-color: #777;
  color: #eee;
  font-size: 40px;
  line-height: 1;text-align: center;text-decoration: none;bottom: 1em;right: 10px;overflow: hidden;width: 46px;
  height: 46px;
  border: none;
  opacity: 0.6;
}
</style>
</body>
</html>