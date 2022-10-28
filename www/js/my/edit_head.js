/**
 * 修改头像
 */

var cropper;

/**
 * 图片裁剪器初始化
 */
function initCropper(){
    var nodeNativeCropperImg = $(".cropper .img img").eq(0).get(0); // 图片原生元素
    var nodeInputImage = $("#input_image"); // 文件选择表单控件
    var nodeButtonCropperControls = $(".button_cropper_control"); // 全部控制按钮
    var nodeButtonCropperControl; // 一个控制按钮
    
    // 初始化裁剪
    cropper = new Cropper(nodeNativeCropperImg, {
        aspectRatio: 1 / 1,
        viewMode: 1, // 限制裁剪框不能超出图片的范围
        autoCropArea: 1, // 设置裁剪区域占图片的大小
        preview: ".preview > .img" // 设置一个区域容器预览裁剪后的结果
    });
    
    // 上传图片
    nodeInputImage.on("change", function() {
		var fileReader = new FileReader();
		var file = nodeInputImage.get(0).files[0];
		if (/^image\/\w+$/.test(file.type)) {
			fileReader.onload = function(e) {
                cropper.replace(e.target.result)
			}
			fileReader.readAsDataURL(this.files[0]);
		}else{
			sun.toast("error", "请选择一个图片文件。", 3000);
		}
	});
    
    // 控制
    nodeButtonCropperControls.on("click",function (e) {
        nodeButtonCropperControl = $(this);
		var data = {
			method: nodeButtonCropperControl.attr('method'),
			parameter: nodeButtonCropperControl.attr('parameter') || undefined,
		};
		var result = cropper[data.method](data.parameter);
        
        // 翻转再次点击
		if(["scaleX", "scaleY"].indexOf(data.method) !== -1){
			nodeButtonCropperControl.attr("parameter", -data.parameter);
		}
	})
};

/**
 * 提交
 */
function submit(){
    var formdata;
    var url = "edit_head_save.php";
    
    cropper.getCroppedCanvas({
        width: 50,
        height: 50,
        fillColor: "transparent",
        imageSmoothingQuality: "high"
    }).toBlob(function(img){
        if(img == null){
            sun.tost("error", "头像创建失败", 3000);
            return;
        }
        
        formdata = new FormData();
        formdata.append("head", img);
        
        $.ajax({
            url: url,
            data: formdata,
            type: "post",
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(ret) {
                if(ret.status == "error"){
                    sun.toast("error", ret.message, 3000);
                    if(ret.data.dom){
                        $(ret.data.dom).focus();
                    }
                    return;
                }
                sun.toast("success", ret.message, 1000, function(){
                    parent.location.reload();
                });
            }
        });
    }, "image/png");
}

if (!HTMLCanvasElement.prototype.toBlob) {
    Object.defineProperty(HTMLCanvasElement.prototype, 'toBlob', {
        value: function(callback, type, quality) {
            var canvas = this;
            setTimeout(function() {
                var binStr = atob(canvas.toDataURL(type, quality).split(',')[1]);
                var len = binStr.length;
                var arr = new Uint8Array(len);
                for (var i = 0; i < len; i++) {
                    arr[i] = binStr.charCodeAt(i);
                }
                callback(new Blob([arr], {
                    type: type || 'image/png'
                }));
            });
        }
    });
}

$(function(){
    initCropper();
});