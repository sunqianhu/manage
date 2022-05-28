/**
 * 首页
 */
var index = {};

/**
 * 添加
 */
index.add = function(){
    var url = "user-add.html";
    sun.layer.open({
        id: "layer_user_add",
        name: "添加用户",
        url: url,
        width: 700,
        height: 500
    });
}

/**
 * 修改
 */
index.edit = function(id){
    var url = "user-edit.html?id="+id;
    sun.layer.open({
        id: "layer_user_edit",
        name: "修改用户",
        url: url,
        width: 700,
        height: 500
    });
}

/**
 * 删除
 */
index.delete = function(id){
    var url = "user-delete.json?id="+id;
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