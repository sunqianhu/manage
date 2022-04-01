<?php
/**
 * 验证码
 */
namespace app\helper;

class Captcha{
    /**
     * 创建验证码图片
     * @param array $datas 数组
     * @param json json字符串
     */
    static function createImage(){
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

        $_SESSION['caption'] = strtolower($code);

        header("Content-type: image/png");
        imagepng($image);
        imagedestroy($image);
    }
}