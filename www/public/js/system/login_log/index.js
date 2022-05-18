/**
 * 登录日志
 */

function searchTimeRange(){
    var laydate = layui.laydate;
    laydate.render({
        elem: "#time_range",
        type: "datetime",
        range: ["#time_start", "#time_end"],
        theme: "#326496"
    });
}

$(function(){
    searchTimeRange();
});