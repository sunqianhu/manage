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

/**
 * 角色多选
 */
add.roleSelect = function(data){
    var domRoleInputBox = $(".role_input_box");
    var xmSelectRole = xmSelect.render({
        el: "#role", 
        language: "zn",
        filterable: true,
        theme: {
            color: "#326496",
        },
        size: "small",
        data: data,
        on: function(data){
            var item = {};
            var node = "";
            for(var i in data.arr){
                item = data.arr[i];
                node += '<input type="hidden" name="role_id[]" value="'+item.value+'" />';
            }
            domRoleInputBox.html(node);
        }
    });
}

$(function(){
    
});