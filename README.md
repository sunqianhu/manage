# sun后台管理系统框架
sun后台管理系统框架是一个使用mvc思想写的，拥有管理系统基础功能的php后台管理系统框架，为快速开发系统做准备。sun后台管理系统框架包含系统登录、系统首页、用户管理、部门管理、角色管理、菜单管理、用户文件管理、登录日志和操作日志功能模块。sun后台管理系统框架的模型使用php pdo操作数据库。sun后台管理系统框架的服务有各种搭建系统的基础服务类，包含不限于数组服务、鉴权服务、缓存服务、验证码服务、配置服务、数据库服务、文件服务、主框架服务、图片处理服务、ip服务、分页服务和安全处理服务。sun后台管理系统的控制器是一个一个的请求文件，没有采用单一入库路由导航调用类的方法的方式。没有采用单一入口的好处一是可以自定义请求url，一个控制器方法就是一个php脚本文件，使开发不用去配置繁琐的路由规则。没有采用单一入口的好处二是使代码基于了文件管理，不会出现一个控制器文件上千行的代码量，不易维护的问题。sun后台管理系统框架开发系统就像搭积木一样的简单，每个控制器文件只需要包含应用app.php公共文件，就可以自动加载各种模型类和各种服务类，轻松处理用户请求。sun后台管理系统前端使用了自带的sun ui简单前端框架，基本上页面不用写重复的css。前端js公共插件放在/js/plus文件夹中，我们开发时可以需要什么js插件，加载什么插件，这就和搭积木是一样的原理。  

# 系统概况
登录  
![登录](https://github.com/sunqianhu/manage/blob/main/%E8%B5%84%E6%96%99/%E7%B3%BB%E7%BB%9F%E5%9B%BE%E7%89%87/%E7%99%BB%E5%BD%95.png “登录”)  
  
用户管理  
![用户管理](https://github.com/sunqianhu/manage/blob/main/%E8%B5%84%E6%96%99/%E7%B3%BB%E7%BB%9F%E5%9B%BE%E7%89%87/%E7%94%A8%E6%88%B7%E7%AE%A1%E7%90%86.png “用户管理”)  
  
部门管理  
![部门管理](https://github.com/sunqianhu/manage/blob/main/%E8%B5%84%E6%96%99/%E7%B3%BB%E7%BB%9F%E5%9B%BE%E7%89%87/%E9%83%A8%E9%97%A8%E7%AE%A1%E7%90%86.png “部门管理”)  
  
角色管理  
![角色管理](https://github.com/sunqianhu/manage/blob/main/%E8%B5%84%E6%96%99/%E7%B3%BB%E7%BB%9F%E5%9B%BE%E7%89%87/%E8%A7%92%E8%89%B2%E7%AE%A1%E7%90%86.png “角色管理”)  
  
菜单管理  
![菜单管理](https://github.com/sunqianhu/manage/blob/main/%E8%B5%84%E6%96%99/%E7%B3%BB%E7%BB%9F%E5%9B%BE%E7%89%87/%E8%8F%9C%E5%8D%95%E7%AE%A1%E7%90%86.png “菜单管理”)  
  
字典管理  
![字典管理](https://github.com/sunqianhu/manage/blob/main/%E8%B5%84%E6%96%99/%E7%B3%BB%E7%BB%9F%E5%9B%BE%E7%89%87/%E5%AD%97%E5%85%B8%E7%AE%A1%E7%90%86.png "字典管理")  
  
用户文件  
![用户文件](https://github.com/sunqianhu/manage/blob/main/%E8%B5%84%E6%96%99/%E7%B3%BB%E7%BB%9F%E5%9B%BE%E7%89%87/%E7%94%A8%E6%88%B7%E6%96%87%E4%BB%B6.png "用户文件")  

