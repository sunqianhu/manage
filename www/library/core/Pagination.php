<?php
/**
 * 分页
 */
namespace library\core;

use library\core\Config;

class Pagination{
    public $id;
    public $recordTotal = 0; // 总记录
    public $pageSize = 50; // 每页显示记录数
    public $pageTotal = 0; // 总页数
    public $pageCurrent = 1; // 当前页
    public $urlTemplate = ''; // url模板
    public $limitStart = 0; // 开始记录
    
    /**
     * 分页
     * @param integer $recordNumber 总记录数
     * @param integer $pageSize 每页显示多少条
     * @param integer $pageCurrent 当前页
     * @return string 分页数据
     */
    function __construct($recordNumber, $pageSize = 50, $pageCurrent = 1, $urlTemplate = ''){
        $this->id = time().rand(100,999);
        if(is_numeric($recordNumber) && $recordNumber > 0){
            $this->recordTotal = $recordNumber;
        }
        if(is_numeric($_GET['page_size']) && $_GET['page_size'] > 0){
            $this->pageSize = $_GET['page_size'];
        }else{
            $this->pageSize = $pageSize;
        }
        if(is_numeric($_GET['page_current']) && $_GET['page_current'] > 0){
            $this->pageCurrent = $_GET['page_current'];
        }else{
            $this->pageCurrent = $pageCurrent;
        }
        $this->pageTotal = ceil($this->recordTotal / $this->pageSize);
        if($this->pageCurrent > $this->pageTotal){
            $this->pageCurrent = $this->pageTotal;
        }
        if($this->pageCurrent < 1){
            $this->pageCurrent = 1;
        }
        if($urlTemplate == ''){
            $this->urlTemplate = $this->getUrlTemplate();
        }
        $this->limitStart = ($this->pageCurrent - 1) * $this->pageSize;
    }
    
    /**
     * 得到url模板
     */
    function getUrlTemplate(){
        $url = $_SERVER['SCRIPT_NAME'];
        
        $_GET['page_size'] = 'PAGE_SIZE';
        $_GET['page_current'] = 'PAGE_CURRENT';
        $url = $url.'?'.http_build_query($_GET);
        
        return $url;
    }
    
    /**
     * 得到url
     * @param integer $pageSize 每页显示多少条
     * @param integer $pageCurrent 当前页
     * @return string url
     */
    function getUrl($pageSize, $pageCurrent){
        $url = str_replace(array('PAGE_SIZE', 'PAGE_CURRENT'), array($pageSize, $pageCurrent), $this->urlTemplate);
        return $url;
    }
    
    /**
     * 得到节点总记录
     * @return string node
     */
    function getNodeTotal(){
        $tag = '';
        
        if($this->pageTotal == 0){
            return $tag;
        }
        
        $tag = '<div class="count">共<span>'.$this->recordTotal.'</span>条</div>';
        
        return $tag;
    }
    
    /**
     * 得到节点记录范围
     * @return string node
     */
    function getNodeRecordRange(){
        $tag = '';
        
        if($this->pageTotal == 0){
            return $tag;
        }
        
        $tag = '<div class="record_range">显示<span>'.($this->limitStart + 1).'-'.($this->limitStart+$this->pageSize).'</span>条</div>';
        
        return $tag;
    }
    
    /**
     * 得到节点页码范围
     * @return string node
     */
    function getNodePageRange(){
        $tag = '';
        
        if($this->pageTotal == 0){
            return $tag;
        }
        
        $tag = '<div class="page_range"><span>'.$this->pageCurrent.'/'.$this->pageTotal.'</span></div>';
        return $tag;
    }
    
