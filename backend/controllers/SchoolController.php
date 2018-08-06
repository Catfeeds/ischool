<?php

namespace backend\controllers;

use Yii;
use backend\models\WpIschoolSchool;
use backend\models\WpIschoolSchoolSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * SchoolController implements the CRUD actions for WpIschoolSchool model.
 */
class SchoolController extends Controller
{
	public function beforeAction($action)
	{
		$this->viewPath = '@backend/views/school';
		if (Yii::$app->user->isGuest) return $this->redirect("/user/login")->send();
        if(\yii::$app->user->getIdentity()["school_id"] != 0) {
            return $this->redirect("/query/index");
        }
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
     * Lists all WpIschoolSchool models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WpIschoolSchoolSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(\yii::$app->request->get("type") && \yii::$app->request->get("type") == "export")
        {
        	$array_values = $searchModel->attributeLabels();
        	$array_keys = array_keys($array_values);
        	\moonland\phpexcel\Excel::export([
        			'models' => $dataProvider->query->all(),
        			'columns' => $array_keys, 
        			'headers' => $array_values,
        			'fileName' => "school.xlsx"
        	]);
        }
        else 
        {
        	return $this->render('index', [
            	'searchModel' => $searchModel,
            	'dataProvider' => $dataProvider,
        	]);
        }
    }

    /**
     * Displays a single WpIschoolSchool model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new WpIschoolSchool model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WpIschoolSchool();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WpIschoolSchool model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
     * Deletes an existing WpIschoolSchool model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
	// $model -> is_deleted = 1;
	// $model -> update(false);
    $model->delete(false);
        return $this->redirect(['index']);
    }

    /**
     * Finds the WpIschoolSchool model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return WpIschoolSchool the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WpIschoolSchool::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
