/**
 * 首页
 */
var index = {};

/**
 * 添加
 */
index.add = function(){
    var url = "dictionary-add.html";
    sun.layer.open({
        id: "layer_dictionary_add",
        name: "添加字典",
        url: url,
        width: 600,
        height: 400
    });
}

/**
 * 修改
 */
index.edit = function(id){
    var url = "dictionary-edit.html?id="+id;
    sun.layer.open({
        id: "layer_dictionary_edit",
        name: "修改字典",
        url: url,
        width: 600,
        height: 400
    });
}

/**
 * 删除
 */
index.delete = function(id){
    var url = "dictionary-delete.json?id="+id;
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