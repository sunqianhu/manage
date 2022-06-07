<?php
/**
 * 验证码
 */
namespace library\service;

class CaptchaService{
    /**
     * 创建验证码
     * @param string $name session名称
     * @param json json字符串
     */
    static function create($name){
        $code = '';
        $colorBg = null;
        $colorFront = null;
        $rand = 0;
        
        $image = imagecreate(60, 25);
        $colorBg = ImageColorAllocate($image, rand(200, 255), rand(200, 255), rand(200, 255));
        imagefill($image, 0, 0, $colorBg);

        for($i=0; $i < 4; $i++){
            $colorFront = imagecolorallocate($image, rand(0, 150), rand(0, 150), rand(0, 150));
            $rand = rand(0, 9);
            $code .= $rand;
            imagestring($image, rand(1, 5), (($i * 14) + rand(2, 10)), rand(0, 10), $rand, $colorFront);
        }

        $_SESSION[$name] = strtolower($code);

        header("Content-type: image/png");
        imagepng($image);
        imagedestroy($image);
    }
}