    /**
     * 得到节点链接
     * @return string node
     */
    function getNodeLink(){
        $tag = '';
        $pageStart = 0;
        $pageEnd = 0;
        $difference = 0;
        
        if($this->pageTotal == 0){
            return $tag;
        }
        
        $pageStart = $this->pageCurrent - 2;
        $pageEnd = $this->pageCurrent + 2;

        $difference = 1 - $pageStart;
        if($difference > 0){
            $pageEnd = $pageEnd + $difference;
        }
        
        $difference = $pageEnd - $this->pageTotal;
        if($difference > 0){
            $pageEnd = $this->pageTotal;
            $pageStart = $pageStart - $difference;
        }
        if($pageStart < 1){
            $pageStart = 1;
        }
        
        if($pageStart > 1){
            $tag .= '<a href="'.$this->getUrl($this->pageSize, $this->pageCurrent - 1).'">上一页</a>';
            $tag .= '<a href="'.$this->getUrl($this->pageSize, 1).'">1</a>';
            if($pageStart > 2){
                $tag .= '<span class="ellipsis">...</span>';
            }
        }else{
            $tag .= '<span class="disabled">上一页</span>';
        }
        
        for($i = $pageStart; $i <= $pageEnd; $i ++){
            $tag .= '<a href="'.$this->getUrl($this->pageSize, $i).'"'.($i == $this->pageCurrent ? ' class="active"' : '').'>'.$i.'</a>';
        }
        
        if($pageEnd < $this->pageTotal){
            if($pageEnd < $this->pageTotal - 1){
                $tag .= '<span class="ellipsis">...</span>';
            }
            $tag .= '<a href="'.$this->getUrl($this->pageSize, $this->pageTotal).'">'.$this->pageTotal.'</a>';
            $tag .= '<a href="'.$this->getUrl($this->pageSize, $this->pageCurrent + 1).'">下一页</a>';
        }else{
            $tag .= '<span class="disabled">下一页</span>';
        }

        return '<div class="link">'.$tag.'</div>';
    }
    
    /**
     * 获取节点limit
     * @return string node
     */
    function getNodeLimit(){
        $tag = '';
        $step = 1;
        
        if($this->pageTotal == 0){
            return $tag;
        }
        
        $tag = '每页显示<select onchange="sun.pagination.limit(\''.$this->urlTemplate.'\', this)">';
        for($i = 10; $i < 500; $i += $step * 10){
            $step ++;
            $tag .= '<option value="'.$i.'"'.($i == $this->pageSize ? ' selected="selected"' : '').'>'.$i.'</option>';
        }
        $tag .= '</select>条';
        
        return '<div class="limit">'.$tag.'</div>';
    }
    
    /**
     * 得到节点跳转到第几页
     * @return string node
     */
    function getNodeSkip(){
        $tag = '';
        
        if($this->pageTotal == 0){
            return $tag;
        }
        
        $url = 'location.href=\''.$this->urlTemplate.'\'.replace(\'PAGE_SIZE\', document.getElementById(\'pagination_skip_'.$this->id.'\').value).replace(\'PAGE_CURRENT\', \'1\');';
        
        $tag = '跳转到第<input type="text" class="number pagination_skip_'.$this->id.'" min="1" class="number" value="'.$this->pageCurrent.'" page_size="'.$this->pageSize.'">页
<button type="button" class="sun-button small plain" onclick="sun.pagination.skip(\''.$this->urlTemplate.'\', \''.$this->id.'\')">确定</button>';
        
        return '<div class="skip">'.$tag.'</div>';
    }
    
    /**
     * 得到节点完整分页
     * @return string node
     */
    function getNodeIntact(){
        $tag = '';
        
        $tag = '<div class="sun-pagination-intact">
<div class="left">
'.$this->getNodeTotal().'
'.$this->getNodeRecordRange().'
</div>
<div class="right">
'.$this->getNodeLimit().'
'.$this->getNodeSkip().'
'.$this->getNodeLink().'
</div>
</div>';

        return $tag;
    }
    
    /**
     * 得到节点简单分页
     * @return string node
     */
    function getNodeSimple(){
        $tag = '';
        
        $tag = '<div class="sun-pagination-simple">';
        if($this->pageCurrent > 1){
            $tag .= '<a href="'.$this->getUrl($this->pageSize, $this->pageCurrent - 1).'">上一页</a>';
        }else{
            $tag .= '<a href="javascript:;" class="disabled">上一页</a>';
        }
        $tag .= $this->getNodePageRange();
        if($this->pageCurrent < $this->pageTotal){
            $tag .= '<a href="'.$this->getUrl($this->pageSize, $this->pageCurrent + 1).'">下一页</a>';
        }else{
            $tag .= '<a href="javascript:;" class="disabled">下一页</a>';
        }
        $tag .= '</div>';

        return $tag;
    }
}