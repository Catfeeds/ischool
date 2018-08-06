<?php

namespace backend\controllers;

use backend\models\WpIschoolTeaclass;
use Yii;
use backend\models\WpIschoolTeacher;
use backend\models\WpIschoolTeacherSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use backend\models\WpIschoolGroupMessage;
use backend\models\WXSendMsg;

/**
 * TeacherController implements the CRUD actions for WpIschoolTeacher model.
 */
class TeacherController extends Controller
{
	public function beforeAction($action)
	{
		$this->viewPath = '@backend/views/teacher';
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
     * Lists all WpIschoolTeacher models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WpIschoolTeacherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        var_dump($dataProvider);exit();
		$array_columns = ['sid','school',
        	[
        		"attribute"=>"cid",
        		"value"=>"cid",
    		],
        	[
        		"attribute"=>"class",
        		"value"=>"class",
    		],
        	'tname',
        	'tel'];
        if(\yii::$app->request->get("type") && \yii::$app->request->get("type") == "export")
        {
        	$array_values = $searchModel->attributeLabels();
        	$array_keys = array_keys($array_values);
        	\moonland\phpexcel\Excel::export([
        			'models' => $dataProvider->query->all(),
        			'columns' => $array_columns,
        			'headers' => $array_values,
        			'fileName' => "teacher.xlsx"
        	]);
        }
        else {
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'arrayColumns' => $array_columns
        ]);
        }
    }

    /**
     * Displays a single WpIschoolTeacher model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionGrouppage()
    {
		$model = new WpIschoolGroupMessage();
		return $this->render("gsend",[
				'model'=>$model
		]);
    }
    public function actionGroupsend()
    {
    
    	$model = new WXSendMsg();
    	$model->doSendMsg(\yii::$app->request->post(),'TEACHER');
	return $this->render("/import/page",[
				"errorinfo"=>"操作成功",
				"location_url"=>"/teacher/grouppage"
	]);
    
    }
    /**
     * Creates a new WpIschoolTeacher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WpIschoolTeacher();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WpIschoolTeacher model.
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
     * Deletes an existing WpIschoolTeacher model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	$model = $this->findModel($id);
//    	$model->is_deleted = 1;
    	$model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the WpIschoolTeacher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return WpIschoolTeacher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WpIschoolTeaclass::findOne($id)) !== null) {
            return $model;
        } else {
        	\yii::trace($model);
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
