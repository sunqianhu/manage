/**
 * 修改
 */

var edit = {
    ztree: null
};

/**
 * ztree初始化
 */
edit.ztreeInit = function(){
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
            onCheck: edit.setPermissionId
        }
    };
    var nodes = edit.permissionData;
    edit.ztree = $.fn.zTree.init($("#ztree_permission"), setting, nodes);
}

/**
 * 设置选中的权限
 */
edit.setPermissionId = function(){
    var domPermissionIds = $("#permission_ids");
    var nodes = edit.ztree.getCheckedNodes(true);
    var nodeLength = nodes.length;
    var i = 0;
    var permissionIds = [];
    
    for(i; i < nodeLength; i++){
        node = nodes[i];
        permissionIds.push(node.id);
    }
    
    domPermissionIds.val(permissionIds.join(","));
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
    edit.ztreeInit();
    edit.formSubmit();
});