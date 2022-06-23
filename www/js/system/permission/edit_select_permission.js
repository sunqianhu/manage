/**
 * 修改选择权限
 */
var editSelectPermission = {
    ztree: null
};

/**
 * ztree初始化
 */
editSelectPermission.ztreeInit = function(){
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
			    pIdKey: "parent_id"
            }
        }
    };
    var nodes = editSelectPermission.permissionData;
    editSelectPermission.ztree = $.fn.zTree.init($("#ztree"), setting, nodes)
}

/**
 * 确定
 */
editSelectPermission.submit = function(){
    var node = {}
    var nodes = editSelectPermission.ztree.getSelectedNodes();
    var iframeWindow;
    
    if(!nodes || nodes.length == 0){
        sun.toast("error", "请选择上级权限", 3000);
        return;
    }
    if(nodes.length > 1){
        sun.toast("error", "只能选择一个权限", 3000);
        return;
    }
    
    node = nodes[0];
    
    iframeWindow = sun.layer.getIframeWindow(window.parent, "layer_permission_edit_iframe");
    iframeWindow.edit.selectPermissionCallback(node);
    window.parent.sun.layer.close("layer_edit_select_permission");
}

$(function(){
    editSelectPermission.ztreeInit();
});