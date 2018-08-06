<?php

namespace backend\controllers;

use backend\models\WpIschoolStudentCard;
use Yii;
use backend\models\WpIschoolStudent;
use backend\models\WpIschoolStudentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\WpIschoolPastudent;
use yii\web\ForbiddenHttpException;

/**
 * StudentController implements the CRUD actions for WpIschoolStudent model.
 */
class StudentController extends Controller
{
    /**
     * @inheritdoc
     */
	private $hash_token = "9XxjVWfXV6NQo2Ki";
	public function beforeAction($action)
	{
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
     * Lists all WpIschoolStudent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WpIschoolStudentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(\yii::$app->request->get("type") && \yii::$app->request->get("type") == "export")
        {
        		$array_values = $searchModel->attributeLabels();
        		$array_keys = array_keys($array_values);
        		/*$all_data = \yii\helpers\ArrayHelper::toArray($dataProvider->query->all(), [
        			'backend\models\WpIschoolStudent' => $array_keys,
        		]);*/
        		\moonland\phpexcel\Excel::export([
        			'models' => $dataProvider->query->all(),
        			'columns' => $array_keys,
        			'headers' => $array_values,
        			'fileName' => "student.xlsx"
        		]);
        }else 
        {
        		return $this->render('index', [
            		'searchModel' => $searchModel,
           			 'dataProvider' => $dataProvider,
        		]);
        }
    }
	//batchedit
    public function actionBatchedit()
    {
    	$params = \yii::$app->request->get();
        \Yii::trace(\yii::$app->request->get());
    	$querystring = \yii::$app->request->queryString;
    	return $this->render("batchedit",[
    			"querystring"=>$querystring,
    			"params" => $params
    			
    	]);
    }
    public function actionBatchupdate()
    {
    	$searchModel = new WpIschoolStudentSearch();
		$searchModel->batchUpdate(Yii::$app->request->queryParams,yii::$app->request->post('enddatepa'),yii::$app->request->post('enddateqq'),yii::$app->request->post('enddatejx'),yii::$app->request->post('enddateck'));
//
    	return $this->redirect("/student/index");
    	
    }
    /**
     * Displays a single WpIschoolStudent model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
	public function actionBind($id)
	{
		$model = $this->findModel($id);
		$parents = WpIschoolPastudent::getParents($id);
		return $this->render('bind', [
				'model' => $model,
				'parents' => $parents
		]);
		
	}
    /**
     * Creates a new WpIschoolStudent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WpIschoolStudent();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WpIschoolStudent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(\yii::$app->request->isPost)
        {
            \yii::trace(Yii::$app->request->post());
            $model->load(Yii::$app->request->post());
            $card_model = WpIschoolStudentCard::findOne(['stu_id'=>$model->id])?: new WpIschoolStudentCard();
            $card_model->load(Yii::$app->request->post());
            if ( $model->save() && $card_model->save()) {
                return $this->redirect(['index']);
            }
            else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WpIschoolStudent model.
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
     * Finds the WpIschoolStudent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return WpIschoolStudent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WpIschoolStudent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
