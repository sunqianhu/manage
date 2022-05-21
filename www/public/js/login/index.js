/**
 * 登录
 */

var index = {};

/**
 * 修改验证码
 */
index.changeCaptcha = function(){
    var url = "login-captcha.html?"+Math.random();
    var domImg = $(".captcha img");
    domImg.attr("src", url);
}

/**
 * 表单提交
 * @param selector 表单jquery选择器
 */
index.formSubmit = function(selector){
    sun.formSubmit({
        selector: selector,
        success: function(ret){
            if(ret.status == "error"){
                sun.toast("error", ret.message, 3000, function(){
                    if(ret.data.dom){
                        $(ret.data.dom).focus();
                    }
                });
                return;
            }
            
            if(ret.data.captcha == "1"){
                index.changeCaptcha();
            }
            
            location.href = "../index.html";
        }
    });
}

$(function(){
    index.formSubmit(".form");
});

