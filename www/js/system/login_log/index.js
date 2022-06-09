/**
 * 首页
 */
var index = {};

$(function(){
    laydate.render({
        elem: ".search .time_range",
        range: ["#time_start", "#time_end"],
        type: "datetime",
        theme: "#326496"
    });
});