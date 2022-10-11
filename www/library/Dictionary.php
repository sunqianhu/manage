<?php
/**
 * 字典服务
 */
namespace library;

use library\DbHelper;
use library\Cache;

class Dictionary{
    /**
     * 得到和设置缓存字典的某一类型
     * @param String $type 类型
     * @return String value
     */
    function getSetCache($type){
        $dbHelper = new DbHelper();
        $pdo = $dbHelper->getInstance();
        $pdoStatement = null;
        $cacheKey = 'dictionary_'.$type; // 缓存key
        $data = '';
        $datas = array();
        $sql = '';
        $dbData = array();
        
        $data = Cache::get($cacheKey);
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
        Cache::set($cacheKey, $data);
        
        return $data;
    }

    /**
     * 得到值
     * @param String $type 类型
     * @param String $key 键
     * @return String value
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
     * @param String $type 类型
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
     * @param String $type 类型
     * @return String value
     */
    function getOption($type, $selectKeys = array()){
        $node = ''; // 节点
        $data = ''; // 字典数据
        $datas = array(); // 字典数据数组
        
        $data = $this->getSetCache($type);
        
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
     * @param String $type 类型
     * @param String $name 单选按钮名称
     * @param String $checkKey 选中项的key
     * @return String value
     */
    function getRadio($type, $name = '', $checkKey = '', $event = ''){
        $node = ''; // 返回值
        $data = ''; // 字典数据
        $datas = array(); // 字典数据数组
        $index = 0;
        
        $data = $this->getSetCache($type);
        
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
            $node .= '<span><label><input type="radio"';
            if($name !== ''){
                $node .= ' name="'.$name.'" id="'.$name.$index.'"';
            }
            if($checkKey == $data['key']){
                $node .= ' checked="checked"';
            }
            if($event !== ''){
                $node .= ' '.$event;
            }
            $node .= ' value="'.$data['key'].'" />'.$data['value'].'</label></span>'."\r\n";
        }
        
        return $node;
    }
    
    /**
     * 得到得到复选按钮
     * @param String $type 类型
     * @param String $name 单选按钮名称
     * @param String $checkKeys 选中项的key
     * @param String $event 事件
     * @return String value
     */
    function getCheckBox($type, $name = '', $checkKeys = array(), $event = ''){
        $node = ''; // 返回值
        $data = ''; // 字典数据
        $datas = array(); // 字典数据数组
        $index = 0;
        
        $data = $this->getSetCache($type);
        
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
            $node .= '<span><label><input type="checkbox"';
            if($name !== ''){
                $node .= ' name="'.$name.'"';
            }
            if(in_array($data['key'], $checkKeys)){
                $node .= ' checked="checked"';
            }
            if($event !== ''){
                $node .= ' '.$event;
            }
            $node .= ' value="'.$data['key'].'" />'.$data['value'].'</label></span>'."\r\n";
        }
        
        return $node;
    }
}