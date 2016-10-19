
// 总览 :
// 1 .判断浏览器版本 . alert(window.sys.ie) 或 alert(window.sys.chrome);
// 2 . dom加载完毕执行 addDomLoaded(fn) ;
// 3 . 事件绑定 addEvent(obj,type,fn) → addEvent(window,"resize",function(){do something});
// 4 . 事件解除 removeEvent(obj,type,fn) → 用法同上
// 5 . 阻止默认事件  preDef(e) →  在以前习惯加return false的地方加这个就行
// 6 . 获取屏幕尺寸 getClient().w → 宽 getClient().h → 高
// 7 . 获取非行间样式 getStyle(obj,attr) → getStyle(body,"height") 返回值都是带单位的
// 8 . 判断是否已经存在某个特定的class hasClass(element,className)
// 9 . each 函数替代for each(arr,fn) → 用的 call 方法 ,如果fn中括号内带个参, 那么就是index当前索引


/**********************************__________ DOM自身事件,属性相关 __________***************************/
//浏览器判断 
//调用方法 : alert(window.sys.ie) → 弹出ie为ie几
(function() {
    window.sys = {}; //外部可访问
    var ua = navigator.userAgent.toLowerCase();
    var s; // 浏览器信息数组
    //三元选择
    (s = ua.match(/msie ([\d.]+)/)) ? sys.ie = s[1]:
        (s = ua.match(/firefox\/([\d.]+)/)) ? sys.firefox = s[1] :
        (s = ua.match(/chrome\/([\d.]+)/)) ? sys.chrome = s[1] :
        (s = ua.match(/opera\/.*version\/([\d.]+)/)) ? sys.opera = s[1] :
        (s = ua.match(/version\/([\d.]+).*safari/)) ? sys.safari = s[1] : 0;

    if (/webkit/.test(ua)) sys.webkit = ua.match(/webkit\/([\d]+)/)[1]; //webkit内核

    //平台、设备和操作系统 
    sys.win = false ;  
    sys.mac = false ;  
    sys.xll = false ;  
    sys.ipad = false ;  

    //检测平台 
    sys.pt = navigator.platform; 
    sys.win = sys.pt.indexOf("Win") == 0; 
    sys.mac = sys.pt.indexOf("Mac") == 0; 
    sys.x11 = (sys.pt == "X11") || (sys.pt.indexOf("Linux") == 0); 
    sys.ipad = (navigator.userAgent.match(/iPad/i) != null)?true:false; 
    //跳转语句，如果是手机访问就自动跳转到wap.baidu.com页面 
    if (sys.win || sys.mac || sys.xll||sys.ipad) { 
       sys.mobie = false ;  //非手机
    } else { 
        sys.mobie = true ;  //手机
    } 

})()



// 添加火狐的鼠标事件滚轮 : 不支持document和window ,这两者用addEvent("window",DOMMouseScroll,fn);
if (navigator.userAgent.toLowerCase().indexOf("firefox") >= 0) {
    addEventListener("DOMMouseScroll",
        function(e) {
            var obj = e.target;
            var onmousewheel;
            while (obj) {
                onmousewheel = obj.getAttribute("onmousewheel") || obj.onmousewheel;
                if (onmousewheel) break;
                if (obj.tagName == "BODY") break;
                obj = obj.parentNode;
            };
            if (onmousewheel) {
                if (e.preventDefault) e.preventDefault();
                e.returnValue = false;
                if (typeof obj.onmousewheel != "function") {
                    eval("window._tmpFun = function(event){" + onmousewheel + "}");
                    obj.onmousewheel = window._tmpFun;
                    window._tmpFun = null;
                };
                setTimeout(function() {
                    obj.onmousewheel(e);
                }, 1);
            };
        }, false);
}

// 是否向下滚动滚轮
function isScrollDown(e) {
    e = e || window.event;
    var down = true;
    if (e.wheelDelta) {
        down = (e.wheelDelta < 0) ? true : false;
    } else {
        down = (e.detail > 0) ? true : false;
    }
    return down;
}

//加载 (当dom加载完后立即执行) 
//调用方法 : addDomLoaded(fn) →  加载完立即执行括号内的函数(window.onload的替代版本)
function addDomLoaded(fn) {
    var timer = null;
    //兼容旧版本
    if ((sys.opera && sys.opera < 9) || (sys.firefox && sys.firefox < 3) || sys.webkit < 525) {
        timer = setInterval(function() {
            if (document && document.getElementById && document.getElementsByTagName && document.body) {
                clearInterval(timer);
                fn()
                    // doReay();
            }
        }, 1)
    } else if (document.addEventListener) {
        addEvent(document, "DOMContentLoaded", function() {
            // doReay();
            fn();
            removeEvent(document, "DOMContentLoaded", arguments.callee);
        })
    } else if (sys.ie && sys.ie < 9) {
        timer = setInterval(function() {
            try {
                document.documentElement.doScroll("left");
                clearInterval(timer);
                fn();
                // doReay();
            } catch (e) {
                //do something
            }
        }, 1)
    }
}

/**
 * 获取当前地址栏地址,改变导航条当前状态
 * @param  {string} url       导航条地址
 * @param  {arr} arr       对应的a链接数组 
 * @param  {string} actClass  切换当前项时特定class , 比如active(可选,默认为active)
 * @param  {string} prevClass 之前的class(可选,默认为空)
 * @param  {element} parNode   传入要修改状态的数组(可选,默认为a)
 * @return {null}           不返回
 */
function checkUrl(url, arr, actClass, parNode, addChangeClass, paraddClass) {
    var array_sj = ['?', '&', '=', '.'];
    //    each(array_sj, function (index) {
    //        console.log(this);
    //        url = url.replace(this, '');
    //    })
    for (var i = 0; i < array_sj.length; i++) {
        url = url.replace(array_sj[i], '');
    }
    url = "^" + url + "$";
    var reg = new RegExp(url);
    window.active = 0;
    // 初始化数据
    actClass = (actClass) ? actClass : "active"; //若未传值 , 就默认为active ;
    parNode = (parNode) ? parNode : arr; //若未传值 , 就默认为是改变a的显示样式状态 ;
    each(parNode, function(index) {
        removeClass(this, actClass);
    })
       
    each(parNode, function(index) {

        var _urlStr = parNode[index].href.toLowerCase();
        var j = 0;
        for (j = 0; j < array_sj.length; j++) {
            _urlStr = _urlStr.replace(array_sj[j], '');
        }
        j = null;
        if (reg.test(_urlStr) && window.active<1) {
            
            addClass(parNode[index], actClass);
            // 如果还要改变它特定class的父级
            if (addChangeClass) {
                var _addChangeNode = findParentByClass(parNode[index], addChangeClass);
                if (_addChangeNode) { //如果找到其对应class的父级,就给它加个class.
                    addClass(_addChangeNode, paraddClass);
                }
            }
            window.active++;
        }
    })
}


// 匹配地址栏,且加上一个当前高亮状态
/**
 * [matchUrl description]
 * @param  {[type]} obj 配置对象:包括匹配替换的数组,需要切换状态的元素,当前状态添加的class,
 * @return {[type]}     [description]
 */
function matchUrl(obj){

    // 地址栏地址参数
    var urlData = {
        'host' : window.location.host, //主机:如localhost:8080(包括端口号)
        'pathname' : window.location.pathname, //去主机地址:如/admin/login
        'protocol' : window.location.protocol ,//协议:http
        'port' : window.location.port , // 端口号 8080 
        'search' : window.location.search , // ?id =1 & p = 1 
        'hash' :window.location.hash,  // 锚点 #comment
        'nativeUrl' : window.location.host+window.location.pathname,
        'params' : null 
    }

    // 地址参数获取
    if(urlData.search){
        urlData.search = urlData.search.substr(1);
        var _searchArr = urlData.search.split('&');
        var _searchData = {} ;
        for(var i = 0 ,_alen = _searchArr.length;i<_alen;i++){
            var _split = _searchArr[i].indexOf('=');
            _searchData[_searchArr[i].substring(0,_split)] = _searchArr[i].substring(_split+1);
        }
        urlData.params = _searchData;
    }

    //默认为实际地址
    var urlStr = (obj.checkAll)?window.location.href.toLowerCase():urlData.nativeUrl.toLowerCase(); 
    //console.log(urlStr);

    //如果存在地址伪装替换
    if(obj.replaceUrlData){ 
        var _replaceItem ;
        if(urlData.pathname=='/'){ //如果是主域名
            urlData.pathname = '/index.aspx';
        }
        var _urlReplaceReg = new RegExp(urlData.pathname.toLowerCase());
        for(var i = 0 , _replaceLen = obj.replaceUrlData.length;i<_replaceLen;i++ ){
            if(_urlReplaceReg.test(obj.replaceUrlData[i].toLowerCase())){
                //console.log('抓到一只');
                _replaceItem = obj.replaceUrlData[i].split('|');
                urlStr = window.location.host+_replaceItem[0].toLowerCase();
            }
        }
        //console.log(_replaceItem);
    }

    //主链接与地址匹配
    if(obj.aLink){ // 需要匹配url的地址项
        var _urlReg ;
        obj.linkActiveClass = obj.linkActiveClass || 'active'; //如果传入特殊需附加class:默认为active
        var _activeEl = obj.parentEl || obj.aLink;  //需要匹配的链接的元素
        var _linkLen = obj.aLink.length ;  //需要匹配的链接的元素的长度
        if(obj.checkStatu && obj.checkStatu.match(urlData.params)){ //如果是查状态码且地址栏确实传入了状态码时
            _urlReg = new RegExp(urlData.params[obj.checkStatu]) ;
        }
        else{
            _urlReg = new RegExp(urlStr); // 用来匹配地址?号前面还是后面
        }
        // 如果是匹配状态码:比如obh.checkStatu = 'id' ; 
        for(var i = 0 ;i<_linkLen;i++){
            var _matchHref = obj.aLink[i].href.toLowerCase() ;
            if(_urlReg.test(_matchHref)){
                if(obj.linkActiveClass||(!obj.linkActiveClass&&!obj.parentActiveClass)){  //如果需要改变链接自身状态
                    addClass(obj.aLink[i],obj.linkActiveClass);
                }
                if(obj.parentActiveClass){  //如果需要父级当前状态
                    addClass(obj.parentEl[i],obj.parentActiveClass);
                }
                break;
            }
        }
    }
    return urlData ;
}


//在最前面appendChild
function prependChild(parent, newChild) {
    if (parent.firstChild) {
        parent.insertBefore(newChild, parent.firstChild);
    } else {
        parent.appendChild(newChild);
    }

    return parent;
}

//事件绑定 (好奇怪 ,IE8以下不兼容)
function addEvent(obj, type, fn) {
    if (obj.addEventListener) { //非IE
        obj.addEventListener(type, fn, false);
    } else {
        obj.attachEvent("on" + type, function() { //兼容IE
            fn.call(obj);
        })
    }
}
//事件绑定
function bindEvent(obj, event, fnCallback) {
    if (obj.addEventListener) { //非IE
        obj.addEventListener(event, fnCallback, false);
    } else {
        obj.attachEvent("on" + event, function() { //兼容IE
            fnCallback.call(obj);
        })
    }
};
//解除事件绑定
function removeBind(obj, type, fn) {
    if (obj.removeEventListener) {
        obj.removeEventListener(type, fn, false);
    } else {
        obj.detachEvent('on' + type, function() {
            fn.call(obj);
        });
    }
};


/*
 *删除元素的指定className
 */
function removeClass(obj, sClass) {
    if(!obj){
        return ; 
    }
    var aClass = obj.className.split(' ');
    if (!obj.className) return;
    for (var i = 0; i < aClass.length; i++) {
        if (aClass[i] == sClass) {
            aClass.splice(i, 1);
            obj.className = aClass.join(' ');
        };
    };
};
/*
 *给元素添加指定的className
 */
function addClass(obj, sClass) {
    if(!obj){
        return ; 
    }
    var aClass = obj.className.split(' ');
    if (!obj.className) {
        obj.className = sClass;
        return;
    }
    for (var i = 0; i < aClass.length; i++) {
        if (aClass[i] == sClass) return;
    }
    obj.className += ' ' + sClass;
};

//判断是否已经绑定存在相同函数
function addEventEqual(es, fn) {
    for (var i in es) {
        if (es[i] == fn) {
            return true;
        }
    }
    return false;
}


// //事件解除
function removeEvent(obj, type, fn) {
    if (obj.removeEventListener) {
        obj.removeEventListener(type, fn, false);
    } else {
        var _arr = [];
        if(obj.events){
            for (var i = 0, len = obj.events[type].length; i < len; i++) {
                if (obj.events[type][i] != fn) { //剔除掉那个要移除的
                    _arr.push(obj.events[type][i]);
                }
            }
            obj.events[type] = _arr;
        }
    }
}

