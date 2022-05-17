/**
 * 部门管理
 */
var index = {};

/**
 * 添加用户
 */
index.add = function(){
    var url = "add";
    sun.layer.open({
        id: "layer_department_add",
        name: "添加部门",
        url: url,
        width: 600,
        height: 400
    });
}

/**
 * 表格行切换
 */
index.tableTrToggle = function(th){
    var domArrow = $(th);
    var domTrThis = domArrow.parents("tr").eq(0);
    var departmentId = domTrThis.attr("department_id");
    var domTrChilds = $(".data table tr[department_parent_id="+departmentId+"]");
    
    if(domTrThis.hasClass("open")){
        domTrThis.removeClass("open");
        domTrChilds.hide();
    }else{
        domTrThis.addClass("open");
        domTrChilds.show();
    }
}

/**
 * 表格初始化
 */
index.tableInit = function(th){
    var domTrs = $(".data table .init_open");
    var domTr;
    var domTrChilds;
    
    var trLength = domTrs.length;
    var i = 0;
    var departmentId = 0;
    
    if(trLength == 0){
        return;
    }
    
    for(i = 0; i < trLength; i++){
        domTr = domTrs.eq(i);
        departmentId = domTr.attr("department_id");
        domTrChilds = $(".data table tr[department_parent_id="+departmentId+"]");
        
        domTr.addClass("open");
        domTrChilds.show();
    }
}

/**
 * 提示框
 */
index.bootstrapTooltip = function(){
    $('[data-toggle="tooltip"]').tooltip();
}

$(function(){
    index.bootstrapTooltip();
    index.tableInit();
});