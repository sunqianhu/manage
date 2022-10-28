<?php
/**
 * 图片处理
 */
namespace library;

class Image{
    public $error = ''; // 错误描述
    
    /**
     * 得到错误
     * @return string 错误描述
     */
    function getError(){
        return $this->error;
    }
    
    /**
     * 设置错误
     * @param string $error 错误描述
     * @return boolean
     */
    function setError($error){
        return $this->error = $error;
    }

    /**
     * 缩略（只支持jpg和png）
     * @param string $srcPath 原图像路径
     * @param string $dstPath 目标图像路径
     * @param integer $size 缩略到的大小
     * @param string $type width|height|无 缩略参照边
     * @return boolean 布尔
     */
    function shrink($srcPath, $dstPath, $size, $type = ''){
        $srcImageSizes = array(); // 源图像信息
        $srcImage = false; // 源图像画布
        $width = 0; // 目的图片宽度
        $height = 0; // 目的图片高度
        $dstImage = false; // 目的画布
        $save = false;

        // 验证
        if(empty($srcPath) || !file_exists($srcPath)){
            $this->setError('原图像不存在');
            return false;
        }
        if(empty($dstPath)){
            $this->setError('目标图像路径不能为空');
            return false;
        }

        // 源图像信息
        $srcImageSizes = getimagesize($srcPath);
        if($srcImageSizes[0] < $size && $srcImageSizes[1] < $size){
            return true;
        }

        // 只支持jpg和png
        if(!in_array($srcImageSizes[2], array(2,3))){
            $this->setError('只支持jpg和png');
            return false;
        }

        // 得到源画布
        switch($srcImageSizes[2]){
            // jpg
            case 2:
                $srcImage = imagecreatefromjpeg($srcPath);
            break;

            // png
            case 3:
                $srcImage = imagecreatefrompng($srcPath);
            break;

            // 默认
            default:
                $srcImage = imagecreatefromjpeg($srcPath);
            break;
        }
        if(!$srcImage){
            $this->setError('得到原图像画布失败');
            return false;
        }

        // 创建目标画布
        switch($type){
            case 'width':
                $width = $size;
                $height = $width * $srcImageSizes[1] / $srcImageSizes[0];
            break;
            case 'height':
                $height = $size;
                $width = $height * $srcImageSizes[0] / $srcImageSizes[1];
            break;
            default:
                if($srcImageSizes[0] >= $srcImageSizes[1]){
                    $width = $size;
                    $height = $width * $srcImageSizes[1] / $srcImageSizes[0];
                }else{
                    $height = $size;
                    $width = $height * $srcImageSizes[0] / $srcImageSizes[1];
                }
            break;
        }
        $dstImage = imagecreatetruecolor($width, $height);
        if(!$dstImage){
            $this->setError('创建目标图像画布失败');
            return false;
        }

        // 透明通道
        if($srcImageSizes[2] == 3){
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
        }

        // 缩略
        if(!imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $width, $height, $srcImageSizes[0], $srcImageSizes[1])){
            $this->setError('图片缩略失败');
            return false;
        }

        // 保存图像
        switch($srcImageSizes[2]){
            case 2:
                $save = imagejpeg($dstImage, $dstPath, 100);
            break;

            case 3:
                $save = imagepng($dstImage, $dstPath);
            break;

            default:
                $save = imagejpeg($dstImage, $dstPath, 100);
            break;
        }

        // 释放资源
        imagedestroy($srcImage);
        imagedestroy($dstImage);
        
        if(!$save){
            $this->setError('图片保存到磁盘失败');
            return false;
        }
        