//阻止冒泡
function stopPro(e) {
    e = e || window.event;
    if (e.stopPropagation) { //除IE 1- 8 外
        e.stopPropagation();
    } else {
        e.cancelBubble = true; //兼容IE 1-8
    }
};

//阻止默认事件
function preDef(e) {
    e = e || window.event;
    if (e.preventDefault) {
        e.preventDefault();
    } else {
        e.returnValue = false;
    }
}

//模拟hover事件 , 兼容IE不支持a以外的hover .
//obj 为添加hover的数组或单个元素 , over为进入事件 ,out为出去事件.
function hover(obj, fnOver, fnOut) {
    each(obj, function(index) {
        this.onmouseover = function() {
            fnOver.call(this, index);
        }
        this.onmouseout = function() {
            fnOut.call(this, index);
        }
    })
}

// enter 事件
function enter(obj, fnEnter, fnLeave) {
    each(obj, function(index) {
        this.onmouseenter = function() {
            fnEnter.call(this, index);
        }
        this.onmouseleave = function() {
            fnLeave.call(this, index);
        }
    })
}

//模拟active函数
function active(arr, fnDown, fnUp) {
    if (!arr.length) {
        var _arr = [];
        _arr.push(arr);
        arr = _arr;
    }
    for (var i = 0; i < arr.length; i++) {
        arr[i].onmousedown = (function(a) {
            return function() {
                fnDown.call(arr[a], a);
            }
        })(i)
        arr[i].onmouseup = (function(a) {
            return function() {
                fnUp.call(arr[a], a);
            }
        })(i)
    }
}

// 加入收藏
/** 
 * 
 * @param {} sURL 收藏链接地址 
 * @param {} sTitle 收藏标题 
 */
