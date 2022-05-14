/**
 * 登录日志
 */
var index = {};

index.searchTimeRange = function(){
    var laydate = layui.laydate;
    laydate.render({
        elem: "#time_range",
        type: "datetime",
        range: ["#time_start", "#time_end"],
        theme: "#326496"
    });
}

$(function(){
    index.searchTimeRange();
});