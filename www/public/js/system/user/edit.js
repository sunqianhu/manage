/**
 * 修改
 */

var edit = {};

/**
 * 选择部门
 */
edit.selectDepartment = function(){
    var url = "user-edit_select_department.json";
    window.parent.sun.layer.open({
        id: "layer_edit_select_department",
        name: "选择上级部门",
        url: url,
        width: 500,
        height: 300
    });
}

/**
 * 选择部门回调
 * @param json node 节点数据
 */
edit.selectDepartmentCallback = function(node){
    var domDepartmentId = $("#department_id");
    var domDepartmentName = $("#department_name");
    
    domDepartmentId.val(node.id);
    domDepartmentName.val(node.name);
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