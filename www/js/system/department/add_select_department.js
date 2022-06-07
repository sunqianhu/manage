/**
 * 添加选择部门
 */
var addSelectDepartment = {
    ztree: null
};

/**
 * ztree初始化
 */
addSelectDepartment.ztreeInit = function(){
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
			    pIdKey: "parent_id"
            }
        }
    };
    var nodes = addSelectDepartment.departmentData;
    addSelectDepartment.ztree = $.fn.zTree.init($("#ztree"), setting, nodes)
}

/**
 * 确定
 */
addSelectDepartment.submit = function(){
    var node = {}
    var nodes = addSelectDepartment.ztree.getSelectedNodes();
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
    
    iframeWindow = sun.layer.getIframeWindow(window.parent, "layer_department_add_iframe");
    iframeWindow.add.selectDepartmentCallback(node);
    window.parent.sun.layer.close("layer_add_select_department");
}

$(function(){
    addSelectDepartment.ztreeInit();
});