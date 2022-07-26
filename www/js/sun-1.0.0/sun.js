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
    
    domToast = $(".sun-toast");
    if(domToast.length > 0){
        domToast.remove();
    }
    
	if(type == "success"){
		node += '<div class="sun-toast success">';
		node += '	<div class="iconfont icon-success icon"></div>';
		node += '	<div class="info">'+info+'</div>';
		node += '</div>';
	}else if(type == "error"){
		node += '<div class="sun-toast error">';
		node += '	<div class="iconfont icon-error icon"></div>';
		node += '	<div class="info">'+info+'</div>';
		node += '</div>';
	}else{
		node += '<div class="sun-toast prompt">';
		node += '	<div class="iconfont icon-prompt icon"></div>';
		node += '	<div class="info">'+info+'</div>';
		node += '</div>';
	}
	
	domBody.append(node);
    domToast = $(".sun-toast");
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
};

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
    
    node = '<div class="sun-loading sun-loading-'+id+'">';
	node += '<div class="bg"></div>';
	node += '<div class="loading">';
	node += '	<div class="img"></div>';
	node += '	<div class="info">'+info+'</div>';
	node += '</div>';
    node += '</div>';
	$("body").append(node);
};

/**
 * 加载中关闭
 * @param id id
 */
sun.loading.close = function(id){
    var domLoading = $(".sun-loading-"+id);
	domLoading.remove();
};

/**
 * 表单提交
 * @param config.element 元素
 * @param config.buttonSubmitText 提交中文字描述
 * @param config.before 请求前的回调
 * @param config.success 请求成功回调
 */
sun.formSubmit = function(config){
    if(!config.element){
        sun.toast("error", "表单选择器参数错误", 3000);
        return false;
    }
    
    var domForm = $(config.element);
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
};

/**
 * 下拉点击
 */
sun.dropDownClick = {};

/**
 * 下拉点击
 * @param string config.element 元素
 */
sun.dropDownClick.init = function(config){
    var domDocument; // 文档
    var domDropdowns; // 下拉所有
    var domDropdownTitles; // 标题所有
    var domDropdownContents; // 内容所有
    var contentDisplay = "none"; // 内容显示
    
    // 配置
    if(!config.element){
        sun.toast("error", "下拉元素选择器参数错误", 3000);
        return false;
    }
    
    // 对象
    domDocument = $(document);
    domDropdowns = $(config.element);
    domDropdownTitles = $(" > .title", domDropdowns);
    domDropdownContents = $(" > .content", domDropdowns);
    
    // 事件
    domDropdownTitles.on("click", function(){
        var domDropdownTitle = $(this);
        var domDropdown = domDropdownTitle.parent();
        var domDropdownContent = $(" > .content", domDropdown);
        
        contentDisplay = domDropdownContent.css("display");
        if(contentDisplay != "none"){
            return;
        }
        
        domDropdownContents.hide();
        domDropdownContent.slideDown(200);
    });
    
    // 关闭
	domDocument.on("click", function(e){
		if($(e.target).closest(config.element).length === 0){
			domDropdownContents.slideUp(200);
		}
	});
};

/**
 * 下拉关闭
 * @param string element 选择器
 */
sun.dropDownClick.close = function(element){
    var domDropdownContents;
    
    if(!element){
        sun.toast("error", "下拉元素选择器参数错误", 3000);
        return false;
    }
    
    domDropdownContents = $(element + " > .content");
    domDropdownContents.slideUp(200);
};

/**
 * 下拉菜单点击
 */
sun.dropDownMenuClick = {};

/**
 * 下拉菜单点击
 * @param string config.element 元素
 */
