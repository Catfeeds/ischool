<?php

namespace backend\controllers;

use Yii;
use backend\models\WpIschoolOrder;
use backend\models\WpIschoolOrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * OrderController implements the CRUD actions for WpIschoolOrder model.
 */
class OrderController extends Controller
{
    /**
     * @inheritdoc
     */
	public function beforeAction($action)
	{
		$this->viewPath = '@backend/views/order';
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
     * Lists all WpIschoolOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WpIschoolOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $array_columns = [
			[
        		"attribute"=>'school',
				"value"=>"studentinfo.school"
			],
			[
				"attribute"=>'class',
				"value"=>"studentinfo.class"
    		],
        	[
        		"attribute"=>'name',
        		"value"=>"studentinfo.name"
    		],
        	[
        		"attribute"=>'stuno2',
        		"value"=>"studentinfo.stuno2"
        	],
        	[
        		"attribute"=>'enddate',
        		"value"=>function ($model)
        		{
        			$studentinfo = $model->studentinfo;
        			return $studentinfo?date("Y-m-d H:i:s",$studentinfo->enddate):"1970-01-01 00:00:00";
        		}
        	],
        	'ctime:datetime',
            'money',
        	[
        		"attribute"=>"ispass",
        		"value"=>function ($model)
            	{
            		return $model->ispass ? "已缴费" : "未缴费";
            	},
            	"filter"=>["未缴费","已缴费"]
    		],
			['class' => 'yii\grid\ActionColumn',
				'template' => '{delete}',
			],
        ];
        if(\yii::$app->request->get("type") && \yii::$app->request->get("type") == "export")
        {
        	$array_values = $searchModel->attributeLabels();
        	$array_keys = array_keys($array_values);
        	\moonland\phpexcel\Excel::export([
        			'models' => $dataProvider->query->all(),
        			'columns' => $array_columns,
        			'headers' => $array_values,
        			'fileName' => "order.xlsx"
        	]);
        }else 
        {
        	return $this->render('index', [
            	'searchModel' => $searchModel,
            	'dataProvider' => $dataProvider,
        		'arrayColumns' => $array_columns
        	]);
        }
    }

    /**
     * Displays a single WpIschoolOrder model.
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
     * Creates a new WpIschoolOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WpIschoolOrder();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WpIschoolOrder model.
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
     * Deletes an existing WpIschoolOrder model.
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
     * Finds the WpIschoolOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return WpIschoolOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WpIschoolOrder::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
