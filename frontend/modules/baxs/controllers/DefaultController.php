<?php

namespace frontend\modules\baxs\controllers;

use api\modules\baxs\models\Patient;
use api\modules\baxs\models\Volunteer;
use frontend\components\Controller;
use Yii;
use yii\filters\VerbFilter;

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
    public function actionIndex()
    {
        $patients = [];
        if (!Yii::$app->user->isGuest) {
            $model = Volunteer::findOne(['mobile' => Yii::$app->user->identity->mobile, 'name' => Yii::$app->user->identity->name]);
            $patients = Patient::findAll(['volunteer_id' => $model->id]);
        }

        if (Yii::$app->user->isGuest && Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $model = Volunteer::findOne(['mobile' => $post['Volunteer']['mobile'], 'name' => $post['Volunteer']['name']]);
            if ($model && Yii::$app->user->login($model)) {
                return $this->redirect(['index']);
            }
        }
        if (!$model) {
            $model = new Volunteer;
        }
        return $this->render('index', [
            'model' => $model,
            'patients' => $patients,
        ]);
    }
}
