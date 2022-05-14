/**
 * 部门管理
 */
var index = {};

/**
 * 添加用户
 */
index.add = function(){
    var url = "add";
    sun.layer.open({
        id: "layer_department_add",
        name: "添加部门",
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