function AddFavorite(sURL, sTitle) {
    try {
        window.external.addFavorite(sURL, sTitle);
    } catch (e) {
        try {
            window.sidebar.addPanel(sTitle, sURL, "");
        } catch (e) {
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}
/** 
 * 
 * @param {} obj 当前对象，一般是使用this引用。 
 * @param {} vrl 主页URL 
 */
function SetHome(obj, vrl) {
    try {
        obj.style.behavior = 'url(#default#homepage)';
        obj.setHomePage(vrl);
    } catch (e) {
        if (window.netscape) {
            try {
                netscape.security.PrivilegeManager
                    .enablePrivilege("UniversalXPConnect");
            } catch (e) {
                alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
            }
            var prefs = Components.classes['@mozilla.org/preferences-service;1']
                .getService(Components.interfaces.nsIPrefBranch);
            prefs.setCharPref('browser.startup.homepage', vrl);
        }
    }
}

/**************************************__________ 基础函数 __________*************************************/
// id选择器
function getById(id) {
    return document.getElementById(id);
}
// class选择器
function getByClass(sClass, sTagName, pNode) {
    pNode = (pNode) ? pNode : document;
    var allChildNode = [];
    if (pNode.getElementsByClassName) {
        allChildNode = pNode.getElementsByClassName(sClass);
    } else {
        sTagName = (sTagName) ? sTagName : "*"; // 若没有传入标签
        var _all = pNode.getElementsByTagName(sTagName);
        for (var i = 0, len = _all.length; i < len; i++) {
            var allClass = _all[i].className.split(/\s+/); //若单个元素有多重标签
            for (var j = 0; j < allClass.length; j++) {
                if (allClass[j] == sClass) {
                    allChildNode.push(_all[i]);
                }
            }
        }
    }
    return allChildNode;
}
// tagName 选择器
function getByTag(tag, pNode) {
    pNode = (pNode) ? pNode : document; //如果未传入上级 ,则默认为document ;
    var tags = pNode.getElementsByTagName(tag);
    return tags;
}
// find查找模拟
function find(pNode, str) {
    var childElements = [];
    var _this = this;
    each(this.elements, function() {
        switch (str.charAt(0)) {
            case "#":
                return getById(str.substring(1));
                break;
            case ".":
                return getByClass(str.substring(1), null, obj);
                break;
            default:
                return getByTag(str, pNode);
                break;
        }
    })
}

//获取innerText 内部文本
function getText(obj) {
    return (obj.textContent) ? obj.textContent : obj.innerText;
}

//设置text
function setText(obj, str) {
    if (obj.textContent) {
        obj.textContent = str;
    } else {
        obj.innerText = str;
    }
}

//删除左右空格
function trim(str) {
    return str.replace(/(^\s*)|(\s*$)/g, "");
}

//each函数
function each(arr, fn, isJson) {
    if (!fn || (arr.length == 0)) {
        return;
    }
    if (arr.length) { // 如果有长度 , 那么这是一个数组,用for循环
        for (var i = 0, len = arr.length; i < len; i++) {
            if (arr[i].length && typeof(arr[i])!='string') { //若为多维数组 ;
                each(arr[i], fn);
            } else {
                fn.call(arr[i], i);
            }
        }
    } else {
        if (isJson) {
            this.i || (this.i = 0);
            for (this.i in arr) {
                fn.call(arr[this.i], i)
            }
        } else { //如果不是数组 ,又不是json , 也误用了each方法,  那就先把这单个元素转换为长度为1的数组.
            var _arr = [];
            _arr.push(arr);
            arr = _arr;
            each(arr, fn);
        }
    }
}

// 单个元素转数组

//图片是否完全加载
function isImgload(arr, fn) {
    //单张图片
    var count = 0;
    var singleLoad = false;
    var size = {};
    if (typeof arr == "string") {
        var _img = new Image();
        _img.src = arr;
        if (_img.height == 0) {
            setTimeout(function() {
                isImgload(arr, fn);
            }, 300)
        } else {
            singleLoad = true;
            size = {
                w: _img.width,
                h: _img.height
            }
        }
    } else if (typeof arr == "object") { //一组图片
        for (var i = 0; i < arr.length; i++) {
            var _img = new Image();
            _img.src = arr[i].src;
            if (_img.height == 0) {
                setTimeout(function() {
                    isImgload(arr, fn);
                }, 300)
                return false;
            } else {
                count++;
            }
        }
    }
    if (count == arr.length - 1 || singleLoad) {
        allLoad = true;
        if (fn) {
            fn(size);
        }
        return;
    }
}

//判断class是否存在
function hasClass(element, className) {
    var reg = new RegExp("(\\s+|^)" + className + "(\\s+|$)");
    try{
         return element.className.match(reg);
    }catch(e){

    }
    // if(element.className){
    //      console.log(element.className);
       
    // }
}

//获取元素在页面的绝对位置:(一直向上累加position值)
function getPos(obj) {
    var iLeft = 0;
    var iTop = 0;
    while (obj) {
        iLeft += obj.offsetLeft;
        iTop += obj.offsetTop;
        obj = obj.offsetParent;
    }
    return {
        l: iLeft,
        t: iTop
    }
}


//获取时间:
function getNowTime() {
    var t = new Date();
    var y = t.getFullYear();
    var m = t.getMonth() + 1;
    var d = t.getDate();
    var h = t.getHours();
    var mi = t.getMinutes();
    var s = t.getSeconds();

    function toZero(num) {
        num = (num < 10) ? "0" + num : "" + num;
        return num;
    }

    var time = toZero(y) + "-" + toZero(m) + "-" + toZero(d) + " " + toZero(h) + ":" + toZero(mi) + ":" + toZero(s);
    return time;
}

/**
 * 获取鼠标进入方向
 * @param  {object} obj 移入的物体
 * @param  {e} e   事件event
 * @return {num}     0为上 , 顺时针累加到3循环
 */
function getDir(obj, e) {
    var W = obj.offsetWidth;
    var H = obj.offsetHeight;
    var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    var a = (e.clientX - getPos(obj).l - (W / 2)) * (W > H ? (H / W) : 1);
    var b = (e.clientY - getPos(obj).t + scrollTop - (H / 2)) * (H > W ? (W / H) : 1);
    return Math.round((((Math.atan2(b, a) * (180 / Math.PI)) + 180) / 90) + 3) % 4
}

// 获取滚动条距顶距离
function getScrollTop(){
    return {
      t : document.documentElement.scrollTop || document.body.scrollTop 
    } 
}
//英语月份转换]
function MonthToNum(txt) {
    var num = 0;
    switch (txt) {
        case "Jan":
            num = 1;
            return "0" + num;
            break;
        case "Feb":
            num = 2;
            return "0" + num;
            break;
        case "Mar":
            num = 3;
            return "0" + num;
            break;
        case "Apr":
            num = 4;
            return "0" + num;
            break;
        case "May":
            num = 5;
            return "0" + num;
            break;
        case "Jun":
            num = 6;
            return "0" + num;
            break;
        case "Jul":
            num = 7;
            return "0" + num;
            break;
        case "Aug":
            num = 8;
            return "0" + num;
            break;
        case "Sep":
            num = 9;
            return "0" + num;
            break;
        case "Oct":
            num = 10;
            return num;
            break;
        case "Nov":
            num = 11;
            return num;
            break;
        case "Dec":
            num = 12;
            return num;
            break;
    }
}


function setCenter(obj, json, fn, parentNode) {
    var selfW = (json) ? json.width : parseInt(getStyle(obj, "width"));
    var selfH = (json) ? json.height : parseInt(getStyle(obj, "height"));
    var wW = (parentNode) ? parentNode.css("width") : getClient().w;
    var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    var wH = (parentNode) ? parentNode.css("height") : getClient().h;
    var l = Math.round((wW - selfW) / 2);
    var t = Math.round((wH - selfH) / 2 + scrollTop);
    obj.l = l;
    obj.t = t
    tStartMove(obj, {
        width: selfW,
        height: selfH,
        left: l,
        top: t
    }, function() {
        if (fn) fn();
    }, "backIn");
}



/**************************************__________ CSS自身相关 __________*************************************/
//获取屏幕尺寸
function getClient() {
    var wW, wH;
    if (window.innerWidth) { //火狐
        wW = window.innerWidth;
        wH = window.innerHeight;
    } else {
        wW = document.documentElement.clientWidth || document.body.clientWidth;
        wH = document.documentElement.clientHeight || document.body.clientHeight;
    }
    return {
        w: wW,
        h: wH
    }
}

//获取非行间样式
function getStyle(obj, attr) {
    if (obj.currentStyle) {
        return obj.currentStyle[attr];
    } else {
        return getComputedStyle(obj, false)[attr];
    }
}

//3D特效Css样式兼容设置: 如:(setCss(obj,{$Transform:"rotateY(60deg)"}))
function setCss(obj, jAttr) {
    var arr = ["Webkit", "Moz", "O", "Ms", ""];
    for (var attr in jAttr) {
        if (attr.charAt(0) == "$") {
            for (var i = 0; i < arr.length; i++) {
                if (arr[i] === "") {
                    attr = attr.toLowerCase();
                }
                obj.style[arr[i] + attr.substring(1)] = jAttr[attr];
            }
        } else {
            if (attr == "opacity") {
                obj.style[attr] = jAttr[attr];
                obj.style.filter = "opacity:(" + jAttr[attr] + ")";
            } else {
                obj.style[attr] = jAttr[attr] + "px";
            }
        }
    }
}

//跨浏览器添加规则
function addRule(sheet, selectorText, cssText, position) {
    if (sheet.insertRule) {
        sheet.insertRule(selectorText + "{" + cssText + "}", position);
    } else {
        sheet.addRule(selectorText, cssText, position);
    }
}

//跨浏览器删除规则
function removeRule(sheet, position) {
    if (sheet.deleteRule) {
        sheet.deleteRule(position);
    } else {
        sheet.removeRule(position);
    }
}

/**************************************__________ Move运动相关 __________*************************************/
//运动函数
function startMove(obj, json, fn) {
    clearInterval(obj.timer);
    obj.timer = setInterval(function() {
        var allFinished = true;
        for (var attr in json) {
            var cur = 0;
            if (attr == "opacity") {
                cur = Math.round(parseFloat(getStyle(obj, attr)) * 100) || 0;
            } else {
                cur = parseInt(getStyle(obj, attr)) || 0;
            }

            if (cur != json[attr]) { //此判断必须在循环里面
                allFinished = false;
                var speed = (json[attr] - cur) / 5;
                speed = (speed > 0) ? Math.ceil(speed) : Math.floor(speed);

                if (attr == 'opacity') {
                    obj.style[attr] = (cur + speed) / 100;
                    obj.style.filter = "alpha(opacity:" + (cur + speed) + ")";
                } else {
                    obj.style[attr] = cur + speed + "px";
                }
            }
        }
        if (allFinished) {
            clearInterval(obj.timer);
            if (fn) {
                fn();
            }
        }
    }, 30)
}

//传入初始值下的运动(并不直接改变,而是传出一个值,模拟transition)
function transMove(obj, startJson, endJson, fn, fncallback, fx, time) {

    time = (time) ? time : 400;
    fx = (fx) ? fx : "linear";

    clearInterval(obj.timer);
    var startTime = now(); //存储初始运动时间
    obj.timer = setInterval(function() {
        var nowJson = {};
        var changeTime = now(); //已用时间, 标示着 ,此时应该已经必须要有多大的结果了. 速度为10 ,已用时间为1,现在该走10Mi了.
        var scale = 1 - Math.max(0, startTime - changeTime + time) / time;
        for (var attr in startJson) {
            nowJson[attr] = Tween[fx](scale * time, startJson[attr], endJson[attr] - startJson[attr], time);
            //传出value的值
            fn.call(obj, nowJson);
            if (scale == 1) {
                clearInterval(obj.timer);
                if (fncallback) {
                    fncallback();
                }
            }
        }
    }, 13)

    function now() {
        return new Date().getTime();
    }
}

//缓冲函数 配合Tween :
function tStartMove(obj, json, time, fx, fn) {
    var timeFlag = false;
    var fxFlag = false;
    var fnFlag = false;
    var _time, _fx, _fn;
    var _aPara = {
        time: time,
        fx: fx,
        fn: fn
    };
    for (var para in _aPara) {
        if (typeof _aPara[para] == "number") {
            timeFlag = true;
            _time = _aPara[para];
        }
        if (typeof _aPara[para] == "function") {
            fnFlag = true;
            _fn = _aPara[para];
        }
        if (typeof _aPara[para] == "string") {
            fxFlag = true;
            _fx = _aPara[para];
        }
    }
    fn = (fnFlag) ? _fn : undefined;
    fx = (fxFlag) ? _fx : "linear";
    time = (timeFlag) ? _time : 400;


    var iCur = {}; //存储初始值 ,即起点 .

    for (var attr in json) {
        if (attr == "opacity") {
            iCur[attr] = Math.round(parseFloat(getStyle(obj, attr)) * 100) || 0;
        } else {
            iCur[attr] = parseInt(getStyle(obj, attr)) || 0; //如果行间不存在这个属性 ,那么它返回的将是一个auto,parseInt后变成NAN 即返回0;
        }
    }

    clearInterval(obj.timer);
    var startTime = now(); //存储初始运动时间
    obj.timer = setInterval(function() {
        var changeTime = now(); //已用时间, 标示着 ,此时应该已经必须要有多大的结果了. 速度为10 ,已用时间为1,现在该走10Mi了.
        var scale = 1 - Math.max(0, startTime - changeTime + time) / time;
        for (var attr in json) {
            var value = Tween[fx](scale * time, iCur[attr], json[attr] - iCur[attr], time);
            if (attr == "oapcity") {
                obj.style[attr] = value;
                obj.style.filter = "alpha(opacity:" + value + ")";
            } else {
                obj.style[attr] = value + "px";
            }
            if (scale == 1) {
                clearInterval(obj.timer);
                if (fn) {
                    fn();
                }
            }
        }
    }, 13)

    function now() {
        return new Date().getTime();
    }
}


//arg1:作用数组对象,作用函数,作用方向(可选),时间间隔(可选),回调函数(可选);
function sortMove(colEl, fn, dir, interval, fnCallback) {
    var _timer = null;
    dir = (dir > 0 || (!dir)) ? 1 : -1;
    var count = 0;
    var len = colEl.length;
    var now = (dir > 0) ? 0 : len - 1; //开始位置 从前或者从后开始

    interval = (interval) ? interval : 100;
    _timer = setInterval(function() {
        if (count == len) {
            clearInterval(_timer);
            if (fnCallback) {
                fnCallback();
            }
        } else {
            fn.call(colEl[now], count);
            now += dir;
            count++;
        }
    }, interval)
}

//Tween : 缓动函数核心算法
var Tween = {
    linear: function(t, b, c, d) { //匀速
        return c * t / d + b;
    },
    easeIn: function(t, b, c, d) { //加速曲线
        return c * (t /= d) * t + b;
    },
    easeOut: function(t, b, c, d) { //减速曲线
        return -c * (t /= d) * (t - 2) + b;
    },
    easeBoth: function(t, b, c, d) { //加速减速曲线
        if ((t /= d / 2) < 1) {
            return c / 2 * t * t + b;
        }
        return -c / 2 * ((--t) * (t - 2) - 1) + b;
    },
    easeInStrong: function(t, b, c, d) { //加加速曲线
        return c * (t /= d) * t * t * t + b;
    },
    easeOutStrong: function(t, b, c, d) { //减减速曲线
        return -c * ((t = t / d - 1) * t * t * t - 1) + b;
    },
    easeBothStrong: function(t, b, c, d) { //加加速减减速曲线
        if ((t /= d / 2) < 1) {
            return c / 2 * t * t * t * t + b;
        }
        return -c / 2 * ((t -= 2) * t * t * t - 2) + b;
    },
    elasticIn: function(t, b, c, d, a, p) { //正弦衰减曲线（弹动渐入）
        if (t === 0) {
            return b;
        }
        if ((t /= d) == 1) {
            return b + c;
        }
        if (!p) {
            p = d * 0.3;
        }
        if (!a || a < Math.abs(c)) {
            a = c;
            var s = p / 4;
        } else {
            var s = p / (2 * Math.PI) * Math.asin(c / a);
        }
        return -(a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * (2 * Math.PI) / p)) + b;
    },
    elasticOut: function(t, b, c, d, a, p) { //正弦增强曲线（弹动渐出）
        if (t === 0) {
            return b;
        }
        if ((t /= d) == 1) {
            return b + c;
        }
        if (!p) {
            p = d * 0.3;
        }
        if (!a || a < Math.abs(c)) {
            a = c;
            var s = p / 4;
        } else {
            var s = p / (2 * Math.PI) * Math.asin(c / a);
        }
        return a * Math.pow(2, -10 * t) * Math.sin((t * d - s) * (2 * Math.PI) / p) + c + b;
    },
    elasticBoth: function(t, b, c, d, a, p) {
        if (t === 0) {
            return b;
        }
        if ((t /= d / 2) == 2) {
            return b + c;
        }
        if (!p) {
            p = d * (0.3 * 1.5);
        }
        if (!a || a < Math.abs(c)) {
            a = c;
            var s = p / 4;
        } else {
            var s = p / (2 * Math.PI) * Math.asin(c / a);
        }
        if (t < 1) {
            return -0.5 * (a * Math.pow(2, 10 * (t -= 1)) *
                Math.sin((t * d - s) * (2 * Math.PI) / p)) + b;
        }
        return a * Math.pow(2, -10 * (t -= 1)) *
            Math.sin((t * d - s) * (2 * Math.PI) / p) * 0.5 + c + b;
    },
    backIn: function(t, b, c, d, s) { //回退加速（回退渐入）
        if (typeof s == 'undefined') {
            s = 1.70158;
        }
        return c * (t /= d) * t * ((s + 1) * t - s) + b;
    },
    backOut: function(t, b, c, d, s) {
        if (typeof s == 'undefined') {
            s = 3.70158; //回缩的距离
        }
        return c * ((t = t / d - 1) * t * ((s + 1) * t + s) + 1) + b;
    },
    backBoth: function(t, b, c, d, s) {
        if (typeof s == 'undefined') {
            s = 1.70158;
        }
        if ((t /= d / 2) < 1) {
            return c / 2 * (t * t * (((s *= (1.525)) + 1) * t - s)) + b;
        }
        return c / 2 * ((t -= 2) * t * (((s *= (1.525)) + 1) * t + s) + 2) + b;
    },
    bounceIn: function(t, b, c, d) { //弹球减振（弹球渐出）
        return c - Tween['bounceOut'](d - t, 0, c, d) + b;
    },
    bounceOut: function(t, b, c, d) {
        if ((t /= d) < (1 / 2.75)) {
            return c * (7.5625 * t * t) + b;
        } else if (t < (2 / 2.75)) {
            return c * (7.5625 * (t -= (1.5 / 2.75)) * t + 0.75) + b;
        } else if (t < (2.5 / 2.75)) {
            return c * (7.5625 * (t -= (2.25 / 2.75)) * t + 0.9375) + b;
        }
        return c * (7.5625 * (t -= (2.625 / 2.75)) * t + 0.984375) + b;
    },
    bounceBoth: function(t, b, c, d) {
        if (t < d / 2) {
            return Tween['bounceIn'](t * 2, 0, c, d) * 0.5 + b;
        }
        return Tween['bounceOut'](t * 2 - d, 0, c, d) * 0.5 + c * 0.5 + b;
    }
};


/**************************************__________ 封装功能小组件 __________*************************************/

//拖拽 (窗口,触发对象,方向,控制目标,运动范围,回调函数)
/**
 * 拖拽
 * @param  {object} obj       作用对象
 * @param  {object} dragAble  特定区域可移动
 * @param  {num:0 ,1 ,2} dir       方向,默认为2 即两个方向, 1 为竖直方向 , 0 表示水平方向
 * @param  {object} target    拖动某一物体影响另一物体
 * @param  {json} rangeJson 运动范围,默认为可视窗口
 * @param  {function} fnMove    根据拖动块移动范围 , 回调一个函数 ,传入移动的距离
 * @return {null}           null .
 */
function drag(obj, dragAble, dir, target, rangeJson, fnMove) {
    if (target) { //如果有拖动鼠标改变对象,根据目标比例调整拖动块的大小
        var long = target.offsetHeight;
        var short = target.parentNode.offsetHeight;
        var scale = short / long;
        obj.style.height = obj.parentNode.offsetHeight * scale + "px";
    }
    if (dir != 0 && dir != 1) {
        var dir = 2;
    }
    if (!rangeJson) {
        var rangeJson = {
            t: 0,
            r: obj.parentNode.offsetWidth - parseInt(getStyle(obj, "width")),
            b: obj.parentNode.offsetHeight - parseInt(getStyle(obj, "height")),
            l: 0
        }
    }
    dragAble = (dragAble) ? dragAble : obj; //如果不存在则默认为整个可拖.
    dragAble.onmousedown = function(e) {
        e = e || window.event;
        var disX = e.clientX - obj.offsetLeft;
        var disY = e.clientY - obj.offsetTop;
        if (dragAble.setCapture) { // 阻止选中其他物体
            obj.setCapture();
        }
        document.onmousemove = function(e) {
            e = e || window.event;

            var iL = e.clientX - disX;
            var iT = e.clientY - disY;

            //限定范围
            if (iT <= rangeJson.t) {
                iT = rangeJson.t;
            }
            if (iT >= rangeJson.b) {
                iT = rangeJson.b;
            }
            if (iL <= rangeJson.l) {
                iL = rangeJson.l;
            }
            if (iL >= rangeJson.r) {
                iL = rangeJson.r;
            }

            //限定拖动方向
            if (dir == 1) { //1表示只能上下拖动
                obj.style.top = iT + "px";
                if (target) {
                    target.style.top = -(iT / rangeJson.b) * (long - short) + "px";
                }
            } else if (dir == 0) { //0表示只需要左右拖动
                obj.style.left = iL + "px";
                if (fnMove) {
                    fnMove(iL);
                }
            } else { //两个方向都可以拖动
                obj.style.top = iT + "px";
                obj.style.left = iL + "px";
            }
        }

        document.onmouseup = function() {
            document.onmousemove = null;
            document.onmouseup = null;
            if (dragAble.releaseCapture) {
                obj.releaseCapture();
            }
        }
        if (e.preventDefault) {
            e.preventDefault();
        }
    }
}

/**
 * 鼠标滚轮控制放大缩小
 * @param  {object} ableObj 作用区域,缩放框架
 * @param  {object} obj     放大缩小实体
 * @param  {num} scaleW  单次滚轮缩放的宽度
 * @return {null}         null
 */
function mouseScale(ableObj, obj, scaleW) {
    scaleW = (scaleW) ? scaleW : 180;
    ableObj.onmousewheel = function(e) {
        e = e || window.event;
        var _prevW = obj.offsetWidth;
        var _prevH = obj.offsetHeight;
        var _scaleH = (_prevH / _prevW) * scaleW;
        var _prevL = parseInt(getStyle(obj, "left"));
        var _prevT = parseInt(getStyle(obj, "top"));
        if (isScrollDown(e)) {
            tStartMove(obj, {
                width: _prevW - scaleW,
                height: _prevH - _scaleH,
                left: _prevL + scaleW / 2,
                top: _prevT + _scaleH / 2
            }, 200)
        } else {
            tStartMove(obj, {
                width: _prevW + scaleW,
                height: _prevH + _scaleH,
                left: _prevL - scaleW / 2,
                top: _prevT - _scaleH / 2
            }, 200)
        }
        preDef(e);
    }
}

// 
// 封装插件 : banner图无缝带导航切换滚动 
// 只需要传进来一个banner窗口就行了 ,可多个 .
// html结构如下: 要点 , banner_window 为容器窗口 ,图片必须是想要展示的图片+2 ; 
// <div class="banner">
//     <div class="bannerWindow">
//         <ul class="bannerList">
//             <li><a href="#"><img src="images/f2_b1_b.jpg" alt=""></a></li>
//             ...
//         </ul>
//     </div>
// </div>
// 
// css结构如下:
// .banner{overflow: hidden;position: relative;}
// .banner li{float: left;}
// .banner-nav{width: 100%;height: 16px;position: absolute;bottom: 10px;text-align: center;}
// .banner-nav-item{ width: 16px; height: 16px;border-radius: 50%;background: gray;display: inline-block;
//     margin-right: 10px;
// }
// .banner-nav .active,
// .banner-nav .banner-nav-item:hover{
//     background: #Fc0;
// }
// .banner-btn{ width: 40px;height: 40px;position: absolute;top:50%;margin-top: -25px;background: #000;
//     cursor: pointer;
// }
// .banner-btn-prev{
//     left:10px;
// }
// .banner-btn-next{
//     right:10px;
// }

//调用
// 函数调用
// var aBanner = So(".banner").elements;
// banner(aBanner,"backIn");
/**
 * 无缝banner滚动
 * @param  {arr}   aBanner 需要添加banner切换效果的组合
 * @param  {string}   tween   缓动形式 可选
 * @param  {num}   time    定时器时间 ,可选
 * @param  {Function} fn      回调函数,可加点修饰,暂时未添加定义
 * @return {null}           null
 */
function banner(aBanner, tween, time, fn) {
    // 初始化
    // 样式初始化:
    each(aBanner, function(index) {
        this.style.position = "relative";
        this.oList = getByTag("ul", this)[0];
    })

    // 克隆第一个和最后一个
    each(aBanner, function(index) {
        // 对象的形式 ,将各元素及属性都绑定在banner对象上
        this.oList = getByTag("ul", this)[0];
        this.aLi = this.oList.children;
        this.len = this.aLi.length;

        // js生成两个备选项图片,为无缝滚动铺垫
        var _firstClone = this.aLi[this.len - 1].cloneNode(true);
        var _lastClone = this.aLi[0].cloneNode(true);
        prependChild(this.oList, _firstClone);
        this.oList.appendChild(_lastClone);
    })

    // DOM结构重排,根据子元素的多少 , 生成ul的宽度 , 使其子元素能够浮动时再同一行显示, 同时生成导航点.并绑定事件
    each(aBanner, function(index) {

        this.lastLen = this.aLi.length; // js添加项后最终li数组的长度 
        this.singleW = parseInt(this.offsetWidth);
        this.oList.style.width = this.lastLen * (this.singleW) + "px";

        this.nav = document.createElement("div");
        this.nav.className = "banner-nav";

        // 生成控制点
        this.navStr = "";
        for (var i = 0; i < this.lastLen - 2; i++) {
            this.navStr += "<a href='javascript:;' class='banner-nav-item'></a>";
        }
        this.nav.innerHTML = this.navStr;
        this.appendChild(this.nav);

        // 生成左右按钮
        this.btnPrev = document.createElement("span");
        this.btnPrev.className = "banner-btn-prev banner-btn";
        this.btnNext = document.createElement("span");
        this.btnNext.className = "banner-btn-next banner-btn";

        this.appendChild(this.btnPrev);
        this.appendChild(this.btnNext);

        // 运动初始化
        this.allMove = true;
        var This = this;
        this.navItem = this.nav.children;
        this.now = 1;
        this.timer = null;
        this.oList.style.marginLeft = -this.singleW + "px";
        this.navItem[0].className += " active";

        // 给每个控制绑定加事件
        bindEvent(this.btnPrev, "click", function() {
            if (This.allMove == false) {
                return;
            }
            var _prevNow = This.now;
            var _next = --This.now;
            bannerMove(This, _next);
            changeNav(This.navItem, _prevNow, _next);
        })
        bindEvent(this.btnNext, "click", function() {
            if (This.allMove == false) {
                return;
            }
            var _prevNow = This.now;
            var _next = ++This.now;
            bannerMove(This, _next);
            changeNav(This.navItem, _prevNow, _next);
        })

        // 底部控制点控制切换
        each(this.navItem, function(index) {
            this.onmouseover = function() {
                bannerMove(This, index + 1);
                changeNav(This.navItem, This.now, index + 1);
                This.now = index + 1;
            }
        })

        // 定时器开启自动播放
        autoMove(This);

    })

    // 主要切换函数
    function bannerMove(obj, next) {
        obj.allMove = false;
        tStartMove(obj.oList, {
            marginLeft: -(obj.singleW * next)
        }, function() {
            if (next == obj.lastLen - 1) {
                obj.oList.style.marginLeft = -obj.singleW + "px";
                obj.now = 1;
            } else if (next == 0) {
                obj.oList.style.marginLeft = -(obj.lastLen - 2) * obj.singleW + "px";
                obj.now = obj.lastLen - 2;
            }
            obj.allMove = true;
        }, tween);
    }

    // 给下面导航点添加事件
    function changeNav(aNavItem, prev, now) {
        removeClass(aNavItem[prev - 1], "active");
        if (now == 0) {
            now = aNavItem.length;
        } else if (now == aNavItem.length + 1) {
            now = 1;
        }
        addClass(aNavItem[now - 1], "active");
    }

    // hover事件和离开事件优化
    hover(aBanner, function() {
        clearInterval(this.timer);
    }, function() {
        autoMove(this);
    })

    // 定时函数
    function autoMove(obj) {
        clearInterval(obj.timer);
        var nextBtn = getByClass("banner-btn-next ", "span", obj);
        time = (time) ? time : 3000;
        obj.timer = setInterval(function() {
            var _prevNow = obj.now;
            var _next = ++obj.now;
            bannerMove(obj, _next);
            changeNav(obj.navItem, _prevNow, _next);
        }, time);
    }
}


/**
 * lightBox展示 , 主要一个遮罩层,一个图片弹出层
 * @param  {arr} lightBoxImg 要展示的图片数组
 * @return {null}             null
 */

//主要css模板
// #lightBox{position:absolute;top:-2000px;left:200px;width:400px;height:400px;background:white url(http://socool.7vi.cc/DEMO/shows/6/images/loading.gif) no-repeat center center;z-index:999;border:10px solid white;}
// #lightImg{width:100%;height:100%;position:relative;}

// #imgDesc{width:100%;height:50px;position:absolute;bottom:0px;left:0px;z-index:120;}
// #imgDescBg{width:100%;height:100%;background:black;position:absolute;bottom:0px;left:0px;opacity:0.5;filter:alpha(opacity:50);z-index:90;}

// #imgDescContent{width:100%;height:100%;text-align:center;line-height:50px;color:white;z-index:99;position:absolute;left:0;top:0;}
// #imgIndex{position:absolute;width:150px;height:100%;line-height:50px;left:0;top:0;color:white;z-index:99;text-indent:20px;}
// .tabBtn{height:100%;width:50px;position:absolute;z-index:110;background-repeat:no-repeat;top:0;background-position:center center;cursor:pointer;opacity:0;filter:alpha(opacity:0);
// transition:all 200ms;}
// .tabBtn:hover{opacity:1;filter:alpha(opacity:100)}
// .prevImg{background-image:url(http://socool.7vi.cc/DEMO/shows/6/images/prev.png);left:0;}
// .nextImg{background-image:url(http://socool.7vi.cc/DEMO/shows/6/images/next.png);right:0;}
// #lightBoxClose{width:50px;height:50px;background:url(http://socool.7vi.cc/DEMO/shows/6/images/close2.png) center center no-repeat;position:absolute;right:0;top:0;z-index:99;cursor:pointer;}

function lightBox(lightBoxImg) {
    // 创建遮罩层和lightbox元素
    createMask();
    createLightBox();

    // 生成半透明遮罩层
    function createMask() {
        var iMask = document.createElement("div");
        iMask.id = "mask";
        with(iMask.style) {
            position = "absolute";
            left = 0;
            top = 0;
            width = "100%";
            height = "100%";
            background = "#000";
            opacity = "0.5";
            filter = "alpha(opacity:50)";
            zIndex = 99;
            display = "none";
        }
        var iBody = So("body").elements[0];
        iBody.appendChild(iMask);
    }

    // 生成lightbox
    function createLightBox() {
        var iLightBox = document.createElement("div");
        iLightBox.id = 'lightBox';
        iLightBox.innerHTML = '<img  alt="" id="lightImg">' +
            '<div id="imgDesc">' +
            '<div id="imgDescBg">' +

            '</div>' +
            '<div id="imgDescContent">' +

            '</div>' +
            '<div id="imgIndex">' +
            '<span id="imgNow">1</span>' +
            '<span id="allImg">/ 12</span>' +
            '</div> ' +
            '<div id="lightBoxClose">' +

           

            '</div>' +
            '</div>' +

           

            '<span class="prevImg tabBtn" id="prevImg"></span>' +
            '<span class="nextImg tabBtn" id="nextImg"></span>';
        iLightBox.style.zIndex = 999;
        iLightBox.style.display = "none";
        var iBody = So("body").elements[0];
        iBody.appendChild(iLightBox);
    }

    //数据初始化
    var lightBox = So("#lightBox").eq();
    var aLightImgLen = lightBoxImg.length || 1;
    var mask = So("#mask").eq();
    var lightImg = So("#lightImg").eq();
    var imgDescContent = So("#imgDescContent").eq();
    var lightBoxClose = So("#lightBoxClose").eq();
    var imgNow = So("#imgNow").eq();
    var allImg = So("#allImg").eq();
    var prevImg = So("#prevImg").eq();
    var nextImg = So("#nextImg").eq();
    lightBox.now = 0;

    allImg.innerHTML = " / " + aLightImgLen;

    // 点击相应图片 ,弹出lightbox和遮罩层
    each(lightBoxImg, function(index) {
        this.onclick = function() {
            showLightBox(this, this.src, index); //将自身的图片地址传进去
        }
    })

    // lightbox展示
    function showLightBox(obj, imgSrc, now) {

        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        mask.style.top = parseInt(scrollTop) + "px";
        lightBox.style.left = getPos(obj).l + "px";
        lightBox.style.top = getPos(obj).t + "px";
        lightBox.style.width = obj.offsetWidth + "px";
        lightBox.style.height = obj.offsetHeight + "px";
        lightImg.src = imgSrc;
        lightBox.style.opacity = 1;
        lightBox.style.filter = "alpha(opacity:100)";

        mask.style.display = "block";
        lightBox.style.display = "block";

        imgNow.innerHTML = now + 1;
        lightBox.now = now;


        window.lock = true;
        var bigPicSrc = imgSrc;
        isImgload(bigPicSrc, function(size) {
            if (size.h > getClient().h * 0.8) { //如果尺寸过大
                var _h = size.h;
                size.h = getClient().h * 0.8;
                size.w = size.w * (size.h / _h);
            }
            setCenter(lightBox, {
                width: size.w,
                height: size.h
            }, function() {
                lightImg.src = bigPicSrc;
                imgDescContent.innerHTML = obj.alt;
            })
        })
        bindEvent(document, "keydown", lightBoxKeyTab);
    }

    //上一张下一张切换 
    prevImg.onclick = function() {
        lightBox.now = (lightBox.now - 1 + aLightImgLen) % aLightImgLen;
        lightBoxTab(lightBoxImg[lightBox.now]);
    }
    nextImg.onclick = function() {
        lightBox.now = (lightBox.now + 1) % aLightImgLen;

        lightBoxTab(lightBoxImg[lightBox.now]);
    }

    //图片切换
    function lightBoxTab(obj) {
        if (aLightImgLen == 1) return;
        startMove(lightImg, {
            opacity: 0
        }, function() {
            var nextImgSrc = obj.src;
            imgNow.innerHTML = lightBox.now + 1;
            isImgload(nextImgSrc, function(size) {
                if (size.h > getClient().h * 0.8) { //如果尺寸过大
                    var _h = size.h;
                    size.h = getClient().h * 0.8;
                    size.w = size.w * (size.h / _h);
                }
                setCenter(lightBox, {
                    width: size.w,
                    height: size.h
                }, function() {
                    lightImg.src = nextImgSrc;
                    lightImg.style.opacity = 1;
                    lightImg.style.filter = "alpha(opacity:100)";
                    imgDescContent.innerHTML = obj.alt;
                })
            })
        })
    }

    // 键盘切换
    function lightBoxKeyTab(e) {
        e = e || window.event;
        if (e.keyCode == 39) { //小键盘的右箭头
            nextImg.onclick();
        } else if (e.keyCode == 37) { //小键盘的左箭头; 
            prevImg.onclick();
        } else if (e.keyCode == 27) { //ESC键退出lightBox ; 
            lightBoxClose.onclick();
        } else if (e.keyCode == 38 || e.keyCode == 40) { //小键盘的上下箭头,阻止默认事件
            preDef(e);
        }
    }

    // 点击遮罩或者关闭按钮,隐藏遮罩层和弹出层
    lightBoxClose.onclick = mask.onclick = function() {
        mask.style.display = "none";
        window.lock = false;
        startMove(lightBox, {
            opacity: 0
        }, function() {
            lightBox.style.display = "none";
            lightImg.src = "";
        })
        removeBind(document, "keydown", lightBoxKeyTab);
    }

    // 有遮罩层 , 滚轮失效 .
    document.onmousewheel = function(e) {
        if (window.lock) {
            preDef(e);
        }
    }
}

/**
 * 扩大镜
 * @param  {arr} imgBox 需要添加扩大镜效果的图片盒子
 * @param  {json} size   json数组,放扩大镜大小(可选)
 * @return {null}        null
 */
// 调用
// var imgBox = So(".imgBox").eq();
// magnifier(imgBox);
// 
// HTML结构
// <div class="imgBox">
//     <img src="http://i1.tietuku.com/20f7d9a6a04e2609.jpg" alt="小图片" class="prev-img">
//     <div class="scaleMask">   //可省
//     </div>
// </div>   
// 
// CSS结构
// .imgBox{
//     overflow: hidden;
//     position: relative;
// }
// .scaleMask{
//     position: absolute;
// }
// 
function magnifier(imgBox, size) {
    createScaleArea();
    bindShowScale();
    //生成一个扩大的区域
    function createScaleArea(size) {
        if (!size) {
            size = {};
            size.w = 600;
            size.h = 400;
        }

        var scaleArea = document.createElement("div");
        scaleArea.innerHTML = '<img src="" alt="" id="scale-big-img">';
        scaleArea.id = "scaleArea";
        scaleArea.style.width = size.w + "px";
        scaleArea.style.height = size.h + "px";

        with(scaleArea.style) {
            position = "absolute";
            background = "#FFF";
            boxShadow = "0 0 3px #000";
            overflow = "hidden";
            visibility = "hidden";
        }
        var oBody = So("body").eq();
        oBody.appendChild(scaleArea);
    }

    var iScaleArea = So("#scaleArea").eq();
    var scaleImg = So("#scale-big-img").eq();
    scaleImg.style.position = "absolute";

    //绑定小图片触发事件
    function bindShowScale() {
        each(imgBox, function() {
            hover(this, function(e) {
                showScale(this, e);
            }, function() {
                hidScale(this);
            })
        })
    }

    // 扩大镜实现主原理
    function showScale(obj, e) {
        e = e || event;
        var _img = obj.getElementsByTagName("img")[0];
        var _mask = getByClass("scaleMask", null, obj)[0];
        if (!_mask) {
            var addMask = document.createElement("div");
            addMask.className = "scaleMask";
            obj.appendChild(addMask);
            _mask = getByClass("scaleMask", null, obj)[0];
        }
        iScaleArea.style.visibility = "visible";
        _mask.style.display = "block";
        _mask.style.left = e.clientX - getPos(obj).l - (_mask.offsetWidth / 2) + "px";
        _mask.style.top = e.clientY - getPos(obj).t - (_mask.offsetHeight / 2) + "px";

        iScaleArea.style.left = getPos(obj).l + obj.offsetWidth + 20 + "px";
        iScaleArea.style.top = getPos(obj).t + "px";
        scaleImg.src = _img.src;

        // 触发同步扩大事件
        followScale(obj, _mask, _img);
    }

    var scaleMove = null;

    // 同步扩大函数
    function followScale(obj, mask, img) {
        var iScaleX, iScaleY;
        isImgload(img.src, function(size) {
            iScaleX = size.w / img.offsetWidth;
            iScaleY = size.h / img.offsetHeight;
        })

        bindEvent(document, "mousemove", scaleMove);
        scaleMove = function(e) {
            e = e || window.event;
            if (mask.offsetWidth != 0) {
                var iT = document.documentElement.scrollTop || document.body.scrollTop;
                var maskL = e.clientX - getPos(obj).l - (mask.offsetWidth / 2);
                var maskT = e.clientY + iT - getPos(obj).t - (mask.offsetHeight / 2);
                if (maskL <= 0) {
                    maskL = 0;
                } else if (maskL >= obj.offsetWidth - mask.offsetWidth) {
                    maskL = obj.offsetWidth - mask.offsetWidth;
                }
                if (maskT < 0) { //注意此处不能和判断左右边界一起, 不能用else if 判断.
                    maskT = 0;
                } else if (maskT >= obj.offsetHeight - mask.offsetHeight) {
                    maskT = obj.offsetHeight - mask.offsetHeight;
                }
                mask.style.left = maskL + "px";
                mask.style.top = maskT + "px";

                scaleImg.style.left = -iScaleX * maskL + "px";
                scaleImg.style.top = -iScaleX * maskT + "px";
            }
        }
    }

    // 隐藏放大镜
    function hidScale(obj) {
        var _mask = getByClass("scaleMask", null, obj)[0];
        iScaleArea.style.visibility = "hidden";
        _mask.style.display = "none";
        removeEvent(document, "mousemove", scaleMove);
    }

}


/**************************************__________ 服务器数据相关 __________*************************************/

//ajax
function ajax(obj) {
    //1 . 创建xhr请求对象
    var xhr = new XMLHttpRequest() || new ActiveXObject("Microsoft XMLHttp");
    //2.1 处理传递过来的数据,拼接url
    var _value = [];
    for (var attr in obj.data) {
        _value.push(attr + "=" + obj.data[attr]);
    }
    var realUrl = obj.url + "?" + _value.join("&");
    //2 . open一个请求
    xhr.open(obj.method, realUrl, obj.anysc);
    //3 . 发送请求
    xhr.send(null);
    //4 . 判断并回调
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                if (obj.success) {
                    obj.success(xhr.responseText); //成功回调函数 
                }
            } else {
                if (obj.failed) {
                    obj.failed();
                }
                // it's wrong ,do something !
            }
        }
    }
}




