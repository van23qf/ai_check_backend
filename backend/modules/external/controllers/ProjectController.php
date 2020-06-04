<?php

namespace backend\modules\external\controllers;

use Yii;
use backend\modules\external\models\Project;
use backend\components\Controller;
use yii\web\NotFoundHttpException;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
{
    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Project();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->validatePage = true;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();
        $model->loadDefaultValues();

        if(Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
            // 取消注释来上传文件/图片
            // $model->uploadFiles($model->fileAttributes);
            // $model->uploadImages($model->fileAttributes);

            if (!$model->hasErrors() && $model->save()) {
                Yii::$app->session->setFlash('success', Project::$modelName.'#'.$model->id.'已添加成功。');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
            // 取消注释来上传文件/图片
            // $model->uploadFiles($model->fileAttributes);
            // $model->uploadImages($model->fileAttributes);

            if (!$model->hasErrors() && $model->save()) {
                Yii::$app->session->setFlash('success', Project::$modelName.'#'.$model->id.'已更新成功。');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Yii::$app->session->setFlash('warning', Project::$modelName.'#'.$model->id.'已成功删除。');
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
