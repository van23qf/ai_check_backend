<?php
return [
    'ssh_username' => '',
    'ssh_password' => '',
    'pandoc' => '/usr/local/bin/pandoc',
    // 需要跳过默认模板消息同步的模块
    'skipNotificationCronJob' => ['medassist', 'yaxb', 'ecase', 'gfyz', 'yns', 'glbg', 'xlc'],
    'cronJobs' => [
        'log/flushall' => [
            'cron' => '0 0 1 * *',
        ],
        // 队列 已使用supervisord运行守护进程
        'queue/run'=> [
            'cron'      => '* * * * *',
        ],
        //pap模板消息同步
        'wechat-notification/sync-pap' => [
            'cron' => '0,10,20,30,40,50 * * * *',
        ],
        //心里程数据同步
        // 'xlc/coin/fresh-patient' => [
        //     'cron' => '0,10,20,30,40,50 * * * *',
        // ],
        //心里程积分
        'xlc/coin/sync-pap' => [
            'cron' => '15 * * * *',
        ],
        //心里程-随访提醒模板消息
        'xlc/apply-remind/check-and-send' => [
            'cron' => '30 9,19 * * *',
        ],
        'earticle/settle/create' => [
            'cron' => '0 9 * * *',
        ],
        // 医药筹 患者同步
        'medassist/pap/sync-patients' => [
            'cron' => '0,10,20,30,40,50 * * * *',
        ],
        // 医药筹 项目统计
        'medassist/pap/sync-projects' => [
            'cron' => '30 * * * *',
        ],
        // 医药筹 医生同步
        'medassist/pap/sync-doctors' => [
            'cron' => '0 23 * * *',
        ],
        // 医药筹 微信模板消息同步
        'medassist/wechat-notification/sync-pap' => [
            'cron' => '0,5,10,15,20,25,30,35,40,45,50,55 * * * *',
        ],
        // 医签到 删除过期会议任务
        'lecture/delete' => [
            'cron' => '* 8 * * *',
        ],
        // 医签到 会议结束结算
        'lecture/lecture-fee' => [
            'cron' => '* 8 * * *',
        ],
        // 队列数量监控
        'supervision/queue' => [
            'cron' => '* * * * *',
        ],
        //依尼舒模板消息
        'yns/wechat-notification/sync-pap' => [
            'cron' => '0,10,20,30,40,50 * * * *',
        ],
        //共赋友助模板消息
        'gfyz/wechat-notification/sync-pap' => [
            'cron' => '0,10,20,30,40,50 * * * *',
        ],
        //优爱相伴模板消息
        'yaxb/wechat-notification/sync-pap' => [
            'cron' => '* * * * *',
        ],
        //同步医生的录入信息
        'doctor/chang' => [
            'cron' => '30 2,4,6,8,10,12,14,16,18,20,22 * * *',
        ],
        //博爱新生自定义短信
        'baxs/sms/send' => [
            'cron' => '30 8 * * *',
        ],
        //病例收集后续上传
        'ecase/follow/create' => [
            'cron' => '0 1 * * *',
        ],
        //病例收集后续上传提醒
        // 'ecase/follow/notice' => [
        //     'cron' => '0 1 * * *',
        // ],
        //同步pap是否上传病例字段
        'ecase/follow/pap-receive' => [
            'cron' => '0 8-18/2 * * *',
        ],
        'glbg/wechat-notification/sync-pap' => [
            'cron' => '0,5,10,15,20,25,30,35,40,45,50,55 * * * *',
        ],
        'xlc/wechat-notification/sync-pap' => [
            'cron' => '0,5,10,15,20,25,30,35,40,45,50,55 * * * *',
        ],
    ],
];
