<?php
/**
 * 字典服务
 */
namespace app\service\system;

use \app\model\system\DictionaryModel;
use \app\Cache;

class DictionaryService{
    /**
     * 得到和设置缓存字典的某一类型
     * @param string $type 类型
     * @return string value
     */
    static function getAndSetCacheType($type){
        $dictionaryModel = null; // 模型
        $cacheKey = 'dictionary_'.$type; // 缓存key
        $data = '';
        $datas = array();
        
        $data = Cache::get($cacheKey);
        if($data !== ''){
            return $data;
        }
        
        $dictionaryModel = new DictionaryModel();
        $datas = $dictionaryModel->getAll(
            '`key`, `value`',
            array(
                'mark'=>'type = :type',
                'value'=>array(
                    ':type'=>$type
                )
            ), 
            'order by `sort` asc'
        );
        if(empty($datas)){
            return $data;
        }
        
        $data = json_encode($datas);
        Cache::set($cacheKey, $data);
        
        return $data;
    }

    /**
     * 得到值
     * @param string $type 类型
     * @param string $key 键
     * @return string value
     */
    static function getValue($type, $key){
        $value = ''; // 返回值
        $data = ''; // 字典数据
        $datas = array(); // 字典数据数组

        $data = self::getAndSetCacheType($type);
        
        // 验证
        if($data == ''){
            return $value;
        }
        $datas = json_decode($data, true);
        if(empty($datas)){
            return $value;
        }
        
        foreach($datas as $data){
            if($data['key'] == $key){
                $value = $data['value'];
                break;
            }
        }
        
        return $value;
    }
    
    /**
     * 得到集合
     * @param string $type 类型
     * @return array 集合
     */
    static function getList($type){
        $data = ''; // 字典数据
        $datas = array(); // 字典数据数组

        $data = self::getAndSetCacheType($type);
        
        if($data == ''){
            return $datas;
        }
        $datas = json_decode($data, true);
        if(empty($datas)){
            return array();
        }
        
        return $datas;
    }
    
    /**
     * 得到得到下拉菜单
     * @param string $type 类型
     * @param string $name select的name属性
     * @param string $id select的id属性
     * @param string $selectKey 选中项key
     * @param string $event 事件
     * @return string value
     */
    static function getSelect($type, $name = '', $id = '', $selectKeys = array(), $event = ''){
        $node = '<select'; // 返回值
        $data = ''; // 字典数据
        $datas = array(); // 字典数据数组
        
        $data = self::getAndSetCacheType($type);
        
        if($name !== ''){
            $node .= ' name="'.$name.'"';
        }
        if($id !== ''){
            $node .= ' id="'.$id.'"';
        }
        if($event !== ''){
            $node .= ' '.$event;
        }
        $node .= '>'."\r\n";
        
        if(!empty($data)){
            $datas = json_decode($data, true);
            if(!empty($datas)){
                foreach($datas as $data){
                    $node .= '<option value="'.$data['key'].'"';
                    if(in_array($data['key'], $selectKeys)){
                        $node .= ' selected="selected"';
                    }
                    $node .= '>'.$data['value'].'</option>'."\r\n";
                }
            }
        }
        
        $node .= '</select>';
        
        return $node;
    }
    
    /**
     * 得到得到下拉菜单选项
     * @param string $type 类型
     * @return string value
     */
    static function getSelectOption($type, $selectKeys = array()){
        $node = ''; // 节点
        $data = ''; // 字典数据
        $datas = array(); // 字典数据数组
        
        $data = self::getAndSetCacheType($type);
        
        // 验证
        if($data == ''){
            return $node;
        }
        $datas = json_decode($data, true);
        if(empty($datas)){
            return $node;
        }
        
        foreach($datas as $data){
            $node .= '<option value="'.$data['key'].'"';
            if(in_array($data['key'], $selectKeys)){
                $node .= ' selected="selected"';
            }
            $node .= '>'.$data['value'].'</option>'."\r\n";
        }
        
        return $node;
    }
    
    /**
     * 得到单选按钮
     * @param string $type 类型
     * @param string $name 单选按钮名称
     * @param string $checkKey 选中项的key
     * @return string value
     */
    static function getRadio($type, $name = '', $checkKey = '', $event = ''){
        $node = ''; // 返回值
        $data = ''; // 字典数据
        $datas = array(); // 字典数据数组
        $index = 0;
        
        $data = self::getAndSetCacheType($type);
        
        // 验证
        if($data == ''){
            return $node;
        }
        $datas = json_decode($data, true);
        if(empty($datas)){
            return $node;
        }
        
        foreach($datas as $data){
            $index ++;
            $node .= '<label><input type="radio"';
            if($name !== ''){
                $node .= ' name="'.$name.'" id="'.$name.$index.'"';
            }
            if($checkKey == $data['key']){
                $node .= ' checked="checked"';
            }
            if($event !== ''){
                $node .= ' '.$event;
            }
            $node .= ' value="'.$data['key'].'" />'.$data['value'].'</label>'."\r\n";
        }
        
        return $node;
    }
    
    /**
     * 得到得到复选按钮
     * @param string $type 类型
     * @param string $name 单选按钮名称
     * @param string $checkKey 选中项的key
     * @param string $event 事件
     * @return string value
     */
    static function getCheckBox($type, $name = '', $checkKey = '', $event = ''){
        $node = ''; // 返回值
        $data = ''; // 字典数据
        $datas = array(); // 字典数据数组
        $index = 0;
        
        $data = self::getAndSetCacheType($type);
        
        // 验证
        if($data == ''){
            return $node;
        }
        $datas = json_decode($data, true);
        if(empty($datas)){
            return $node;
        }
        
        foreach($datas as $data){
            $index ++;
            $node .= '<label><input type="checkbox"';
            if($name !== ''){
                $node .= ' name="'.$name.'"';
            }
            if($checkKey == $data['key']){
                $node .= ' checked="checked"';
            }
            if($event !== ''){
                $node .= ' '.$event;
            }
            $node .= ' value="'.$data['key'].'" />'.$data['value'].'</label>'."\r\n";
        }
        
        return $node;
    }
}