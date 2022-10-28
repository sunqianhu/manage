/**
 * 首页
 */

/**
 * 添加
 */
function add(){
    var url = "add.php";
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
function edit(id){
    var url = "edit.php?id="+id;
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
