<?php

namespace backend\controllers;

use Yii;
use backend\models\WpIschoolSafecard;
use backend\models\WpIschoolSafecardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * SafeCardController implements the CRUD actions for WpIschoolSafecard model.
 */
class SafecardController extends Controller
{
	public function beforeAction($action)
	{
		$this->viewPath = '@backend/views/safecard';
		if (Yii::$app->user->isGuest) return $this->redirect("/user/login")->send();
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
     * Lists all WpIschoolSafecard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WpIschoolSafecardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$column_arr = [
        	'id',
            'stuid',
            'info',
            'ctime:datetime',
             'receivetime:datetime',
        ];
        if(\yii::$app->request->get("type") && \yii::$app->request->get("type") == "export")
        {
        	$array_values = $searchModel->attributeLabels();
        	$array_keys = array_keys($array_values);
        	/*$all_data = \yii\helpers\ArrayHelper::toArray($dataProvider->query->all(), [
        			'backend\models\WpIschoolSchool' => $array_keys,
        	]);*/
        	\moonland\phpexcel\Excel::export([
        			'models' => $dataProvider->query->all(),
        			'columns' => $column_arr,
        			'headers' => $array_values,
        			"fileName"=>"safecard.xlsx"
        	]);
        }else 
        {
        		return $this->render('index', [
            		'searchModel' => $searchModel,
            		'dataProvider' => $dataProvider,
        			'columnsArray' => $column_arr
        		]);
        }
    }

    /**
     * Displays a single WpIschoolSafecard model.
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
     * Creates a new WpIschoolSafecard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WpIschoolSafecard();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WpIschoolSafecard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WpIschoolSafecard model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the WpIschoolSafecard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return WpIschoolSafecard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WpIschoolSafecard::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
