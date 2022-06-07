<?php
/**
 * 分页服务
 */
namespace library\service;

use \library\service\ConfigService;

class PaginationService{
    public $id;
    public $recordTotal = 0; // 总记录
    public $pageSize = 50; // 每页显示记录数
    public $pageTotal = 0; // 总页数
    public $pageCurrent = 1; // 当前页
    public $urlTemplate = ''; // url模板
    public $limitStart = 0; // 开始记录
    
    public $nodeTotal = ''; // 节点总记录
    public $nodeRecordRange = ''; // 节点记录范围
    public $nodeLink = ''; // 节点链接
    public $nodeLimit = ''; // 节点每页显示
    public $nodeSkip = ''; // 节点跳转

    /**
     * 分页
     * @param int $recordNumber 总记录数
     * @param int $pageSize 每页显示多少条
     * @param int $pageCurrent 当前页
     * @return string 分页数据
     */
    function __construct($recordNumber, $pageSize = 50, $pageCurrent = 1, $urlTemplate = ''){
        $this->id = time().rand(100,999);
        if(is_numeric($recordNumber) && $recordNumber > 0){
            $this->recordTotal = $recordNumber;
        }
        if(is_numeric($pageSize) && $pageSize > 0){
            $this->pageSize = $pageSize;
        }
        $this->pageTotal = ceil($this->recordTotal / $this->pageSize);
        if(is_numeric($pageCurrent) && $pageCurrent > 0){
            $this->pageCurrent = $pageCurrent;
        }
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
        
        $this->nodeTotal = $this->getNodeTotal();
        $this->nodeRecordRange = $this->getNodeRecordRange();
        $this->nodeLink = $this->getNodeLink();
        $this->nodeLimit = $this->getNodeLimit();
        $this->nodeSkip = $this->getNodeSkip();
    }
    
    /**
     * 得到url模板
     */
    function getUrlTemplate(){
        $appDomain = ConfigService::getOne('app_domain');
        $url = $appDomain.substr($_SERVER['SCRIPT_NAME'], 1);
        
        $_GET['page_size'] = 'PAGE_SIZE';
        $_GET['page_current'] = 'PAGE_CURRENT';
        $url = $url.'?'.http_build_query($_GET);
        
        return $url;
    }
    
    /**
     * 得到url
     * @param int $pageSize 每页显示多少条
     * @param int $pageCurrent 当前页
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
        $node = '';
        
        if($this->pageTotal == 0){
            return $node;
        }
        
        $node = '<div class="count">共<span>'.$this->recordTotal.'</span>条</div>';
        
        return $node;
    }
    
    /**
     * 得到节点记录范围
     * @return string node
     */
    function getNodeRecordRange(){
        $node = '';
        
        if($this->pageTotal == 0){
            return $node;
        }
        
        $node = '<div class="record_range">显示<span>'.($this->limitStart + 1).'-'.($this->limitStart+$this->pageSize).'</span>条</div>';
        
        return $node;
    }
    
    /**
     * 获取节点link
     * @return string node
     */
    function getNodeLink(){
        $node = '';
        $pageStart = 0;
        $pageEnd = 0;
        $difference = 0;
        
        if($this->pageTotal == 0){
            return $node;
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
            $node .= '<a href="'.$this->getUrl($this->pageSize, $this->pageCurrent - 1).'">上一页</a>';
            $node .= '<a href="'.$this->getUrl($this->pageSize, 1).'">1</a>';
            if($pageStart > 2){
                $node .= '<span class="ellipsis">...</span>';
            }
        }else{
            $node .= '<span class="disabled">上一页</span>';
        }
        
        for($i = $pageStart; $i <= $pageEnd; $i ++){
            $node .= '<a href="'.$this->getUrl($this->pageSize, $i).'"'.($i == $this->pageCurrent ? ' class="active"' : '').'>'.$i.'</a>';
        }
        
        if($pageEnd < $this->pageTotal){
            if($pageEnd < $this->pageTotal - 1){
                $node .= '<span class="ellipsis">...</span>';
            }
            $node .= '<a href="'.$this->getUrl($this->pageSize, $this->pageTotal).'">'.$this->pageTotal.'</a>';
            $node .= '<a href="'.$this->getUrl($this->pageSize, $this->pageCurrent + 1).'">下一页</a>';
        }else{
            $node .= '<span class="disabled">下一页</span>';
        }

        return '<div class="link">'.$node.'</div>';
    }
    
    /**
     * 获取节点limit
     * @return string node
     */
    function getNodeLimit(){
        $node = '';
        $step = 1;
        
        if($this->pageTotal == 0){
            return $node;
        }
        
        $node = '每页显示<select onchange="sun.pagination.limit(\''.$this->urlTemplate.'\', this)">';
        for($i = 10; $i < 500; $i += $step * 10){
            $step ++;
            $node .= '<option value="'.$i.'"'.($i == $this->pageSize ? ' selected="selected"' : '').'>'.$i.'</option>';
        }
        $node .= '</select>条';
        
        return '<div class="limit">'.$node.'</div>';
    }
    
    /**
     * 得到节点跳转到第几页
     * @return string node
     */
    function getNodeSkip(){
        $node = '';
        
        if($this->pageTotal == 0){
            return $node;
        }
        
        $url = 'location.href=\''.$this->urlTemplate.'\'.replace(\'PAGE_SIZE\', document.getElementById(\'pagination_skip_'.$this->id.'\').value).replace(\'PAGE_CURRENT\', \'1\');';
        
        $node = '跳转到第<input type="text" class="number pagination_skip_'.$this->id.'" min="1" class="number" value="'.$this->pageCurrent.'" page_size="'.$this->pageSize.'">页
<button type="button" class="sun_button sun_button_small sun_button_secondary" onclick="sun.pagination.skip(\''.$this->urlTemplate.'\', \''.$this->id.'\')">确定</button>';
        
        return '<div class="skip">'.$node.'</div>';
    }
    
    /**
     * 得到节点完整分页
     * @return string node
     */
    function getNodeIntact(){
        $node = '';
        
        $node = '<div class="sun_pagination_intact">
<div class="left">
'.$this->nodeTotal.'
'.$this->nodeRecordRange.'
</div>
<div class="right">
'.$this->nodeLimit.'
'.$this->nodeSkip.'
'.$this->nodeLink.'
</div>
</div>';

        return $node;
    }
}