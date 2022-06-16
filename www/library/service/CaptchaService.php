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
        $chars = array();
        $width = 100;
        $height = 35;
        $img = null;
        $color = null;
        $font = ''; // 字体路径
        $i = 0;
        
        $img = imagecreate($width, $height);
        imagecolorallocate($img, 255, 255, 255);

        // 干扰点
        $color = imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));
        for($i = 0; $i < 200; $i ++){
            imagesetpixel($img, rand(0, $width), rand(0, $height), $color);
        }

        // 验证码文字
        $chars = array('a','b','c','d','e','f','g','h','k','m','n','p','q','r','s','t','u','v','w','x','y','A','B','C','D','E','F','G','H','K','M','N','P','R','S','T','U','V','W','X','Y','3','4','5','6','7','8','9');
        shuffle($chars);
        
        $font = dirname(dirname(__DIR__)).'/image/heiti.ttf';
        $color = imagecolorallocate($img, rand(0,150), rand(0,150), rand(0,150));
        imagettftext($img, rand(15, 25), rand(-10, 10), rand(0, 5), rand(15, 30), $color, $font, $chars[0]);
        
        $color = imagecolorallocate($img, rand(0,150), rand(0,150), rand(0,150));
        imagettftext($img, rand(15, 25), rand(-10, 10), rand(25, 30), rand(15, 30), $color, $font, $chars[1]);
        
        $color = imagecolorallocate($img, rand(0,150), rand(0,150), rand(0,150));
        imagettftext($img, rand(15, 25), rand(-10, 10), rand(50, 55), rand(15, 30), $color, $font, $chars[2]);
        
        $color = imagecolorallocate($img, rand(0,150), rand(0,150), rand(0,150));
        imagettftext($img, rand(15, 25), rand(-10, 10), rand(75, 80), rand(15, 30), $color, $font, $chars[3]);

        // 干扰线
        for($i = 0; $i < 6; $i++) {
            imageline($img, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $color);
        }
        
        imagepng($img);
        imagedestroy($img);
        
        $_SESSION[$name] = strtolower($chars[0].$chars[1].$chars[2].$chars[3]);
    }
}