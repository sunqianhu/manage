/**
 * 添加选择菜单
 */
var addSelectMenu = {
    ztree: null
};

/**
 * ztree初始化
 */
addSelectMenu.ztreeInit = function(){
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
			    pIdKey: "parent_id"
            }
        }
    };
    var nodes = addSelectMenu.menuData;
    addSelectMenu.ztree = $.fn.zTree.init($("#ztree"), setting, nodes);
}

/**
 * 确定
 */
addSelectMenu.submit = function(){
    var node = {}
    var nodes = addSelectMenu.ztree.getSelectedNodes();
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
    
    iframeWindow = sun.layer.getIframeWindow(window.parent, "layer_menu_add_iframe");
    iframeWindow.add.selectMenuCallback(node);
    window.parent.sun.layer.close("layer_add_select_menu");
}

$(function(){
    addSelectMenu.ztreeInit();
});