// 正则验证表达式
var checkReg = {
    "isEmail": /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/, //邮箱格式
    "isName": /[\u4e00-\u9fa5_a-zA-Z0-9_]{8,20}/, //名字格式规范(中文,字母 ,下划线,4-20位)
    "isPhone": /^(^0\d{2}-?\d{8}$)|(^0\d{3}-?\d{7}$)|(^0\d2-?\d{8}$)|(^0\d3-?\d{7}$)$/,
    "isIP": /^((([1-9]\d?)|(1\d{2})|(2[0-4]\d)|(25[0-5]))\.){3}(([1-9]\d?)|(1\d{2})|(2[0-4]\d)|(25[0-5]))$/, // 是否为固定电话
    "isIdCard": /^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/, // 是否为身份证号码
    "isMobile": /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/, // 是否为手机号码
    "isPassWord": /\w[a-zA-Z0-9_]{8,20}/, //密码格式规范(中文,字母 ,下划线,4-20位)
    "isCode": /\w[a-zA-Z0-9]{3}/
}

// 表单验证主要调用函数
/**
 * 表单验证
 * @param  {arr} inputData →所有需要验证的输入框数组
 * @param  {json} jsonObj   →需要验证表达式判断的输入
 * @param  {button} submit   → 提交按钮,点击进行全局检测
 * @param  {function} fnSuc    → 成功执行的函数
 * @param  {function} fnFail    →失败执行的函数
 * @param  {function} showError → 边检测边执行时检测为错误时调用函数(可选)
 * @return {[type]}           [description]
 */

