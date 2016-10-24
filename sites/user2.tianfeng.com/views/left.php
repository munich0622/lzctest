<div class="u-menu">
            <ul class="u-nav" id="user_menu">
                <li class="item" id="user_menu_my" name="user_menu_my">
                    <h3 class="t1">
                        我的主页<span title="折叠"></span></h3>
                    <ul class="sub">
                        <li><a <?php if($method == 'User::index'):?>class="current"<?php endif;?> href="/user/index">个人主页</a></li>
                        <li><a href="/user/info">个人资料</a></li>
                        <li><a href="/user/password">密码设置</a></li>
                        <li><a href="/user/tj_url">推荐有奖</a></li>
                    </ul>
                </li>
                <li class="item" id="user_menu_funds" name="user_menu_funds">
                    <h3 class="t2">
                        资金管理<span title="折叠"></span></h3>
                    <ul class="sub">
                        <li><a class="current" href="个人主页.htm">个人主页</a></li><li><a href="个人资料.htm">个人资料</a></li><li>
                            <a href="认证管理.htm">认证管理</a></li><li><a href="密码管理.htm">密码设置</a></li><li><a href="推荐有奖.htm">推荐有奖</a></li></ul>
                </li>
                <li class="item" id="user_menu_invest" name="user_menu_invest">
                    <h3 class="t4">
                        资金管理<span title="折叠"></span></h3>
                    <ul class="sub">
                        <li><a class="current" href="个人主页.htm">个人主页</a></li><li><a href="个人资料.htm">个人资料</a></li><li>
                            <a href="认证管理.htm">认证管理</a></li><li><a href="密码管理.htm">密码设置</a></li><li><a href="推荐有奖.htm">推荐有奖</a></li></ul>
                </li>
                <li class="item" id="user_menu_loan" name="user_menu_loan">
                    <h3 class="t3">
                        资金管理<a name="user_login"></a><span title="折叠"></span></h3>
                    <ul class="sub">
                        <li><a class="current" href="个人主页.htm">个人主页</a></li><li><a href="个人资料.htm">个人资料</a></li><li>
                            <a href="认证管理.htm">认证管理</a></li><li><a href="密码管理.htm">密码设置</a></li><li><a href="推荐有奖.htm">推荐有奖</a></li></ul>
                </li>
            </ul>
            <script type="text/javascript">
                var menuClosed = Ops.getCookie('menuClosed');

                $(".item h3 span").click(function () {

                    menuClosed = Ops.getCookie('menuClosed');
                    if (menuClosed == undefined || menuClosed == null) {
                        menuClosed = '';
                        Ops.setCookie('menuClosed', menuClosed);
                    }
                    //console.log(menuClosed+',click;;;');	
                    $(this).parent().parent().toggleClass('bg-slide');
                    $(this).parent().parent().find(".sub").slideToggle('fast');

                    if ($(this).attr('title') == '折叠') {
                        $(this).attr('title', '展开');
                    } else {
                        $(this).attr('title', '折叠');
                    }

                    var pid = $(this).parent().parent().attr('id');

                    if ($(this).parent().parent().hasClass('bg-slide') && menuClosed.indexOf("#" + pid + "#") == -1) {
                        var cookies = menuClosed + '#' + pid + '#';
                    } else {
                        var cookies = menuClosed.replace("#" + pid + "#", '');
                    }
                    Ops.setCookie('menuClosed', cookies);
                });

                if (menuClosed != null) {
                    var closedMatch = menuClosed.match(/([a-z_]+)/g);
                    for (var i in closedMatch) {
                        var idObj = $('#' + closedMatch[i]);
                        idObj.toggleClass('bg-slide');
                        idObj.find(".sub").hide();
                        idObj.find('h3 span').attr('title', '展开');
                    }
                } else {
                    $("#user_menu_loan h3 span").click();
                }
            </script>
        </div>