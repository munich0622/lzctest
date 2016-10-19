
// 测试所依赖的是否已经加载完
testSo();
function testSo() {
    if (!window.sys) {
        setTimeout(testSo, 10);
        console.log(1);
    }
    else {

        /*-------------------------------------------------------------\
            我的资源
        \-------------------------------------------------------------*/
        //显示侧边子导航栏
        function showHiddenNav() {
            var aHasDropdownMenu = So('.has-dropdown-menu').eq();
            if (aHasDropdownMenu) {
                each(aHasDropdownMenu, function (index) {
                    this.onclick = function () {
                        if (hasClass(this, 'active')) {
                            removeClass(this, 'active');
                        }
                        else {
                            addClass(this, 'active');
                        }
                    }
                })

                //阻止下拉子菜单点击冒泡收起菜单
                var aDropDownItem = So('.dropdown-item').eq();
                if (aDropDownItem) {
                    each(aDropDownItem, function () {
                        this.onclick = function (e) {
                            stopPro(e);
                        }
                    })
                }
            }
        }
        showHiddenNav();

        /*-------------------------------------------------------------\
            发布资源
        \-------------------------------------------------------------*/

        /*+++++++++++++++++分布切换+++++++++++++++++++*/

        // 发布表单验证
        function checkPubForm(step) {
            switch (step) {
                case 2: //跳往第二步 ，检测第一步就ok了
                    return checkSteps1();
                    break;
                case 3: //
                    return checkSteps2();
                    break;
                case 4: //
                    return checkSteps3();
                    break;
                case 5: //
                    // alert(checkSteps4_2());
                    return checkSteps4_2();
                    break;
            }
        }
        // 第一步检测
        function checkSteps1() {
            var cityCheck = So('.city-choose-list input').eq();
            var _hasCheckCity = null;
            each(cityCheck, function () {
                if (this.checked) {  //若有选中一个就返回.
                    _hasCheckCity = true;
                }
            })
            if (!_hasCheckCity) {
                // alert('请选择城市!');
                Msg('请选择城市!');
                return false;
            }
            else {
                return true;
            }
        }

        // 第二步检测
        function checkSteps2() {
            var typeResult = So('#typeResult').eq(1);
            var sizeResult = So('#size-input').eq(1);
            if (typeResult.value == '') { //检测是否选择资源类型
                alert('请选择资源类型!');
                return false;
            }
            else {
                if (trim(sizeResult.value) != '' && sizeResult.value >= 0) {
                    return true;
                }
                else {
                    alert('请正确输入资源面积!');
                    return false;
                }
            }
        }

        //第三步检测
        function checkSteps3() {
            return true;
        }

        // 第四步检测
        function checkSteps4_2() {
            var aNeeded = So('.needed').eq();
            var _result = true;
            for (var i = 0, len = aNeeded.length; i < len; i++) {
                if (hasClass(aNeeded[i], 'needed-input')) {  // 如果为资源标题输入框
                    if (trim(aNeeded[i].value) == '') {
                        alert('资源标题不能为空!');
                        _result = false;
                        return _result;
                    }
                }
                if (hasClass(aNeeded[i], 'needed-upload')) {
                    if (!aNeeded[i].children.length) {
                        var _tip = aNeeded[i].getAttribute('data-tip');
                        alert('请选择上传' + _tip + '图片!');
                        // alert('请选择上传图片!');
                        _result = false;
                        return _result;
                    }
                }
            }
            return _result;
        }

        //var aGoNextBtn = So('.go-next').eq(1);
        //if(aGoNextBtn){
        //	aGoNextBtn.onclick = function(){
        //		var _nextStep = parseInt(aGoNextBtn.getAttribute('data-steps')) ;
        //		if(checkPubForm(_nextStep)){
        //			window.location.href = '/account/pubResourceStep'+_nextStep+'.aspx' ; 
        //		}
        //	}
        //}



        // 显示额外搜索条件
        function showExtraLimit() {
            var extraBtn = So('#search-extra-limit-btn').eq(); //更多选项按钮
            var searchExtraLimitBox = So('#search-extra-limit-box').eq();
            extraBtn.onclick = (function () {
                var extraFlag = -1;   //默认为关闭更多筛选条件
                return function () {  //闭包借用局部变量
                    if (extraFlag == -1) {
                        addClass(extraBtn, 'active');
                        searchExtraLimitBox.style.visibility = "visible";
                        startMove(searchExtraLimitBox, { height: 50, marginBottom: 20 }, function () {
                        });
                    }
                    else if (extraFlag == 1) {
                        removeClass(extraBtn, 'active');
                        startMove(searchExtraLimitBox, { height: 0, marginBottom: 0 }, function () {
                            // searchExtraLimitBox.style.display = "none";
                            searchExtraLimitBox.style.visibility = "hidden";
                        });
                    }
                    extraFlag *= -1;
                }
            })()
        }

        showExtraLimit();

        // 添加选中按钮
        var resourceCheckIcon = null;
        var rTypeResult = So('#rTypeResult input').eq(1);
        var rTypeUnit = So('#r-type-unit').eq(1);
        var resourceArr = [];

        // 添加选中标签
        function addCheckedIcon(obj) {
            if (!resourceCheckIcon) { // 如果不存在选中标签，则创建一个
                resourceCheckIcon = document.createElement('i');
                resourceCheckIcon.className = 'check-icon';
                obj.appendChild(resourceCheckIcon);
            }
            else {
                var _checkIcon = resourceCheckIcon;
                removeClass(resourceCheckIcon.parentNode, 'checked')
                resourceCheckIcon.parentNode.removeChild(resourceCheckIcon);
                obj.appendChild(_checkIcon);
                addClass(obj, 'checked');
            }
        }
        // 填充选择信息
        function addCheckInfo(obj) {
            rTypeResult.value = obj.getAttribute('data-value');
            if (obj.getAttribute('data-unit') == 'square') {
                rTypeUnit.innerHTML = '亩';
            }
            else {
                rTypeUnit.innerHTML = '平方米';
            }
        }


        function showOtherList() {
            var rTypeOthersList = So('#r-type-others-list').eq(1);
            var rTypeOthersLi = rTypeOthersList.getElementsByTagName('li');
            var isShowOthers = -1; //默认其他列表隐藏
            OtherTypeBtn.onclick = function () {
                return function () {
                    if (isShowOthers == -1) { //去放大
                        tStartMove(rTypeOthersList, { height: 232 }, function () {
                            addClass(rTypeOthersList, 'open');
                            expandItem(rTypeOthersLi);
                        })
                    }
                    else if (isShowOthers == 1) { //去缩小
                        removeClass(rTypeOthersList, 'open');
                        expandItem(rTypeOthersLi);
                    }
                    isShowOthers *= -1;
                }()
            }

            // 逐个放大各个项
            function expandItem(aItem) {
                sortMove(aItem, function () {
                    transMove(this, { scaleValue: (isShowOthers == -1) ? 1 : 0 }, { scaleValue: (isShowOthers == -1) ? 0 : 1 }, function (data) {
                        setCss(this, { $Transform: 'scale(' + data.scaleValue + ')' });
                    }, function () {
                        if (isShowOthers == -1) {
                            startMove(rTypeOthersList, { height: 0 }, function () {

                            })
                        }
                    }, 'easeOutStrong', 600)
                })
            }
        }

        // 其他土地的展开
        var OtherTypeBtn = So('#other-type-btn').eq(1);
        if (OtherTypeBtn) {
            showOtherList();
        }



        // 地图事件
        //地图数组
        var where = [];
        function GetMap() {
            console.log("请求地图");
            if (where.length > 0) {
                return where;
            }
            else {
                $.ajax({
                    type: 'POST',
                    url: "/ajax/ajax_getmap.aspx?type=province",
                    cache: false,
                    async: false,
                    dataType: "json",
                    success: function (data) {
                        for (var i = 0; i < data.length; i++) {
                            where[i] = data[i];
                        }
                    }
                });
            }
        }

        //资源搜索页和发布资源第一步获取地图需要请求省市数据
        if (So('#city-choose-list').eq(1) || So('#search-city-limit').eq(1)) {
            GetMap();
        }


        var ieMapCity = { 0: "黑龙江", 1: "黑龙江", 2: "吉林", 3: "吉林", 4: "辽宁", 5: "辽宁", 6: "河北", 7: "河北", 8: "山东", 9: "山东", 10: "江苏", 11: "江苏", 12: "浙江", 13: "浙江", 14: "安徽", 15: "安徽", 16: "河南", 17: "河南", 18: "山西", 19: "山西", 20: "陕西", 21: "陕西", 22: "甘肃", 23: "甘肃", 24: "湖北", 25: "湖北", 26: "江西", 27: "江西", 28: "福建", 29: "福建", 30: "湖南", 31: "湖南", 32: "贵州", 33: "贵州", 34: "四川", 35: "四川", 36: "云南", 37: "云南", 38: "青海", 39: "青海", 40: "海南", 41: "海南", 42: "上海", 43: "上海", 44: "重庆", 45: "重庆", 46: "天津", 47: "天津", 48: "北京", 49: "北京", 50: "内蒙古", 51: "内蒙古", 52: "广西", 53: "广西", 54: "新疆", 55: "新疆", 56: "西藏", 57: "西藏", 58: "广东", 59: "广东", 60: "宁夏", 61: "宁夏", 62: "香港", 63: "香港", 64: "台湾", 65: "台湾", 66: "澳门", 67: "澳门" }

        var aPath = [];
        var aText = [];
        var aProvince = [];
        document.onclick = function (e) {
            e = e || window.event;
            el = e.target || e.srcElement;
            if (el.tagName == 'shape') {  //IE8的wml绘图
                writeCity(ieMapCity[el.getAttribute('raphaelid')]);
            }
            if (el.tagName == 'path' || el.tagName == 'text' || el.tagName == 'tspan') {
                if (aPath.length == 0) {
                    aPath = So('path').eq();
                    aText = So('text').eq();
                    aTspan = So('tspan').eq();

                    each(aPath, function (i) {
                        this.setAttribute('index', i);
                        aText[i].setAttribute('index', i);
                        var _provinceNameStr = (aTspan[i].textContent) || (aTspan[i].innerHTML);
                        aProvince.push(_provinceNameStr);
                    })
                }
                // alert(aProvince[el.getAttribute('index')||el.parentNode.getAttribute('index')]);  //得到省份
                var _province = aProvince[el.getAttribute('index') || el.parentNode.getAttribute('index')];
                writeCity(_province); //写入城市
            }
            else if (hasClass(el, 'r-type-item') || hasClass(el, 'r-type-img') || hasClass(el, 'r-type-tit')) {
                // 资源种类选择
                if (hasClass(el, 'r-type-item')) {
                    addCheckedIcon(el);
                    addCheckInfo(el.children[1]);
                }
                else {
                    addCheckedIcon(el.parentNode);
                    addCheckInfo(el.parentNode.children[1]);
                }
            }
        }




        //根据地图选择城市
        function writeCity(province) {
            var _reg = new RegExp(province);
            var disChooseList = So('#dis-choose-list').eq(1);
            var disChooseTit = So('#dis-choose-tit').eq(1);
            var cityChooseList = So('#city-choose-list').eq(1);
            var cityChooseTit = So('#city-choose-tit').eq(1);
            each(where, function (index) {
                if (where[index].province.match(_reg)) {
                    cityChooseTit.innerHTML = where[index].province;
                    writeCityList(where[index].city.split('|'), where[index].cid.split('|'), where[index].province); //得到匹配的城市,写入
                }
            })

            //写入城市
            function writeCityList(cityList, cidlist, province) {
                var _str = '';
                for (var i = 0, len = cityList.length; i < len ; i++) {
                    _str += '<label for="where' + '_' + cidlist[i] + '">' +
                                 '<input type="radio" name = "radiocity" id="where' + '_' + cidlist[i] + '" onclick="getDis(' + cidlist[i] + ');" data-city="' + cityList[i] + '" data-province="' + province + '">' + cityList[i] +
                            '</label>'

                }
                cityChooseList.innerHTML = _str;
                disChooseList.innerHTML = "";
                disChooseTit.innerHTML = "请选择区";

            }
        }

        /*-------------------------------------------------------------\
            消息提醒
        \-------------------------------------------------------------*/
        // 信息提醒类
        function Message() {
            return this.bind();
        }
        Message.prototype = {
            init: function () {  //初始化信息基本元素
                this.message = So('#message').eq(1);
                this.noMessageClass = 'no-message';
                this.aItem = So('.message-item').eq();   //单条消息元素
                if (this.aItem.length == 0) {
                    this.hiddenBar();
                    return false;
                }
                this.aItemCheck = So('.message-item-check').eq();
                this.aContent = So('.message-content').eq(); //内容(标题+内容+间距)
                this.aTitle = So('.message-item-tit h3').eq();  //单条标题
                this.aDetail = So('.message-item-info').eq();  //详情信息
                this.aSee = So('.message-item-seeMore').eq(); //看单个
                this.aDel = So('.message-item-del').eq(); // 删单个,
                this.delAll = So('#message-del-all').eq(1);
                this.readAll = So('#message-read-all').eq(1);
                this.checkALlBtn = So('#message-checkAll').eq(1);
                this.messageTotal = So('#message-total').eq(1); //总消息
                this.unReadNum = So('#unread').eq(1); //未读消息
                this.readedClass = 'readed';
                this.oExtraH = parseInt(getStyle(this.aDetail[0], 'marginTop')) + parseInt(getStyle(this.aDetail[0], 'marginBottom'));
                this.titH = parseInt(this.aTitle[0].offsetHeight);
                return true;
            },
            seeMore: function (index) {  //查看 : 展开隐藏的信息详情
                var __this = this;
                var _allH = this.oExtraH + this.titH + parseInt(this.aDetail[index].offsetHeight); // 详情自身的高度 + margin高和标题高度
                tStartMove(this.aContent[index], { height: _allH }, function () {
                    __this.aItem[index].open = true;
                    __this.aSee[index].innerHTML = '收起';
                    __this.read(index);
                })
            },
            closeMore: function (index) {  //关闭详情
                var __this = this;
                tStartMove(this.aContent[index], { height: this.titH }, function () {
                    __this.aItem[index].open = false;
                    __this.aSee[index].innerHTML = '查看';
                })
            },
            checkAll: function () {
                var _checked = (this.checkALlBtn.checked) ? true : false;
                each(this.aItemCheck, function () {
                    this.checked = _checked;
                })
            },
            del: function (index) {
                var _id = this.aItem[index].id;
                this.aItem[index].parentNode.removeChild(this.aItem[index]);
                this.messageTotal.innerHTML = parseInt(this.messageTotal.innerHTML) - 1;

                ajax({
                    url: '/ajax/ajax_msg.aspx',
                    method: 'post',
                    anysc: true,
                    data: {
                        'type': 'del',
                        'id': _id  //消息对应的id 
                    },
                    success: function () {

                    },
                    failed: function () {
                        //提交阅读状态失败
                    }
                });
            },
            read: function (index) {
                if (!hasClass(this.aItem[index], this.readedClass)) {
                    var _id = this.aItem[index].id;
                    this.unReadNum.innerHTML = parseInt(this.unReadNum.innerHTML) - 1;
                    addClass(this.aItem[index], this.readedClass);

                    ajax({
                        url: '/ajax/ajax_msg.aspx',
                        method: 'post',
                        anysc: true,
                        data: {
                            'type': 'read',
                            'id': _id  //消息对应的id 
                        },
                        success: function () {

                        },
                        failed: function () {
                            //提交阅读状态失败
                        }
                    });
                }
            },
            hiddenBar: function () { //若没消息 ,则隐藏所有
                addClass(this.message, this.noMessageClass);
            },
            bind: function () {  //绑定
                var result = this.init();
                var __this = this;
                if (result) {
                    //绑定查看
                    each(this.aTitle, function (index) {
                        __this.aSee[index].onclick = this.onclick = function () {
                            if (__this.aItem[index].open) {  //如果是打开的
                                __this.closeMore(index);
                            }
                            else {
                                __this.seeMore(index);
                            }
                        }
                    })
                    // 绑定删除
                    each(this.aDel, function (index) {
                        this.onclick = function () {
                            __this.del(index);
                            __this.bind();
                        }
                    })
                    // 选择部删除
                    __this.delAll.onclick = function () {
                        each(__this.aItemCheck, function (index) {
                            if (this.checked) {
                                console.log("1", index);
                                __this.del(index);
                            }
                        })
                        __this.bind();
                    }
                    //标记已读
                    __this.readAll.onclick = function () {
                        each(__this.aItemCheck, function (index) {
                            if (this.checked) {
                                __this.read(index);
                            }
                        })
                    }
                    this.checkALlBtn.onclick = function () {
                        __this.checkAll();
                    }
                }
                else {  // 解绑所有事件
                    if (__this.checkALlBtn) {  //如果不是一开始就没有一条消息 
                        __this.checkALlBtn.onclick = null;
                        __this.delAll.onclick = null;
                        __this.readAll.onclick = null;
                        __this = null;
                    }
                }
            }
        }

        if (So('#message').eq(1)) {
            var myMessage = new Message();
        }


        /*-------------------------------------------------------------\
            用户中心匹配左侧导航改当前状态
        \-------------------------------------------------------------*/
        // var aLink = So('.side-nav-item a').eq();
        // if (aLink) {
        //     var urlStr = location.href.toLowerCase();
        //     checkUrl(urlStr, aLink, "active", null, 'has-dropdown-menu', 'active');
        // }

        //个人用户中心改变当前状态
        var userUrlCheck = matchUrl({
            'aLink': So('.side-nav-item a').eq(),
            'parentEl': So('.has-dropdown-menu').eq(),
            'linkActiveClass': 'active',
            'parentActiveClass': 'active',
            'replaceUrlData': [
        '/account/pubResourcestep1.aspx|/account/pubResourcestep2.aspx|/account/pubResourcestep3.aspx|/account/pubResourcestep4_dm.aspx|/account/pubResourcestep4_jt.aspx|/account/pubResourcestep4_qy.aspx|/account/pubResourcestep5.aspx'
            ]
        })

        //顶部改变当前状态
        var navUrlCheck = matchUrl({
            'aLink': So('.h-nav-link').eq(),
            'replaceUrlData': [
        '/front/rsearch.aspx|/front/rdetailHot.aspx|commissions',
        '/accout/rsearch.aspx|/accout/rDetailhot.aspx|commissions',
        '/service/pvNews.aspx|/service/pvNewsDetail.aspx'
            ]
        });

        //服务侧边栏改变当前状态
        var serviceUrlCheck = matchUrl({
            'aLink': So('.service-nav-link').eq(),
            'parentEl': So('.service-nav-item').eq(),
            'linkActiveClass': 'select',
            'parentActiveClass': 'active',
            'replaceUrlData': [
            '/service/question.aspx|/service/download.aspx'
            ]

        })

        //信息侧边栏改变当前状态
        matchUrl({
            'aLink': So('.message-nav-link ').eq(),
            'parentEl': So('.message-nav-item').eq(),
            'linkActiveClass': 'select',
            'parentActiveClass': 'active',
            'checkAll': true,
            'checkStatu': 'cid'
        })


        // 正则替换
        function regReplace(str, regList) {
            for (var i = 0, len = regList.length; i < len; i++) {
                for (var j = 0, jLen = regList[i].Original.length; j < jLen; j++) {
                    str = str.replace(regList[i].Original[j].toLowerCase(), regList[i].changeTo);
                }
            }
            return str;
        }

        /*-------------------------------------------------------------\
            前台页面
        \-------------------------------------------------------------*/
        // 导航条下拉菜单

        var hasDropdownMenu = So('#has-dropdown-menu').eq(1);

        function bindDropdown() {
            var dropdownMenuList = So('#dropdown-menu-list').eq(1);
            var dropdownNow = -1;  //默认为隐藏 
            return function () {
                hasDropdownMenu.onmouseenter = function () {
                    this.style.zIndex = 99;
                    addClass(hasDropdownMenu, 'open');
                    tStartMove(dropdownMenuList, { height: 430 });
                }
                hasDropdownMenu.onmouseleave = function () {
                    removeClass(hasDropdownMenu, 'open');
                    tStartMove(dropdownMenuList, { height: 0 }, function () {
                        hasDropdownMenu.style.zIndex = 0;
                    });
                }
            }()
        }
        if (hasDropdownMenu) {
            bindDropdown();
        }

        /*-------------------------------------------------------------\
            首页
        \-------------------------------------------------------------*/
        // 案例切换
        ~function caseControl() {
            var casePrevBtn = So('#case-prev-btn').eq(1);
            if (!casePrevBtn) {
                return;
            }
            var caseNextBtn = So('#case-next-btn').eq(1);
            var caseNow = 0;
            var aImgLi = So('.home-case-imgItem').eq();
            var aInfoLi = So('.home-case-infoItem').eq();
            addClass(aInfoLi[0], 'active');
            addClass(aImgLi[0], 'active');
            var aLoadBorder = So('.loading-vertical-border').eq();
            var aNav = So('.case-nav-bar span').eq();
            addClass(aNav[0], 'active');
            // var autoFlag = false ; 
            return function () {
                // 上一项
                casePrevBtn.onclick = function () {
                    var _prevOne = caseNow;
                    caseNow = (++caseNow) % aImgLi.length;
                    tabCase(caseNow, _prevOne);
                }
                // 下一项
                caseNextBtn.onclick = function () {
                    var _prevOne = caseNow;
                    caseNow = (--caseNow + aImgLi.length) % aImgLi.length;
                    tabCase(caseNow, _prevOne);
                }

                // 导航点切换
                each(aNav, function (index) {
                    this.onclick = function () {
                        if (index == caseNow) {
                            return;
                        }
                        tabCase(index, caseNow);
                        caseNow = index;
                    }
                })

            }()
            // 切换函数
            function tabCase(next, prev) {
                aImgLi[next].style.zIndex = 99;
                aInfoLi[next].style.zIndex = 99;
                caseLoading();
                tStartMove(aImgLi[next], { left: 0 }, function () {
                    aImgLi[next].style.zIndex = 9;
                    aImgLi[prev].style.left = -700 + 'px';
                }, 'easeInStrong');
                setTimeout(function () {
                    changeCaseNav(next, prev);
                    tStartMove(aInfoLi[next], { left: 0 }, function () {
                        aInfoLi[next].style.zIndex = 9;
                        aInfoLi[prev].style.left = -390 + 'px';
                    }, 'easeInStrong', 500);
                }, 200)
            }

            // 加载进度条变化
            function caseLoading() {
                each(aLoadBorder, function () {
                    this.style.opacity = 0.4;
                    this.style.filter = 'alpha(opacity:40)';
                })
                startMove(aLoadBorder[0], { opacity: 100 });
                setTimeout(function () {
                    startMove(aLoadBorder[1], { opacity: 100 });
                }, 200)

                setTimeout(function () {
                    startMove(aLoadBorder[2], { opacity: 100 });
                }, 400)
            }

            // 导航原点的变化
            function changeCaseNav(next, prev) {
                removeClass(aNav[prev], 'active');
                addClass(aNav[next], 'active');
            }
        }()
        // caseControl();


        // 侧边栏回到顶部
        goToTop({
            'btn': So('#side-go-top').eq(1),
            'autoHidden': true,
            'hiddenFlag': 400
        });


        /*-------------------------------------------------------------\
            资源详情页
        \-------------------------------------------------------------*/
        //选项卡切换
        (function showAboutBlock() {
            var aboutTabItems = So('.r-d-about-tabItem').eq();
            if (!aboutTabItems) {
                return;
            }
            var aboutBlocks = So('.r-d-about-block').eq();
            var aboutBlockNow = 0;
            var moveEnd = true;
            return function () {
                each(aboutTabItems, function (index) {
                    this.onclick = function () {
                        if (aboutBlockNow === index || (!moveEnd)) {
                            return;
                        }
                        else {
                            moveEnd = false;
                            addClass(this, 'active');
                            removeClass(aboutTabItems[aboutBlockNow], 'active');
                            startMove(aboutBlocks[aboutBlockNow], { opacity: 0 }, function () {
                                aboutBlocks[aboutBlockNow].style.display = 'none';
                                aboutBlocks[index].style.display = 'block';
                                startMove(aboutBlocks[index], { opacity: 100 }, function () {
                                    aboutBlockNow = index;
                                    moveEnd = true;
                                })
                            })
                        }
                    }
                })
            }()
        })()


        // 详情页留言版功能实现 : 回复事件全都挂载到留言盒子r-d-comments-listBox上
        function showCommentBook() {
            var commentsListBox = So('.r-d-comments-listBox').eq(1);  //评论列表
            if (!commentsListBox) {
                return;
            }
            var aboutComment = So('.r-d-about-comments').eq(1);   //整个评论容器
            var commentData = { //储存评论的信息
                'type': 'rescom_add',//提交类型
                'rid': So().GetQueryString('resno'), //资源id
                'fid': '0', //评论的父级 0表示自身为一级评论
                'replyid': null,  //回复谁
                'content': '',
                'myname': So('.has-login-link a').eq(1).innerHTML,
                'myid': $('#com_id').val(),
                'myimgurl': $('#com_imgurl').val(),
                'cTime': ''
            };
            aboutComment.onclick = function (e) {

                e = e || window.event;
                var el = e.srcElement || e.target; //触发对象
                console.log(el);
                var commentBookClone = null;    //留言板模板容器
                // 留言版单体模版
                (function cloneCommentBook() {
                    if (!commentBookClone) {
                        return function () {
                            var _smaple = So('#comment-book').eq(1);
                            commentBookClone = _smaple.cloneNode(true);
                            return commentBookClone;
                        }()
                    }
                })()

                //删除原始留言板
                function delCommentBook() {
                    delNodeById('comment-book');
                }

                // 重置留言板 :
                function resetCommentBook() {
                    So('#comments-book-container').eq(1).appendChild(commentBookClone);
                    So('.r-d-comments-textBox').eq(1).value = '';  //清空输入框
                    So('.r-d-comments-wordCount').eq(1).innerHTML = '0';
                }

                //如果是登陆按钮
                if (hasClass(el, 'login-alert-ctrl')) {
                    // console.log(el);
                    showLoginBox();
                    el.click();
                }

                //给相应的回复栏添加一个留言板窗体		
                console.log(el)
                if (hasClass(el, 'comments-reply') || (hasClass(el.parentNode, 'comments-reply'))) {  //如果是回复按钮 ,添加留言本
                    var _gbookParent = findParentByClass(el, 'r-d-comments-content');
                    var _li = _gbookParent.parentNode;
                    delCommentBook();
                    _li.appendChild(commentBookClone);
                    commentData.fid = el.getAttribute('data-fid');
                    commentData.replyto = el.getAttribute('data-replyto');
                    commentData.replyid = el.getAttribute('data-replyid');
                }
                else if (el.id === 'comment-reset') {  //如果为取消按钮
                    delCommentBook();  //删掉现有的
                    resetCommentBook();
                    //清空data数据
                    commentData.fid = '0';
                    commentData.replyto = null;
                    commentData.replyid = null;
                }
                else if (hasClass(el, 'r-d-submit-comments')) { //如果是确定提交按钮
                    if (trim(commentData.content) == '') {
                        Msg('评论内容不能为空!');
                        return;
                    }
                    console.log(commentData);
                    $.ajax({
                        url: '/ajax/submit_ajax.ashx',
                        anysc: true,
                        data: commentData,
                        type: 'POST',
                        cache: false,
                        async: false,
                        dataType: "json",
                        success: function (response) {
                            if (response.flag == "1") {
                                commentData.fid = response.fid;  //如果是一级评论,则需要返回这个值
                                commentData.cTime = getNowTime();
                                writeInComment(el);
                                // 增加评论数目统计
                                var _countComments = So('.r-d-comments-tit span').eq(1);
                                _countComments.innerHTML = parseInt(_countComments.innerHTML) + 1;
                            }
                        }
                    });
                }
            }

            // dom写入评论
            function writeInComment(obj) {

                if (commentData.replyto) {  //如果是子评论 , 那么他必须有一个回复的人
                    var _commentsItem = findParentByClass(obj, 'r-d-comments-item');
                    var _addItem = _commentsItem.getElementsByTagName('ul')[0];
                    if (!_addItem) { //如果还没有附加评论,新创建一个列表
                        var _addCommentList = document.createElement('ul');
                        _addCommentList.className = 'r-d-comments-dddList';
                        _commentsItem.appendChild(_addCommentList);
                        _addItem = _commentsItem.getElementsByTagName('ul')[0];
                    }
                    var _commentsLi = document.createElement('li');
                    _commentsLi.className = 'r-d-comments-addItem';
                    _commentsLi.innerHTML =
                                '<div class="r-d-comments-face">' +
                                    '<div class="r-d-comment-faceContainer">' +
                                        '<img src="' + commentData.myimgurl + '" alt="">' +
                                    '</div>' +
                                '</div>' +
                                '<div class="r-d-comments-content">' +
                                    '<p class="r-d-comments-name">' + commentData.myname + '</p>' +
                                    '<p class="r-d-comments-info">' +
                                        '<a class="r-d-comments-replyTo">@' + commentData.replyto + ':</a>' +
                                        '<span>' + commentData.content + '</span>' +
                                    '</p>' +
                                    '<p class="r-d-comments-timeline">' +
                                        '<span>' + commentData.cTime + '</span>' +
                                        '<a href="javascript:;" class="comments-reply" data-fid="' + commentData.fid + '" data-replyto="' + commentData.myname + '" data-replyid="' + commentData.myid + '">回复</a>' +
                                    '</p>' +
                                '</div>';
                    _addItem.appendChild(_commentsLi);
                    So('#comment-reset').eq(1).click();
                }
                else {  //如果是一级评论
                    var _commentsLi = document.createElement('li');
                    _commentsLi.className = 'r-d-comments-item';
                    _commentsLi.innerHTML =
                                '<div class="r-d-comments-face">' +
                                    '<div class="r-d-comment-faceContainer">' +
                                        '<img src="' + commentData.myimgurl + '" alt="">' +
                                    '</div>' +
                                '</div>' +
                                '<div class="r-d-comments-content">' +
                                    '<p class="r-d-comments-name">' + commentData.myname + '</p>' +
                                    '<p class="r-d-comments-info">' +
                                        '<span>' + commentData.content + '</span>' +
                                    '</p>' +
                                    '<p class="r-d-comments-timeline">' +
                                        '<span>' + commentData.cTime + '</span>' +
                                        '<a href="javascript:;" class="comments-reply" data-fid="' + commentData.fid + '" data-replyto="' + commentData.myname + '" data-replyid="' + commentData.myid + '">回复</a>' +
                                    '</p>' +
                                '</div>';
                    So('.r-d-comments-list').eq(1).appendChild(_commentsLi);
                    So('#comment-reset').eq(1).click();
                }
            }

            // 键盘输入事件
            aboutComment.onkeyup = function (e) {
                e = e || window.event;
                var el = e.srcElement || e.target;
                if (el.tagName == 'TEXTAREA') {  //如果是输入框:监听键盘输入事件
                    So('.r-d-comments-wordCount').eq(1).innerHTML = el.value.length;
                    commentData.content = el.value; // 将输入框的值写入data数据中
                    if (el.value.length >= 1000) {
                        el.value = el.value.substring(0, 1000);
                        So('.r-d-comments-wordCount').eq(1).innerHTML = '40';
                        So('.r-d-comments-wordCount').eq(1).style.color = 'red';
                    }
                    else {
                        So('.r-d-comments-wordCount').eq(1).style.color = '#333';
                    }
                }
            }
        };
        showCommentBook();

        // 资源详情页弹出登陆窗
        function showLoginBox() {
            each(So('.login-alert-ctrl').eq(), function () {
                this.onclick = function () {
                    AlertMes.call(this, {
                        'el': this,
                        'contentEl': '#alert-login',
                        'width': '402',
                        'height': '420',
                        'startTop': -900,
                        'initBindFn': tabLoginType,
                        'fnCallback': function (data) {
                            if (data.confirmResult === 'true') {

                                if (data.username != "" && data.password != "") {
                                    $.ajax({
                                        type: 'POST',
                                        url: "/ajax/ajax_user.ashx?type=user_login&username=" + data.username + "&password=" + data.password,
                                        cache: false,
                                        async: false,
                                        dataType: "json",
                                        success: function (tt) {
                                            if (tt.errCode === '1') {
                                                var loginWrap = So('#v-comW').eq(1);
                                                var loginReg = So('#login-or-reg').eq(1);
                                                loginWrap.className = 'has-login v-comW';
                                                loginReg.className = 'login-or-reg fr has-login';
                                                
                                                So('.has-login-link').html(tt.errMsg);
                                                So('#mask').eq(1).click();
                                            } else if (data.errCode === '0') {
                                                Msg({
                                                    type: 'error',
                                                    msg: tt.errMsg
                                                });
                                            } 
                                        }
                                    });

                                } else {
                                    Msg("请填写用户名和登录密码！");
                                }
                            }
                        }
                    });
                }
            });
        }






        // 详情页侧边推荐切换
        function resouceDetailSug() {
            var sugBox = So('#r-d-sug').eq(1);
            if (!sugBox) {
                return;
            }
            var sugClose = So('#close-sug').eq(1);
            var sugMask = So('#r-d-sug-mask').eq(1);
            var sugTab = So('#r-d-sug-tab').eq(1);
            var sugItemList = So('#r-d-sug-list').eq(1);
            var sugItems = So('.r-d-sug-item').eq();
            var sugTabItems = So('.r-d-sug-tabItem').eq();
            var sugNow = 0;
            var sugLen = sugTabItems.length;
            var sugTimer = null;

            // 侧边栏关闭事件
            bindEvent(sugClose, 'click', function () {
                sugBox.style.display = 'none';
                autoPlaySug(false);
            })

            // 切换函数
            var sugTab = function (next) {
                removeClass(sugTabItems[sugNow], 'active');
                addClass(sugTabItems[next], 'active');
                sugMask.innerHTML = sugItems[next].getAttribute('data-sugTit') || '';
                startMove(sugItemList, { marginLeft: next * -170 }, function () {
                    sugNow = next;
                })
            }

            // 侧边栏点击切换事件
            return (function () {
                // 点击切换
                each(sugTabItems, function (index) {
                    this.onclick = function () {
                        if (sugNow === index) {
                            return;
                        }
                        sugTab(index);
                    }
                })
                // 自动播放
                autoPlaySug(true);
                // 暂停播放
                sugBox.onmouseover = function () {
                    autoPlaySug(false);
                }
                sugBox.onmouseout = function () {
                    autoPlaySug(true);
                }
            })()

            // 定时播放
            function autoPlaySug(playFlag) {
                if (playFlag) {
                    clearInterval(sugTimer);
                    sugTimer = setInterval(function () {
                        var _next = (sugNow + 1) % sugLen;
                        sugTab(_next);
                    }, 3000)
                }
                else {
                    clearInterval(sugTimer);
                    sugTimer = null;
                }
                if (getStyle(sugBox, 'display') === 'none') {
                    clearInterval(sugTimer);
                    sugTimer = null;
                }
            }
        }
        resouceDetailSug();


        /*-------------------------------------------------------------\
            光伏超市搜索城市选择
        \-------------------------------------------------------------*/
        // 根据身份获取城市
        function getCityByProvince(province) {
            var _reg = new RegExp(province);
            for (var i = 0, provinceLen = where.length; i < provinceLen; i++) {
                if (where[i].province.match(_reg)) {
                    // console.log(where[i].city.split('|')) ; //得到匹配的城市
                    return (where[i].city.split('|'));
                }
            }
        }

        // 获取最后一个子节点
        function getLastChild(node) {
            return node.children[parseInt((node.children.length) - 1)];
        }

        var searchFilterCity = {
            aProvinece: So('.search-city-item').eq(), //单个省链接
            aProvineceRow: So('.r-search-other-limitRow').eq(), //单行
            bindCityShow: function (searchCityItem) {  //绑定鼠标移入事件:显示城市
                var _this = this;
                each(searchCityItem, function () {
                    this.onmouseover = function () {
                        _this.clearActive();
                        _this.activeNow(this);
                        _this.writeCity(this, getCityByProvince(this.innerHTML));
                    }
                })
            },
            showCityBox: function (cityBox) {  //显示城市容器 ;移入单个链接即显示
                cityBox.style.display = 'block';
            },
            clearActive: function () {
                each(this.aProvinece, function () {
                    removeClass(this, 'hoverNow');
                });
            },
            activeNow: function (now) {
                addClass(now, 'hoverNow')
            },
            writeCity: function (provnice, citys) { //写入城市
                var _cityBox = getLastChild(provnice.parentNode);
                // var _cityStr = '<a href="/front/rsearch.aspx' + '">全省</a>' ;
                var _cityStr = '';
                _cityBox.children[0].style.opacity = 0;
                each(citys, function () {
                    var key = '?z=' + $("#ContentPlaceHolder1_ziyuan").val() + '&p=' + provnice.innerHTML + '&c=' + this + '';
                    key += '&s=' + $("#ContentPlaceHolder1_status").val() + '&t=' + $("#ContentPlaceHolder1_time").val() + '&po=' + $("#ContentPlaceHolder1_po").val() + '&keyword=' + $("#ContentPlaceHolder1_key").val();
                    _cityStr += '<a href="/front/rsearch.aspx' + key + '">' + this + '</a>';
                })
                _cityBox.children[0].innerHTML = _cityStr;
                this.openBox(_cityBox);
                _cityBox.children[0].style.opacity = 1;
            },
            openBox: function (cityBox) { //展开城市选择项
                var _h = cityBox.children[0].offsetHeight;
                cityBox.style.height = _h + 'px';
            },
            hiddenCityBox: function () { //隐藏城市容器 :移出一整行大容器才隐藏
                var $this = this;
                each(this.aProvineceRow, function () {
                    this.onmouseleave = function () {
                        getLastChild(this).style.height = 0 + 'px';
                        $this.clearActive();
                    }
                })
            },
            run: function () {  //初始化配置,并运行
                var _this = this;
                return (function () {
                    _this.bindCityShow(_this.aProvinece);
                    _this.hiddenCityBox();
                })()
            }
        }
        searchFilterCity.run();

        (function ($) {
            $.QueryString = function (name) {
                var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
                var r = window.location.search.substr(1).match(reg);
                if (r != null) return unescape(r[2]); return null;
            }
        })(jQuery);


        // 显示更多选项
        function tabSeeMoreCity() {
            var seeMoreCity = false; //默认为收起
            return (function () {
                So('#see-more-city').eq(1).onclick = function () {
                    if (!seeMoreCity) { //如果是收起
                        addClass(So('#search-city-limit').eq(1), 'see-limit-more');
                        seeMoreCity = true;
                        this.innerHTML = '收起&raquo;';
                    }
                    else {
                        removeClass(So('#search-city-limit').eq(1), 'see-limit-more')
                        seeMoreCity = false;
                        this.innerHTML = '更多&raquo;';
                    }
                }
            })()
        }

        if (So('#see-more-city').eq(1)) {
            tabSeeMoreCity();
        }


        function provincechange() {
            var pid = $("#province option:selected").val();
            if (pid != "") {
                $.ajax({
                    type: 'POST',
                    url: "http://115.159.29.70:8009/api/GetCity",
                    data: {
                        d: "{\"token\":\"\",\"data\":{\"pid\":\"" + pid + "\"}}"
                    },
                    success: function (data) {
                        if (data.flag == 1) {
                            $("#city").empty();
                            $("#city").prepend("<option value=''>请选择市</option>");
                            $.each(data.data.list, function (i, item) {
                                $("#city").append("<option value='" + item.cityname + "'>" + item.cityname + "</option>");
                            });
                        } else {
                            $("#city").empty();
                            $("#city").prepend("<option value=''>请选择市</option>");
                        }
                    },
                    cache: false,
                    dataType: "json"
                });
            } else {
                $("#city").empty();
                $("#city").prepend("<option value=''>请选择市</option>");
            }
        }

        /*-------------------------------------------------------------\
            个人用户提现
        \-------------------------------------------------------------*/
        // 支付密码框的阻止输入
        var integral = {};
        integral.payPassInput = So('#integral-to-money2-payPass').eq(1);
        if (integral.payPassInput) {
            // 密码字数限制输入
            integral.payPassInput.onkeyup = function (e) {
                // alert(e.keyCode)
                e = e || window.event;
                if (integral.payPassInput.value.length == 6 && (e.keyCode != 8) && (e.keyCode != 9)) {
                    integral.payPassInput.disabled = 'true';
                    // integral.payPassInput.value = integral.payPassInput.value.substring(0,6);
                }
            }

            // 支付密码重新输入
            integral.resetPayPass = So('#reset-payPass').eq(1);
            integral.resetPayPass.onclick = function () {
                integral.payPassInput.value = '';
                integral.payPassInput.removeAttribute('disabled');
                // integral.payPassInput.disabled = 'false' ; 
            }
        }



        //------- 调用删除银行卡弹窗
        each(So('.integral2money-del-bank').eq(), function () {
            this.onclick = function () {
                AlertMes.call(this, {
                    'el': this,
                    'contentEl': '#alert-delBank',
                    'iClass': 'alert-delBank',
                    'width': 436,
                    'height': 250,
                    'animateStyle': 'bounceOut',
                    'initBindFn': limitPassword,
                    'destory': true,
                    'fnCallback': function (data) {  //弹窗关闭后回调
                        console.log(data);
                        if (data.confirmResult === 'true') {
                            alert('确认提交回调!');
                        }
                        else if (data.confirmResult === 'false') {
                            alert('您选择了取消!');
                        }
                    }
                });
            }
        })


        // 点击切换支付宝或者是银联
        function alertBankInit() {
            integral.addBank = So('.integral-bank-add-typeItem').eq();
            if (integral.addBank) {
                each(integral.addBank, function () {
                    this.onclick = function (index) {
                        var _addClass = this.getAttribute('data-show');
                        var _alertAddBank = findParentByClass(this, 'alert-addBank');
                        _alertAddBank.className = 'alert-addBank alert-elect ' + _addClass;
                        getByClass('add-bank-type', null, _alertAddBank)[0].value = _addClass;
                    }
                })
            }
        }
        alertBankInit();

        // 密码长度(6位)超出阻止输入
        function limitPassword(passInputs, resetPassBtns) {
            // 限制密码位数 ,并禁用
            var alimitPass = [];
            each(So('.limit-pass-item').eq(), function (index) {
                var _limitItem = {};
                _limitItem.limitInput = getByClass('limit-input', null, this)[0];
                _limitItem.limitReset = getByClass('limit-reset', null, this)[0];
                alimitPass.push(_limitItem);
            })
            if (alimitPass.length > 0) {
                each(alimitPass, function () {
                    var $this = this;
                    this.limitInput.onkeyup = function (e) {
                        e = e || window.event;
                        if ($this.limitInput.value.length == 6 && (e.keyCode != 8) && (e.keyCode != 9)) {
                            $this.limitInput.disabled = 'true';
                        }
                    }
                    this.limitReset.onclick = function (e) {
                        $this.limitInput.value = '';
                        $this.limitInput.removeAttribute('disabled');
                    }
                })
            }
        }

        //------- 调用添加银行卡弹窗
        //integral.addBankLink = So('#add-bank-link').eq(1);
        //if (integral.addBankLink) {
        //    integral.addBankLink.onclick = function () {
        //        AlertMes.call(this, {
        //            'el': this,
        //            'contentEl': '#alert-addBank',
        //            'iClass': '',
        //            'width': 522,
        //            'height': 624,
        //            'initBindFn': alertBankInit,
        //            'animateStyle': 'bounceOut',
        //            'fnCallback': function (data) {  //弹窗关闭后回调
        //                console.log(data);
        //                if (data.confirmResult === 'true') {
        //                    alert('确认提交回调!');
        //                }
        //                else if (data.confirmResult === 'false') {
        //                    alert('您选择了取消!');
        //                }
        //            }
        //        });
        //    }
        //}




        //立即实名认证弹窗
        each(So('.i-right-now').eq(), function () {
            this.onclick = function () {
                AlertMes.call(this, {
                    'el': this,
                    'contentEl': '#alert_box',
                    'iClass': 'alert_box',
                    'width': 612,
                    'height': 694,
                    'startTop': -900,
                    'initBindFn': function () {
                        Agree();
                        showProtocol();
                    }
                });
            }
        })
        //实名认证成功弹窗
        function Agree() {
            each(So('.a-c-argee').eq(), function () {
                this.onclick = function () {
                    AlertMes.call(this, {
                        'el': this,
                        'contentEl': '#aleart_approve',
                        'iClass': 'aleart_approve',
                        'width': '448',
                        'height': '275',
                        'startTop': -900
                    });
                }
            })
        }



        //提现之前的提示
        function showSubNav(id) {
            document.getElementById(id).style.display = 'block';
        }
        function hideSubNav(id) {
            document.getElementById(id).style.display = 'none';
        }
        //银行卡有效期说明
        //function showvalidity(id) {
        //    document.getElementById(id).style.display = 'block';
        //}
        //function hidevalidity(id) {
        //    document.getElementById(id).style.display = 'none';
        //}


        //预热记录弹窗
        each(So('.y-r-record').eq(), function () {
            this.onclick = function () {
                AlertMes.call(this, {
                    'el': this,
                    'contentEl': '#aleart-involvement',
                    'iClass': 'aleart-involvement',
                    'width': '813',
                    'height': '477',
                    'updateView': function () { //传入标志符,更改视图
                        var _id = this.getAttribute('title')
                        alert(_id);
                    },
                    'startTop': -900
                });
            }
        })



        //用户协议
        function showProtocol() {
            each(So('.u-s-protocol').eq(), function () {
                alert();
                this.onclick = function () {

                    AlertMes.call(this, {
                        'el': this,
                        'contentEl': '#alert-compact',
                        'iClass': 'alert-compact',
                        'width': '480',
                        'height': '350',
                        'startTop': -900
                    });
                }
            })
        }

        function agreement() {
            getElementById('.u-s-protocol').value = 'alert-compact';
        }


        CountDown({
            'timer': So('.timer').eq()
        })


        //鼠标点击事件（竞价结束）
        function showbidend(id) {
            document.getElementById(id).style.display = 'block';
        }
        function hidebidend(id) {
            document.getElementById(id).style.display = 'none'
        }
        //我参与的资源-查看原因弹窗
        each(So('.c-l-cause').eq(), function () {
            this.onclick = function () {
                AlertMes.call(this, {
                    'el': this,
                    'contentEl': '#alert-lookcause',
                    'iClass': 'alert-lookcause',
                    'width': '497',
                    'height': '400',
                    'startTop': -900
                });
            }
        })







        /////////
        // 客户服务 //
        /////////

        var service = {};

        /*展开收起问答*/
        service.aQuestionAsk = So('.question-ask').eq();
        if (service.aQuestionAsk) {
            service.aQuestionItem = So('.question-item').eq();
            service.aQuestionAnswer = So('.question-answer').eq();
            each(service.aQuestionAsk, function (index) {
                this.onclick = function () {
                    var _$this = this;
                    var _thisParentNode = this.parentNode;
                    var _questionCont = getByClass('question-answe-cont', 'div', service.aQuestionItem[index])[0];
                    var _contHeight = _questionCont.offsetHeight;
                    if (!service.aQuestionItem[index].open) {
                        startMove(service.aQuestionAnswer[index], { height: _contHeight }, function () {
                            addClass(service.aQuestionItem[index], 'question-expand');
                            removeClass(service.aQuestionItem[index], 'question-close');
                            service.aQuestionItem[index].open = true;
                        });
                    }
                    else {
                        startMove(service.aQuestionAnswer[index], { height: 0 }, function () {
                            addClass(service.aQuestionItem[index], 'question-close');
                            removeClass(service.aQuestionItem[index], 'question-expand');
                            service.aQuestionItem[index].open = false;
                        });
                    }
                }
            })
        }




        function tabLoginType() {
            each(So('.normal-login-select').eq(), function () {
                this.onclick = function () {
                    each(So('.alert-login-box').eq(), function () {
                        addClass(this, 'login-box-normal');
                        removeClass(this, 'login-box-fast');
                    })
                }
            })
            each(So('.fast-login-select').eq(), function () {
                this.onclick = function () {
                    each(So('.alert-login-box').eq(), function () {
                        addClass(this, 'login-box-fast');
                        removeClass(this, 'login-box-normal');
                    })
                }
            })
        }

        //企业委托书弹窗
        each(So('.what-firm-attorney').eq(), function () {
            this.onclick = function () {
                AlertMes.call(this, {
                    'el': this,
                    'contentEl': '#aleart-letter',
                    'iClass': 'aleart-letter',
                    'width': '685',
                    'height': '800',
                    'startTop': -900
                });
            }
        })
        //升级为企业-审核中弹窗
        each(So('.e-t-addmit').eq(), function () {
            this.onclick = function () {
                AlertMes.call(this, {
                    'el': this,
                    'contentEl': '#alert-wait',
                    'iClass': 'alert-wait',
                    'width': '450',
                    'height': '300',
                    'startTop': -900
                });
            }
        })
        //审核不通过弹窗 
        each(So('.no-pass').eq(), function () {
            this.onclick = function () {
                AlertMes.call(this, {
                    'el': this,
                    'contentEl': '#alert-nopass',
                    'iClass': 'alert-nopass',
                    'width': '450',
                    'height': '300',
                    'startTop': -900
                });
            }
        })


        //用法1 :完整版
        //Msg({
        //    type : 'error',
        //    title : '标题标题',
        //    msg : '这是一段内容' ,
        //    cancelBtn : true ,
        //    dataId : '12062',
        //    // autoOff : 5,
        //    // notMask : true
        //})
        //.sure(function(){
        //    window.location.href = '#';
        //    console.log(this);
        //})
        //.cancel(function(){
        //    // console.log(this);
        //    console.log('你选择了取消')
        //})
        //.autoFn(function(){
        //    alert('自执行完毕!');
        //})


        //用法2 
        // Msg('hello world')
        // .sure(function(){
        //     console.log('你选择了确认') ;
        // })
        // .cancel(function(){
        //     console.log('你选择了取消') ;
        // })


        // 底部宽度根据基础宽度来调整
        var is1200 = So('.comeW').eq();
        if (is1200.length != 0) {
            addClass(So('#footer-wrap').eq(1), 'comeW');
        }
        else {
            addClass(So('#footer-wrap').eq(1), 'h-comW');
        }


        // 导航条的当前状态
        var myData = matchUrl({
            'aLink': So('.h-nav-link').eq(),
            'replaceUrlData': [
        '/front/rsearch.aspx|/front/rdetailHot.aspx|commissions',
        '/accout/rsearch.aspx|/accout/rDetailhot.aspx|commissions',
        '/service/pvNews.aspx|/service/pvNewsDetail.aspx'
            ]
        });


        // 合作企业轮播
        (function coopComTab() {

            var tabList = So('#coop-company-list').eq(1);
            if (!tabList) {
                return;
            }
            var tabBtn, aTabItem, iMoveW, iNow, iItemLen;
            iNow = 0;
            aTabItem = tabList.getElementsByTagName('li');
            iItemLen = aTabItem.length;
            console.log(iItemLen);
            if (iItemLen < 2) {
                return;
            }

            iMoveW = aTabItem[1].offsetLeft - aTabItem[0].offsetLeft;
            tabList.style.width = iItemLen * iMoveW + 200 + "px";
            tabBtn = So('.coop-ctrl-btn').eq();

            //上一个
            tabBtn[0].onclick = function () {
                next = (iNow - 1 + iItemLen) % iItemLen;
                _move(next);
            }
            //下一个
            tabBtn[1].onclick = function () {
                next = (iNow + 1) % iItemLen;
                _move(next);
            }

            function _move(next) {
                startMove(tabList, { marginLeft: -iMoveW * next }, function () {
                    console.log('运动完成');
                    iNow = next;
                })
            }
        })()

        // 随机选择hot图标
        function setHotIcon() {
            var aHotIconLen = 5 ;
            var _randomIndex = Math.floor(aHotIconLen * Math.random());
            var _backgroundStr = '/images/x/icon/hot/hot-' + _randomIndex + '.gif';
            each(So('.hot-icon').elements, function () {
                this.style.backgroundImage = 'url(' + _backgroundStr + ')';
            })
        }
        setHotIcon(); 

        // 随机选择new图标
        function setNewIcon() {

        }

        //function iconSet() {

        //}



        // 最外层判断是否加载的后括号
    }
}





