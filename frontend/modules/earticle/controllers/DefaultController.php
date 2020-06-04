<?php

namespace frontend\modules\earticle\controllers;

use api\modules\eaccount\models\Account;
use api\modules\earticle\models\Member;
use api\modules\earticle\models\Special;
use api\modules\earticle\models\Upload;
use api\modules\earticle\models\User;
use common\models\Sms;
use frontend\components\Controller;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Response;

/**
 * DefaultController implements the CRUD actions for Upload model.
 */
class DefaultController extends Controller
{
    public $cacheKey;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Creates a new Upload model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpload()
    {
        if ($_GET['openid']) {
            $user = User::find()->where(['openid' => trim($_GET['openid'])])->one();
            if ($user !== null) {
                $member = Member::find()->where(['user_id' => $user->id])->one();
                if ($member !== null) {
                    Yii::$app->user->login($member);
                }
            }
        }
        $member = new Member;
        if (Yii::$app->user->identity) {
            $member = Yii::$app->user->identity;
        }
        $upload = new Upload;
        $upload->loadDefaultValues();
        if (Yii::$app->request->isGet && !Yii::$app->user->isGuest) {
            $account = Account::findOne(['mobile' => Yii::$app->user->identity->mobile]);
        }

        if (Yii::$app->request->isGet) {
            Yii::$app->session['files'] = null;
        }
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $info = $post['Upload'];

            if (Yii::$app->user->isGuest) {
                if (YII_ENV != 'dev' && Yii::$app->session->get('verifycode') != trim($info['verifycode'])) {
                    Yii::$app->session->setFlash('error', '无效的短信验证码');
                    return $this->redirect(['upload']);
                }
                $member = Member::find()->where(['mobile' => trim($info['mobile'])])->one();
                if ($member === null) {
                    Yii::$app->session->setFlash('error', '不存在该会员，请核对您输入的信息');
                    return $this->redirect(['upload']);
                }
                Yii::$app->user->login($member);
                return $this->redirect(['upload']);
            }
            if (!$info['entity_id']) {
                Yii::$app->session->setFlash('error', '请选择病种分类');
                return $this->redirect(['upload']);
            }
            if (Yii::$app->session['files']) {
                $files = Yii::$app->session['files'];
                foreach ($files as $key => $file) {
                    $model = new Upload();
                    $model->member_id = Yii::$app->user->id;
                    $model->mobile = Yii::$app->user->identity->mobile;
                    // $model->task_id = $info['task_id'];
                    $model->special_id = $info['special_id'];
                    $model->entity_id = $info['entity_id'];
                    $model->amount = $info['amount'] ? $info['amount'] : 0;
                    $model->file_path = $file;
                    $model->status = Upload::STATUS_PENDING;
                    $model->save(false);
                }
                Yii::$app->session['files'] = null;
                Yii::$app->session->setFlash('success', '您的文章已上传成功。');
                return $this->redirect(['upload']);
            } else {
                Yii::$app->session->setFlash('error', '请先上传文件');
                return $this->redirect(['upload']);
            }
        }

        return $this->render('upload', [
            'model' => $upload,
            'account' => $account,
            'member' => $member,
        ]);
    }

    public function actionUploadArticle()
    {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // Support CORS
        // header("Access-Control-Allow-Origin: *");
        // other CORS headers if any...
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit; // finish preflight CORS requests here
        }

        // Settings
        $targetDir = Yii::getAlias('@webroot') . '/assets/upload_tmp';
        $uploadDir = Yii::getAlias('@webroot') . '/upload/article-application/' . date('Ym');

        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds

        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir, 0777, true);
        }

        // Create target dir
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        // Get a file name
        //        if (isset($_REQUEST["name"])) {
        //            $fileName = $_REQUEST["name"];
        //        } elseif (!empty($_FILES)) {
        //            $fileName = $_FILES["file"]["name"];
        //        } else {
        $fileName = uniqid("file_") . '.' . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
