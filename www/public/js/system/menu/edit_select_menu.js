/**
 * 修改选择菜单
 */
var editSelectMenu = {
    ztree: null
};

/**
 * ztree初始化
 */
editSelectMenu.ztreeInit = function(){
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
			    pIdKey: "parent_id"
            }
        }
    };
    var nodes = editSelectMenu.menuData;
    editSelectMenu.ztree = $.fn.zTree.init($("#ztree"), setting, nodes)
}

/**
 * 确定
 */
editSelectMenu.submit = function(){
    var node = {}
    var nodes = editSelectMenu.ztree.getSelectedNodes();
    var iframeWindow;
    
    if(!nodes || nodes.length == 0){
        sun.toast("error", "请选择上级菜单", 3000);
        return;
    }
    if(nodes.length > 1){
        sun.toast("error", "只能选择一个菜单", 3000);
        return;
    }
    
    node = nodes[0];
    
    iframeWindow = sun.layer.getIframeWindow(window.parent, "layer_menu_edit_iframe");
    iframeWindow.edit.selectMenuCallback(node);
    window.parent.sun.layer.close("layer_edit_select_menu");
}

$(function(){
    editSelectMenu.ztreeInit();
});