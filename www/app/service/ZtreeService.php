<?php
/**
 * ztree服务
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
     * @return array
     */
    static function setOpenByFirst($datas){
        if(empty($datas)){
            return $datas;
        }
        $index = 0;
        foreach($datas as $key => $data){
            $index ++;
            if($index == 1){
                $datas[$key]['open'] = 1;
            }
        }
        
        return $datas;
    }
}
