<?php


namespace backend\modules\external\controllers;


use backend\modules\external\models\Project;
use Yii;
use backend\modules\external\models\ApiConfig;
use backend\components\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;


class ApiConfigController extends Controller
{


    public function actionIndex($project_id) {
        $project = Project::findOne($project_id);
        $searchModel = new ApiConfig();
        $dataProvider = $searchModel->search(['ApiConfig'=>['project'=>$project->project_name]]);
        $dataProvider->pagination->validatePage = true;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new ApiConfig();

        $model->loadDefaultValues();
        $project = Project::findOne($_GET['project_id']);
        $model->project = $project->project_name;

        if(Yii::$app->request->isPost)
        {
            $post = Yii::$app->request->post();
            $model->load($post);
            if (!$model->hasErrors() && $model->save()) {
                Yii::$app->session->setFlash('success', ApiConfig::$modelName.'#'.$model->id.'已添加成功。');
                return $this->redirect(['index', 'project_id' => $project->id]);
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
        $model = ApiConfig::findOne($id);

        $project = Project::findOne(['project_name'=>$model->project]);

        if(Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());

            if (!$model->hasErrors() && $model->save()) {
                Yii::$app->session->setFlash('success', ApiConfig::$modelName.'#'.$model->id.'已更新成功。');
                return $this->redirect(['index', 'project_id' => $project->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'project' => $project,
        ]);
    }

}