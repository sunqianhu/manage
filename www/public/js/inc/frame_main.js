/**
 * 页面框架主要
 */

var frameMain = {};

// 菜单活跃
frameMain.menuActive = function(){
    var domActiveLi = $(".page_left .menu li.active"); // 活跃的li
    var domActiveParentLis = domActiveLi.parents(".page_left .menu li"); // 活跃的li的所有父li
    var domActiveParentLiUls = $(" > ul", domActiveParentLis); // 活跃的li的所有父li的ul
    
    domActiveParentLis.addClass("open");
    domActiveParentLiUls.css({"display":"block"});
}

// 菜单切换
frameMain.menuToggle = function(){
    var domAs = $(".page_left .menu li a");
    
    domAs.on("click", function(){
        var domClickA = $(this); // 点击的a
        var domClickLi = domClickA.parent(); // 点击的li
        var domClickLiUl = domClickA.next("ul"); // 点击的li的ul
        var domSiblingLis = domClickLi.siblings("li"); // 同级li
        var domSiblingLiUls = $(" > ul", domSiblingLis); // 同级li的ul
        var clickLiUlExists = domClickLiUl.length > 0 ? true : false; // 点击的li的ul是否存在
        
        // 没有子菜单什么都不做
        if(!clickLiUlExists){
            return;
        }
        
        // 展开折叠
        if(domClickLi.hasClass("open")){
            domClickLi.removeClass("open"); // 去掉open
			domClickLiUl.slideUp(); // 折叠
		}else{
            domClickLi.addClass("open"); // 添加open
			domClickLiUl.slideDown(); // 展开
            
            domSiblingLis.removeClass("open"); // 同级去掉open
            domSiblingLiUls.slideUp(); // 同级折叠
		}
    });
}

$(function(){
    frameMain.menuActive();
    frameMain.menuToggle();
});