// 其中json 示范 :
// {
//             isName : userName ,      // 前面不变(对应的是正则调用用到的名称), 后面是对应意义的ID
//            isEmail : email,        //邮件
//             isCard : card,         //身份证号
//           isMobile : mobile,       //手机号码
//            isPhone : phone,        //座机
//         isPassWord : passWord,    //密码
//         repeatWord : repeatWord,  //重复密码
//                arr : elements          //***仅这项特殊一点 ,后面输入的是一个输入框对象数组 ,即只需验证不为空的选项.且arr不能变.

/* 函数调用示范
formCheck(
    aNeedCheck,
    {
        isName : userName,     
       isEmail : email,       
      isIdCard : card,        
      isMobile : mobiePhone,      
       isPhone : phone,       
    isPassWord : passWord,    
    repeatWord : repeatPass,
        isCode : imgCode,
           arr : aIsNotNull  
    },
    send,   //确认提交发送按钮
    isOk,   // 成功验证回调函数 → 提交服务器
    isWrong, // 提示结果错误
    showError //单个错误提示 .
)

// 提交回调函数
function isOk(){
    alert("ok,提交成功");
}

// 提交回调函数
function isWrong(){
    alert("请填写完整信息!!")
}

// 错误提示函数
function showError(obj){
    var _Error = getByClass("errorTips",null,obj.parentNode)[0];
    // var _Error = obj.parentNode.getElementsByClassName("errorTips")[0];
    if(obj.isTrue){
        _Error.style.visibility = "hidden";
    }
    else{
        _Error.style.visibility = "visible";
    }
}
 */

function formCheck(inputData, jsonObj, submit, fnSuc, fnFail, showError) {
    for (var attr in jsonObj) {
        if (attr == "arr") {
            for (var i = 0, arrLen = jsonObj["arr"].length; i < arrLen; i++) {
                jsonObj["arr"][i].onblur = (function(a) {
                    return function() {
                        if (jsonObj["arr"][a].value == "") {
                            jsonObj["arr"][a].isTrue = false;
                        } else if (jsonObj["arr"][a].value != "") {
                            jsonObj["arr"][a].isTrue = true;
                            // it's error, do something 
                        }
                        // 显示检验结果
                        if (showError) {
                            showError(this);
                        }
                    }
                })(i)
            }
        } else if (attr == "repeatWord") {
            //密码确认函数
            jsonObj[attr].onblur = function() {
                if (checkReg["isPassWord"].test(jsonObj["repeatWord"].value) && (jsonObj["repeatWord"].value == jsonObj["isPassWord"].value)) {
                    jsonObj["repeatWord"].isTrue = true;
                } else {
                    jsonObj["repeatWord"].isTrue = false;
                }
                if (showError) {
                    showError(jsonObj["repeatWord"]);
                }
            }
        } else {
            jsonObj[attr].onblur = (function(str) {
                return function() {
                    if (checkReg[str].test(this.value)) {
                        jsonObj[str].isTrue = true;
                    } else {
                        jsonObj[str].isTrue = false;
                    }
                    // 显示检验结果
                    if (showError) {
                        showError(this);
                    }
                }
            })(attr)
        }
    }

    // 提交确认 , 验证所有,确认是否提交.
    submit.onclick = function() {
        var trueNum = 0;
        for (var i = 0, len = inputData.length; i < len; i++) {
            if (inputData[i].isTrue != true) {
                // if(fnFail){
                //  fnFail();
                // }
                // return ;  //一旦出现错误, 返回.
                if (showError) {
                    showError(inputData[i]);
                }

            } else if (inputData[i].isTrue == true) {
                trueNum++;
            }
        }
        if (trueNum == inputData.length) {
            // 初步验证正确完毕, 可提交服务器进一步验证
            if (fnSuc) {
                fnSuc();
            }
        } else {
            // 初步验证失败, do something !!
            if (fnFail) {
                fnFail();
            }
        }
    }
}

/**************************************__________ 简化版面向对象 __________*************************************/
// 选择与执行函数 : 
//前台调用
function So(args) {
    return new Base(args);
}

