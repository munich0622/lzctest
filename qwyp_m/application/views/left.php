<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <meta http-equiv=content-type content="text/html; charset=utf-8" />
        <link href="<?php   echo base_url('public/css/admin.css');?>" type="text/css" rel="stylesheet" />
        <script language=javascript>
            function expand(el)
            {
                childobj = document.getElementById("child" + el);

                if (childobj.style.display == 'none')
                {
                    childobj.style.display = 'block';
                }
                else
                {
                    childobj.style.display = 'none';
                }
                return;
            }
        </script>
    </head>
    <body>
        <table height="100%" cellspacing=0 cellpadding=0 width=170 background=<?php   echo base_url('public/img/menu_bg.jpg');?> border=0>
            <tr>
                <td valign=top align=middle>
                    <table cellspacing=0 cellpadding=0 width="100%" border=0>
                        <tr><td height=10></td></tr>
                    </table>
                    <table cellspacing=0 cellpadding=0 width=150 border=0>
                        <tr height=22>
                            <td style="padding-left: 30px" background=<?php echo base_url('public/img/menu_bt.jpg');?> ><a class=menuparent onclick=expand(1) href="javascript:void(0);">账号管理</a></td>
                        </tr>
                        <tr height=4><td></td></tr>
                    </table>
                    <table id=child1 style="display: none" cellspacing=0 cellpadding=0 width=150 border=0>
                        <tr height=20>
                            <td align=middle width=30><img height=9 src="<?php   echo base_url('public/img/menu_icon.gif');?>  " width=9></td>
                            <td><a class=menuchild href="<?php echo site_url('user/index');?>" target="right">个人管理</a></td>
                        </tr>
                        <tr height=20>
                            <td align=middle width=30><img height=9 src="<?php   echo base_url('public/img/menu_icon.gif');?>" width=9></td>
                            <td><a class=menuchild href="<?php echo site_url('user/update_pass');?>" target="right">修改密码</a></td>
                        </tr>
                        <tr height=20>
                            <td align=middle width=30><img height=9 src="<?php   echo base_url('public/img/menu_icon.gif');?>" width=9></td>
                            <td><a class=menuchild href="<?php echo site_url('app/app_zlist');?>" target="right">搜索功能</a></td>
                        </tr>
                        <tr height=20>
                            <td align=middle width=30><img height=9 src="<?php   echo base_url('public/img/menu_icon.gif');?>" width=9></td>
                            <td><a class=menuchild href="<?php echo site_url('app/app_zlist');?>" target="right">市场架构</a></td>
                        </tr>  
                        <tr height=20>
                            <td align=middle width=30><img height=9 src="<?php   echo base_url('public/img/menu_icon.gif');?>" width=9></td>
                            <td><a class=menuchild href="<?php echo site_url('task/add_task');?>" target="right">个人框架</a></td>
                        </tr>      
                        <tr height=20>
                            <td align=middle width=30><img height=9 src="<?php   echo base_url('public/img/menu_icon.gif');?>" width=9></td>
                            <td><a class=menuchild href="<?php echo site_url('task/task_list');?>" target="right">自动安放</a></td>
                        </tr>  
                        <tr height=20>
                            <td align=middle width=30><img height=9 src="<?php   echo base_url('public/img/menu_icon.gif');?>" width=9></td>
                            <td><a class=menuchild href="<?php echo site_url('task/task_list');?>" target="right">个人升级管理</a></td>
                        </tr>       
                        <tr height=20>
                            <td align=middle width=30><img height=9 src="<?php   echo base_url('public/img/menu_icon.gif');?>  " width=9></td>
                            <td><a class=menuchild href="<?php echo site_url('user/create_qrcode');?>" target="right">显示个人二维码</a></td>
                        </tr>                            
                        <tr height=4><td colspan=2></td></tr>
                    </table>
                    <!--  
                    <table cellspacing=0 cellpadding=0 width=150 border=0>
                        <tr height=22>
                            <td style="padding-left: 30px" background=<?php   echo base_url('public/img/menu_bt.jpg');?>><a class=menuparent onclick=expand(2) href="javascript:void(0);">任务管理</a></td>
                        </tr>
                        <tr height=4><td></td></tr>
                    </table>
                    <table id=child2 style="display: none" cellspacing=0 cellpadding=0 width=150 border=0>
                        <tr height=20>
                            <td align=middle width=30><img height=9 src="<?php   echo base_url('public/img/menu_icon.gif');?>" width=9></td>
                            <td><a class=menuchild href="<?php echo site_url('task/add_task');?>" target="right">添加任务</a></td>
                        </tr>
                        <tr height=20>
                            <td align=middle width=30><img height=9 src="<?php   echo base_url('public/img/menu_icon.gif');?>" width=9></td>
                            <td><a class=menuchild href="<?php echo site_url('task/task_list');?>" target="right">任务列表</a></td>
                        </tr>
                    </table>
                    -->
                </td>
            </tr>
       </table>
                    
    </body>
</html>