/**
 * 部门管理
 */
var index = {};

/**
 * 添加用户
 */
index.add = function(){
    var url = "add";
    layer.open({
        type: 2, // iframe层
        title: "添加部门",
        maxmin: true, // 开启最大化最小化按钮
        area: ["600px", "400px"],
        skin: "sun_layer",
        content: url,
        btn: ["提交", "关闭"],
        yes: function(index, layero){
            var layerWindow = $(layero).find("iframe")[0].contentWindow;
            layerWindow.add.formSubmit();
        }
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