//基类
function Base(args) {
    this.elements = [];
    var _this = this;
    if (typeof args == "string") {
        var elements = trim(args).split(/\s+/);
        if (elements.length > 1) {
            var parentNode = [document]; // 默认document为父节点
            var childNode = [] //储存获取的节点
            for (var i = 0; i < elements.length; i++) {
                switch (elements[i].charAt(0)) {
                    case "#":
                        parentNode = [];
                        childNode = [];
                        childNode.push(_this.getId(elements[i].substring(1)));
                        parentNode = childNode;
                        break;
                    case ".":
                        childNode = [];
                        for (var j = 0; j < parentNode.length; j++) {
                            var temps = this.getClass(elements[i].substring(1), null, parentNode[j]);
                            for (var k = 0; k < temps.length; k++) {
                                childNode.push(temps[k]);
                            }
                        }
                        parentNode = childNode;
                        break;
                    default:
                        childNode = [];

                        for (var j = 0; j < parentNode.length; j++) {
                            var temps = this.getTag(elements[i], parentNode[j]);
                            for (var k = 0; k < temps.length; k++) {
                                childNode.push(temps[k]);
                            }
                        }
                        parentNode = childNode;
                        break;
                }
            }

            this.elements = childNode;
        } else {
            //find模拟
            switch (args.charAt(0)) {
                case "#":
                    this.elements.push(this.getId(args.substring(1)));
                    break;
                case ".":
                    each(this.getClass(args.substring(1)), function() {
                        _this.elements.push(this);
                    })
                    break;
                default:
                    each(this.getTag(args), function() {
                        _this.elements.push(this);
                    })
                    break;
            }
        }
    } else if (typeof args == "object") {
        if (args != undefine) {
            this.elements[0] = args;
        }
    } else if (typeof args == "function") {
        this.ready(args);
    }
}

// 原型方法
Base.prototype = {
    //就绪
    ready: function(args) {
        addDomLoaded(args);
    },
    //选择函数的find函数
    find: function(str) {
        var childElements = [];
        var _this = this;

        each(this.elements, function() {
            switch (str.charAt(0)) {
                case "#":
                    childElements.push(_this.getId(str.substring(1)));
                    break;
                case ".":
                    each(_this.getClass(str.substring(1), null, this), function() {
                        childElements.push(this);
                    })
                    break;
                default:
                    each(_this.getTag(str, this), function() {
                        childElements.push(this);
                    })
                    break;
            }
        })

        this.elements = childElements;
        return this;
    },

    //获取元素
    getId: function(id) {
        return document.getElementById(id);
    },

    getTag: function(tag, pNode) {
        pNode = (pNode) ? pNode : document; //如果未传入上级 ,则默认为document ;
        var tags = pNode.getElementsByTagName(tag);
        return tags;
    },

    writeCookie: function (obj, expiresT) {
        for (var attr in obj) {
            // if(key&&value){
            var _writeCookie = attr + '=' + obj[attr];
            if (expiresT) {  // 分钟
                var date = new Date();
                date.setTime(date.getTime() + expiresT * 60 * 1000);
                _writeCookie += (';expires=' + date.toGMTString());
            }
            document.cookie = _writeCookie;
            // }
        }
    },
    getCookie: function (cookieName) {
        if (!cookieName) {
            return null;
        }
        if (document.cookie.indexOf(cookieName) !== -1) {
            var cs = document.cookie.split(';'),
    			i = 0,
    			len = cs.length;
            for (; i < len; i++) {
                if (trim(cs[i].split('=')[0]) === cookieName) {
                    return cs[i].split('=')[1];
                }
            }
        }
        return null;
    },

    getClass: function(sClass, sTagName, pNode) {

        pNode = (pNode) ? pNode : document;
        var allChildNode = [];
        if (pNode.getElementsByClassName) {
            allChildNode = pNode.getElementsByClassName(sClass);
        } else {
            sTagName = (sTagName) ? sTagName : "*"; // 若没有传入标签
            var _all = document.getElementsByTagName(sTagName);
            for (var i = 0, len = _all.length; i < len; i++) {
                var allClass = _all[i].className.split(/\s+/); //若单个元素有多重标签
                for (var j = 0; j < allClass.length; j++) {
                    if (allClass[j] == sClass) {
                        allChildNode.push(_all[i]);
                    }
                }
            }
        }
        return allChildNode;
    },

    css: function(attr, value) {

        if (typeof value === "number" && attr != "opacity") {
            value = value + "px";
        }
        for (var i = 0, len = this.elements.length; i < len; i++) {
            if (arguments.length == 1) {
                if (this.elements[i].currentStyle) {
                    return this.elements[i].currentStyle[attr];
                } else {
                    return getComputedStyle(this.elements[i], false)[attr];
                }
            } else {
                this.elements[i].style[attr] = value;
            }
        }
        return this;
    },

    center: function(json, fn, parentNode) {
        each(this.elements, function() {
            var selfW = (json) ? json.width : parseInt(getStyle(this, "width"));
            var selfH = (json) ? json.height : parseInt(getStyle(this, "height"));
            var wW = (parentNode) ? parentNode.css("width") : getClient().w;
            var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            var wH = (parentNode) ? parentNode.css("height") : getClient().h;
            var l = Math.round((wW - selfW) / 2);
            var t = Math.round((wH - selfH) / 2 + scrollTop);
            this.l = l;
            this.t = t
            tStartMove(this, {
                width: selfW,
                height: selfH,
                left: l,
                top: t
            }, function() {
                if (fn) fn();
            }, "elasticOut");
        })
        return this;
    },

    //添加link或style的css规则
    addRule: function(num, selectorText, cssText, index) {
        var sheet = document.styleSheets[num];
        index = (index) ? index : 0;
        addRule(sheet, selectorText, cssText, index);
        return this;
    },

    //移除规则表的项
    removeRule: function(num, index) {
        var sheet = document.styleSheets[num];
        index = (index) ? index : 0;
        removeRule(sheet, index);
        return this;
    },

    html: function(str) {
        for (var i = 0; i < this.elements.length; i++) {
            if (!str) {
                return this.elements[i].innerHTML;
            } else {
                this.elements[i].innerHTML = str;
            }
        }
        return this;
    },

    //得到输入框或其他的内在内容
    value: function(str) {
        for (var i = 0; i < this.elements.length; i++) {
            if (!str) {
                return this.elements[i].value;
            } else {
                this.elements[i].value = str;
            }
        }
        return this;
    },

    //设置元素属性
    setAttr: function(attr, value) {
        each(this.elements, function() {
            this.setAttribute(attr, value);
        })
    },

    hover: function(hover, out) {
        each(this.elements, function(index) {
            this.onmouseover = function() {
                hover.call(this, index);
            }
            this.onmouseout = function() {
                out.call(this, index);
            }
        })
    },

    eq: function(index) {
        if(index==1){
           return this.elements[0]; 
        }
        else if(index==-1){
            return this.elements[this.elements.length-1];
        }
        else{
            return this.elements ;
        }   
    },

    show: function() {
        each(this.elements, function() {
            this.style.display = "block";
        })
    },

    hidden: function() {
        each(this.elements, function() {
            this.style.display = "none";
        })
    },

    click: function(fnCallback) {
        each(this.elements, function() {
            this.onclick = fnCallback;
            this.click = fnCallback;
        })
    },

    //事件(onkeydown等)
    bind: function(event, fn) {
        each(this.elements, function() {
            addEvent(this, event, fn);
        })
    },

    // 拖拽
    drag: function(dragAble, dir, target, rangejson, fnMove) {
        each(this.elements, function() {
            drag(this, dragAble, dir, target, rangejson, fnMove);
        })
    },

    //插件入口
    extend: function(name, fn) {
        Base.prototype[name] = fn;
    },

    //运动
    startMove: function(json, fn) {
        each(this.elements, function() {
            if (fn) {
                startMove(this, json, fn);
            } else {
                startMove(this, json);
            }
        })
    },

    GetQueryString : function(param){  // 获取地址栏的s和t的值
        var reg = new RegExp("(^|&)"+ param +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r!=null)return unescape(r[2]); return null;
    },

    //Tween运动
    tStartMove: function(json, fn) {
        each(this.elements, function() {
            if (fn) {
                tStartMove(this, json, fn);
            } else if (!fn) {
                tStartMove(this, json);
            }
        })
    }
}

So.each = function(arr,fn){
    if(!arr.length || fn ){
        return ;
    }
    for(var i = 0 ,len = arr.length;i<len ;i++){
        fn.call(this,i);
    }
}





//背景闪烁以作为提示(多用于表单提交)
function changeBgTip(obj, changeColor) {
    var foreColor = getStyle(obj, "backgroundColor");
    var changeColor = (changeColor) ? changeColor : "red";
    var num = 0;
    obj.timer = setInterval(function() {
        num++;
        if (num == 5) {
            clearInterval(obj.timer);
        } else {
            if (num % 2 == 1) {
                obj.style.backgroundColor = changeColor;
            } else {
                obj.style.backgroundColor = foreColor;
            }
        }
    }, 200)
}


// 回到顶部
function goToTop(obj) {
    if(!obj.btn){
        return ; 
    }
    if(obj.autoHidden){
        addEvent(window,'scroll',function(){
            var _t = document.documentElement.scrollTop || document.body.scrollTop;
            if(_t < obj.hiddenFlag){
               obj.btn.style.display = 'none' ;
            }
            else if(_t>obj.hiddenFlag){
                obj.btn.style.display = 'block' ;
            }
        })
    }
    obj.btn.onclick = function() {
        var iT = document.documentElement.scrollTop || document.body.scrollTop;
        transMove(document, {
                t: iT
            }, {
                t: 0
            },
            function(dis) {
                document.documentElement.scrollTop = document.body.scrollTop = dis.t;
            },function(){
                if(obj.autoHidden===true){
                    obj.btn.style.display = 'none' ;
                }
            });
    }
}


//切换active导航条激活状态(传入导航列表,索引关键字(正则匹配用),通用class, 附加class)
function changeActive(objArr, keys, sClass) {
    //获取地址栏的网址
    var urlStr = location.href.toLowerCase();
    //遍历所有关键词,返回与地址栏匹配的值.
    for (var key in keys) {
        var _reg = new RegExp(key.toLowerCase());

        if (_reg.test(urlStr)) {
            //清除所有class ,以及给返回的匹配的值添加class
            for (var i = 0; i < objArr.length; i++) {
                // objArr[i].className = prevClass ;
                removeClass(objArr[i], sClass);
            }
            objArr[(keys[key])].className += (" " + sClass);
            return;
        }
    }
}



// 通过class找父级
function findParentByClass(obj, sClass) {
    while (obj.parentNode) {
        obj = obj.parentNode;
        if (obj.className) {
            if (hasClass(obj, sClass)) {
                return obj;
            }
        }
    }
}


// 图片上传预览显示 :传入预览图容器
function UploadImg(fileBtnId, imgContainer,uploadList,single) {
    this.fileBtn = fileBtnId;
    this.prevImg = imgContainer;
    this.uploadList = uploadList || [] ; 
    this.single = single ; 
    return this.bindChange();
}

