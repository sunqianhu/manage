/**
 * sun ui
 */

var sun = {};

/**
 * 打开自动消失的提示框
 * @param {string} type 类型 success | error | prompt
 * @param msg 内容
 * @param time 时间（毫秒）
 * @param callback 回调函数
 */
sun.toast = function(type, info, time, callback){
	var tag = "";
    var nodeBody = $("body");
    var nodeToast = null;
	var windowHeight = $(window).height();
	var scrollHeight = $(document).scrollTop();
    var toastWidth = 0;
    var toastHegiht = 0;
    
	if(!type || !info || !time){
		return;
	}
    
    nodeToast = $(".sun-toast");
    if(nodeToast.length > 0){
        nodeToast.remove();
    }
    
	if(type == "success"){
		tag += '<div class="sun-toast success">';
		tag += '	<div class="iconfont icon-success icon"></div>';
		tag += '	<div class="info">'+info+'</div>';
		tag += '</div>';
	}else if(type == "error"){
		tag += '<div class="sun-toast error">';
		tag += '	<div class="iconfont icon-error icon"></div>';
		tag += '	<div class="info">'+info+'</div>';
		tag += '</div>';
	}else{
		tag += '<div class="sun-toast prompt">';
		tag += '	<div class="iconfont icon-prompt icon"></div>';
		tag += '	<div class="info">'+info+'</div>';
		tag += '</div>';
	}
	
	nodeBody.append(tag);
    nodeToast = $(".sun-toast");
    toastWidth = nodeToast.outerWidth();
    toastHegiht = nodeToast.outerHeight();
	nodeToast.css({"left":"50%", "top":((windowHeight - toastHegiht) / 2 + scrollHeight)+"px", "margin-left":"-"+(toastWidth / 2)+"px"});
    
	setTimeout(function(){
		nodeToast.animate({top:"-50px", opacity:"0"}, 500, function(){
			nodeToast.remove();
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
    var tag = "";
    
    tag = '<div class="sun-loading sun-loading-'+id+'">';
	tag += '<div class="bg"></div>';
	tag += '<div class="loading">';
	tag += '	<div class="img"></div>';
	tag += '	<div class="info">'+info+'</div>';
	tag += '</div>';
    tag += '</div>';
	$("body").append(tag);
};

/**
 * 加载中关闭
 * @param id id
 */
sun.loading.close = function(id){
    var nodeLoading = $(".sun-loading-"+id);
	nodeLoading.remove();
};

/**
 * 提交表单
 * @param config.selector 表单form选择器
 * @param config.before 请求前的回调
 * @param config.success 请求成功回调
 * @param config.buttonSelector 提交按钮节点选择器
 * @param config.buttonText 提交按钮提交时的文字
 * @param config.buttonClass 提交按钮提交时的样式
 */
sun.submitForm = function(config){
    var nodeForm; // 表单节点
    var submitHandle; // 提交处理程序
    
    // 验证
    if(!config.selector){
        sun.toast("error", "表单选择器参数错误", 3000);
        return false;
    }
    nodeForm = $(config.selector);
    if(nodeForm.length == 0){
        sun.toast("error", "没有找到表单节点", 3000);
        return false;
    }
    
    // 初始化
    if(!config.buttonSelector){
        config.buttonSelector = "input:submit";
    }
    if(!config.buttonText){
        config.buttonText = "处理中...";
    }
    
    // 提交处理
    submitHandle = function(){
        var nodeButtons; // 按钮所有
        var nodeButton; // 按钮一个
        var buttonText = ""; // 按钮文字
        var url = ""; // 提交url
        var method = ""; // 提交方式
        var data; // 提交数据

        url = nodeForm.attr("action");
        method = nodeForm.attr("method");
        data = nodeForm.serialize();
        nodeButtons = $(config.buttonSelector, nodeForm);

        // 提交前
        if(config.before){
            if(!config.before()){
                return false;
            }
        }
        
        // 按钮文字改成提交中
        nodeButtons.each(function(index, element) {
            nodeButton = $(this);
            buttonText = nodeButton.val();
            if(!buttonText){
                buttonText = nodeButton.html();
            }
            
            nodeButton.attr({"disabled": "disabled"}); // 禁用防止重复提交
            nodeButton.attr({"value_old": buttonText});
            nodeButton.val(config.buttonText);
            nodeButton.text(config.buttonText);
            if(config.buttonClass){
                nodeButton.addClass(config.buttonClass);
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
                    nodeButtons.each(function(index, element) {
                        nodeButton = $(this);
                        buttonText = nodeButton.attr("value_old");
                        
                        nodeButton.val(buttonText);
                        nodeButton.text(buttonText);
                        if(config.buttonClass){
                            nodeButton.removeClass(config.buttonClass);
                        }
                        nodeButton.removeAttr("disabled");
                    });
                }, 1000);
            }
        });

        return false;
    }
    
    // 绑定事件
    nodeForm.off("submit");
    nodeForm.on("submit", submitHandle);
};

/**
 * 下拉点击
 */
sun.dropDownClick = {};

/**
 * 下拉点击
 * @param string config.selector 元素
 */
sun.dropDownClick.init = function(config){
    var nodeDocument; // 文档
    var nodeDropdowns; // 下拉所有
    var nodeDropdownTitles; // 标题所有
    var nodeDropdownContents; // 内容所有
    var contentDisplay = "none"; // 内容显示
    
    // 配置
    if(!config.selector){
        sun.toast("error", "下拉元素选择器参数错误", 3000);
        return false;
    }
    
    // 对象
    nodeDocument = $(document);
    nodeDropdowns = $(config.selector);
    nodeDropdownTitles = $(" > .title", nodeDropdowns);
    nodeDropdownContents = $(" > .content", nodeDropdowns);
    
    // 事件
    nodeDropdownTitles.on("click", function(){
        var nodeDropdownTitle = $(this);
        var nodeDropdown = nodeDropdownTitle.parent();
        var nodeDropdownContent = $(" > .content", nodeDropdown);
        
        contentDisplay = nodeDropdownContent.css("display");
        if(contentDisplay != "none"){
            return;
        }
        
        nodeDropdownContents.hide();
        nodeDropdownContent.slideDown(200);
    });
    
    // 关闭
	nodeDocument.on("click", function(e){
		if($(e.target).closest(config.selector).length === 0){
			nodeDropdownContents.slideUp(200);
		}
	});
};

/**
 * 下拉关闭
 * @param string element 选择器
 */
sun.dropDownClick.close = function(element){
    var nodeDropdownContents;
    
    if(!element){
        sun.toast("error", "下拉元素选择器参数错误", 3000);
        return false;
    }
    
    nodeDropdownContents = $(element + " > .content");
    nodeDropdownContents.slideUp(200);
};

/**
 * 下拉菜单点击
 */
sun.dropDownMenuClick = {};

/**
 * 下拉菜单点击
 * @param string config.selector 元素
 */
sun.dropDownMenuClick.init = function(config){
    var nodeDocument; // 文档
    var nodeDropdownMenus; // 菜单所有
    var nodeDropdownMenuTitles; // 标题所有
    var nodeDropdownMenuContents; // 内容所有
    var nodeDropdownMenuContentLis; // 内容的选项
    var contentDisplay = "none"; // 内容是否显示
    
    // 配置
    if(!config.selector){
        sun.toast("error", "下拉元素选择器参数错误", 3000);
        return false;
    }
    
    // 对象
    nodeDocument = $(document);
    nodeDropdownMenus = $(config.selector);
    nodeDropdownMenuTitles = $(" > .title", nodeDropdownMenus);
    nodeDropdownMenuContents = $(" > .content", nodeDropdownMenus);
    nodeDropdownMenuContentLis = $(" > ul > li", nodeDropdownMenuContents);
    
    // 菜单
    nodeDropdownMenuTitles.on("click", function(){
        var nodeDropdownMenuTitle = $(this);
        var nodeDropdownMenu = nodeDropdownMenuTitle.parent();
        var nodeDropdownMenuContent = $(" > .content", nodeDropdownMenu);
        
        contentDisplay = nodeDropdownMenuContent.css("display");
        if(contentDisplay != "none"){
            return;
        }
        
        nodeDropdownMenuContents.hide();
        nodeDropdownMenuContent.slideDown(200);
    });
    
    // 选项
    nodeDropdownMenuContentLis.on("click", function(){
        var nodeDropdownMenuContentLi = $(this);
        var nodeDropdownMenu = nodeDropdownMenuContentLi.parents(config.selector).eq(0);
        var nodeDropdownMenuContent = $(" > .content", nodeDropdownMenu);
        
        nodeDropdownMenuContent.slideUp(200);
    });
    
    // 关闭
	nodeDocument.on("click", function(e){
		if($(e.target).closest(config.selector).length === 0){
			nodeDropdownMenuContents.slideUp(200);
		}
	});
};

/**
 * 下拉菜单点击关闭
 * @param string element 选择器
 */
sun.dropDownMenuClick.close = function(element){
    var nodeDropdownMenuContents;
    
    if(!element){
        sun.toast("error", "下拉元素选择器参数错误", 3000);
        return false;
    }
    
    nodeDropdownMenuContents = $(element + " > .content");
    nodeDropdownMenuContents.slideUp(200);
};

/**
 * 下拉悬停初始化
 * @param string config.selector 元素
 */
sun.dropDownHover = function(config){
    var nodeDropdowns; // 下拉所有
    var nodeDropdownContents; // 内容所有
    var contentDisplay = "none"; // 内容显示
    var canHide = true; // 是否可以隐藏
    var sto;
    
    // 配置
    if(!config.selector){
        sun.toast("error", "下拉元素选择器参数错误", 3000);
        return false;
    }
    
    // 对象
    nodeDropdowns = $(config.selector);
    nodeDropdownContents = $(" > .content", nodeDropdowns);
    
    // 鼠标移入
    nodeDropdowns.on("mouseenter", function(){
        var nodeDropdown = $(this);
        var nodeDropdownContent = $(" > .content", nodeDropdown);
        
        contentDisplay = nodeDropdownContent.css("display");
        if(contentDisplay != "none"){
            return;
        }
        
        canHide = false;
        nodeDropdownContents.hide();
        nodeDropdownContent.slideDown(200);
    });
    
    // 事件移除
    nodeDropdowns.on("mouseleave", function(){
        var nodeDropdown = $(this);
        var nodeDropdownContent = $(" > .content", nodeDropdown);
        
        // 可以关闭
        expanded = true;
        
        // 延时关闭
        if(sto){
            clearTimeout(sto);
        }
        sto = setTimeout(function(){
            if(expanded){
                nodeDropdownContent.slideUp(200);
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
    var nodeBody = $("body");
    var nodeLayer;
    var nodeLayerBg;
    var nodeLayerLayer;
    var nodeLayerTitle;
    var nodeLayerIframe;
    var nodeLayerLoading;
    
    var windowWidth = $(window).width();
	var windowHeight = $(window).height();
	var left = 0;
	var top = 0;
    var tag = ""; // 节点信息
	
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

    tag = '<div class="sun-layer" id="'+config.id+'">';
    tag += '   <div class="bg"></div>';
    tag += '   <div class="layer" style=" width:'+config.width+'px; height:'+config.height+'px; left:'+left+'px; top:0px">';
    tag += '       <div class="title">';
    tag += '           <span class="name">'+config.name+'</span>';
    tag += '           <a href="javascript:;" class="close" onclick="sun.layer.close(\''+config.id+'\');">×</a>';
    tag += '       </div>';
    tag += '       <iframe id="'+config.id+'_iframe" name="'+config.id+'_iframe" src="'+config.url+'" style=" width:100%; height:'+(config.height - 50)+'px" frameborder="0" scrolling="auto"></iframe>';
    tag += '       <div class="loading">加载中...</div>';
    tag += '   </div>';
    tag += '</div>';
	nodeBody.append(tag);
	
	// 变量
	nodeLayer = $("#"+config.id);
	nodeLayerBg = $(".bg", nodeLayer);
	nodeLayerLayer = $(".layer", nodeLayer);
	nodeLayerTitle = $(".layer > .title", nodeLayer);
	nodeLayerIframe = $(".layer > iframe", nodeLayer);
	nodeLayerLoading = $(".layer > .loading", nodeLayer);
	
	// 加载完成
	nodeLayerIframe.load(function(){
		nodeLayerLoading.fadeOut(500);
	});
	
	// 动画显示
	nodeLayerLayer.animate({top: top + "px", opacity:1}, 500, function(){});
	
	// 移动层
	nodeLayerTitle.on("mousedown", function(event){
		event = event || window.event;
		
		var isMove = true;
		var currsorX = event.pageX - nodeLayerLayer.offset().left;
		var currsorY = event.pageY - nodeLayerLayer.offset().top;
		
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
            nodeLayerLayer.css({"left":(left + "px"), "top":(top + "px")});
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
	var nodeLayer = $('#'+id);
    var nodeLayerBg = $(".bg", nodeLayer);
    var nodeLayerLayer = $(".layer", nodeLayer);
    
	nodeLayerBg.animate({opacity:"0"});
	nodeLayerLayer.animate({top:"-50px", opacity:"0"}, 500, function(){
		nodeLayer.remove();
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
    var nodeInput = $(".pagination_skip_"+id);
    var pageSize = 0;
    var pageCurrent = 0;
    
    if(nodeInput == null){
        sun.toast("error", "分页参数错误", 3000);
        return;
    }
    
    pageSize = nodeInput.attr("page_size");
    pageCurrent = nodeInput.val();
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
    var nodeSelect = $(th);
    var pageSize = nodeSelect.val();
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
 * @param string config.selector 元素
 * @param string config.column 那一列
 * @param string config.expand 展开几级
 */
sun.treeTable.init = function(config){
    var nodeTable; // 表格
    var nodeTrs; // 所有tr
    var nodeTr; // 一个tr
    var nodeTd; // 需要处理的td
    var nodeChildTrs; // 所有子tr
    var trLength = 0; // tr数量
    var trChildLength = 0; // 子tr长度
    var level = 1; // 级别
    var i = 0; // for 索引
    var id = 0; // id
    var parentId = 0; // 父级id
    
    // 配置
    if(!config.selector || !config.column || !config.expand){
        sun.toast("error", "表格树参数错误", 3000);
        return false;
    }
    
    // trs
    nodeTable = $(config.selector);
    nodeTrs = $("> tbody > tr", nodeTable);
    trLength = nodeTrs.length;
    if(trLength == 0){
        nodeTrs = $(" > tr", nodeTable);
        trLength = nodeTrs.length;
    }
    if(trLength == 0){
        return;
    }
    
    // 初始化
    for(i = 0; i < trLength; i ++){
        nodeTr = nodeTrs.eq(i);
        nodeTd = $(" > td", nodeTr).eq(config.column);
        id = nodeTr.attr("tree_table_id");
        nodeChildTrs = $("tr[tree_table_parent_id='"+id+"']", nodeTable);
        trChildLength = nodeChildTrs.length;
        level = nodeTr.attr("tree_table_level");
        
        nodeTd.css("padding-left", (30 * (level - 1))+"px");
        if(trChildLength > 0){
            nodeTd.prepend('<span class="iconfont icon-arrow-down arrow" onclick="sun.treeTable.toggle(this)"></span>');
        }
        
        // 展开级别
        if(level >= config.expand){
            nodeTr.addClass("close");
        }
        if(level > config.expand){
            nodeTr.hide();
        }
    }    
}

/**
 * 表格树切换
 * @param obj th 箭头对象
 */
sun.treeTable.toggle = function(th){
    var nodeArrow = $(th);
    var nodeTr = nodeArrow.parents("tr").eq(0);
    var id = nodeTr.attr("tree_table_id");
    var close = nodeTr.hasClass("close"); // 是否关闭
    
    if(close){
        nodeTr.removeClass("close");
        sun.treeTable.childOpen(id);
    }else{
        nodeTr.addClass("close");
        sun.treeTable.childClose(id);
    }
}

/**
 * 表格树子项打开
 */
sun.treeTable.childOpen = function(id){
    var nodeTrs = $("tr[tree_table_parent_id='"+id+"']");
    var nodeTr;
    var trLength = nodeTrs.length;
    var id = 0;
    var close = false; // 是否关闭
    
    if(trLength == 0){
        return;
    }
    
    nodeTrs.each(function(){
        nodeTr = $(this);
        id = nodeTr.attr("tree_table_id");
        close = nodeTr.hasClass("close");
        
        nodeTr.show();
        if(!close){
            sun.treeTable.childOpen(id);
        }
    });
}

/**
 * 表格树子项关闭
 */
sun.treeTable.childClose = function(id){
    var nodeTrs = $("tr[tree_table_parent_id='"+id+"']");
    var nodeTr;
    var trLength = nodeTrs.length;
    var id = 0;
    
    if(trLength == 0){
        return;
    }
    
    nodeTrs.each(function(){
        nodeTr = $(this);
        id = nodeTr.attr("tree_table_id");
        
        nodeTr.hide();
        sun.treeTable.childClose(id);
    });
}

/**
 * 文件上传
 * @param string config.selector 元素
 * @param string config.name 文件上传表单字段名
 * @param string config.accept 可选择的文件mime类型
 * @param string config.url 处理url
 * @param obj config.data 额外的参数 [{key: "", value: ""}]
 * @param string config.success 成功回调
 */
sun.uploadFile = function(config){
    var nodeButton; // 按钮上传
    var nodeFile; // 表单控件file
    var nodeNativeFile; // 表单控件file原生
    var nodeProgress; // 进度条
    var nodeProgressChartBg; // 进度条图形背景
    var nodeProgressText; // 进度条文字
    var tag = ""; // 节点
    var formData; // 表单对象
    var i = 0; // 遍历索引
        
    // 验证
    if(!config.selector || !config.name){
        sun.toast("error", "文件上传参数错误", 3000);
        return false;
    }
    
    nodeButton = $(config.selector);
    if(nodeButton.length === 0){
        sun.toast("error", "文件上传element参数错误", 3000);
        return false;
    }
    
    // 节点
    tag = '<input type="file" name="'+ config.name +'"';
    if(config.accept){
        tag += ' accept="' + config.accept +'"';
    }
    tag += ' style=" display:none">';
    nodeButton.after(tag);
    nodeFile = nodeButton.next("input[type=file]").eq(0);
    nodeNativeFile = nodeFile.get(0);
    
    tag = '<span class="sun-upload-file-progress">';
    tag += '<span class="chart"><span class="bg"></span></span>';
    tag += '<span class="text">0%</span>';
    tag += '</span>';
    nodeButton.after(tag);
    nodeProgress = nodeButton.next(".sun-upload-file-progress").eq(0);
    nodeProgressChartBg = $(" > .chart > .bg", nodeProgress);
    nodeProgressText = $(" > .text", nodeProgress);
    
    // 选择文件
    nodeButton.on("click", function(){
        nodeFile.trigger("click");
    });
    
    // 上传
    nodeFile.on("change", function(){
        if(nodeFile.val() == ""){
            return;
        }
        
        // 数据
        formData = new FormData();
        formData.append(config.name, nodeNativeFile.files[0]);
        if(config.data){
            for(data in config.data){
                formData.append(data.key, data.value);
            }
        }
        
        // 上传
        nodeProgress.css({"display":"inline-block"});
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
                        
                        nodeProgressChartBg.width(percent + "%");
                        nodeProgressText.html(percent + "%");
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
                nodeFile.get(0).value = "";
                nodeProgress.hide();
                
                if(config.complete){
                    config.complete();
                }
            }
        });
    });
}
