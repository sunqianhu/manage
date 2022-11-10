<?php
/**
 * 验证器
 */
namespace library\core;

class Validate{
    // 规则
    public $rule = array();
    
    // 描述
    public $message = array();
    
    // 错误
    public $error = array(
        'field'=>'',
        'message'=>''
    );
    
    /**
     * 设置规则
     * @param array $rule 规则数组
     * @return boolean
     */
    function setRule($rule){
        $this->rule = $rule;
    }
    
    /**
     * 得到规则
     * @return boolean
     */
    function getRule(){
        return $this->rule;
    }
    
    /**
     * 设置描述
     * @param array $message 描述
     * @return boolean
     */
    function setMessage($message){
        $this->message = $message;
    }
    
    /**
     * 得到描述
     * @return boolean
     */
    function getMessage(){
        return $this->message;
    }
    
    /**
     * 得到字段描述
     * @return string
     */
    function getMessageField($field, $checkName, $checkValue){
        $message = '';
        
        // 自定义描述
        if(!empty($this->message[$field.'.'.$checkName])){
            $message = $this->message[$field.'.'.$checkName];
        }
        
        // 默认描述
        if(empty($message)){
            switch($checkName){
                case 'require':
                    $message = $checkName.'不能为空';
                break;
                case 'max_length':
                    $message = $checkName.'长度不能超过'.$checkValue;
                break;
                case 'number':
                    $message = $checkName.'不是一个数字';
                break;
                case 'regex':
                    $message = $checkName.'不符合规则';
                break;
            }
        }
        
        return $message;
    }
    
    /**
     * 设置错误
     * @param string $field 字段
     * @param string $message 描述
     */
    function setError($field, $message = ''){
        $this->error['field'] = $field;
        $this->error['message'] = $message;
    }
    
    /**
     * 得到错误
     * @return string 错误描述
     */
    function getError(){
        return $this->error;
    }
    
    /**
     * 得到错误字段
     * @return string 错误描述
     */
    function getErrorField(){
        return $this->error['field'];
    }
    
    /**
     * 得到错误描述
     * @return string 错误描述
     */
    function getErrorMessage(){
        return $this->error['message'];
    }
    
    /**
     * 验证
     * @param array $datas 数据
     * @return boolean
     */
    function check($datas){
        $field = ''; // 数据字段
        $value = ''; // 数据值
        $ruleString = ''; // 一个字段的规则字符串
        $rules = array(); // 一个字段的规则数组
        $rule = ''; // 一个字段的一个规则
        $checks = array(); // 验证
        $checkName = ''; // 验证名
        $checkValue = ''; // 验证值
        $message = ''; // 描述
        
        // 验证
        if(empty($this->rule)){
            return true;
        }
        
        // 检测
        foreach($this->rule as $field => $ruleString){
            $value = '';
            if(isset($datas[$field])){
                $value = $datas[$field];
            }
            
            $rules = explode('|', $ruleString);
            if(empty($rules)){
                continue;
            }
            
            foreach($rules as $rule){
                $checks = explode(':', $rule);
                $checkName = $checks[0];
                $checkValue = '';
                if(!empty($checks[1])){
                    $checkValue = $checks[1];
                }
                
                // 字段描述
                $message = $this->getMessageField($field, $checkName, $checkValue);
                
                // 检测
                switch($checkName){
                    // 必填
                    case 'require':
                        if(!$this->checkRequire($value, $checkValue)){
                            $this->setError($field, $message);
                            return false;
                        }
                    break;
                    
                    // 长度等于
                    case 'length':
                        if(!$this->checkLength($value, $checkValue)){
                            $this->setError($field, $message);
                            return false;
                        }
                    break;
                    
                    // 最大长度
                    case 'max_length':
                        if(!$this->checkMaxLength($value, $checkValue)){
                            $this->setError($field, $message);
                            return false;
                        }
                    break;
                    
                    // 最小长度
                    case 'min_length':
                        if(!$this->checkMinLength($value, $checkValue)){
                            $this->setError($field, $message);
                            return false;
                        }
                    break;
                    
                    // 数字
                    case 'number':
                        if(!$this->checkNumber($value)){
                            $this->setError($field, $message);
                            return false;
                        }
                    break;
                    
                    // 数字字符串
                    case 'number_string':
                        if(!$this->checkNumberString($value, $checkValue)){
                            $this->setError($field, $message);
                            return false;
                        }
                    break;
                    
                    // 数字数组
                    case 'number_array':
                        if(!$this->checkNumberArray($value)){
                            $this->setError($field, $message);
                            return false;
                        }
                    break;
                    
                    // 正则
                    case 'regex':
                        if(!$this->checkRegex($value, $checkValue)){
                            $this->setError($field, $message);
                            return false;
                        }
                    break;
                }
            }
        }
        
        return true;
    }
    
    /**
     * 检测必填
     * @return boolean 验证是否通过
     */
    function checkRequire($value, $rule = ''){
        // 字符串
        if($value === ''){
            return false;
        }
        
        // 数组
        if(is_array($value)){
            if(empty($value)){
                return false;
            }
        }
        
        // 排除
        if($rule == '^0' && strval($value) === '0'){
            return false;
        }

        return true;
    }
    
    /**
     * 检测长度
     * @param $value 值
     * @param $length 最大长度
     * @return boolean 验证是否通过
     */
    function checkLength($value, $length){
        if(mb_strlen($value) != $length){
            return false;
        }
        
        return true;
    }
    
    /**
     * 检测最大长度
     * @param $value 值
     * @param $max 最大长度
     * @return boolean 验证是否通过
     */
    function checkMaxLength($value, $max){
        if(mb_strlen($value) > $max){
            return false;
        }
        
        return true;
    }
    
    /**
     * 检测最小长度
     * @param $value 值
     * @param $min 最小长度
     * @return boolean 验证是否通过
     */
    function checkMinLength($value, $min){
        if(mb_strlen($value) < $min){
            return false;
        }
        
        return true;
    }
    
    /**
     * 检测数字
     * @return boolean 验证是否通过
     */
    function checkNumber($value){
        if(!is_numeric($value)){
            return false;
        }

        return true;
    }
    
    /**
     * 检测数字字符串
     * @return boolean 验证是否通过
     */
    function checkNumberString($value, $split){
        $datas = explode($split, $value);
        
        if(empty($datas)){
            return false;
        }
        
        foreach($datas as $data){
            if(!is_numeric($data)){
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * 检测数字数组
     * @return boolean 验证是否通过
     */
    function checkNumberArray($value){
        $datas = $value;
        
        if(empty($datas)){
            return false;
        }
        
        foreach($datas as $data){
            if(!is_numeric($data)){
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * 检测正则
     * @return boolean 验证是否通过
     */
    function checkRegex($value, $pattern){
        if(empty($pattern)){
            return true;
        }
        
        if(preg_match($pattern, $value) === 0){
            return false;
        }
        
        return true;
    }
}