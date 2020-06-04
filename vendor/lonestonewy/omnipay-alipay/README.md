# Omnipay: Alipay

**增加了批量付款到支付宝账户和即时到帐批量退款接口**

[![Build Status](https://travis-ci.org/lokielse/omnipay-alipay.png?branch=master)](https://travis-ci.org/lokielse/omnipay-alipay)
[![Latest Stable Version](https://poser.pugx.org/lokielse/omnipay-alipay/version.png)](https://packagist.org/packages/lokielse/omnipay-alipay)
[![Total Downloads](https://poser.pugx.org/lokielse/omnipay-alipay/d/total.png)](https://packagist.org/packages/lokielse/omnipay-alipay)

[Omnipay](https://github.com/omnipay/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Alipay support for Omnipay.

> Cross-border Alipay payment please use [`lokielse/omnipay-global-alipay`](https://github.com/lokielse/omnipay-global-alipay)

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it to your `composer.json` file:

```json
{
    "require": {
        "lonestonewy/omnipay-alipay": "dev-master"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:


* Alipay_Express (Alipay Express Checkout) 支付宝即时到账接口
* Alipay_Secured (Alipay Secured Checkout) 支付宝担保交易接口
* Alipay_Dual (Alipay Dual Function Checkout) 支付宝双功能交易接口
* Alipay_WapExpress (Alipay Wap Express Checkout) 支付宝WAP客户端接口
* Alipay_MobileExpress (Alipay Mobile Express Checkout) 支付宝无线支付接口
* Alipay_Bank (Alipay Bank Checkout) 支付宝网银快捷接口

基于lokielse的工作，增加了以下接口

* Alipay_BatchTrans  (Alipay Express Checkout) 支付宝批量转账到支付宝账户接口
* Alipay_RefundExpress  (Alipay Express Checkout) 支付宝即时退款接口

## Usage

### Purchase
```php
$gateway = Omnipay::create('Alipay_Express');
$gateway->setPartner('8888666622221111');
$gateway->setKey('your**key**here');
$gateway->setSellerEmail('merchant@example.com');
$gateway->setReturnUrl('http://www.example.com/return');
$gateway->setNotifyUrl('http://www.example.com/notify');

//For 'Alipay_MobileExpress', 'Alipay_WapExpress'
//$gateway->setPrivateKey('/such-as/private_key.pem');

$options = [
    'out_trade_no' => date('YmdHis') . mt_rand(1000,9999), //your site trade no, unique
    'subject'      => 'test', //order title
    'total_fee'    => '0.01', //order total fee
];

$response = $gateway->purchase($options)->send();

$response->getRedirectUrl();
$response->getRedirectData();

//For 'Alipay_MobileExpress'
//Use the order string with iOS or Android SDK
$response->getOrderString();
```

### Return/Notify
```php
$gateway = Omnipay::create('Alipay_Express');
$gateway->setPartner('8888666622221111');
$gateway->setKey('your**key**here');
$gateway->setSellerEmail('merchant@example.com');

//For 'Alipay_MobileExpress', 'Alipay_WapExpress'
//$gateway->setAlipayPublicKey('/such-as/alipay_public_key.pem');

$options = [
    'request_params'=> array_merge($_POST, $_GET), //Don't use $_REQUEST for may contain $_COOKIE
];

$response = $gateway->completePurchase($options)->send();

if ($response->isPaid()) {

   // Paid success, your statements go here.

   //For notify, response 'success' only please.
   //die('success');
} else {

   //For notify, response 'fail' only please.
   //die('fail');
}
```

### 批量付款到支付宝账户
```php
$gateway    = Omnipay::create('Alipay_BatchTransGateway');
$gateway->setPartner('8888666622221111');
$gateway->setKey('your**key**here');
$gateway->setSellerEmail('merchant@example.com');
$gateway->setNotifyUrl('http://www.example.com/notify');

$detail_data = '流水号1^收款方账号1^收款账号姓名1^付款金额1^备注说明1|流水号2^收款方账号2^收款账号姓名2^付款金额2^备注说明2......';

$params = [
    'email'=>'merchant@example.com',
    'account_name'=>'merchant.name',
    'detail_data'=>$detail_data,
    'batch_no'=>$batch_no,//批号
    'batch_num'=>$batch_num,//笔数
    'batch_fee'=>$batch_fee,//总金额
    'pay_date'=>$pay_date,//付款日期
];
$response = $gateway->purchase($params)->send();

$redirect_url = $response->getRedirectUrl();
```
### 即时退款接口
该接口需要联系支付宝商服提前申请
```php
$gateway    = Omnipay::create('Alipay_RefundExpressGateway');
$gateway->setPartner('8888666622221111');
$gateway->setKey('your**key**here');
$gateway->setSellerEmail('merchant@example.com');
$gateway->setSignType('MD5');
$gateway->setInputCharset('UTF-8');
$gateway->setNotifyUrl('http://www.example.com/notify');

$data    = array(
    'refund_date' => date('Y-m-d H:i:s',time()),
    'batch_no'=>'1234567890',//退款编号
    'batch_num' => 1,//退款笔数
    'detail_data'=> mb_convert_encoding($this->alipay_trade_no.'^'.$amount.'^客户取消订单','GBK'),//退款数据，一次可以发起一批退款
);

$response = $gateway->refund($data)->send();
$debugData = $response->getData();

Yii::info('支付宝退款数据：'.print_r($debugData, true), 'payment');

if ($response->isSuccessful()) {
    //退款处理成功
}

```
上面的notify使用方法类似，具体可以参考支付宝文档，不再赘述。



For general usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

## Related

- [Omnipay-Alipay](https://github.com/lokielse/omnipay-alipay)
- [Laravel-Omnipay](https://github.com/ignited/laravel-omnipay)
- [Omnipay-GlobalAlipay](https://github.com/lokielse/omnipay-global-alipay)
- [Omnipay-WechatPay](https://github.com/lokielse/omnipay-wechatpay)
- [Omnipay-UnionPay](https://github.com/lokielse/omnipay-unionpay)

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/lokielse/omnipay-alipay/issues),
or better yet, fork the library and submit a pull request.
