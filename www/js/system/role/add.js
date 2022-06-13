/**
 * 添加
 */

var add = {
    ztree: null
};

/**
 * ztree初始化
 */
add.ztreeInit = function(){
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
            onCheck: add.setMenuId
        }
    };
    var nodes = add.menuData;
    add.ztree = $.fn.zTree.init($("#ztree_menu"), setting, nodes);
}

/**
 * 设置选中的菜单
 */
add.setMenuId = function(){
    var domMenuIds = $("#menu_ids");
    var nodes = add.ztree.getCheckedNodes(true);
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
add.formSubmit = function(){
    sun.formSubmit({
        element: ".form",
        success: function(ret){
            if(ret.status == "error"){
                sun.toast("error", ret.message, 3000);
                if(ret.data && ret.data.dom){
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
    add.ztreeInit();
    add.formSubmit();
});