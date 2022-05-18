/**
 * 登录
 */

// 修改验证码
function changeCaptcha(){
    var url = "captcha?"+Math.random();
    var domImg = $(".captcha img");
    domImg.attr("src", url);
}

/**
 * 表单提交
 * @param selector 表单jquery选择器
 */
function formSubmit(selector){
    sun.formSubmit({
        selector: selector,
        success: function(ret){
            console.log(ret);
            if(ret.status == "error"){
                sun.toast("error", ret.msg, 3000, function(){
                    if(ret.dom){
                        $(ret.dom).focus();
                    }
                });
                return;
            }
            
            if(ret.captcha == "1"){
                changeCaptcha();
            }
            
            location.href = "../index/index";
        }
    });
}

$(function(){
    formSubmit(".form");
});

