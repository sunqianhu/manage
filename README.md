# sun后台管理系统框架
## 概述
sun后台管理系统框架是一个使用mvc思想写的，拥有管理系统基础功能的php后台管理系统框架，为快速开发系统做准备。框架包含系统登录、系统首页、用户管理、部门管理、角色管理、菜单管理、用户文件管理、登录日志和操作日志功能模块。框架有各种搭建系统的基础服务类，包含不限于数组服务、鉴权服务、缓存服务、验证码服务、配置服务、数据库服务、文件服务、主框架服务、图片处理服务、ip服务、分页服务和安全处理服务。使用框架开发就像搭积木一样的简单，可自动加载各种模型类、服务类或js插件，轻松完成处理用户请求。框架前端使用了sun ui简单的前端UI框架，所有重复的css js代码都写好了，不用做重复的工作。框架前端js公共插件放在/js/plus文件夹中，我们开发时需要什么插件，就加载什么插件。   
  
## 入手
### 使用模型
```Php
use library\model\system\UserModel;

$userModel->insert(array(
    'username'=>$_POST['username'],
    'status'=>$_POST['status'],
    'password'=>md5($_POST['password']),
    'name'=>$_POST['name'],
    'phone'=>$_POST['phone'],
    'department_id'=>$_POST['department_id'],
    'role_id_string'=>$_POST['role_id_string'],
    'time_add'=>time()
));
```
  
### 使用服务
```Php
use library\service\ZtreeService;

$departments = ZtreeService::setOpenByFirst($departments);
```
  
### 使用表单验证
```Php
use library\service\ValidateService;

$validateService->rule = array(
    'username' => 'require|max_length:64',
    'status' => 'require|number',
    'password' => 'require|min_length:8',
    'name' => 'require|max_length:32',
    'phone' => 'require|number|min_length:11|max_length:11',
    'department_id' => 'require:^0|number',
    'role_ids' => 'require|number_array'
);
$validateService->message = array(
    'username.require' => '请输入用户名',
    'username.max_length' => '用户名不能大于64个字',
    'password.require' => '请输入密码',
    'password.min_length' => '密码不能小于8个字符',
    'name.require' => '请输入姓名',
    'name.max_length' => '姓名不能大于32个字',
    'phone.require' => '请输入手机号码',
    'phone.number' => '手机号码只能是数字',
    'phone.max_length' => '手机号码只能11位',
    'phone.min_length' => '手机号码只能11位',
    'department_id.require' => '请选择部门',
    'department_id.number' => '部门参数必须是个数字',
    'role_ids.require' => '请选择角色',
    'role_ids.number_array' => '角色参数错误'
);
if(!$validateService->check($_POST)){
    $return['message'] = $validateService->getErrorMessage();
    $return['data']['dom'] = '#'.$validateService->getErrorField();
    echo json_encode($return);
    exit;
}
```
  
### 鉴权
```Php
use library\service\AuthService;

AuthService::isPermission('system_user')
```
  
### 分页
```Php
use library\service\PaginationService;

$paginationService = new PaginationService($recordTotal, @$_GET['page_size'], @$_GET['page_current']);
$paginationNodeIntact = $paginationService->getNodeIntact();

<?php echo $paginationNodeIntact;?>
```
  
### 使用字典
```Php
use library\service\system\DictionaryService;

DictionaryService::getValue('system_user_status', 1) // 字典值
```
  
### 使用js表单提交插件
```javascript
sun.formSubmit({
    element: ".form",
    success: function(ret){
        if(ret.status == "error"){
            sun.toast("error", ret.message, 3000);
            if(ret.data && ret.data.dom){
                $(ret.data.dom).focus();
            }
            return;
        }
        sun.toast("success", ret.message, 1000, function(){
            window.parent.location.reload();
        });
    }
});
```
  
### 使用js文件上传插件
```javascript
sun.fileUpload({
    element: ".sun_button",
    name: "file",
    url: "test_save.php",
    success: function(ret){
        alert(JSON.stringify(ret));
    }
});
```
  
### 使用js表格树插件
```javascript
sun.treeTable.init({
    element: ".sun_treetable",
    column: 1,
    expand: 3
});
```
  
### 使用js下拉插件
```javascript
sun.dropDownClickMenu({
    element: ".data table .operation_more"
});
```
  
### 使用js弹层插件
```javascript
sun.layer.open({
    id: "layer_department_edit",
    name: "修改部门",
    url: url,
    width: 600,
    height: 400
});
```
  
### 使用css动画插件
```html
<link href="js/plug/animate-4.1.1/animate.min.css" rel="stylesheet" type="text/css" />
<div class="animate__animated animate__bounceInDown">
</div>
```
  
还有很多服务和插件，打开代码一看就懂，做系统就像搭积木一样，欢迎体验。
  
## 截图
登录  
![登录](https://github.com/sunqianhu/manage/blob/main/%E8%B5%84%E6%96%99/%E7%B3%BB%E7%BB%9F%E5%9B%BE%E7%89%87/%E7%99%BB%E5%BD%95.png)  
  
用户管理  
![用户管理](https://github.com/sunqianhu/manage/blob/main/%E8%B5%84%E6%96%99/%E7%B3%BB%E7%BB%9F%E5%9B%BE%E7%89%87/%E7%94%A8%E6%88%B7%E7%AE%A1%E7%90%86.png)  
  
添加用户  
![添加用户](https://github.com/sunqianhu/manage/blob/main/%E8%B5%84%E6%96%99/%E7%B3%BB%E7%BB%9F%E5%9B%BE%E7%89%87/%E6%B7%BB%E5%8A%A0%E7%94%A8%E6%88%B7.png)  
  
用户详情  
![用户详情](https://github.com/sunqianhu/manage/blob/main/%E8%B5%84%E6%96%99/%E7%B3%BB%E7%BB%9F%E5%9B%BE%E7%89%87/%E7%94%A8%E6%88%B7%E8%AF%A6%E6%83%85.png)  
  
部门管理  
![部门管理](https://github.com/sunqianhu/manage/blob/main/%E8%B5%84%E6%96%99/%E7%B3%BB%E7%BB%9F%E5%9B%BE%E7%89%87/%E9%83%A8%E9%97%A8%E7%AE%A1%E7%90%86.png)  
  
角色管理  
![角色管理](https://github.com/sunqianhu/manage/blob/main/%E8%B5%84%E6%96%99/%E7%B3%BB%E7%BB%9F%E5%9B%BE%E7%89%87/%E8%A7%92%E8%89%B2%E7%AE%A1%E7%90%86.png)  
  
菜单管理  
![菜单管理](https://github.com/sunqianhu/manage/blob/main/%E8%B5%84%E6%96%99/%E7%B3%BB%E7%BB%9F%E5%9B%BE%E7%89%87/%E8%8F%9C%E5%8D%95%E7%AE%A1%E7%90%86.png)  
  
字典管理  
![字典管理](https://github.com/sunqianhu/manage/blob/main/%E8%B5%84%E6%96%99/%E7%B3%BB%E7%BB%9F%E5%9B%BE%E7%89%87/%E5%AD%97%E5%85%B8%E7%AE%A1%E7%90%86.png)  
  
用户文件  
![用户文件](https://github.com/sunqianhu/manage/blob/main/%E8%B5%84%E6%96%99/%E7%B3%BB%E7%BB%9F%E5%9B%BE%E7%89%87/%E7%94%A8%E6%88%B7%E6%96%87%E4%BB%B6.png)  