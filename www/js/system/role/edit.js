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
            onCheck: edit.setMenuId
        }
    };
    var nodes = edit.menuData;
    edit.ztree = $.fn.zTree.init($("#ztree_menu"), setting, nodes);
}

/**
 * 设置选中的菜单
 */
edit.setMenuId = function(){
    var domMenuIds = $("#menu_ids");
    var nodes = edit.ztree.getCheckedNodes(true);
    var nodeLength = nodes.length;
    var i = 0;
    var menuIds = [];
    
    for(i; i < nodeLength; i++){
        node = nodes[i];
        menuIds.push(node.id);
    }
    
    domMenuIds.val(menuIds.join(","));
}

/**
 * 提交表单
 */
edit.formSubmit = function(){
    sun.formSubmit({
        element: ".form",
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