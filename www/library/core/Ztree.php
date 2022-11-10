<?php
/**
 * ztree
 */
namespace library\core;

class Ztree{
    /**
     * 数据parent_id转换为pid
     * @param array $datas 数据
     * @return array
     */
    function parentIdToPid($datas){
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
    function setOpenByFirst($datas){
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
    
    /**
     * 设置选中
     * @param array $datas 数据
     * @param array $ids 选中id数组
     * @return array
     */
    static function setChecked($datas, $ids, $primaryKey = 'id', $childKey = 'child'){
        foreach($datas as $key => $data){
            if(in_array($data[$primaryKey], $ids)){
                $datas[$key]['checked'] = true;
            }

            if(!empty($data[$childKey])){
                $datas[$key][$childKey] = $this->setChecked($data[$childKey], $ids);
            }
        }
        
        return $datas;
    }
}
