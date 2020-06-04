<?php

namespace backend\controllers;

use Yii;
use common\models\Administrator;
use common\models\search\AdministratorSearch;
use backend\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdministratorController implements the CRUD actions for Administrator model.
 */
class AdministratorController extends Controller
{
    /**
     * Lists all Administrator models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdministratorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Administrator model.
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
     * Creates a new Administrator model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Administrator();

        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword($model->password);
            $model->generateAuthKey();
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {

            $roles = \yii\helpers\ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');

            if(!Yii::$app->user->isAdmin){
                unset($roles['Admin']);
            }

            return $this->render('create', [
                'model' => $model,
                'roles' => $roles,
            ]);
        }
    }

    /**
     * Updates an existing Administrator model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->password) $model->setPassword($model->password);
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $roles = \yii\helpers\ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');

            if(!Yii::$app->user->isAdmin){
                unset($roles['Admin']);
            }

            return $this->render('update', [
                'model' => $model,
                'roles' => $roles,
            ]);
        }
    }

    /**
     * Deletes an existing Administrator model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Administrator model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Administrator the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Administrator::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
