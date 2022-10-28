/**
 * 页面框架主要
 */

var frameMain = {};

/**
 * 用户下拉菜单
 */
frameMain.userDropDownMenu = function(){
    sun.dropDownHover({
        selector: ".page .page_header .link .user"
    });
}

/*
 * 页面左边初始化
 */
frameMain.pageLeftInit = function(){
	var nodeBody = $("body");
    var cookie = "";
    var windowWidth = $(window).width();
    
    cookie = frameMain.pageLeftGetCookie();
	if(cookie == "close"){
		nodeBody.addClass("close");
	}
    
    if(windowWidth < 768 && cookie == ""){
        nodeBody.addClass("close");
    }
}

/**
 * 页面左边打开关闭
 */
frameMain.pageLeftToggle = function(){
    var nodeBody = $("body");
    
    if(nodeBody.hasClass("close")){
        nodeBody.removeClass("close");
        frameMain.pageLeftSetCookie("open");
    }else{
        nodeBody.addClass("close");
        frameMain.pageLeftSetCookie("close");
    }
};

/*
 * 页面左边得到cookie
 */
frameMain.pageLeftGetCookie = function(){
	var cookies = document.cookie.split(";");
	var length = cookies.length;
    var i = 0;
    var items = [];
    var key = "";
    var value = "";
    
    for(i=0; i < length; i++){
        items = cookies[i].split("=");
        key = $.trim(items[0]);
        value = decodeURI($.trim(items[1]));
        if(key == "frame_main_page_left"){
            return value;
        }
    }
	
	return "";
}

/*
 * 页面左边设置cookie
 */
frameMain.pageLeftSetCookie = function(value){
	var dateMy = new Date();
    dateMy.setDate(dateMy.getDate() + 360);
    document.cookie="frame_main_page_left="+value+"; expires="+dateMy.toDateString()+"; path=/";
}

/**
 * 菜单活跃
 */
frameMain.menuActive = function(){
    var nodeActiveLi;
    var nodeActiveParentLis;
    var nodeActiveParentLiUls;
    
    nodeActiveLi = $(".page_left .menu li.active"); // 活跃的li
    if(nodeActiveLi.length == 0){
        return;
    }
    
    nodeActiveParentLis = nodeActiveLi.parents(".page_left .menu li"); // 活跃的li的所有父li
    nodeActiveParentLiUls = $(" > ul", nodeActiveParentLis); // 活跃的li的所有父li的ul

    nodeActiveParentLis.addClass("open");
    nodeActiveParentLiUls.css({"display":"block"});
};

/**
 * 菜单切换
 */
frameMain.menuToggle = function(){
    var nodeAs = $(".page_left .menu li a");
    
    nodeAs.on("click", function(){
        var nodeClickA = $(this); // 点击的a
        var nodeClickLi = nodeClickA.parent(); // 点击的li
        var nodeClickLiUl = nodeClickA.next("ul"); // 点击的li的ul
        var nodeSiblingLis = nodeClickLi.siblings("li"); // 同级li
        var nodeSiblingLiUls = $(" > ul", nodeSiblingLis); // 同级li的ul
        var clickLiUlExists = nodeClickLiUl.length > 0 ? true : false; // 点击的li的ul是否存在
        
        // 没有子菜单什么都不做
        if(!clickLiUlExists){
            return;
        }
        
        // 展开折叠
        if(nodeClickLi.hasClass("open")){
            nodeClickLi.removeClass("open"); // 去掉open
			nodeClickLiUl.slideUp(); // 折叠
		}else{
            nodeClickLi.addClass("open"); // 添加open
			nodeClickLiUl.slideDown(); // 展开
            
            nodeSiblingLis.removeClass("open"); // 同级去掉open
            nodeSiblingLiUls.slideUp(); // 同级折叠
		}
    });
};

/**
 * 修改用户头像
 */
frameMain.editUserHead = function(){
    var url = "/my/edit_head.php";
    window.parent.sun.layer.open({
        id: "edit_head",
        name: "修改头像",
        url: url,
        width: 700,
        height: 480
    });
};

/**
 * 修改用户密码
 */
frameMain.editUserPassword = function(){
    var url = "/my/edit_password.php";
    window.parent.sun.layer.open({
        id: "edit_password",
        name: "修改密码",
        url: url,
        width: 500,
        height: 300
    });
};

$(function(){
    frameMain.userDropDownMenu();
    frameMain.pageLeftInit();
    frameMain.menuActive();
    frameMain.menuToggle();
});