sun.dropDownMenuClick.init = function(config){
    var domDocument; // 文档
    var domDropdownMenus; // 菜单所有
    var domDropdownMenuTitles; // 标题所有
    var domDropdownMenuContents; // 内容所有
    var domDropdownMenuContentLis; // 内容的选项
    var contentDisplay = "none"; // 内容是否显示
    
    // 配置
    if(!config.element){
        sun.toast("error", "下拉元素选择器参数错误", 3000);
        return false;
    }
    
    // 对象
    domDocument = $(document);
    domDropdownMenus = $(config.element);
    domDropdownMenuTitles = $(" > .title", domDropdownMenus);
    domDropdownMenuContents = $(" > .content", domDropdownMenus);
    domDropdownMenuContentLis = $(" > ul > li", domDropdownMenuContents);
    
    // 菜单
    domDropdownMenuTitles.on("click", function(){
        var domDropdownMenuTitle = $(this);
        var domDropdownMenu = domDropdownMenuTitle.parent();
        var domDropdownMenuContent = $(" > .content", domDropdownMenu);
        
        contentDisplay = domDropdownMenuContent.css("display");
        if(contentDisplay != "none"){
            return;
        }
        
        domDropdownMenuContents.hide();
        domDropdownMenuContent.slideDown(200);
    });
    
    // 选项
    domDropdownMenuContentLis.on("click", function(){
        var domDropdownMenuContentLi = $(this);
        var domDropdownMenu = domDropdownMenuContentLi.parents(config.element).eq(0);
        var domDropdownMenuContent = $(" > .content", domDropdownMenu);
        
        domDropdownMenuContent.slideUp(200);
    });
    
    // 关闭
	domDocument.on("click", function(e){
		if($(e.target).closest(config.element).length === 0){
			domDropdownMenuContents.slideUp(200);
		}
	});
};

/**
 * 下拉菜单点击关闭
 * @param string element 选择器
 */
sun.dropDownMenuClick.close = function(element){
    var domDropdownMenuContents;
    
    if(!element){
        sun.toast("error", "下拉元素选择器参数错误", 3000);
        return false;
    }
    
    domDropdownMenuContents = $(element + " > .content");
    domDropdownMenuContents.slideUp(200);
};

/**
 * 下拉悬停初始化
 * @param string config.element 元素
 */
sun.dropDownHover = function(config){
    var domDropdowns; // 下拉所有
    var domDropdownContents; // 内容所有
    var contentDisplay = "none"; // 内容显示
    var canHide = true; // 是否可以隐藏
    var sto;
    
    // 配置
    if(!config.element){
        sun.toast("error", "下拉元素选择器参数错误", 3000);
        return false;
    }
    
    // 对象
    domDropdowns = $(config.element);
    domDropdownContents = $(" > .content", domDropdowns);
    
    // 鼠标移入
    domDropdowns.on("mouseenter", function(){
        var domDropdown = $(this);
        var domDropdownContent = $(" > .content", domDropdown);
        
        contentDisplay = domDropdownContent.css("display");
        if(contentDisplay != "none"){
            return;
        }
        
        canHide = false;
        domDropdownContents.hide();
        domDropdownContent.slideDown(200);
    });
    
    // 事件移除
    domDropdowns.on("mouseleave", function(){
        var domDropdown = $(this);
        var domDropdownContent = $(" > .content", domDropdown);
        
        // 可以关闭
        expanded = true;
        
        // 延时关闭
        if(sto){
            clearTimeout(sto);
        }
        sto = setTimeout(function(){
            if(expanded){
                domDropdownContent.slideUp(200);
            }
        }, 500);
    });
};

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

    node = '<div class="sun-layer" id="'+config.id+'">';
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
};

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
};

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
};

/**
 * 分页
 */
sun.pagination = {};

/**
 * 分页跳转到指定页
 * @param string url 分页模板链接
 * @param string id 分页对象id
 */
