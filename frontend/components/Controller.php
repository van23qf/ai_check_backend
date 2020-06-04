<?php
namespace frontend\components;

use yii;
use yii\web\Controller as BaseController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\WechatConfig as Config;
use common\models\User;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use EasyWeChat\Foundation\Application as WechatApplication;
use yii\helpers\FileHelper;
use yii\base\UserException;

/**
* 控制器基类
*/
class Controller extends BaseController
{
  public $config;
  public $wechat;
  public $pap_api;

  public function init()
  {
      $config = Config::find()->where(['module_id' => $this->module->id])->one();
      if ($config === null) {
          throw new UserException('找不到对应的微信配置');
      }
      $this->pap_api = 'http://'.$config->pap_domain.'/weixin/xlcmp/';
      $this->config = $config;
      $this->wechat = $this->config->getWechatApp();

      Yii::$app->setComponents([
          'mailer'=> [
              'class' => 'yii\swiftmailer\Mailer',
              'viewPath' => '@common/mail',
              'useFileTransport'=>false,
              'transport'=>[
                'class' => 'Swift_SmtpTransport',
                'host'=> $config['email_host'] ?$config['email_host']: Yii::$app->params['emails']['default']['host'],
                'username'=> $config['email_account'] ? $config['email_account']: Yii::$app->params['emails']['default']['username'],
                'password'=> $config['email_pwd'] ? $config['email_pwd']: Yii::$app->params['emails']['default']['password'],
                'port'=>465,
                'encryption'=>'ssl',
              ],
          ]
      ]);
  }
}