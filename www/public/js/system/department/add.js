/**
 * 添加部门
 */

/**
 * 选择部门
 */
function selectDepartment(){
    var url = "addSelectDepartment";
    window.parent.sun.layer.open({
        id: "layer_add_select_department",
        name: "选择父部门",
        url: url,
        width: 500,
        height: 300
    });
}

/**
 * 选择部门回调
 * @param json node 节点数据
 */
function selectDepartmentCallback(node){
    var domParentId = $("#parent_id");
    var domParentName = $("#parent_name");
    
    domParentId.val(node.id);
    domParentName.val(node.name);
}

/**
 * 提交表单
 */
function formSubmit(){
    sun.formSubmit({
        selector: ".form",
        success: function(ret){
            if(ret.status == "error"){
                sun.toast("error", ret.msg, 3000);
                $(ret.dom).focus();
                return;
            }
            sun.toast("success", ret.msg, 1000, function(){
                window.parent.location.reload();
            });
        }
    });
}

$(function(){
    formSubmit();
});