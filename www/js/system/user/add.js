/**
 * 添加用户
 */
var add = {};

/**
 * 提交表单
 */
add.formSubmit = function(){
    var layerIndex = 0;
    
    
    layerIndex = parent.layer.getFrameIndex(window.name);
    parent.layer.close(layerIndex);
}

$(function(){
    $(".role").select2({
        placeholder: "请选择",
        allowClear: true
    });
});