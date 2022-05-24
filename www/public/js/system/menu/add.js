/**
 * 添加菜单
 */

var add = {};

/**
 * 选择菜单
 */
add.selectMenu = function(){
    var url = "menu-add_select_menu.json";
    window.parent.sun.layer.open({
        id: "layer_add_select_menu",
        name: "选择上级菜单",
        url: url,
        width: 500,
        height: 300
    });
}

/**
 * 选择菜单回调
 * @param json node 节点数据
 */
add.selectMenuCallback = function(node){
    var domParentId = $("#parent_id");
    var domParentName = $("#parent_name");
    
    domParentId.val(node.id);
    domParentName.val(node.name);
}

/**
 * 提交表单
 */
add.formSubmit = function(){
    sun.formSubmit({
        selector: ".form",
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
    add.formSubmit();
});