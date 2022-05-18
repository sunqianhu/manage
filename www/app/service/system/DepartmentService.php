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
<tr tree_table_id="'.$department['id'].'" tree_table_parent_id="'.$department['parent_id'].'" class="tr'.$styleClass.'">
<td>'.$department['id'].'</td>
<td class="name">';
            for($i = 0; $i < $indent; $i ++){
                $node .= '<span class="indent"></span>';
            }
            if(!empty($department['child'])){
                $node .= '<span class="iconfont icon-arrow_down arrow" onclick="treeTableToggle(this)"></span>';
            }
            $node .= $department['name'].'
</td>
<td>'.$department['sort'].'</td>
<td>
<a href="javascript:;" class="sun_button sun_button_sm sun_mr5" data-toggle="tooltip" title="添加子部门" onClick="index.add('.$department['id'].');">添加</a>
<a href="javascript:;" class="sun_button sun_button_sm sun_button_secondary sun_mr5">修改</a>
<a href="javascript:;" class="sun_button sun_button_secondary sun_button_sm sun_mr5">删除</a>
</td>
</tr>
';
            if(!empty($department['child'])){
                $node .= self::getIndexTreeNode($department['child']);
            }
        }

        return $node;
    }
    
}
