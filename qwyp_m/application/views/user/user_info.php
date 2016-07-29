<!DOCTYPE html>
<html lang="zh-CN"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>个人资料管理</title>
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/bootstrap.min.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/font-awesome.min.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/ectouch.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/user.css');?>">
<link rel="stylesheet" href="<?php   echo base_url('public/mobile/css/photoswipe.css');?>">
</head>

<body>
<div class="con">
<?php echo $this->load->view("head", array(), true);?>
<form name="subform" class="subform" method="post" action="/user/save_user_info" >
  <section class="flow-consignee ect-bg-colorf">
    <ul>
      <li>
        <div class="input-text"><b class="pull-left">姓名：</b><span>
          <input name="uname" type="text" placeholder="请输入姓名" value="<?php echo $user['uname'];?>">
          </span></div>
      </li>
      <li>
        <div class="input-text"><b class="pull-left">手机:</b><span>
          <?php echo $user['phone'];?>
          </span></div>
      </li>
      <li>
        <div class="input-text"><b class="pull-left">微信号:</b><span>
          <input name="weixin_name" type="text" value="<?php echo $user['weixin_name'];?>" placeholder="请输入微信号">
          </span></div>
      </li>
      <li>
        <div class="input-text"><b class="pull-left">身份证:</b><span>
          <input name="id_card" type="text" value="<?php echo $user['id_card'];?>" placeholder="请输入身份证号">
          </span></div>
      </li>
      <li>
        <div class="input-text"><b class="pull-left">银行卡号:</b><span>
          <input name="bank_num" type="text" value="<?php echo $user['bank_num'];?>" placeholder="请输入身份证号">
          </span></div>
      </li>
      <li>
        <div class="input-text"><b class="pull-left">所属银行:</b>
          <span>
          <select name="bank" style="-webkit-appearance:menulist; ">
          	  <?php foreach($bank_list as $key=>$val):?>
              	   <option value=<?php echo $val['id'];?> <?php if($val['id'] == $user['bank']):?>selected="selected"<?php endif;?>><?php echo $val['bank_name'];?></option>
              <?php endforeach;?>
          </select>
          </span>
        </div>
      </li>
      <?php if(isset($user['tj_name'] ) && !empty($user['tj_name'] )):?>
      <li>
        <div class="input-text"><b class="pull-left">推荐人姓名:</b>
        <span></span>
        <?php echo $user['tj_name'] ;?>
        </div>
      </li>
      <li>
        <div class="input-text"><b class="pull-left">推荐人电话:</b>
        <span></span>
        <?php echo $user['tj_phone'];?>
        </div>
      </li>
      <li>
        <div class="input-text"><b class="pull-left">推荐人微信:</b>
        <span></span>
        <?php echo $user['tj_weixin'];?>
        </div>
      </li>
      <?php endif;?>
      <li>
        <div class="input-text"><b class="pull-left">注册时间:</b>
        <span></span>
        <?php echo date("Y-m-d H:i:s",$user['reg_time']);?>
        </div>
      </li>
     </ul>
  </section>
  <div class="two-btn ect-padding-tb ect-padding-lr ect-margin-tb text-center">
    <input name="submit" type="submit" value="确认修改" class="btn btn-info ect-btn-info ect-colorf ect-bg">
  </div>
</form>
</div>
</body></html>