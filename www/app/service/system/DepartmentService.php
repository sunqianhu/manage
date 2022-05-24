<?php
/**
 * 部门服务
 */
namespace app\service\system;

class DepartmentService{
    
    /**
     * 得到首页节点树
     * @param array $datas 数据
     * @return array
     */
    static function getIndexTreeNode($departments){
        $node = '';
        $indent = 0;
        $i = 0;
        $styleClass = '';
        
        if(!empty($departments)){
        foreach($departments as $department){
            $indent = $department['level'] - 1;
            if($department['level'] == 1 && empty($department['child'])){
                $styleClass .= ' parent_close'; // 一级无子项的关闭
            }
            if($department['level'] >= 2 && !empty($department['child'])){
                $styleClass .= ' parent_close'; // 二级关闭以上有子项的都关闭
            }
            if($department['level'] > 2){
                $styleClass .= ' child_close'; // 二级以上不显示
            }

            $node .= '
<tr tree_table_id="'.$department['id'].'" tree_table_parent_id="'.$department['parent_id'].'" class="tr tr'.$department['id'].''.$styleClass.'">
<td>'.$department['id'].'</td>
<td class="name">';
            for($i = 0; $i < $indent; $i ++){
                $node .= '<span class="indent"></span>';
            }
            if(!empty($department['child'])){
                $node .= '<span class="iconfont icon-arrow_down arrow" onclick="index.treeTableToggle(this)"></span>';
            }
            $node .= $department['name'].'
</td>
<td>'.$department['sort'].'</td>
<td>'.$department['remark'].'</td>
<td>';
            if($department['parent_id'] != 0){
                $node .= '<a href="javascript:;" class="sun_button sun_button_sm sun_button_secondary sun_mr5" onclick="index.edit('.$department['id'].');">修改</a> ';
                $node .= '<a href="javascript:;" class="sun_button sun_button_secondary sun_button_sm sun_mr5" onclick="index.delete('.$department['id'].');">删除</a>';
            }
            $node .= '</td>
</tr>
';
            if(!empty($department['child'])){
                $node .= self::getIndexTreeNode($department['child']);
            }
        }
        }else{
            $node = '<tr><td colspan="5" align="center">无部门</td></td>';
        }
        return $node;
    }
    
}