        return true;
    }
    
    /**
     * 图片加水印（只支持jpg和png）
     * @param string $srcPath 原图像路径
     * @param string $markPath 水印图像路径
     * @param string$alpha 水印透明度
     * @return boolean 布尔
     */
    function waterMark($srcPath, $markPath, $alpha = 80){
        $srcImageSizes = array(); // 源图像信息
        $srcImage = false; // 源画布
        $waterMarkImageSizes = array(); // 水印图像信息
        $waterMarkImage = false; // 水印画布
        $save = false;

        // 验证
        if(empty($srcPath) || !file_exists($srcPath)){
            $this->setError('原图像不存在');
            return false;
        }
        if(empty($markPath) || !file_exists($markPath)){
            $this->setError('水印图像不存在');
            return false;
        }

        // 源图像信息
        $srcImageSizes = getimagesize($srcPath);

        // 只支持jpg和png
        if(!in_array($srcImageSizes[2], array(2,3))){
            $this->setError('只支持jpg和png');
            return false;
        }

        switch($srcImageSizes[2]){
            // jpg
            case 2:
                $srcImage = imagecreatefromjpeg($srcPath);
            break;

            // png
            case 3:
                $srcImage = imagecreatefrompng($srcPath);
            break;

            // 其他
            default:
                $srcImage = imagecreatefromjpeg($srcPath);
            break;
        }
        if(!$srcImage){
            $this->setError('得到原图片画布失败');
            return false;
        }

        // 透明通道
        if($srcImageSizes[2] == 3){
            imagealphablending($srcImage, false);
            imagesavealpha($srcImage, true);
        }

        // 得到水印画布
        $waterMarkImageSizes = getimagesize($markPath);
        switch($waterMarkImageSizes[2]){
            // jpg
            case 2:
                $waterMarkImage = imagecreatefromjpeg($markPath);
            break;

            // png
            case 3:
                $waterMarkImage = imagecreatefrompng($markPath);
            break;

            // 其他
            default:
                $waterMarkImage = imagecreatefromjpeg($markPath);
            break;
        }
        if(!$waterMarkImage){
            $this->setError('创建目标图片画布失败');
            return false;
        }

        // 加水印
        $x = $srcImageSizes[0] - $waterMarkImageSizes[0];
        $y = $srcImageSizes[1] - $waterMarkImageSizes[1];
        if(!imagecopymerge($srcImage, $waterMarkImage, $x, $y, 0, 0, $waterMarkImageSizes[0], $waterMarkImageSizes[1], $alpha)){
            $this->setError('图片加水印失败');
            return false;
        }

        // 保存
        switch($srcImageSizes[2]){
            case 2:
                $save = imagejpeg($srcImage, $srcPath);
            break;

            case 3:
                $save = imagepng($srcImage, $srcPath);
            break;

            default:
                $save = imagejpeg($srcImage, $srcPath);
            break;
        }
        
        imagedestroy($srcImage);
        imagedestroy($waterMarkImage);

        if(!$save){
            $this->setError('图片保存到磁盘失败');
            return false;
        }
        
        return true;
    }
    
    /**
     * 正方形居中裁剪
     * @param string $srcPath 原图像路径
     * @param string $dstPath 目标图像路径
     * @return boolean 布尔
     */
    function squareCenterCrop($srcPath, $dstPath){
        $srcImageSizes = array(); // 源图像信息
        $srcImage = false; // 源图像画布
        $width = 0; // 目的图片宽度
        $height = 0; // 目的图片高度
        $dstImage = false; // 目的画布
        $sideMin = 0; // 最小边
        $offset = 0; // 偏移量
        $save = false;

        // 验证
        if(empty($srcPath) || !file_exists($srcPath)){
            $this->setError('原图像不存在');
            return false;
        }
        if(empty($dstPath)){
            return false;
        }

        // 源图像信息
        $srcImageSizes = getimagesize($srcPath);
        $width = $srcImageSizes[0];
        $height = $srcImageSizes[1];
        
        if($width == $height){
            return true;
        }
        
        $sideMin = $width;
        if($sideMin > $height){
            $sideMin = $height;
        }
        
        if($width > $height){
            $offset = ceil(($width - $height) / 2);
        }else{
            $offset = ceil(($height - $width)  / 2);
        }

        // 只支持jpg和png
        if(!in_array($srcImageSizes[2], array(2,3))){
            $this->setError('只支持jpg和png');
            return false;
        }

        // 得到源画布
        switch($srcImageSizes[2]){
            // jpg
            case 2:
                $srcImage = imagecreatefromjpeg($srcPath);
            break;

            // png
            case 3:
                $srcImage = imagecreatefrompng($srcPath);
            break;

            // 默认
            default:
                $srcImage = imagecreatefromjpeg($srcPath);
            break;
        }
        if(!$srcImage){
            $this->setError('得到原图片画布失败');
            return false;
        }

        // 创建目标画布
        $dstImage = imagecreatetruecolor($sideMin, $sideMin);
        if(!$dstImage){
            $this->setError('创建目标画布失败');
            return false;
        }

        // 透明通道
        if($srcImageSizes[2] == 3){
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
        }

        // 缩略
        if($width > $height){
            if(!imagecopyresampled($dstImage, $srcImage, 0, 0, $offset, 0, $sideMin, $sideMin, $sideMin, $sideMin)){
                $this->setError('图片裁剪失败');
                return false;
            }
        }else{
            if(!imagecopyresampled($dstImage, $srcImage, 0, 0, 0, $offset, $sideMin, $sideMin, $sideMin, $sideMin)){
                $this->setError('图片裁剪失败');
                return false;
            }
        }
        
        // 保存图像
        switch($srcImageSizes[2]){
            case 2:
                $save = imagejpeg($dstImage, $dstPath, 100);
            break;

            case 3:
                $save = imagepng($dstImage, $dstPath);
            break;

            default:
                $save = imagejpeg($dstImage, $dstPath, 100);
            break;
        }

        // 释放资源
        imagedestroy($srcImage);
        imagedestroy($dstImage);
        
        if(!$save){
            $this->setError('图片保存到磁盘失败');
            return false;
        }
        
        return true;
    }
}