sun.pagination.skip = function(url, id){
    var domInput = $(".pagination_skip_"+id);
    var pageSize = 0;
    var pageCurrent = 0;
    
    if(domInput == null){
        sun.toast("error", "分页参数错误", 3000);
        return;
    }
    
    pageSize = domInput.attr("page_size");
    pageCurrent = domInput.val();
    if(isNaN(pageCurrent) || pageCurrent < 1){
        sun.toast("error", "页数错误", 3000);
        return;
    }
    
    url = url.replace("PAGE_SIZE", pageSize);
    url = url.replace("PAGE_CURRENT", pageCurrent);
    location.href = url;
};

/**
 * 分页每页显示记录数
 * @param string url 分页模板链接
 * @param string id 分页对象id
 */
sun.pagination.limit = function(url, th){
    var domSelect = $(th);
    var pageSize = domSelect.val();
    var pageCurrent = 1;
    
    if(isNaN(pageSize) || pageSize < 1){
        sun.toast("error", "每页显示记录数参数错误", 3000);
        return;
    }
    
    url = url.replace("PAGE_SIZE", pageSize);
    url = url.replace("PAGE_CURRENT", pageCurrent);
    location.href = url;
};

/**
 * 表格树
 */
sun.treeTable = {};

/**
 * 表格树初始化
 * @param string config.element 元素
 * @param string config.column 那一列
 * @param string config.expand 展开几级
 */
sun.treeTable.init = function(config){
    var domTable; // 表格
    var domTrs; // 所有tr
    var domTr; // 一个tr
    var domTd; // 需要处理的td
    var domChildTrs; // 所有子tr
    var trLength = 0; // tr数量
    var trChildLength = 0; // 子tr长度
    var level = 1; // 级别
    var i = 0; // for 索引
    var id = 0; // id
    var parentId = 0; // 父级id
    
    // 配置
    if(!config.element || !config.column || !config.expand){
        sun.toast("error", "表格树参数错误", 3000);
        return false;
    }
    
    // trs
    domTable = $(config.element);
    domTrs = $("> tbody > tr", domTable);
    trLength = domTrs.length;
    if(trLength == 0){
        domTrs = $(" > tr", domTable);
        trLength = domTrs.length;
    }
    if(trLength == 0){
        return;
    }
    
    // 初始化
    for(i = 0; i < trLength; i ++){
        domTr = domTrs.eq(i);
        domTd = $(" > td", domTr).eq(config.column);
        id = domTr.attr("tree_table_id");
        domChildTrs = $("tr[tree_table_parent_id='"+id+"']", domTable);
        trChildLength = domChildTrs.length;
        level = domTr.attr("tree_table_level");
        
        domTd.css("padding-left", (30 * (level - 1))+"px");
        if(trChildLength > 0){
            domTd.prepend('<span class="iconfont icon-arrow-down arrow" onclick="sun.treeTable.toggle(this)"></span>');
        }
        
        // 展开级别
        if(level >= config.expand){
            domTr.addClass("close");
        }
        if(level > config.expand){
            domTr.hide();
        }
    }    
}

/**
 * 表格树切换
 * @param obj th 箭头对象
 */
sun.treeTable.toggle = function(th){
    var domArrow = $(th);
    var domTr = domArrow.parents("tr").eq(0);
    var id = domTr.attr("tree_table_id");
    var close = domTr.hasClass("close"); // 是否关闭
    
    if(close){
        domTr.removeClass("close");
        sun.treeTable.childOpen(id);
    }else{
        domTr.addClass("close");
        sun.treeTable.childClose(id);
    }
}

/**
 * 表格树子项打开
 */
sun.treeTable.childOpen = function(id){
    var domTrs = $("tr[tree_table_parent_id='"+id+"']");
    var domTr;
    var trLength = domTrs.length;
    var id = 0;
    var close = false; // 是否关闭
    
    if(trLength == 0){
        return;
    }
    
    domTrs.each(function(){
        domTr = $(this);
        id = domTr.attr("tree_table_id");
        close = domTr.hasClass("close");
        
        domTr.show();
        if(!close){
            sun.treeTable.childOpen(id);
        }
    });
}

