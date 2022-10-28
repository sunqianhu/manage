<?php
/**
 * 字典模型
 */
namespace library\model;

use library\DbHelper;
use library\Cache;

class Dictionary{
    /**
     * 得到和设置缓存字典的某一类型
     * @param string $type 类型
     * @return string value
     */
    function getSetCache($type){
        $dbHelper = new DbHelper();
        $pdo = $dbHelper->getPdo();
        $pdoStatement = null;
        $cacheKey = 'dictionary_'.$type; // 缓存key
        $data = '';
        $datas = array();
        $sql = '';
        $dbData = array();
        $cache = new Cache();
        
        $data = $cache->get($cacheKey);
        if($data !== ''){
            return $data;
        }
        
        $sql = 'select `key`, `value` from dictionary where type = :type order by `sort` asc';
        $dbData = array(
            ':type'=>$type
        );
        $pdoStatement = $dbHelper->query($pdo, $sql, $dbData);
        $datas = $dbHelper->fetchAll($pdoStatement);
        if(empty($datas)){
            return $data;
        }
        
        $data = json_encode($datas);
        $cache->set($cacheKey, $data);
        
        return $data;
    }

    /**
     * 得到值
     * @param string $type 类型
     * @param string $key 键
     * @return string value
     */
    function getValue($type, $key){
        $value = ''; // 返回值
        $data = ''; // 字典数据
        $datas = array(); // 字典数据数组

        $data = $this->getSetCache($type);
        
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
    function getList($type){
        $data = ''; // 字典数据
        $datas = array(); // 字典数据数组

        $data = $this->getSetCache($type);
        
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
     * 得到得到下拉菜单选项
     * @param string $type 类型
     * @return string value
     */
    function getOption($type, $selectKeys = array()){
        $tag = ''; // 标签
        $data = ''; // 字典数据
        $datas = array(); // 字典数据数组
        
        $data = $this->getSetCache($type);
        
        // 验证
        if($data == ''){
            return $tag;
        }
        $datas = json_decode($data, true);
        if(empty($datas)){
            return $tag;
        }
        
        foreach($datas as $data){
            $tag .= '<option value="'.$data['key'].'"';
            if(in_array($data['key'], $selectKeys)){
                $tag .= ' selected="selected"';
            }
            $tag .= '>'.$data['value'].'</option>'."\r\n";
        }
        
        return $tag;
    }
    
    /**
     * 得到单选按钮
     * @param string $type 类型
     * @param string $name 单选按钮名称
     * @param string $checkKey 选中项的key
     * @return string value
     */
    function getRadio($type, $name = '', $checkKey = '', $event = ''){
        $tag = ''; // 返回值
        $data = ''; // 字典数据
        $datas = array(); // 字典数据数组
        $index = 0;
        
        $data = $this->getSetCache($type);
        
        // 验证
        if($data == ''){
            return $tag;
        }
        $datas = json_decode($data, true);
        if(empty($datas)){
            return $tag;
        }
        
        foreach($datas as $data){
            $index ++;
            $tag .= '<span><label><input type="radio"';
            if($name !== ''){
                $tag .= ' name="'.$name.'" id="'.$name.$index.'"';
            }
            if($checkKey == $data['key']){
                $tag .= ' checked="checked"';
            }
            if($event !== ''){
                $tag .= ' '.$event;
            }
            $tag .= ' value="'.$data['key'].'" />'.$data['value'].'</label></span>'."\r\n";
        }
        
        return $tag;
    }
    
    /**
     * 得到得到复选按钮
     * @param string $type 类型
     * @param string $name 单选按钮名称
     * @param string $checkKeys 选中项的key
     * @param string $event 事件
     * @return string value
     */
    function getCheckBox($type, $name = '', $checkKeys = array(), $event = ''){
        $tag = ''; // 返回值
        $data = ''; // 字典数据
        $datas = array(); // 字典数据数组
        $index = 0;
        
        $data = $this->getSetCache($type);
        
        // 验证
        if($data == ''){
            return $tag;
        }
        $datas = json_decode($data, true);
        if(empty($datas)){
            return $tag;
        }
        
        foreach($datas as $data){
            $index ++;
            $tag .= '<span><label><input type="checkbox"';
            if($name !== ''){
                $tag .= ' name="'.$name.'"';
            }
            if(in_array($data['key'], $checkKeys)){
                $tag .= ' checked="checked"';
            }
            if($event !== ''){
                $tag .= ' '.$event;
            }
            $tag .= ' value="'.$data['key'].'" />'.$data['value'].'</label></span>'."\r\n";
        }
        
        return $tag;
    }
}