/**
 * 菜单管理
 */
var index = {};

/**
 * 添加
 * @param int parentId 上级id
 */
index.add = function(parentId){
    var url = "add.php?parent_id="+parentId;
    sun.layer.open({
        id: "layer_menu_add",
        name: "添加菜单",
        url: url,
        width: 700,
        height: 500
    });
}

/**
 * 修改
 */
index.edit = function(id){
    var url = "edit.php?id="+id;
    sun.layer.open({
        id: "layer_menu_edit",
        name: "修改菜单",
        url: url,
        width: 700,
        height: 500
    });
}

/**
 * 删除
 */
index.delete = function(id){
    var url = "delete.php?id="+id;
    var domTr = $(".data table .tr"+id);
    if(!confirm("确定删除吗？")){
        return;
    }
    
    $.getJSON(url, function(ret){
        if(ret.status == "error"){
            sun.toast("error", ret.message, 3000);
            return;
        }
        domTr.remove();
    });
}

$(function(){
    // 表格树
    sun.treeTable.init({
        selector: ".sun_treetable",
        column: 1,
        expand: 3
    });
});