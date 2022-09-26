<?php
/**
 * 验证码
 */
namespace library;

class Captcha{

    /**
     * 创建验证码
     * @return String 验证码字符串
     */
    static function create(){
        $chars = array('a','b','c','d','e','f','g','h','k','m','n','p','q','r','s','t','u','v','w','x','y','A','B','C','D','E','F','G','H','K','M','N','P','R','S','T','U','V','W','X','Y','3','4','5','6','7','8','9');
        $width = 100;
        $height = 35;
        $image = null;
        $color = null;
        $font = dirname(__DIR__).'/resource/heiti.ttf'; // 字体路径
        $i = 0;
        
        $image = imagecreate($width, $height);
        imagecolorallocate($image, 255, 255, 255);

        // 干扰点
        for($i = 0; $i < 200; $i ++){
            $color = imagecolorallocate($image, rand(0,255), rand(0,255), rand(0,255));
            imagesetpixel($image, rand(0, $width), rand(0, $height), $color);
        }

        // 字符
        shuffle($chars);
        
        $color = imagecolorallocate($image, rand(0,150), rand(0,150), rand(0,150));
        imagettftext($image, rand(15, 25), rand(-10, 10), rand(0, 5), rand(15, 30), $color, $font, $chars[0]);
        imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $color);
        
        $color = imagecolorallocate($image, rand(0,150), rand(0,150), rand(0,150));
        imagettftext($image, rand(15, 25), rand(-10, 10), rand(25, 30), rand(15, 30), $color, $font, $chars[1]);
        imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $color);
        
        $color = imagecolorallocate($image, rand(0,150), rand(0,150), rand(0,150));
        imagettftext($image, rand(15, 25), rand(-10, 10), rand(50, 55), rand(15, 30), $color, $font, $chars[2]);
        imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $color);
        
        $color = imagecolorallocate($image, rand(0,150), rand(0,150), rand(0,150));
        imagettftext($image, rand(15, 25), rand(-10, 10), rand(75, 80), rand(15, 30), $color, $font, $chars[3]);
        imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $color);

        imagepng($image);
        imagedestroy($image);
        
        return strtolower($chars[0].$chars[1].$chars[2].$chars[3]);
    }
}