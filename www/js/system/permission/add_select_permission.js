/**
 * 添加选择权限
 */
var addSelectPermission = {
    ztree: null
};

/**
 * ztree初始化
 */
addSelectPermission.ztreeInit = function(){
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
			    pIdKey: "parent_id"
            }
        }
    };
    var nodes = addSelectPermission.permissionData;
    addSelectPermission.ztree = $.fn.zTree.init($("#ztree"), setting, nodes);
}

/**
 * 确定
 */
addSelectPermission.submit = function(){
    var node = {}
    var nodes = addSelectPermission.ztree.getSelectedNodes();
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
    
    iframeWindow = sun.layer.getIframeWindow(window.parent, "layer_permission_add_iframe");
    iframeWindow.add.selectPermissionCallback(node);
    window.parent.sun.layer.close("layer_add_select_permission");
}

$(function(){
    addSelectPermission.ztreeInit();
});