/**
 * 菜单管理
 */
/**
 * 添加
 */
function add(){
    var url = "add";
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
function bootstrapTooltip(){
    $('[data-toggle="tooltip"]').tooltip();
}

$(function(){
    bootstrapTooltip();
});