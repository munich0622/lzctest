<div class="ect-bg">
  <header class="ect-header ect-margin-tb ect-margin-lr text-center ect-bg icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history"></a> <span>个人资料</span></header>
  <nav class="ect-nav ect-nav-list" style="display:none;">
    <ul class="ect-diaplay-box text-center">
      <li class="ect-box-flex"><a href="javascript:void(0)"><i class="ect-icon ect-icon-home"></i>首页</a></li>
      <li class="ect-box-flex"><a href="javascript:void(0)"><i class="ect-icon ect-icon-cate"></i>分类</a></li>
      <li class="ect-box-flex"><a href="javascript:void(0)"><i class="ect-icon ect-icon-search"></i>搜索</a></li>
      <li class="ect-box-flex"><a href="javascript:void(0)"><i class="ect-icon ect-icon-flow"></i>购物车</a></li>
      <li class="ect-box-flex"><a href="javascript:void(0)"><i class="ect-icon ect-icon-user"></i>个人中心</a></li>
    </ul>
  </nav>
</div>
<div class="user-info">
  <div class="user-img pull-left"><i class="glyphicon glyphicon-user"></i></div>
  <dl class="pull-left">
    <dt>
      <h4><?php echo $_SESSION['user']['phone'];?> | <a href="/login/loginout" class="ect-colorf">退出</a></h4>
    </dt>
    <dd>您的等级是 <?php echo $_SESSION['user']['level'];?> 级用户</dd>
  </dl>
  <span class="pull-right"><a href="" class="ect-colorf">&nbsp;<i class="fa fa-envelope-o "></i></a></span>
</div>