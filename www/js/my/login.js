/**
 * 登录
 */

/**
 * 粒子背景
 */
function bg(){
    $(".page > .bg").particleground({
        dotColor: "rgba(50,100,150,0.05)",
        lineColor: "rgba(50,100,150,0.05)",
    });
}

/**
 * 修改验证码
 */
function changeCaptcha(){
    var url = "captcha.php?"+Math.random();
    var nodeImg = $(".captcha img");
    nodeImg.attr("src", url);
}

/**
 * 提交表单
 */
function submitForm(){
    sun.submitForm({
        selector: ".form",
        success: function(ret){
            if(ret.data.captcha == "1"){
                changeCaptcha();
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
    bg();
    submitForm();
});