UploadImg.prototype = {
    constructor: UploadImg,
    uploadSrc: '',
    srcArr : [] , //返回一个图片地址数组
    bindChange: function() { //绑定上传动作事件
        var _this = this;
        bindEvent(this.fileBtn, 'change', function() {
            _this.getImgSrc();
            _this.setImgSrc();
            return false;
        })
    },
    getImgSrc: function() { //获取上传的图片地址
        var __this = this ; 
        this.srcArr = []; // 初始化缓存初始数据
        this.uploadList = this.fileBtn.files;
        if (this.fileBtn.files && this.fileBtn.files[0]) { //存在且存在至少一个值
            each(this.fileBtn.files,function(index){
                var _src = window.URL.createObjectURL(__this.fileBtn.files[index]);
                __this.srcArr.push(_src);
            })
        } else {
            this.fileBtn.select();
            this.uploadSrc = document.selection.createRange().text;
        }
    },
    setImgSrc: function(uploadSrc) { //写入图片地址
        // this.prevImg.src = this.uploadSrc ;
        if (parseInt(sys.ie) < 10) { //兼容IE10以下 : 且不支持多张同时长传
            var __this = this ; 
            var _img = document.createElement('img');
            __this.prevImg.appendChild(_img);
            var _appendImg = So('#localImag img').eq(-1);
            _appendImg.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
            _appendImg.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = this.uploadSrc;
        } else {
            var __this = this;
            // alert(__this.srcArr.length);
            if(this.single){
                __this.prevImg.innerHTML = '' ; //如果是单图片,初始化清空原先选择图片
            }
            each(__this.srcArr,function(){
                var _img = document.createElement('img');
                _img.src = this ; 
                 __this.prevImg.appendChild(_img);
                // __this.prevImg.setAttribute('src', this);
            })
            return this.uploadList ; 
        }
    }
}


    // 通过id删除一个节点
    function delNodeById(id){
        var _delObj = So('#'+id).eq(1);
        if(_delObj && _delObj.parentNode){
             _delObj.parentNode.removeChild(_delObj) ;
             return true ;
        }
    }

    //////////
    // 生成唯一遮罩(单体) //
    //////////  
    var createMask = function () {
        var mask;
        var $this = this;
        function init() {
            mask = document.createElement('div');
            mask.id = 'mask';
            var _body = document.getElementsByTagName('body')[0];
            var _bodyHeight = parseInt(_body.offsetHeight);
            var _scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            mask.style.height = ((_bodyHeight > getClient().h) ? _bodyHeight : getClient().h) + 'px';
            document.body.appendChild(mask);
            mask.onmousewheel = function () {
                return false;
            }
        }
        return function () {
            return mask || (init());
        }
    }();


    /////////
    // 纯信息弹窗 //
    /////////
    var Msg = function(args){
        return new Msg.prototype.init(args);
    }

    Msg.prototype = {
        constructor : Msg ,
        init : function(args){
            if(So('#mask').eq(1)){  // 如果事先已有一个遮罩在, 也就是弹窗后的弹窗 , 此时不处理遮罩
                var _maskStatu = getStyle(So('#mask').eq(1),'display') ;
                if(_maskStatu && _maskStatu =='block'){
                    this.notCloseMask = true ; 
                }
            }
            //保证唯一
            try{
                if(args.only){
                    if(So('.msg-box').eq(1)){
                        return false; 
                    }
                }
            }
            catch(e){

            }

            if(typeof args == 'string'){
                this.msg = args ;
            }
            else{
                this.msgType = {'ok':{'iClass':'msg-icon-ok','iIcon':'glyphicon-ok'},
                               'error':{'iClass':'msg-icon-false','iIcon':'glyphicon-remove'},
                               'warning':{'iClass':'msg-icon-warning','iIcon':'glyphicon-warning-sign'},
                              }
                for(var attr in args){
                    this[attr] = args[attr];    
                }
            }
            this.getText().show();  
            return this ;             
        },
        getText : function(){
            var _text = '' ;
            if(this.title){  //是否添加标题
                _text += '<h3 class="msg-tit">'+this.title+'</h3>' ;
            }
            if(this.type){
                _text += '<div class="msg-body '+this.type+'">' ;
            }
            else{
                _text += '<div class="msg-body">' ;
            }
            if(this.type){  //是否添加提示类型
                _text += '<i class="msg-icon '+this.msgType[this.type].iClass+'">'+
                             '<span class="glyphicon '+this.msgType[this.type].iIcon+'"></span>'+
                         '</i>' ;
            }
            _text += '<span class="msg-text">'+this.msg+'</span>'+
                     '</div>'+
                     '<div class="msg-footer">'+
                        '<button type="button" class="msg-btn msg-sure">确认</button>' ;
            if(this.cancelBtn){  //是否添加取消按钮
                _text += '<button type="button" class="msg-btn msg-cancel">取消</button>' ;
            }
            _text += '</div>';
            this.text = _text ; 
            return this ; 
        },
        show : function(){
            var $this = this ;
            this.hideMask = function(){
                // So('#mask').eq(1).style.display = 'none';
                $this.destory.call($this);
                $this.cancel();
            }
            if(!this.notMask){
                createMask();
                So('#mask').eq(1).style.display = 'block';
                bindEvent(So('#mask').eq(1),'click',this.hideMask);
            }
            this.infoBox = document.createElement('div');
            this.infoBox.innerHTML = this.text ; 
            this.infoBox.className = 'msg-box';
            this.infoBox.style.visibility = 'visible';
            if(this.w){
                this.infoBox.style.width = this.w + 'px';
            }
            if(this.h){
                this.infoBox.style.height = this.h + 'px';
            }
            this.infoBox.style.opacity = 1 ;
            this.infoBox.onmousewheel = function () {
                return false;
            }
            this.bind();
            document.body.appendChild(this.infoBox);
            var _aSureBtn = getByClass('msg-sure','button',this.infoBox);
            var _aCancelBtn = getByClass('msg-cancel','button',this.infoBox);
            if(_aSureBtn.length){
                this.sureBtn = _aSureBtn[0] ;
            }
            if(_aCancelBtn.length){
                this.cancelBtn = _aCancelBtn[0] ;
            }
            var _scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            this.infoBox.style.left = (getClient().w - this.infoBox.offsetWidth)/2 + 'px';
            this.infoBox.style.top = (getClient().h - this.infoBox.offsetHeight)/2 + _scrollTop + 'px';
            transMove(this.infoBox,{scale:0.1},{scale:1},function(data){
                // this.style.WebkitTransform = 'scale('+data.scale+')';
                setCss(this,{$Transform:'scale('+data.scale+')'});
            },function(){
                if(!$this.notMask){
                    startMove($this.infoBox,{opacity:100})
                }
            },'backOut',600);
            return this ;
        },
        bind : function(){
            var $this = this ; 
            addEvent(this.infoBox,'click',function(e){  //监听信息框点击事件
                e = e || window.event ; 
                var el = e.srcElement || e.target ;
                if(hasClass(el,'msg-btn')){
                    $this.destory();
                    if(hasClass(el,'msg-sure')){
                        $this.sure();
                    }
                    if(hasClass(el,'msg-cancel')){
                        $this.cancel();
                    }
                }
            })
            if(this.autoOff){ //如果自动关闭
                this.msgTimerBox = document.createElement('span');
                this.msgTimerBox.className = 'msg-timer';
                this.infoBox.appendChild(this.msgTimerBox);
                this.autoHide(); 
            }
            this.keyCtrl = function(e){ // 键盘控制隐藏
                e = e || window.event ;
                if(e.keyCode == 27){
                    So('#mask').eq(1).click();
                    // $this.destory.call($this);
                    // $this.cancel();
                    // startMove(So('#mask').eq(1),{opacity:0},function(){
                    //     So('#mask').eq(1).style.display = 'none';
                    // });
                }
            }   
            addEvent(document,'keyup',$this.keyCtrl);
            return this ;
        },
        destory : function(){ 
            var $this = this ;
            transMove(this.infoBox,{scale:1},{scale:0},function(data){
                setCss(this,{$Transform:'scale('+data.scale+')'});
            },function(){
                if($this.hideMask){
                    if(So('#mask').eq(1)){
                       removeEvent(So('#mask').eq(1),'click',$this.hideMask); 
                    }
                }
                removeEvent(document,'keyup',$this.keyCtrl);
                $this.infoBox.parentNode.removeChild($this.infoBox);
                if(!$this.notMask){
                    if(!$this.notCloseMask){   //如果不是不关闭遮罩
                        startMove(So('#mask').eq(1),{opacity:0},function(){
                            So('#mask').eq(1).style.display = 'none';
                            So('#mask').eq(1).style.opacity = 0.6 ;
                            So('#mask').eq(1).style.filter = 'alpha(opacity=60)' ;
                        })
                    }
                }
                if($this.autoTimer){
                    clearInterval($this.autoTimer);
                }
                delete $this ; 
                $this = null ;  //在销毁信息框时 ,清除内存占用
            },'backIn',600);
            return null ;
        },
        autoHide : function(){
            var $this = this ; 
            this.autoTimer = null ; 
            this.autoOff = parseInt(this.autoOff);
            this.autoTimer = setInterval(function(){
                $this.autoOff-=1 ;
                $this.msgTimerBox.innerHTML = '<b>'+$this.autoOff+'</b>' +' S后关闭';
                if($this.autoOff==0){
                    clearInterval($this.autoTimer);
                    $this.autoFn();
                    $this.destory();
                }  
            },1000)
        },
        sure : function(callback){
            if(callback){
                this.sure = callback ;
            }
            return this ;
        },
        cancel : function(callback){
            if(callback){
                this.cancel = callback ;
            }
            return this ;
        },
        autoFn : function(callback){  //自动关闭执行函数
            if(callback){
                this.autoFn = callback ;
            }
            return this ;
        }
    }

    Msg.prototype.init.prototype = Msg.prototype;



    /////////
    // 省市区三级联动 //
    /////////
    // if 传入元素 , 找到元素写入 ; 
    // if 没有传入元素 ,则返回数据让外部函数调用
    var LinkAge = function (args) {
        return new LinkAge.prototype.init(args);
    }

    LinkAge.prototype = {
        constructor: LinkAge,
        init: function (args) {
            if (args) { //如果有参数 , 则给参数对象绑定事件 ,以及其他处理
                for (var attr in args) {
                    this[attr] = args[attr];
                }
                this.getALlData();
                this.dealPro();
                this.bind();
                if (this.provinceEl && this.provinceEl.getAttribute('data-default')) { //如果有省默认值

                    var $this = this;
                    this.proNow = this.provinceEl.getAttribute('data-default');
                    this.provinceEl.value = this.proNow;

                    if (this.cityEl && this.cityEl.getAttribute('data-default')) { //如果有省默认值

                        this.cityNow = this.cityEl.getAttribute('data-default');
                        this.getCityByPro();
                        this.cityEl.value = this.cityNow;

                        if (this.districtEl && (this.districtEl.getAttribute('data-default') || this.districtEl.getAttribute('data-default') === "")) { //如果有省默认值
                            this.disNow = this.districtEl.getAttribute('data-default') || "请选择";
                            //如果是输入框,直接输出
                            if(this.districtEl.tagName=='INPUT'){
                                this.districtEl.value = this.districtEl.getAttribute('data-default') ;
                            }
                            else if(this.districtEl.tagName == 'SELECT'){ //如果是选择下拉
                                this.getDisByCity();
                                this.districtEl.value = this.disNow;
                            }
                        }
                    }
                }
            } else { //否则直接返回一个实例 ,方便调用方法
                this.getALlData();
                this.dealPro();
                return this;
            }
        },
        getALlData: function () {
            var _this = this;
            if (!this.data && !window.mapCity) {
                $.ajax({
                    type: 'POST',
                    url: "/ajax/ajax_getmap.aspx?type=all",
                    cache: false,
                    async: false,
                    dataType: "json",
                    success: function (res) {
                        _this.data = res;
                        _formatData(res);
                        return _this.data;
                    }
                });
            } else { //暂存城市列表 , 一个多个地方选择避免每次都get
                this.data = window.mapCity || this.data;
            }

            function _formatData(data) {
                var _formatedData = [];

                for (var i = 0, iLen = data.length; i < iLen; i++) {
                    _formatedData[i] = {};
                    _formatedData[i].pro = data[i].pro; //省格式

                    _formatedData[i].city = []; //数组中弹出市格式
                    _formatedData[i].dis = []; //相应城市下的区为一个数组元素

                    for (var j = 0, jLen = data[i].city.length; j < jLen; j++) {
                        var _oCityAndDis = data[i].city[j].split('|');
                        _formatedData[i].city.push(_oCityAndDis.shift()); //数组中弹出市格式
                        _formatedData[i].dis.push(_oCityAndDis); //相应城市下的区为一个数组元素
                    }
                }
                if (_this.provinceEl.getAttribute('data-hasAll')) {  //如果有全国往数组前
                    _formatedData.unshift({
                        city: [],
                        dis: [],
                        pro: "全国"
                    })
                }
                _this.data = _formatedData;
                window.mapCity = _formatedData; //window对象暂存城市地图
            }
            return this;
        },
        dealPro: function () {
            var _proArr = [];
            each(this.data, function () {
                _proArr.push(this['pro']);
            })
            this.addOption({
                el: this.provinceEl,
                data: _proArr
            });
            this.proData = _proArr;
            return this;
        },
        getCityByPro: function () { //根据省来获得市,顺便可以获得省在data的下标
            this.proNow = this.proNow || arguments[0];
            var _this = this;
            var _cityArr = [];

            for (var i = 0, proLen = this.data.length; i < proLen; i++) {
                var _loopNow = this.data[i];
                if (_loopNow['pro'] == _this.proNow) {
                    _this.proIndex = i; // 当前省在data的下标 
                    _cityArr = _loopNow['city'];
                    _this.activeCityData = _cityArr;
                    break;
                }
            }

            this.addOption({
                el: this.cityEl,
                data: _cityArr
            });

            if (this.districtEl && this.districtEl.tagName == 'INPUT') {
                this.districtEl.value = '';
            }
            return this;
        },
        getDisByCity: function () {
            var _this = this;
            this.cityNow = this.cityNow || arguments[0];
            var _disArr = [];

            var _cityArr = this.data[this.proIndex].city;
            for (var i = 0, cityLen = _cityArr.length; i < cityLen; i++) {
                var _loopNow = _cityArr[i];
                if (_loopNow == _this.cityNow) {
                    _this.cityIndex = i; //获取当前城市的数组下标
                    break;
                }
            }

            _disArr = this.data[this.proIndex].dis[this.cityIndex];
            if (this.districtEl && this.districtEl.tagName == 'INPUT') {
                this.districtEl.value = '';
            }
            else if (this.districtEl && this.districtEl.tagName == 'SELECT') {
                this.addOption({
                    el: this.districtEl,
                    data: _disArr
                });
            }
            this.activeDisData = _disArr;
            return this;
        },
        succGetPro: function (callback) { //获得省回调
            if (callback) {
                this.succGetPro = callback(this.proData);
            }
            return this;
        },
        succGetCity: function (callback) { //获得市回调
            if (callback) {
                this.succGetCity = callback(this.activeCityData);
            }
            return this;
        },
        succGetDis: function (callback) { //获得区回调
            if (callback) {
                this.succGetDis = callback(this.activeDisData);
            }
            return this;
        },
        addOption: function (obj) {
            if (!obj.el) {
                return;
            }
            if (parseInt(sys.ie) < 10) { //如果为IE10以下
                obj.el.innerHTML = '';
                var _option = document.createElement('option');
                _option.value = '请选择';
                _option.innerHTML = '请选择';
                obj.el.appendChild(_option);

                each(obj.data, function () {
                    var _option = document.createElement('option');
                    _option.value = this;
                    _option.innerHTML = this;
                    obj.el.appendChild(_option);
                })
            } else {
                var _str = '<option value="请选择">请选择</option>';
                each(obj.data, function () {
                    _str += '<option value="' + this + '">' + this + '</option>';
                })
                obj.el.innerHTML = _str;
            }
        },
        resetDis: function () {
            this.districtEl.innerHTML = '';
            var _option = document.createElement('option');
            _option.value = '请选择';
            _option.innerHTML = '请选择';
            this.districtEl.appendChild(_option);
        },
        bind: function () {
            var _this = this;
            //绑定省元素的数据切换 :刷新市 刷新区为默认 
            bindEvent(this.provinceEl, 'change', function (index) {
                _this.proNow = this.value;
                _this.getCityByPro();
                if (_this.districtEl) { //如果有区级
                    _this.resetDis();
                }
            })

            //绑定市元素的数据切换
            bindEvent(this.cityEl, 'change', function (index) {
                _this.cityNow = this.value;
                if (_this.districtEl) {
                    _this.getDisByCity();
                }
            })
        }
    }

    LinkAge.prototype.init.prototype = LinkAge.prototype;


    /////////
    // 弹窗类 //
    /////////
    function AlertMes(data) {
        var $$this = this; //触发对象,绑定是否已创建消息盒判断
        if (!$$this.infoBox) {
            return new AlertMes.prototype.init($$this, data);
        }
    }
    AlertMes.prototype = {
        constructor: AlertMes,
        init: function ($$this, data) {
            createMask();
            if (!$$this.infoBox) {  //如果第一次创建
                for (var attr in data) {
                    this[attr] = data[attr];
                }
                // console.log(this);
                this.alertBoxId = this.el.getAttribute('data-id') || null;  //弹窗的唯一ID标识符
                this.startTop = this.startTop || (-400 - getClient().h);
                this.create(); // 创建弹窗
                this.bind(); // 创建绑定
                $$this.infoBox = this.infoBox;
            }
            return this;
        },
        bind: function () { //绑定事件
            var $this = this;
            this.show(); //初始化显示

            this.infoBox.onmousewheel = function () {
                return false;
            }

            bindEvent(this.el, 'click', function (e) {  //触发按钮绑定展示
                $this.show();
                stopPro(e);
            });

            // 弹窗自绑定函数
            if (this.initBindFn) {
                $this.initBindFn($this.alertBoxId);
            }

            //隐藏按钮绑定隐藏弹窗
            this.aCloseBtn = getByClass('alert-close-item', null, $this.infoBox);
            each(this.aCloseBtn, function () {
                var _selfBtn = this;
                bindEvent(this, 'click', function (e) {
                    $this.data = {};
                    $this.needData = {} ; 
                    $this.data.confirmResult = this.getAttribute('alert-confirm');
                    if ($this.data.confirmResult === 'true') {  //如果是确认提交,则提交数据判断处理
                        if ($this.fnCallback) {
                            this.formInput = getByClass('alert-form', null, $this.infoBox);
                            for (var i = 0, _len = this.formInput.length; i < _len; i++) {
                                var _this = this.formInput[i];
                                $this.data[_this.getAttribute('alert-name')] = _this.value;
                                if(_this.getAttribute('data-need')){
                                    $this.needData[_this.getAttribute('alert-name')] = _this.value ; 
                                }
                                _this.removeAttribute('disabled');
                            }

                            var _callResult = $this.fnCallback.call(this, $this.data,$this.alertBoxId,$this.needData);

                            if (_callResult) {
                                if (_this.tagName == 'SELECT') {
                                    _this.value = 0;
                                }
                                else {
                                    _this.value = '';
                                }
                                $this.hidden(hasClass(_selfBtn, 'not-close-mask'));
                            }
                        }
                    }
                    else {   //如果是取消,则不做任何处理
                        $this.hidden(hasClass(_selfBtn, 'not-close-mask'));
                    }
                    // console.log(this.getAttribute('alert-confirm'));
                    stopPro(e);
                })
            })

            // 遮罩触发隐藏
            bindEvent(So('#mask').eq(1), 'click', function (e) {
                $this.hidden();
                stopPro(e);
            })
            // 回调函数
        },
        create: function () {
            var $this = this; //当前根对象
            this.infoBox = document.createElement("div");
            this.infoBox.className = "alert-box";
            addClass(this.infoBox, this.iClass);
            try {
                this.infoBox.innerHTML = So($this.contentEl).eq(1).innerHTML;
            } catch (e) {
                this.infoBox.innerHTML = '默认样式';
            }
            with (this.infoBox.style) {
                position = "absolute";
                width = $this.width + 'px';
                left = (getClient().w - $this.width) / 2 + 'px';
                top: this.startTop + 'px';
                zIndex = 99999;
            }
            document.body.appendChild(this.infoBox);
            if(this.height){ //若强制设定高度
                this.infoBox.style.height = $this.height + 'px';
            }

            return this;
        },
        show: function () {
            var $this = this;
            if (this.isShow) {
                return;
            }
            else {
                So('#mask').eq(1).style.display = 'block';
                this.height = this.height || this.infoBox.offsetHeight ; 
                $this.endTop = (getClient().h - $this.height) / 2 + getScrollTop().t;
                if ($this.updateView) { //更新弹窗内容视图
                    $this.updateView();
                }
                tStartMove(this.infoBox, { top: $this.endTop }, function () {
                    $this.isShow = true;
                }, $this.animateStyle);
            }
        },
        hidden: function (isHiddenMask) {
            var $this = this;
            if (!this.isShow) {
                return;
            }
            else {
                tStartMove(this.infoBox, { top: this.startTop }, function () {
                    $this.isShow = false;
                    if (isHiddenMask) { //如果不关闭遮罩层
                        return;
                    }
                    else {
                        $this.hideMask();
                    }
                });
            }
        },
        hideMask: function () {
            So('#mask').eq(1).style.display = 'none';
        }
    }
    // 原型链继承
    AlertMes.prototype.init.prototype = AlertMes.prototype;    


    /////////
    // 倒计时 //
    /////////
    var CountDown = function (args) {
        if (args.timer.length === 0) {
            return;
        }
        else {
            return new CountDown.prototype.init(args);
        }
    };

    CountDown.prototype = {
        constructor: CountDown,
        init: function (args) {
            this.aTimer = []; // 页面所有倒计时器
            if (!args.timer.length) { // 强制转换为数组处理
                var _arr = [];
                _arr.push(args.timer);
                args.timer = _arr;
            }
            //console.log(args.timer.length);
            if (!args.timer.length) {
                return;
            }
            this.timerLen = args.timer.length;
            for (var i = 0 ; i < this.timerLen; i++) {
                var _timer = {};
                _timer.timeWrap = args.timer[i];
                _timer.endTime = _timer.timeWrap.getAttribute('data-endTime').replace(/-/g, '/');
                // _timer.dayEl = _timer.timeWrap.getElementsByClassName('day')[0];
                // _timer.hourEl = _timer.timeWrap.getElementsByClassName('hour')[0];
                // _timer.minEl = _timer.timeWrap.getElementsByClassName('min')[0];
                // _timer.secEl = _timer.timeWrap.getElementsByClassName('sec')[0];

                _timer.dayEl = getByClass('day', null, _timer.timeWrap)[0];
                _timer.hourEl = getByClass('hour', null, _timer.timeWrap)[0];
                _timer.minEl = getByClass('min', null, _timer.timeWrap)[0];
                _timer.secEl = getByClass('sec', null, _timer.timeWrap)[0];

                _timer.disSec = parseInt((new Date(_timer.endTime) - new Date()) / 1000) + 1;
                this.aTimer.push(_timer);
            }
            this.run();
            return this;
        },
        count: function () {
            for (var i = 0 ; i < this.timerLen; i++) {
                var _now = this.aTimer[i];
                _now.disSec -= 1;
                _now.disMin = parseInt(_now.disSec / (60)) % 60;
                _now.disHour = parseInt(_now.disSec / (60 * 60)) % 24;
                _now.disDay = parseInt(_now.disSec / (24 * 60 * 60));
            }
            return this;
        },
        fillZero: function (str) {
            var _num = parseInt(str);
            return (_num < 10) ? '0' + _num : _num;
        },
        writeTime: function () {
            this.count();
            for (var i = 0 ; i < this.timerLen; i++) {
                var _now = this.aTimer[i];
                if (_now.disSec < 0) {
                    _now.disDay = 0;
                    _now.disHour = 0;
                    _now.disMin = 0;
                    _now.disSec = 0;
                    _now.delFlag = true;
                    this.delFlag = true;
                    console.log(this);
                    this.endFn();
                }
                if (_now.domDay != _now.disDay) {
                    _now.dayEl.innerHTML = this.fillZero(_now.disDay);
                    _now.domDay = _now.disDay;
                }
                if (_now.domHour != _now.disHour) {
                    _now.hourEl.innerHTML = this.fillZero(_now.disHour);
                    _now.domHour = _now.disHour;
                }
                if (_now.domMin != _now.disMin) {
                    _now.minEl.innerHTML = this.fillZero(_now.disMin);
                    _now.domMin = _now.disMin;
                }
                if (_now.domSec != _now.disSec) {
                    _now.secEl.innerHTML = this.fillZero((_now.disSec) % 60);
                    _now.domSec = _now.disSec;
                }
            }
            if (this.delFlag) { //发现为0的计时,计时删除
                this.del();
            }
            return this;
        },
        run: function () {
            var $this = this;
            this.interval = setInterval(function () {
                $this.writeTime();
            }, 1000);
            return this;
        },
        del: function () {
            var _arr = [];
            for (var i = 0 ; i < this.timerLen; i++) {
                if (!this.aTimer[i].delFlag) {
                    _arr.push(this.aTimer[i]);
                }
            }
            this.aTimer = _arr;
            this.timerLen = this.aTimer.length;
            return this;
        },
        stop: function () {
            clearInterval(this.interval);
            return this;
        },
        endFn: function (callback) {
            if (callback) {
                this.endFn = callback;
                //callback.call(this);
            }
        }
    }

    CountDown.prototype.init.prototype = CountDown.prototype;


    //鼠标点击事件（竞价结束）
    function showbidend(id) {
        document.getElementById(id).style.display = 'block';
    }
    function hidebidend(id) {
        document.getElementById(id).style.display = 'none'
    }
    
    function WaitGo(time) {
        return new WaitGo.prototype.init(time) ; 
    }

    WaitGo.prototype = {
        constructor : WaitGo ,
        init : function(time) {
            this.waitTime = time ; 
            this.countTime = 0 ; 
            this.countTimer = null ; 
            return this ; 
        } ,
        count : function(callback) {
            var self = this ; 
            this.countTimer = setInterval(function(){
                self.countTime ++ ; 
                callback.call(this,self.countTime) ;
                if(self.countTime>=self.waitTime) {
                    clearInterval(self.countTimer) ;
                    self.go() ; 
                }
            },1000) ;
            return this ; 
        } ,
        go : function(callback) {
            if(callback) {
                this.go = callback ; 
            }
        }
    }
    WaitGo.prototype.init.prototype = WaitGo.prototype;



    // --------睡眠禁用时间
    var Sleep = function (args) {
        return new Sleep.prototype.init(args);
    }
    Sleep.prototype = {
        constructor: Sleep,
        init: function (args) {
            for (var option in args) { //获取初始配置值
                this[option] = args[option];
            }
            if (!this.sleepTime) {
                return false;
            }
            this.interval = null;
            this.run();
            return this;
        },
        run: function () {
            var self = this;
            this.interval = setInterval(function () {
                self.sleepTime--;
                if (self.sleepTime <= 0) {
                    clearInterval(self.interval);
                    self.end();
                }
                else {
                    self.ing();
                }
            }, 1000);
        },
        ing: function (callback) {  //睡眠中
            if (callback) {
                this.ing = callback;
            }
            return this;
        },
        end: function (callback) { //睡眠结束回调
            if (callback) {
                this.end = callback;
            }
            return this;
        }
    }
    Sleep.prototype.init.prototype = Sleep.prototype;

   