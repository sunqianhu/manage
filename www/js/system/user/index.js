/**
 * 首页
 */
var searchDepartmentZtree;

/**
 * 搜索部门初始化
 */
function searchDepartmentZtreeInit(){
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
			    pIdKey: "parent_id"
            }
        },
        callback: {
            onClick: searchDepartmentSelected
        }
    };
    var nodes = departmentData;
    searchDepartmentZtree = $.fn.zTree.init($(".search #ztree"), setting, nodes);
    
    sun.dropDownClick.init({
        selector: ".search .department"
    });
}

/**
 * 设置选中的菜单
 */
function searchDepartmentSelected(event, id, node){
    var nodeDepartmentId = $(".search #department_id");
    var nodeDepartmentName = $(".search #department_name");
    var nodeDropDownContent = $(".search .department > .content");
    
    if(!node){
        return;
    }
    
    nodeDepartmentId.val(node.id);
    nodeDepartmentName.val(node.name);
    sun.dropDownClick.close(".search .department");
}

/**
 * 添加
 */
function add(){
    var url = "add.php";
    sun.layer.open({
        id: "add",
        name: "添加用户",
        url: url,
        width: 700,
        height: 500
    });
}

/**
 * 修改
 */
function edit(id){
    var url = "edit.php?id="+id;
    sun.layer.open({
        id: "edit",
        name: "修改用户",
        url: url,
        width: 700,
        height: 500
    });
}

/**
 * 启用
 */
function enable(id){
    var url = "enable.php?id="+id;
    
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

/**
 * 停用
 */
function disable(id){
    var url = "disable.php?id="+id;
    if(!confirm("确定停用吗？")){
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
    searchDepartmentZtreeInit();
    
    sun.dropDownMenuClick.init({
        selector: ".data table .operation_more"
    });
});