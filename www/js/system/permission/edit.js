/**
 * 修改权限
 */

var edit = {};

/**
 * 选择权限
 */
edit.selectPermission = function(){
    var url = "edit_select_permission.php";
    window.parent.sun.layer.open({
        id: "layer_edit_select_permission",
        name: "选择上级权限",
        url: url,
        width: 500,
        height: 300
    });
}

/**
 * 选择权限回调
 * @param json node 节点数据
 */
edit.selectPermissionCallback = function(node){
    var domParentId = $("#parent_id");
    var domParentName = $("#parent_name");
    
    domParentId.val(node.id);
    domParentName.val(node.name);
}

/**
 * 提交表单
 */
edit.formSubmit = function(){
    sun.formSubmit({
        selector: ".form",
        success: function(ret){
            if(ret.status == "error"){
                sun.toast("error", ret.message, 3000);
                if(ret.data.dom){
                    $(ret.data.dom).focus();
                }
                return;
            }
            sun.toast("success", ret.message, 1000, function(){
                window.parent.location.reload();
            });
        }
    });
}

$(function(){
    edit.formSubmit();
});