开放API系统说明文档
修订者 : 任孟洋 , 日期 : 2018/4/2

##1 概述
 >本开放系统主要对第三方服务商提供开放平台服务
 >认证鉴权服务
 >用资源获取服务
 >HTTP协议头认证（Basic MTEwMjA4NDI5NDpha3U4aEN5WEJkOVNxWWtU）


##2 接入指引
>json : http://auth.xxx.dev.com/apis/rest/authService/authorize/json
>xml : http://auth.xxx.dev.com/apis/rest/authService/authorize/xml

 
##3.参数说明 
>t_ ：请求发起时的时间戳，服务器端会对时间戳进行校验，时间戳与真实时间相差较大时将返回错误信息.
>p_ ：和时间相关的数值，计算方式为 (t_ % 10000 ) * 3 + 2345
>access_token ：通过致云桌面软件登陆会生成访问Token 
>client_id ：开放系统为第三方服务商提供Open-key
>client_secret ：开放系统为第三方服务商提供的密钥
>auth_code ：接入鉴权接口生成的授权码 


##4.错误码
|     CODE码              |       说明        |
| ----------------------- |:-----------------:|
|  100014    |   访问token已过期，过期时间一般为3个月|
|  100008    |   该client_id 不存在 |
|  100009    |   HTTP协议头认证非法 |
|  100002    |   client_id 访问非法 |
|  100019    |   授权码不存在，请重新授权 |
|  100018    |   该授权码无权限访问 |
|100020  |授权码已经过期，请重启授权|
|  100021    |   授权客户端非法持有授权码 |
|  100023    |   授权用户非法持有授权码 |

##5.通用码
|     CODE码              |       说明        |
| ----------------------- |:-----------------:|
|  200    |   成功码|
|  304    |   未修改 |
|  400    |   请求无效 |
|  401    |   未授权 |
|  500    |   服务内部错误 |
|  501    |   不支持该客户端 |
|  601    |   参数非法|
|  602    |   内容为空 |

##6 API列表

####6.1鉴权接口

|     NAME                |      EXPLAIN      |
| ----------------------- |:-----------------|
|  url    |   http://auth.xxx.dev.com/apis/rest/authService/authorize/json|
|  支持验证方式    |  <a href="https://www.cnblogs.com/xzwblog/p/6834663.html">HTTP协议头</a>  |
|格式|  JSON|
|http请求方式 |   GET|
|是否需要鉴权|  不需要|

**请求实例**
```PHP
http://auth.xxxx.dev.com/apis/rest/authService/authorize/json?access_token=*************
&client_id=********
&t_=1422339152058&p_=8519 

```


|     参含义数            |      含义                    |
| ----------------------- |:-----------------------------|
|  access_token    | 通过致云桌面软件登陆会生成访问Token，过期时间3个月 |
|  client_id    | HTTP协议BasicAuth,UserName值,由开放平台颁发 |
|  client_secret    | HTTP协议BasicAuth,Password值，由开放平台颁发|


**响应结果**
```PHP
{
    "data": {
        "auth_code": "c3a8ad070c745db455e30a51511dca819b607bf7"
    },
    "code": 200,
    "message": "ok"
}
```

####6.2用户信息获取接口

|     NAME                |      EXPLAIN      |
| ----------------------- |:-----------------|
|  url    | http://auth.xxxx.dev.com/apis/rest/userService/getUserInfo/json|
|  支持验证方式    |  <a href="https://www.cnblogs.com/xzwblog/p/6834663.html">HTTP协议头</a>  |
|格式|  JSON|
|http请求方式 |   GET|
|是否需要鉴权|  需要|

**请求实例**
```PHP
http://auth.xxx.dev.com/apis/rest/userService/getUserInfo/json?access_token=*************
&client_id=**********
&auth_code=**********
&t_=1422339152058&p_=8519 

```


|     参含义数            |      含义                    |
| ----------------------- |:-----------------------------|
|  access_token    | 通过致云桌面软件登陆会生成访问Token，过期时间3个月 |
|  client_id    | 由开放平台颁发 |
|  auth_code    | 由授权接口生成 |


**响应结果**
```PHP
{
    "data": {
        "username": "li******40",
        "truename": "李**",
        "sex": "3",
        "phone": "13******0170",
        "is_admin": "1",
        "status": null,
        "avatar": "http://avatar.xxxx.dev.com/000/00/11/52_avatar_small",
        "created": "2017-11-30 10:37:30",
        "nowtime": 1522404566
    },
    "code": 200,
    "message": "ok"
}
```

