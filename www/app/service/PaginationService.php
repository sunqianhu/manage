<?php
/**
 * 分页服务
 */
namespace app\service;

use \app\Config;

class PaginationService{
    public $id;
    public $recordTotal; // 总记录
    public $pageSize = 50; // 每页显示记录数
    public $pageTotal = 0; // 总页数
    public $pageCurrent = 0; // 当前页
    public $urlTemplate = ''; // url模板
    public $limitStart = 0; // 开始记录
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
        if($urlTemplate == ''){
            $this->urlTemplate = $this->getUrlTemplate();
        }
        $this->limitStart = ($this->pageCurrent - 1) * $this->pageSize;
        
        $this->nodeLink = $this->getLink();
        $this->nodeLimit = $this->getLimit();
        $this->nodeSkip = $this->getSkip();
    }
    
    /**
     * 得到url模板
     */
    function getUrlTemplate(){
        $appDomain = Config::get('app_domain');
        $url = $appDomain.substr($_SERVER['PATH_INFO'], 1);
        
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
     * 获取link
     * @param $page 分页参数
     * @return dom
     */
    function getLink(){
        $node = '';
        $pageStart = 0;
        $pageEnd = 0;
        $difference = 0;
        
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

        return $node;
    }
    
    /**
     * 获取limit
     * @param $page 分页参数
     * @return dom
     */
    function getLimit(){
        $node = '';
        $step = 1;
        
        $node = '每页显示<select onchange="sun.pagination.limit(\''.$this->urlTemplate.'\', this)">';
        for($i = 10; $i < 500; $i += $step * 10){
            $step ++;
            $node .= '<option value="'.$i.'"'.($i == $this->pageSize ? ' selected="selected"' : '').'>'.$i.'</option>';
        }
        $node .= '</select>条';
        
        return $node;
    }
    
    /**
     * 得到跳转到第几页
     * @param $page 分页参数
     * @return dom
     */
    function getSkip(){
        $node = '';
        $url = 'location.href=\''.$this->urlTemplate.'\'.replace(\'PAGE_SIZE\', document.getElementById(\'pagination_skip_'.$this->id.'\').value).replace(\'PAGE_CURRENT\', \'1\');';
        
        $node = '跳转到第<input type="text" class="number pagination_skip_'.$this->id.'" min="1" class="number" value="'.$this->pageCurrent.'" page_size="'.$this->pageSize.'">页
<button type="button" class="sun_button sun_button_sm sun_button_secondary" onclick="sun.pagination.skip(\''.$this->urlTemplate.'\', \''.$this->id.'\')">确定</button>';
        
        return $node;
    }
    
    
    /**
     * 得到跳转到第几页
     * @param $page 分页参数
     * @return dom
     */
    function getIntact(){
        $node = '';
        
        $node = '<div class="sun_pagination_intact">
<div class="left">
<div class="count">共<span>'.$this->recordTotal.'</span>条</div>
</div>
<div class="right">
<div class="limit">'.$this->nodeLimit.'</div>
<div class="skip">'.$this->nodeSkip.'</div>
<div class="link">'.$this->nodeLink.'</div>
</div>
</div>';

        return $node;
    }
}