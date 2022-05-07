/**
 * 用户管理
 */
var index = {};

index.bootstrapInit = function(){
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
}

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
    sun.dropdown();
}

$(function(){
    index.bootstrapInit();
    index.operationMore();
});