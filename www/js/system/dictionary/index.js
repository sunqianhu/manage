/**
 * 首页
 */

/**
 * 添加
 */
function add(){
    var url = "add.php";
    sun.layer.open({
        id: "add",
        name: "添加字典",
        url: url,
        width: 600,
        height: 400
    });
}

/**
 * 修改
 */
edit = function(id){
    var url = "edit.php?id="+id;
    sun.layer.open({
        id: "edit",
        name: "修改字典",
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