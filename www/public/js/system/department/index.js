/**
 * 部门管理
 */
/**
 * 添加用户
 */
function add(){
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
 * 提示框
 */
function bootstrapTooltip(){
    $('[data-toggle="tooltip"]').tooltip();
}

/**
 * 表格树展开关闭
 */
function treeTableToggle(th){
    var domArrow = $(th);
    var domTr = domArrow.parents("tr").eq(0);
    var id = domTr.attr("tree_table_id");
    var close = domTr.hasClass("parent_close"); // 是否关闭
    
    if(close){
        domTr.removeClass("parent_close");
        treeTableSonOpen(id);
    }else{
        domTr.addClass("parent_close");
        // 递归关闭子项 添加 child_close
        treeTableChildClose(id);
    }
    
}

/**
 * 表格树打开儿子层
 */
function treeTableSonOpen(id){
    var domTrs = $(".data table tr[tree_table_parent_id='"+id+"']");
    var domTr;
    var trLength = domTrs.length;
    
    if(trLength == 0){
        return;
    }
    
    domTrs.each(function(){
        domTr = $(this);
        domTr.removeClass("child_close");
    });
}

/**
 * 表格树递归关闭子项
 */
function treeTableChildClose(id){
    var domTrs = $(".data table tr[tree_table_parent_id='"+id+"']");
    var domTr;
    var trLength = domTrs.length;
    var id = 0;
    
    if(trLength == 0){
        return;
    }
    
    domTrs.each(function(){
        domTr = $(this);
        id = domTr.attr("tree_table_id");
        
        domTr.addClass("child_close");
        domTr.addClass("parent_close");
        treeTableChildClose(id);
    });
}

$(function(){
    bootstrapTooltip();
});