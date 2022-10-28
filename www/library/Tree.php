<?php
/**
 * 树
 */
namespace library;

class Tree{
    /**
     * 得到数据树
     * @param array $datas 数据
     * @return array
     */
    function getTree($datas, $child = 'child', $id = 'id', $parentId = 'parent_id'){
        $middle = array(); // 中间数组
        $tree = array(); // 树形结构数组
        
        // 重构索引
        foreach($datas as $value){
            $middle[$value[$id]] = $value;
        }
        $datas = $middle;
        
        // 数组重构
        foreach($datas as $data){
            if(isset($datas[$data[$parentId]])) {
                // 存在上级
                $datas[$data[$parentId]][$child][] = &$datas[$data[$id]]; // 传地址，保证子项也跟到动。
            }else{
                // 不存在上级
                $tree[] = &$datas[$data[$id]]; // 传地址，保证后续更新datas，tree也被更新。
            }
        }

        return $tree;
    }
    
    /**
     * 为树形结构数据加上级别
     * @param array $datas 数据
     * @param integer $level 开始级别
     * @return array
     */
    function addLevel($datas, $level = 1){
        foreach($datas as $key => $data){
            $datas[$key]['level'] = $level;
            
            if(!empty($data['child'])){
                $datas[$key]['child'] = $this->addLevel($data['child'], ($level + 1));
            }
        }
        
        return $datas;
    }
}
