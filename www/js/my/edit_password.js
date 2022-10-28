/**
 * 修改密码
 */

/**
 * 提交表单
 */
function submitForm(){
    sun.submitForm({
        selector: ".form",
        success: function(ret){
            if(ret.status == "error"){
                sun.toast("error", ret.message, 3000);
                if(ret.data.dom){
                    $(ret.data.dom).focus();
                }
                return;
            }
            sun.toast("success", ret.message, 1000, function(){
                parent.sun.layer.close("edit_password");
            });
        }
    });
}

$(function(){
    submitForm();
});