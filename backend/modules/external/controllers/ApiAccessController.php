<?php


namespace backend\modules\external\controllers;


use Yii;
use backend\modules\external\models\ApiAccess;
use backend\components\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;


class ApiAccessController extends Controller
{


    public function actionIndex($project_id) {
        $searchModel = new ApiAccess();
        $dataProvider = $searchModel->search(['ApiAccess'=>['project_id'=>$project_id]]);
        $dataProvider->pagination->validatePage = true;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new ApiAccess();

        $model->loadDefaultValues();
        $model->project_id = $_GET['project_id'];

        if(Yii::$app->request->isPost)
        {
            $post = Yii::$app->request->post();
            $model->load($post);
            if (!$model->hasErrors() && $model->save()) {
                Yii::$app->session->setFlash('success', ApiAccess::$modelName.'#'.$model->id.'已添加成功。');
                return $this->redirect(['index', 'project_id' => $model->project_id]);
            }else {
                Yii::$app->session->setFlash('error', \GuzzleHttp\json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = ApiAccess::findOne($id);

        if(Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());

            if (!$model->hasErrors() && $model->save()) {
                Yii::$app->session->setFlash('success', ApiAccess::$modelName.'#'.$model->id.'已更新成功。');
                return $this->redirect(['index', 'project_id' => $model->project_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

}