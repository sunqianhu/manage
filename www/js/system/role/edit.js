/**
 * 修改
 */

var ztree;

/**
 * ztree初始化
 */
function ztreeInit(){
    var setting = {
        check: {
            enable: true,
            chkStyle: "checkbox",
            chkboxType: {"Y" : "p", "N" : "ps"}
        },
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
			    pIdKey: "parent_id"
            }
        },
        callback: {
            onCheck: setPermissionId
        }
    };
    var nodes = permissionData;
    ztree = $.fn.zTree.init($("#ztree_permission"), setting, nodes);
}

/**
 * 设置选中的权限
 */
function setPermissionId(){
    var nodePermissionIds = $("#permission_ids");
    var nodes = ztree.getCheckedNodes(true);
    var nodeLength = nodes.length;
    var i = 0;
    var permissionIds = [];
    
    for(i; i < nodeLength; i++){
        node = nodes[i];
        permissionIds.push(node.id);
    }
    
    nodePermissionIds.val(permissionIds.join(","));
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
    ztreeInit();
    submitForm();
});