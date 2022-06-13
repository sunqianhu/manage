/**
 * 登录
 */

var index = {};

/**
 * 修改验证码
 */
index.changeCaptcha = function(){
    var url = "captcha.php?"+Math.random();
    var domImg = $(".captcha img");
    domImg.attr("src", url);
}

/**
 * 表单提交
 */
index.formSubmit = function(){
    sun.formSubmit({
        element: ".form",
        success: function(ret){
            if(ret.data.captcha == "1"){
                index.changeCaptcha();
            }
            
            if(ret.status == "error"){
                sun.toast("error", ret.message, 3000, function(){
                    if(ret.data.dom){
                        $(ret.data.dom).focus();
                    }
                });
                return;
            }
            
            location.href = "../index.php";
        }
    });
}

$(function(){
    index.formSubmit();
});

