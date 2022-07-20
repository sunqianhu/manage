<?php
/**
 * 部门服务
 */
namespace library\service\system;

use library\model\system\DepartmentModel;

class DepartmentService{
    
    /**
     * 得到部门名
     * @access public
     * @param int $id 用户id
     * @return string 用户姓名
     */
    static function getName($id){
        $departmentModel = new DepartmentModel();
        $name = '';
        
        $name = $departmentModel->selectOne('name', array(
            'mark'=>'id = :id',
            'value'=>array(
                ':id'=>$id
            )
        ));
        
        return $name;
    }
    
    /**
     * 得到首页节点树
     * @param array $datas 数据
     * @return array
     */
    static function getIndexTreeNode($departments, $level = 1){
        $node = '';
        if(!empty($departments)){
        foreach($departments as $department){
            $node .= '
<tr tree_table_id="'.$department['id'].'" tree_table_parent_id="'.$department['parent_id'].'" tree_table_level="'.$level.'" class="tr tr'.$department['id'].'">
<td>'.$department['id'].'</td>
<td class="column">'.$department['name'].'</td>
<td>'.$department['sort'].'</td>
<td>'.$department['remark'].'</td>
<td>
<a href="javascript:;" class="sun-button sun-button-secondary sun-button-small sun-mr5" onclick="index.add('.$department['id'].');" title="添加子部门">添加</a>
<a href="javascript:;" class="sun-button sun-button-small secondary sun-mr5" onclick="index.edit('.$department['id'].');">修改</a>
<span class="sun-dropdown-menu sun-dropdown-menu-align-right operation_more">
<div class="title"><a href="javascript:;" class="sun-button sun-button-secondary sun-button-small">更多 <span class="iconfont icon-arrow-down arrow"></span></a></div>
<div class="content">
<ul>
<li><a href="javascript:;" onClick="index.delete('.$department['id'].')">删除</a></li>
</ul>
</div>
</span>
</td>
</tr>
';
            if(!empty($department['child'])){
                $node .= self::getIndexTreeNode($department['child'], ($level + 1));
            }
        }
        }else{
            $node = '<tr><td colspan="5" align="center">无部门</td></td>';
        }
        return $node;
    }
    
}
