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
    static function getIndexTreeNode($departments, $levelView = 1){
        $node = '';
        $i = 0;
        $indent = 0; // 缩进
        $open = '';
        
        if($levelView < 3){
            $open = ' init_open';
        }
        
        foreach($departments as $department){
            $indent = $department['level'] - 1;
            
            $node .= '
<tr department_id="'.$department['id'].'" department_parent_id="'.$department['parent_id'].'" class="tr'.$open.'">
    <td>'.$department['id'].'</td>
    <td class="name">';
            for($i = 0; $i < $indent; $i ++){
                $node .= '<span class="indent"></span>';
            }
            if(!empty($department['child'])){
                $node .= '<span class="iconfont icon-sjx_right arrow" onclick="index.tableTrToggle(this)"></span>';
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
                $levelView ++;
                $node .= self::getIndexTreeNode($department['child'], $levelView);
            }
        }

        return $node;
    }
    
}
