/**
 * 添加部门
 */
var add = {};

/**
 * 选择部门
 */
add.selectDepartment = function(){
    var url = "addSelectDepartment";
    window.parent.layer.open({
        type: 2, // iframe层
        title: "添加部门",
        maxmin: true, // 开启最大化最小化按钮
        area: ["500px", "300px"],
        skin: "sun_layer",
        content: url,
        btn: ["确认", "关闭"],
        yes: function(index, layero){
            var layerWindow = $(layero).find("iframe")[0].contentWindow;
            layerWindow.add.formSubmit();
        }
    });
}

/**
 * 提交表单
 */
add.formSubmit = function(){
    var layerIndex = 0;
    
    layerIndex = parent.layer.getFrameIndex(window.name);
    parent.layer.close(layerIndex);
}

$(function(){
});