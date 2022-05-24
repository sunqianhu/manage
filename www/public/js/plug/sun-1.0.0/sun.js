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
 * @param config.selector 选择器
 * @param config.buttonSubmitText 提交中文字描述
 * @param config.before 请求前的回调
 * @param config.success 请求成功回调
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
    if(!config.buttonSubmitText){
        config.buttonSubmitText = "处理中...";
    }
    
    domForm.on("submit", function(){
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

        // 提交
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
    });
}

/**
 * 下拉菜单
 * @param config.selector 选择器
 */
sun.dropdown = function(config){
    var domDocument; // 文档
    var domDropdowns; // 菜单组所有
    var domDropdownButtons; // 按钮所有
    var domDropdownMenus; // 菜单所有
    
    // 配置
    if(!config.selector){
        sun.toast("error", "selector参数错误", 3000);
        return false;
    }
    if(!config.trigger){
        config.trigger = ["click"];
    }
    
    // 对象
    domDocument = $(document);
    domDropdowns = $(config.selector);
    domDropdownButtons = $(".sun_dropdown_button", domDropdowns);
    domDropdownMenus = $(".sun_dropdown_menu", domDropdowns);
    
    // 事件
    domDropdownButtons.on(config.trigger.join(","), function(){
        var domDropdownButton = $(this);
        var domDropdown = domDropdownButton.parent();
        var domDropdownMenu = $(".sun_dropdown_menu", domDropdown);
        
        domDropdownMenus.hide();
        domDropdownMenu.slideDown(100);
    });
    
    // 关闭
	domDocument.on("click", function(e){
		if($(e.target).closest(".sun_dropdown_button").length === 0){
			domDropdownMenus.hide();
		}
	});
}

/**
 * 弹层
 */
sun.layer = {};

/**
 * 弹层打开
 * @param config.id id
 * @param config.name 名称
 * @param config.url url
 * @param config.width 宽度
 * @param config.height 高度
 */
sun.layer.open = function(config){
    var domBody = $("body");
    var domLayer;
    var domLayerBg;
    var domLayerLayer;
    var domLayerTitle;
    var domLayerIframe;
    var domLayerLoading;
    
    var windowWidth = $(window).width();
	var windowHeight = $(window).height();
	var left = 0;
	var top = 0;
    var node = ""; // 节点信息
	
	// 定位
	if(config.height > windowHeight){
		config.height = windowHeight;
		top = 0;
	}else{
		top = (windowHeight - config.height) / 2;
	}
	if(config.width > windowWidth){
		config.width = windowWidth;
		left = 0;
	}else{
		left = (windowWidth - config.width) / 2;
	}

    node = '<div class="sun_layer" id="'+config.id+'">';
    node += '   <div class="bg"></div>';
    node += '   <div class="layer" style=" width:'+config.width+'px; height:'+config.height+'px; left:'+left+'px; top:0px">';
    node += '       <div class="title">';
    node += '           <span class="name">'+config.name+'</span>';
    node += '           <a href="javascript:;" class="close" onclick="sun.layer.close(\''+config.id+'\');">×</a>';
    node += '       </div>';
    node += '       <iframe id="'+config.id+'_iframe" name="'+config.id+'_iframe" src="'+config.url+'" style=" width:100%; height:'+(config.height - 50)+'px" frameborder="0" scrolling="auto"></iframe>';
    node += '       <div class="loading">加载中...</div>';
    node += '   </div>';
    node += '</div>';
	domBody.append(node);
	
	// 变量
	domLayer = $("#"+config.id);
	domLayerBg = $(".bg", domLayer);
	domLayerLayer = $(".layer", domLayer);
	domLayerTitle = $(".layer > .title", domLayer);
	domLayerIframe = $(".layer > iframe", domLayer);
	domLayerLoading = $(".layer > .loading", domLayer);
	
	// 加载完成
	domLayerIframe.load(function(){
		domLayerLoading.fadeOut(500);
	});
	
	// 动画显示
	domLayerLayer.animate({top: top + "px", opacity:1}, 500, function(){});
	
	// 移动层
	domLayerTitle.on("mousedown", function(event){
		event = event || window.event;
		
		var isMove = true;
		var currsorX = event.pageX - domLayerLayer.offset().left;
		var currsorY = event.pageY - domLayerLayer.offset().top;
		
		$(document).on("mousemove", function(event){
            var left = 0;
            var top = 0;
        
			if(!isMove){
                return;
			}
            
            left = ((event.pageX - $(document).scrollLeft()) - currsorX);
            top = ((event.pageY - $(document).scrollTop()) - currsorY);
            if(top < 0){
                top = 0;
            }
            domLayerLayer.css({"left":(left + "px"), "top":(top + "px")});
		});
        
        $(document).on("mouseup", function(){
			isMove = false;
		});
	});
}

/**
 * 弹窗关闭
 * @param id id
 */
sun.layer.close = function(id){
	var domLayer = $('#'+id);
    var domLayerBg = $(".bg", domLayer);
    var domLayerLayer = $(".layer", domLayer);
    
	domLayerBg.animate({opacity:"0"});
	domLayerLayer.animate({top:"-50px", opacity:"0"}, 500, function(){
		domLayer.remove();
	});
}

/**
 * 找到指定id的弹层iframe
 * @param obj win window对象
 * @param int id id
 * @return obj window对象
 */
sun.layer.getIframeWindow = function(win, id){
    var iframeWindow;
    
    if(!win.frames[id]){
        sun.toast("error", "没有找到弹层页面对象", 3000);
        return false;
    }
    iframeWindow = win.frames[id];
    
    return iframeWindow;
}