/**
 * 部门管理
 */

/**
 * 添加
 * @param {number} parentId 上级id
 */
function add(parentId){
    var url = "add.php?parent_id="+parentId;
    sun.layer.open({
        id: "department_add",
        name: "添加部门",
        url: url,
        width: 600,
        height: 400
    });
}

/**
 * 修改
 */
function edit(id){
    var url = "edit.php?id="+id;
    sun.layer.open({
        id: "department_edit",
        name: "修改部门",
        url: url,
        width: 600,
        height: 400
    });
}

/**
 * 删除
 */
function myDelete(id){
    var url = "delete.php?id="+id;
    var nodeTr = $(".data table .tr"+id);
    if(!confirm("确定删除吗？")){
        return;
    }
    
    $.getJSON(url, function(ret){
        if(ret.status == "error"){
            sun.toast("error", ret.message, 3000);
            return;
        }
        sun.toast("success", ret.message, 1000, function(){
            nodeTr.remove();
        });
    });
}

$(function(){
    // 表格树
    sun.treeTable.init({
        selector: ".sun-treetable",
        column: 1,
        expand: 3
    });
    
    sun.dropDownMenuClick.init({
        selector: ".data table .operation_more"
    });
});