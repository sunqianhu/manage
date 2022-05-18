/**
 * 用户管理
 */
/**
 * 添加用户
 */
function add(){
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
function bootstrapTooltip(){
    $('[data-toggle="tooltip"]').tooltip();
}

/**
 * 列表更多操作
 */
 function operationMore(){
    sun.dropdown({
        selector: ".data .sun_dropdown",
        trigger: ["click"]
    });
}

$(function(){
    bootstrapTooltip();
    operationMore();
    
    laydate.render({
      elem: ".time_range",
      range: ['#time_start', "#time_end"],
      theme: "#326496",
      format: "yyyy-MM-dd HH:mm:ss"
    });
});