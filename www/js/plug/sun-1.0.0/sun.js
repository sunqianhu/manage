/**
 * sun ui
 */

var sun = {};

/**
 * 打开自动消失的提示框
 * @param type 类型 success | error | prompt
 * @param msg 内容
 * @param time 时间（毫秒）
 * @param callback 回调函数
 */
sun.toast = function(type, info, time, callback){
	var node = "";
    var domBody = $("body");
    var domToast = null;
	var windowHeight = $(window).height();
	var scrollHeight = $(document).scrollTop();
    var toastWidth = 0;
    var toastHegiht = 0;
    
	if(!type || !info || !time){
		return;
	}
    
    domToast = $(".sun_toast");
    if(domToast.length > 0){
        domToast.remove();
    }
    
	if(type == "success"){
		node += '<div class="sun_toast success">';
		node += '	<div class="iconfont icon-success icon"></div>';
		node += '	<div class="info">'+info+'</div>';
		node += '</div>';
	}else if(type == "error"){
		node += '<div class="sun_toast error">';
		node += '	<div class="iconfont icon-error icon"></div>';
		node += '	<div class="info">'+info+'</div>';
		node += '</div>';
	}else{
		node += '<div class="sun_toast prompt">';
		node += '	<div class="iconfont icon-prompt icon"></div>';
		node += '	<div class="info">'+info+'</div>';
		node += '</div>';
	}
	
	domBody.append(node);
    domToast = $(".sun_toast");
    toastWidth = domToast.outerWidth();
    toastHegiht = domToast.outerHeight();
	domToast.css({"left":"50%", "top":((windowHeight - toastHegiht) / 2 + scrollHeight)+"px", "margin-left":"-"+(toastWidth / 2)+"px"});
    
	setTimeout(function(){
		domToast.animate({top:"-50px", opacity:"0"}, 500, function(){
			domToast.remove();
			if(typeof(callback) != "undefined") {
				callback();
			}
		});
	}, time);
}

/**
 * 加载中
 */
sun.loading = {};
/**
 * 加载中打开
 * @param id id
 * @param info 描述
 */
sun.loading.open = function(id, info){
    var node = "";
    
	node = '<div class="sun_loading_bg sun_loading_bg_'+id+'"></div>';
	node += '<div class="sun_loading sun_loading_'+id+'">';
	node += '	<div class="img"></div>';
	node += '	<div class="info">'+info+'</div>';
	node += '</div>';
	$("body").append(node);
}

/**
 * 加载中关闭
 * @param id id
 */
sun.loading.close = function(id){
	$(".sun_loading_bg_"+id).remove();
	$(".sun_loading_"+id).remove();
}

/**
 * 表单提交
 * @param config 配置
 */
sun.formSubmit = function(config){
    if(!config.selector){
        sun.toast("error", "表单选择器参数错误", 3000);
        return false;
    }
    
    var domForm = $(config.selector);
    var domInputSubmits; // 所有input提交按钮
    var domInputSubmit; // 一个input提交按钮
    var domButtonSubmits; // 所有button提交按钮
    var domButtonSubmit; // 一个button提交按钮
    var url = ""; // 提交url
    var method = ""; // 提交方式
    var data; // 提交数据
    
    // 验证
    if(domForm.length == 0){
        sun.toast("error", "没有找到表单节点", 3000);
        return false;
    }
    
    // 初始值
    if(!config){
        config = {};
    }
    if(!config.buttonSubmitText){
        config.buttonSubmitText = "处理中...";
    }
    
    url = domForm.attr("action");
    method = domForm.attr("method");
    data = domForm.serialize();

    domInputSubmits = $("input:submit", domForm);
    domButtonSubmits = $("button[type='submit']", domForm);

    // 提交前
    if(config.before){
        if(!config.before()){
            return false;
        }
    }

    // 按钮文字改成提交中
    domInputSubmits.each(function(index, element) {
        domInputSubmit = $(this);
        domInputSubmit.attr({"disabled":"disabled"}); // 禁用防止重复提交
        if(config.buttonSubmitText){
            domInputSubmit.attr({"value_old":domInputSubmit.val()});
            domInputSubmit.val(config.buttonSubmitText);
        }
        if(config.buttonSubmitClass){
            domInputSubmit.addClass(config.buttonSubmitClass);
        }
    });
    domButtonSubmits.each(function(index, element) {
        domButtonSubmit = $(this);
        domButtonSubmit.attr({"disabled":"disabled"});
        if(config.buttonSubmitText){
            domButtonSubmit.attr({"value_old":domButtonSubmit.html()});
            domButtonSubmit.html(config.buttonSubmitText);
        }
        if(config.buttonSubmitClass){
            domButtonSubmit.addClass(config.buttonSubmitClass);
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
        error: function(obj, info, e){
            sun.toast("error", info, 3000);
        },
        complete: function(){
            if(config.complete){
                config.complete();
            }

            // 按钮还原
            setTimeout(function(){
                domInputSubmits.each(function(index, element) {
                    domInputSubmit = $(this);
                    if(config.buttonSubmitText){
                        domInputSubmit.val(domInputSubmit.attr("value_old"));
                    }
                    if(config.buttonSubmitClass){
                        domInputSubmit.removeClass(config.buttonSubmitClass);
                    }
                    domInputSubmit.removeAttr("disabled");
                });
                domButtonSubmits.each(function(index, element) {
                    domButtonSubmit = $(this);
                    if(config.buttonSubmitText){
                        domButtonSubmit.html(domButtonSubmit.attr("value_old"));
                    }
                    if(config.buttonSubmitClass){
                        domButtonSubmit.removeClass(config.buttonSubmitClass);
                    }
                    domButtonSubmit.removeAttr("disabled");
                });
            }, 1000);
        }
    });

    return false;
}

/**
 * 下拉菜单
 */
sun.dropdown = function(){
    var domDocument; // 文档
    var domDropdownButtons; // 下拉按钮
    var domDropdownMenus; // 菜单
    
    domDocument = $(document);
    domDropdownButtons = $(".sun_dropdown_button");
    domDropdownMenus = $(".sun_dropdown_menu");
    
    domDropdownButtons.on("click", function(){
        var domDropdownButton = $(this);
        var domDropdownMenu = domDropdownButton.nextAll(".sun_dropdown_menu");
        
        domDropdownMenus.hide();
        domDropdownMenu.stop().slideToggle(100);
    });
    
    // 关闭
	domDocument.on("click", function(e){
		if($(e.target).closest(".sun_dropdown_button").length === 0){
			domDropdownMenus.slideUp(100);
		}
	});
}
