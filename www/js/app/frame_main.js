/**
 * 页面框架主要
 */

var appFrameMain = {};

// 菜单初始化
appFrameMain.menuInit = function(){
    var domAs = $(".menu ul li a"); // 所有功能链接
    var domLiCurrent = $(".menu li.current"); // 当前li
    var domLiCurrentParents = domLiCurrent.parents(".menu li"); // 当前li的所有父li
    
    // 初始化
    domLiCurrentParents.addClass("open");
    
    // 折叠
    domAs.on("click", function(){
        var domACurrent = $(this);
        var domLiCurrent = domACurrent.parent();
        var domLiParents = domACurrent.parents(".menu li");
        var domLiOpens = $(".menu ul li.open");
        var url = domACurrent.attr("href");
        
        if(url != "javascript:;" && url != "#"){
            return;
        }
        if(domLiCurrent.hasClass("open")){
            return;
        }
        
        domLiOpens.removeClass("open");
        domLiParents.addClass("open");
    });
}

$(function(){
    appFrameMain.menuInit();
});