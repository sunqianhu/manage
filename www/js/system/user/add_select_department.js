/**
 * 添加选择部门
 */
var ztree;

/**
 * ztree初始化
 */
function ztreeInit(){
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
			    pIdKey: "parent_id"
            }
        }
    };
    var nodes = departmentData;
    ztree = $.fn.zTree.init($("#ztree"), setting, nodes)
}

/**
 * 确定
 */
function submit(){
    var node = {}
    var nodes = ztree.getSelectedNodes();
    var iframeWindow;
    
    if(!nodes || nodes.length == 0){
        sun.toast("error", "请选择部门", 3000);
        return;
    }
    if(nodes.length > 1){
        sun.toast("error", "只能选择一个部门", 3000);
        return;
    }
    
    node = nodes[0];
    
    iframeWindow = sun.layer.getIframeWindow(window.parent, "layer_user_add_iframe");
    iframeWindow.selectDepartmentCallback(node);
    window.parent.sun.layer.close("layer_add_select_department");
}

$(function(){
    ztreeInit();
});