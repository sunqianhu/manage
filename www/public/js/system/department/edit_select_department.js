/**
 * 修改选择部门
 */
var editSelectDepartment = {
    ztree: null
};

/**
 * ztree初始化
 */
editSelectDepartment.ztreeInit = function(){
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
			    pIdKey: "parent_id"
            }
        }
    };
    var nodes = editSelectDepartment.departmentData;
    editSelectDepartment.ztree = $.fn.zTree.init($("#ztree"), setting, nodes)
}

/**
 * 确定
 */
editSelectDepartment.submit = function(){
    var node = {}
    var nodes = editSelectDepartment.ztree.getSelectedNodes();
    var iframeWindow;
    
    if(!nodes || nodes.length == 0){
        sun.toast("error", "请选择上级部门", 3000);
        return;
    }
    if(nodes.length > 1){
        sun.toast("error", "只能选择一个部门", 3000);
        return;
    }
    
    node = nodes[0];
    
    iframeWindow = sun.layer.getIframeWindow(window.parent, "layer_department_edit_iframe");
    iframeWindow.edit.selectDepartmentCallback(node);
    window.parent.sun.layer.close("layer_edit_select_department");
}

$(function(){
    editSelectDepartment.ztreeInit();
});