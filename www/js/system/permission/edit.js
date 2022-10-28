/**
 * 修改权限
 */

/**
 * 选择权限
 */
function selectPermission(){
    var url = "edit_select_permission.php";
    window.parent.sun.layer.open({
        id: "edit_select_permission",
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
function selectPermissionCallback(node){
    var nodeParentId = $("#parent_id");
    var nodeParentName = $("#parent_name");
    
    nodeParentId.val(node.id);
    nodeParentName.val(node.name);
}

/**
 * 提交表单
 */
function submitForm(id){
    sun.submitForm({
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
    submitForm();
});