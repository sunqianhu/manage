/**
 * sun表单提交插件
 * @version 1.0.0
 */

(function ($){
    $.fn.sunFormSubmit = function(config){
        var domForm = $(this);
        var url = "";
        var method = "";
        var domButtonSubmit1 = null;
        var domButtonSubmit2 = null;
        var button = null; // 每个提交按钮
        var data = "";

        if(domForm.length == 0){
            return;
        }

        domForm.on("submit", function(){
            if(config.before){
                if(!config.before()){
                    return false;
                }
            }

            url = domForm.attr("action");
            method = domForm.attr("method");
            data = domForm.serialize();

            domButtonSubmit1 = $("input:submit", domForm);
            domButtonSubmit2 = $("button[type='submit']", domForm);

            // 按钮文字改成提交中
            domButtonSubmit1.each(function(index, element) {
                button = $(this);
                button.attr({"disabled":"disabled"});
                if(config.buttonSubmitText){
                    button.attr({"value_old":button.val()});
                    button.val(config.buttonSubmitText);
                }
                if(config.buttonSubmitClass){
                    button.addClass(config.buttonSubmitClass);
                }
            });
            domButtonSubmit2.each(function(index, element) {
                button = $(this);
                button.attr({"disabled":"disabled"});
                if(config.buttonSubmitText){
                    button.attr({"value_old":button.html()});
                    button.html(config.buttonSubmitText);
                }
                if(config.buttonSubmitClass){
                    button.addClass(config.buttonSubmitClass);
                }
            });

            // 请求网络
            $.ajax({
                url: url,
                data: data,
                type: method,
                dataType: "json",
                success: function(ret){
                    if(config.success){
                        config.success(ret);
                    }
                },
                error: function(obj, errorInfo, e){
                    alert(errorInfo);
                },
                complete: function(){
                    if(config.complete){
                        config.complete();
                    }

                    //按钮还原
                    setTimeout(function(){
                        domButtonSubmit1.each(function(index, element) {
                            button = $(this);
                            if(config.buttonSubmitText){
                                button.val(button.attr("value_old"));
                            }
                            if(config.buttonSubmitClass){
                                button.removeClass(config.buttonSubmitClass);
                            }
                            button.removeAttr("disabled");
                        });
                        domButtonSubmit2.each(function(index, element) {
                            button = $(this);
                            if(config.buttonSubmitText){
                                button.html(button.attr("value_old"));
                            }
                            if(config.buttonSubmitClass){
                                button.removeClass(config.buttonSubmitClass);
                            }
                            button.removeAttr("disabled");
                        });
                    }, 2000);
                }
            });

            return false;
        });
    }
})(jQuery);