<?php
namespace backend\controllers;

use api\modules\pap\models\SmsNotice;
use Yii;
use backend\components\Controller;
use yii\db\Expression;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\base\UserException;
use backend\models\LoginForm;
use common\models\PasswordModifyForm;
use common\models\WechatConfig;
use common\models\User;
use common\models\WechatNotification;
use common\models\Sms;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return \yii\helpers\ArrayHelper::merge([
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'captcha'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'password', 'config-selected'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ], parent::behaviors());
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'width'=>60,
                'height'=>30,
                'testLimit' =>10,
                'maxLength'=>4,
                'minLength'=>4,
                'foreColor'=>0x333333,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionPassword()
    {
        $model = new PasswordModifyForm;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            echo json_encode(ActiveForm::validate($model));
            Yii::$app->end();
        }

        if ($model->load(Yii::$app->getRequest()->post())&& $model->validate() && $model->modify()) {
            Yii::$app->session->setFlash('success', '您的登录密码已修改成功！');
            return $this->redirect(['/site/index']);
        }

        return $this->render('password', [
            'model'  => $model,
        ]);
    }

    public function actionConfigSelected($id = 0){
        if(!$id){
            $session = Yii::$app->session;
            $session['config'] = null;
            Yii::$app->session->setFlash('success', '已切换到不限微信配置');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $configs = Yii::$app->user->identity->getConfigs()->select(['id', 'name'])->asArray()->all();
        $valid_config_ids = \yii\helpers\ArrayHelper::getColumn($configs, 'id');
        if(in_array($id, $valid_config_ids)){
            $config = WechatConfig::findOne($id);
            $session = Yii::$app->session;
            $session['config'] = $config->attributes;
            Yii::$app->session->setFlash('success', '当前微信配置已切换到'.$config->name);
            return $this->redirect(Yii::$app->request->referrer);
        }
        else{
            throw new UserException('无效的id');
        }
    }
}
