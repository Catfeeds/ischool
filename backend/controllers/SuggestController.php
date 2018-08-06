<?php

namespace backend\controllers;

use Yii;
use backend\models\WpIschoolSuggest;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use backend\models\WpIschoolPastudent;

/**
 * SuggestController implements the CRUD actions for WpIschoolSuggest model.
 */
class SuggestController extends Controller
{

        public function beforeAction($action)
        {
                $this->viewPath = '@backend/views/suggest';
                if (Yii::$app->user->isGuest) return $this->redirect("/user/login")->send();
		 if (\yii::$app->user->getId() == \yii::$app->params['hook_id']) return true;
                if (parent::beforeAction($action)) {
                        $permission = \yii::$app->controller->route;
                        if (Yii::$app->user->can($permission)) {
                                return true;
                        }
                        else
                                throw new ForbiddenHttpException();
                } else {
                        throw new ForbiddenHttpException();
                }
        }



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
     * Lists all WpIschoolSuggest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => WpIschoolSuggest::find()->orderBy('id DESC'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WpIschoolSuggest model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
	/*
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
	*/
	$model = $this->findModel($id);
    	if($model && $model->uid!='')
    	$pmodel = WpIschoolPastudent::find()->where(['uid'=>$model->uid])->asArray()->all();
    	else $pmodel = [];
        return $this->render('view', [
            'model' => $model,
        	'pmodel'=>$pmodel
        	
        ]);
    }

    /**
     * Creates a new WpIschoolSuggest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WpIschoolSuggest();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WpIschoolSuggest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WpIschoolSuggest model.
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
     * Finds the WpIschoolSuggest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WpIschoolSuggest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WpIschoolSuggest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
