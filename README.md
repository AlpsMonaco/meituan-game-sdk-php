# meituan-game-sdk-php

## 1.说明
美团游戏开放平台 支付成功通知 支付回调php demo  
美团只提供了java版本，这边实现了php版本  
网址 https://mgc.meituan.com/open/#/docs

规则是 随机16字节+ 数据 n字节 + tag 16字节


## 2.用法
```
$appId  = "appid here";
$appSecret = "app secret here";
$secretKey = meituan::createKey($appId . "&" . $appSecret);
$data = "miwen";
$sign = meituan::encryptSHA1Str($data . $secretKey); //useless

// key 是base64_encode 后的;
// key is a base64_encode value;
meituan::decryptWithAESGCM256($secretKey, $data);
```