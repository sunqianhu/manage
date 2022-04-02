/**
 * 登录
 */

// 修改验证码
function changeCaptcha(){
    var url = "index.php?c=login&a=captcha&mo="+Math.random();
    var domImg = $(".captcha img");
    domImg.attr("src", url);
}

/**
 * 表单提交
 */
function formSubmit(){
    $(".form").sunFormSubmit({
        buttonSubmitText: "处理中...",
        success: function(ret){
            if(ret.status == "error"){
                sunToast.open("error", ret.msg, 3000, function(){
                    if(ret.dom){
                        $(ret.dom).focus();
                    }
                    if(ret.captcha == "1"){
                        changeCaptcha();
                    }
                });
                return;
            }
            location.href = "index.php?c=index&a=main";
        }
    });
}

$(function(){
    formSubmit();
});