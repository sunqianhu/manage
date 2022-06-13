/**
 * 修改
 */

var edit = {};

/**
 * 提交表单
 */
edit.formSubmit = function(){
    sun.formSubmit({
        element: ".form",
        success: function(ret){
            if(ret.status == "error"){
                sun.toast("error", ret.message, 3000);
                if(ret.data.dom){
                    $(ret.data.dom).focus();
                }
                return;
            }
            sun.toast("success", ret.message, 1000, function(){
                parent.sun.layer.close("layer_self_edit_password");
            });
        }
    });
}

$(function(){
    edit.formSubmit();
});