####6.3企业信息获取

|     NAME                |      EXPLAIN      |
| ----------------------- |:-----------------|
|  url    |http://auth.xxxx.dev.com/apis/rest/companyService/getCompanyInfo/json|
|  支持验证方式    |  <a href="https://www.cnblogs.com/xzwblog/p/6834663.html">HTTP协议头</a>  |
|格式|  JSON|
|http请求方式 |   GET|
|是否需要鉴权|  需要|

**请求实例**
```PHP
http://auth.xxxx.dev.com/apis/rest/companyService/getCompanyInfo/json?access_token=*************
&client_id=**********
&auth_code=**********
&t_=1422339152058&p_=8519 

```


|     参含义数            |      含义                    |
| ----------------------- |:-----------------------------|
|  access_token    | 通过致云桌面软件登陆会生成访问Token，过期时间3个月 |
|  client_id    | 由开放平台颁发 |
|  auth_code    | 由授权接口生成 |


**响应结果**
```PHP
{
    "data": {
        "company_name": "水磨沟区*******餐饮店",
        "company_type": "1",
        "tax_number": "6523248***************03404",
        "province": "",
        "city": "",
        "zone": "",
        "address": "乌鲁木齐市***巷186号",
        "disable": "",
        "tel_phone": "",
        "legal_person_phone": "133*****0170",
        "legal_person_name": "李**",
        "created": "2017-11-30 10:37:30",
        "nowtime": 1522404881
    },
    "code": 200,
    "message": "ok"
}
```


##7其他
 **`注意：所有接口都需要HTTP协议头认证 !`**

**提供与测试人员**
```PHP

client_id：1102084294
client_secret：aku8hCyXBd9SqYkT
------------------------------------------------
access_token:9106c9fd1e138a042d4aedf94cede7a766e0
access_token:55427e5af26a1319e168beb699b0fd1b11e

注：上token如有误，请自己去测试系统中，自己提取

```


##8相关问题
>以下问题需要明确：
>4.access_token是如何不借助客户端生成？
>    答：目前只能借助客户端生成，如需要Token,请到这里去获取
>5.要获取用户信息或企业信息的业务流程？
>    答：1.先通过授权接口获取授权码
>        2.通过授权码获取企业信息，用户信息 
>6.异常参数是如何处理的？
>    答：1.参数重复情况，协议层自动覆盖
>        2.参数多的情况，不是接口必须的，是不会做处理
>        3.参数是接口必须的，但规则非法，或者为空情况，会报错参数非法
>7.“client_id 访问非法”，含义不明确？
>    答：C企业拿B企业的授权码，访问用户C的资源，者属于client_id访问非法
>8.“HTTP协议头认证非法”是否包括HTTPS；
>    答：是的，HTTP与HTTPS，只能是否加密而已，协议层是完全一样的。
>9.“该授权码无权限访问”，权限包括哪些？
>    答： 1.获取用户信息权限getUserInfo
>         2.获取企业信息权限getCompanyInfo
>10“授权码已经过期，请重启授权”，过期时长是多少？
>    答：授权码过期时间为30秒，30秒后请重新走授权流程
>11.“授权客户端非法持有授权码”，含义不明确？
>    答：1.C企业拿B企业的授权码，访问用户C的资源，者属于授权码访问非法
>        2.与问题7类同，持凭据不同而已
>12.“授权用户非法持有授权码”，含义不明确？
>    答 1.C企业拿B企业的授权码，访问用户C的资源，者属于授权码非法持有
>       2.与问题7，问题11类同，持凭据不同而已。
>13.“501不支持该客户端”，如何判断一个客户端是否被支持？
>    答:该参数，暂不生效。
>    
>14.“参数非法”，含义不明确？
>    答: 1.参数access_token (字母+数字) 32位
>        2.参数client_id （数字） 10位
>        3.参数auth_code （字母+数字）40位
>        4.空或规则不符，则提示参数非法    
>15.“内容为空”，是指什么信息为空？
>    答：返回结果为空的会进行抛出
>16.“HTTP协议BasicAuth,UserName值,由开放平台颁发”，平台是指哪个平台？
>    答：由开放平台，目前暂由线下颁发，如有需要请联系xxx团队
>17.“通过xxx软件登陆会生成访问Token”是每次更新Token，还是3个月后，会更新一次？
>    答:3个月更新一次