//        }

        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;

        // Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                echo ('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
                Yii::$app->end();
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }

        // Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            echo ('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            Yii::$app->end();
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                echo ('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
                Yii::$app->end();
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                echo ('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                Yii::$app->end();
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                echo ('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                Yii::$app->end();
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

        $index = 0;
        $done = true;
        for ($index = 0; $index < $chunks; $index++) {
            if (!file_exists("{$filePath}_{$index}.part")) {
                $done = false;
                break;
            }
        }
        if ($done) {
            if (!$out = @fopen($uploadPath, "wb")) {
                echo ('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
                Yii::$app->end();
            }

            if (flock($out, LOCK_EX)) {
                for ($index = 0; $index < $chunks; $index++) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }

                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }

                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }

                flock($out, LOCK_UN);
            }
            @fclose($out);
        }
        $files = Yii::$app->session['files'];
        $files[] = str_replace(Yii::getAlias('@webroot'), '', $uploadPath);
        Yii::$app->session['files'] = $files;
        echo ('{"jsonrpc" : "2.0", "result" : "' . print_r($files, true) . '", "id" : "id"}');
        Yii::$app->end();
    }

    //短信
    public function actionSendverifycode()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
            //次数限制
            $count = Yii::$app->cache->get('smscount_' . $_POST['mobile']);
            if (!$count) {
                $count = 1;
            } else {
                $count += 1;
            }

            if ($count > 10) { //最大重试3次
                echo JSON::encode(array('isValid' => false, 'resultContent' => '最多重发10次，若没有收到请耐心等待'));
                Yii::$app->end();
            }

            Yii::$app->cache->set('smscount_' . $_POST['mobile'], $count, 3600);

            //发送频繁限制
            $time = Yii::$app->cache->get('smscount_' . $_POST['mobile']);
            if ($time && time() - $time < 60) {
                echo JSON::encode(array('isValid' => false, 'resultContent' => '60秒之内不能重发，请勿频繁点击'));
                Yii::$app->end();
            } else {
                Yii::$app->cache->set('smstime_' . $_POST['mobile'], time(), 3600);
            }

            $verifycode = rand(100000, 999999);
            Yii::$app->session['verifycode'] = $verifycode;
            $appname = Yii::$app->name;

            switch ($_POST['SmsType']) {
                case '2':
                    $message = "重置密码：您的手机验证码为{$verifycode}，请确认是本人操作，否则请忽视。";
                    break;
                case '3':
                    $message = "您好！您的手机验证码为{$verifycode}（短信验证码，请勿泄露） ，请继续操作。";
                    break;
                case '4':
                    $message = "更换手机号：您的原手机验证码为{$verifycode}请确认是本人操作，否则请忽视。";
                    break;
                default:
                    $message = "注册通知：您的手机验证码为{$verifycode}，请继续操作。";
                    break;
            }
            try {
                $sms = new Sms;
                $sms->mobile = $_POST['mobile'];
                $sms->content = $message;
                $sms->config_id = $this->config->id;
                $sms->user_id = 0;
                $sms->type = Sms::TYPE_VERIFYCODE;
                if ($sms->save()) {
                    return ['isValid' => true, 'resultContent' => '短信已发送'];
                }
            } catch (Exception $e) {
                return ['isValid' => false, 'resultContent' => '短信发送失败(' . $e->getMessage() . ')，请与客服联系'];
            }
        }
    }

    public function actionCreateSpecial()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
            try {
                $post = Yii::$app->request->post();
                $member = Yii::$app->user->identity;
                $special = new Special;
                $special->name = $post['name'];
                if ($special->save()) {
                    Yii::$app->session->setFlash('success', '新建成功');
                    return $this->redirect(['upload']);
                }
            } catch (Exception $e) {
                return ['isValid' => false, 'resultContent' => '新建失败(' . $e->getMessage() . ')'];
            }
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['upload']);
    }
}
