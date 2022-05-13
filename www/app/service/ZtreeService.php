<?php
/**
 * 验证器
 */
namespace app\service;

class ZtreeService{
    /**
     * 数据parent_id转换为pid
     * @param array $datas 数据
     * @return array
     */
    static function parentIdToPid($datas){
        if(empty($datas)){
            return $datas;
        }
        foreach($datas as $key => $data){
            $datas[$key]['pid'] = $data['parent_id'];
            unset($datas[$key]['parent_id']);
        }
        
        return $datas;
    }
    
    /**
     * 通过级别设置打开
     * @param array $datas 数据
     * @param int $level 级别
     * @return array
     */
    static function setOpenByLevel($datas, $level){
        if(empty($datas)){
            return $datas;
        }
        foreach($datas as $key => $data){
            if($data['level'] == $level){
                $datas[$key]['open'] = 1;
            }
        }
        
        return $datas;
    }
}
