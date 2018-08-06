<?php

namespace backend\controllers;

use Yii;
use backend\models\WpIschoolClass;
use yii\data\ArrayDataProvider;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use backend\models\WpIschoolStudent;
use yii\web\ForbiddenHttpException;

/**
 * ClassController implements the CRUD actions for WpIschoolClass model.
 */
class ClassController extends Controller
{
	public function beforeAction($action)
	{
		$this->viewPath = '@backend/views/class';
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
     * Lists all WpIschoolClass models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$search_school = \yii::$app->request->get("school")?:"";
    	$allModel = WpIschoolClass::getAllClassInfo($search_school);
    	if(\yii::$app->request->get("type") && \yii::$app->request->get("type") == "export")
    	{
    			$array_values =  [
            		'school' => '学校名称',
           			 'id' => '班级ID',
            		'name' => '班级名字',
            		'number' => '人数',
            		'tname' => '班主任',
           			 'tel' => '联系电话',
        		];
    			$array_keys = array_keys($array_values);
    			\moonland\phpexcel\Excel::export([
    					'models' => $allModel,
    					'columns' => $array_keys,
    					'headers' => $array_values,
    					'fileName' => "class.xlsx"
    			]);
    	}else 
    	{
        	$dataProvider = new ArrayDataProvider([
        		'allModels' => $allModel,
        		'key'=>"id"
       		 ]);

        	return $this->render('index', [
            	'dataProvider' => $dataProvider,
        		'searchSchool'=>$search_school
        		]);
    	}
    }


    /**
     * Displays a single WpIschoolClass model.
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
     * Creates a new WpIschoolClass model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WpIschoolClass();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WpIschoolClass model.
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
     * Deletes an existing WpIschoolClass model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	$result = WpIschoolStudent::findAll(["cid"=>$id]);
    	if(empty($result))
    	{
        	$model = $this->findModel($id);
			// $model -> is_deleted = 1;
			$model -> delete();
        	return $this->redirect(['index']);
    	}else 
    	{
    		return $this->render("/import/page",[
    				"errorinfo"=>"班级存在学生，不能删除",
    				"location_url"=>"/class/index"
    		]);
    	}
    }

    /**
     * Finds the WpIschoolClass model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return WpIschoolClass the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WpIschoolClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
