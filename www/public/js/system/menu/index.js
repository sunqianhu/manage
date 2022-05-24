/**
 * 菜单管理
 */
var index = {};
/**
 * 添加
 */
index.add = function(){
    var url = "menu-add.html";
    sun.layer.open({
        id: "layer_menu_add",
        name: "添加菜单",
        url: url,
        width: 600,
        height: 400
    });
}

/**
 * 提示框
 */
index.bootstrapTooltip = function(){
    $('[data-toggle="tooltip"]').tooltip();
}

$(function(){
    index.bootstrapTooltip();
});