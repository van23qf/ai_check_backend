<?php

return [
    'adminEmail' => 'luzirq@qq.com',
    'supportEmail' => 'luzirq@qq.com',
    'servicePhone' => '02783558911',
    'serviceName' => '客服MM',
    'frontend.url' => 'https://www.papv3.ilvzhou.com',
    'pc.url' => 'https://pc.papv3.ilvzhou.com',
    'backend.url' => 'https://backend.papv3.ilvzhou.com',
    'api.url' => 'https://api.papv3.ilvzhou.com',
    'wechat.url' => 'https://wechat.papv3.ilvzhou.com/?#',
    'user.passwordResetTokenExpire' => 3600,
    // 各项目邮箱配置
    'emails' => [
        'default' => [
            'account' => 'no-reply@sysmail.ilvzhou.com', // 完整的邮箱
            'host' => 'smtpdm.aliyun.com', // smtp服务器地址
            'username' => 'no-reply@sysmail.ilvzhou.com', // 注意企业邮箱要带@后缀
            'password' => 's9XSNQIel8dFffBn',
            'port' => '80',
            // 'encryption' => 'ssl', // 若开启则port不同，一般为 994
        ],
    ],
    // 项目的短信配置，注意，服务器上在local中做了覆盖
    'sms' => [],

    'wxpay.bank.fee' => 0.001, //微信付款到银行卡手续费率
    'bank_icons' => [
        '交通银行' => 'bcm', '工商银行' => 'icbc', '建设银行' => 'ccb',
        '农业银行' => 'abc', '招商银行' => 'cmb', '中国银行' => 'boc',
        '民生银行' => 'cmbc', '平安银行' => 'pab', '邮储银行' => 'psbc',
        '光大银行' => 'ceb', '华夏银行' => 'hxb', '浦发银行' => 'spdb',
        '中信银行' => 'cncb', '兴业银行' => 'cib', '广发银行' => 'gdb',
        '北京银行' => 'amount', '宁波银行' => 'amount'
    ],
    'bank_codes' => [
        '工商银行' => '1002', '农业银行' => '1005', '中国银行' => '1026',
        '建设银行' => '1003', '招商银行' => '1001', '邮储银行' => '1066',
        '交通银行' => '1020', '浦发银行' => '1004', '民生银行' => '1006',
        '兴业银行' => '1009', '平安银行' => '1010', '中信银行' => '1021',
        '华夏银行' => '1025', '广发银行' => '1027', '光大银行' => '1022',
        '北京银行' => '1032', '宁波银行' => '1056',
    ],
    'jpush' => [
        'app_key' => 'b6d84f4066117fef08e969c8',
        'master_secret' => '58aaecc0ed506c036c2969e6',
    ],
    'wechat_open_platform' => [
        'app_id'   => 'wxbe47c4a02d140fa5',
        'secret'   => '08afc7a3269d3ffed0aea1c5a7b0a897',
        'token'    => '6YSzxdKoERSRoAfx',
        'aes_key'  => 'iM9YzJW9xcRurMGMtAWSaeVDDkGBMm8JLi809KyAdSw'
    ],
];
