/**
 * 添加部门
 */
var add = {};

/**
 * 选择部门
 */
add.selectDepartment = function(){
    var url = "addSelectDepartment";
    window.parent.sun.layer.open({
        id: "layer_add_select_department",
        name: "选择父部门",
        url: url,
        width: 500,
        height: 300
    });
}

/**
 * 提交表单
 */
add.formSubmit = function(){
    sun.formSubmit({
        selector: ".form",
        success: function(ret){
            if(ret.status == "error"){
                sun.toast("error", ret.msg, 3000);
                $(ret.dom).focus();
                return;
            }
            sun.toast("success", ret.msg, 1000, function(){
            
            });
        }
    });
}

$(function(){
    add.formSubmit();
});