/**
 * 表格树子项关闭
 */
sun.treeTable.childClose = function(id){
    var domTrs = $("tr[tree_table_parent_id='"+id+"']");
    var domTr;
    var trLength = domTrs.length;
    var id = 0;
    
    if(trLength == 0){
        return;
    }
    
    domTrs.each(function(){
        domTr = $(this);
        id = domTr.attr("tree_table_id");
        
        domTr.hide();
        sun.treeTable.childClose(id);
    });
}

/**
 * 文件上传
 * @param string config.element 元素
 * @param string config.name 文件上传表单字段名
 * @param string config.accept 可选择的文件mime类型
 * @param string config.url 处理url
 * @param obj config.data 额外的参数 [{key: "", value: ""}]
 * @param string config.success 成功回调
 */
sun.fileUpload = function(config){
    var domButton; // 按钮上传
    var domFile; // 表单控件file
    var domNativeFile; // 表单控件file原生
    var domProgress; // 进度条
    var domProgressChartBg; // 进度条图形背景
    var domProgressText; // 进度条文字
    var node = ""; // 节点
    var formData; // 表单对象
    var i = 0; // 遍历索引
        
    // 验证
    if(!config.element || !config.name){
        sun.toast("error", "文件上传参数错误", 3000);
        return false;
    }
    
    domButton = $(config.element);
    if(domButton.length === 0){
        sun.toast("error", "文件上传element参数错误", 3000);
        return false;
    }
    
    // 节点
    node = '<input type="file" name="'+ config.name +'"';
    if(config.accept){
        node += ' accept="' + config.accept +'"';
    }
    node += ' style=" display:none">';
    domButton.after(node);
    domFile = domButton.next("input[type=file]").eq(0);
    domNativeFile = domFile.get(0);
    
    node = '<span class="sun-file-upload-progress">';
    node += '<span class="chart"><span class="bg"></span></span>';
    node += '<span class="text">0%</span>';
    node += '</span>';
    domButton.after(node);
    domProgress = domButton.next(".sun-file-upload-progress").eq(0);
    domProgressChartBg = $(" > .chart > .bg", domProgress);
    domProgressText = $(" > .text", domProgress);
    
    // 选择文件
    domButton.on("click", function(){
        domFile.trigger("click");
    });
    
    // 上传
    domFile.on("change", function(){
        if(domFile.val() == ""){
            return;
        }
        
        // 数据
        formData = new FormData();
        formData.append(config.name, domNativeFile.files[0]);
        if(config.data){
            for(data in config.data){
                formData.append(data.key, data.value);
            }
        }
        
        // 上传
        domProgress.css({"display":"inline-block"});
        $.ajax({
            url: config.url, // 上传地址
            async: true, // 异步
            type: 'post', // post方式
            data: formData, // FormData数据
            processData: false, // 不转换的信息为查询字符串
            contentType: false, // 不设置http头
            dataType: "json", // 预期服务器返回的数据类型
            xhr: function(){ // 增强 XMLHttpRequest 对象
                myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){
                    myXhr.upload.addEventListener("progress", function(e){ // 监听progress事件
                        var loaded = e.loaded;// 已上传
                        var total = e.total;// 总大小
                        var percent = Math.floor((loaded / total) * 100);
                        
                        domProgressChartBg.width(percent + "%");
                        domProgressText.html(percent + "%");
                    });
                }
                return myXhr;
            },
            success: function(ret){
                if(config.success){
                    config.success(ret);
                }
            },
            error: function(xhr, message){
                if(config.error){
                    config.success(message);
                }else{
                    sun.toast("error", message, 3000);
                }
            },
            complete: function(XHR, TS){
                domFile.get(0).value = "";
                domProgress.hide();
                
                if(config.complete){
                    config.complete();
                }
            }
        });
    });
}
