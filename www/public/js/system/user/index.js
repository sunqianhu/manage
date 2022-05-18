/**
 * 用户管理
 */
var index = {};
/**
 * 添加用户
 */
index.add = function(){
    var url = "add";

    sun.layer.open({
        id: "layer_user_add",
        name: "添加用户",
        url: url,
        width: 700,
        height: 500
    });
}

/**
 * 提示框
 */
index.bootstrapTooltip = function(){
    $('[data-toggle="tooltip"]').tooltip();
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
    index.bootstrapTooltip();
    index.operationMore();
    
    laydate.render({
      elem: ".time_range",
      range: ['#time_start', "#time_end"],
      theme: "#326496",
      format: "yyyy-MM-dd HH:mm:ss"
    });
});