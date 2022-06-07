/**
 * 修改菜单
 */

var edit = {};

/**
 * 选择菜单
 */
edit.selectMenu = function(){
    var url = "edit_select_menu.php";
    window.parent.sun.layer.open({
        id: "layer_edit_select_menu",
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
edit.selectMenuCallback = function(node){
    var domParentId = $("#parent_id");
    var domParentName = $("#parent_name");
    
    domParentId.val(node.id);
    domParentName.val(node.name);
}

/**
 * 提交表单
 */
edit.formSubmit = function(){
    sun.formSubmit({
        selector: ".form",
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
    edit.formSubmit();
});