/**
 * 首页
 */
var index = {};

/**
 * 添加
 */
index.add = function(){
    var url = "role-add.html";
    sun.layer.open({
        id: "layer_role_add",
        name: "添加角色",
        url: url,
        width: 600,
        height: 400
    });
}

/**
 * 修改
 */
index.edit = function(id){
    var url = "role-edit.html?id="+id;
    sun.layer.open({
        id: "layer_role_edit",
        name: "修改角色",
        url: url,
        width: 600,
        height: 400
    });
}

/**
 * 删除
 */
index.delete = function(id){
    var url = "role-delete.json?id="+id;
    if(!confirm("确定删除吗？")){
        return;
    }
    
    $.getJSON(url, function(ret){
        if(ret.status == "error"){
            sun.toast("error", ret.message, 3000);
            return;
        }
        sun.toast("success", ret.message, 1000, function(){
            location.reload();
        });
    });
}

$(function(){
});