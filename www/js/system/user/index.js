/**
 * 用户管理
 */
var index = {};

/**
 * 添加用户
 */
index.add = function(){
    var url = "add";
    layer.open({
        type: 2, // iframe层
        title: "添加用户",
        maxmin: true, // 开启最大化最小化按钮
        area: ["700px", "500px"],
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
 * 列表更多操作
 */
index.operationMore = function(){
    sun.dropdown({
        selector: ".data .sun_dropdown",
        trigger: ["click"]
    });
}

$(function(){
    index.operationMore();
});