/**
 * 登录
 */

var login = {};

// 修改验证码
login.changeCaptcha = function(){
    var url = "captcha?"+Math.random();
    var domImg = $(".captcha img");
    domImg.attr("src", url);
}

/**
 * 表单提交
 * @param selector 表单jquery选择器
 */
login.formSubmit = function(selector){
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
                login.changeCaptcha();
            }
            
            location.href = "../index/index";
        }
    });
}

$(function(){
    login.formSubmit(".form");
});

