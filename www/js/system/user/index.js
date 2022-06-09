/**
 * 首页
 */
var index = {
    searchDepartmentZtree: null
};

/**
 * 搜索部门初始化
 */
index.searchDepartmentZtreeInit = function(){
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
			    pIdKey: "parent_id"
            }
        },
        callback: {
            onClick: index.searchDepartmentSelected
        }
    };
    var nodes = index.departmentData;
    index.searchDepartmentZtree = $.fn.zTree.init($(".search #ztree"), setting, nodes);
    
    sun.dropDown({
        selector: ".search .department"
    });
}

/**
 * 设置选中的菜单
 */
index.searchDepartmentSelected = function(event, id, node){
    var domDepartmentId = $(".search #department_id");
    var domDepartmentName = $(".search #department_name");
    var domDropDownContent = $(".search .department > .content");
    
    if(!node){
        return;
    }
    
    domDepartmentId.val(node.id);
    domDepartmentName.val(node.name);
    sun.dropDownClose(".search .department");
}

/**
 * 添加
 */
index.add = function(){
    var url = "add.php";
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
    var url = "edit.php?id="+id;
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
    index.searchDepartmentZtreeInit();
    
    sun.dropDownMenu({
        selector: ".data table .operation_more"
    });
});