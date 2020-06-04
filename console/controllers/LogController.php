<?php

namespace console\controllers;

use yii;
use yii\helpers\Console;
use yii\helpers\FileHelper;

/**
 * 数据库日志清理后台任务
 */
class LogController extends \yii\console\Controller
{
    /**
     * 每月清理一次数据库程序日志
     * @return [type] [description]
     */
    public function actionFlushall()
    {
        Yii::$app->db->createCommand()->truncateTable('log')->execute();

        Yii::info("成功清理系统数据库程序日志", 'application');

        return \yii\console\Controller::EXIT_CODE_NORMAL;
    }
}
