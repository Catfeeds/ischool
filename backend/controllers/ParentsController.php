<?php

namespace backend\controllers;

use Yii;
use backend\models\WpIschoolPastudent;
use backend\models\WpIschoolPastudentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\WpIschoolStudent;
use yii\web\ForbiddenHttpException;
use backend\models\WpIschoolGroupMessage;
use backend\models\WXSendMsg;

/**
 * ParentsController implements the CRUD actions for WpIschoolPastudent model.
 */
class ParentsController extends Controller
{
	public function beforeAction($action)
	{
		$this->viewPath = '@backend/views/parents';
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
     * Lists all WpIschoolPastudent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new WpIschoolGroupMessage();
        $searchModel = new WpIschoolPastudentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(\yii::$app->request->get("type") && \yii::$app->request->get("type") == "export")
        {
        	$array_values = $searchModel->attributeLabels();
        	$array_keys = array_keys($array_values);
        	\moonland\phpexcel\Excel::export([
        			'models' => $dataProvider->query->all(),
        			'columns' => $array_keys,
        			'headers' => $array_values,
        			'fileName' => "parent.xlsx"
        	]);
        }else 
        {
        	return $this->render('index', [
            	'searchModel' => $searchModel,
            	'dataProvider' => $dataProvider,
                'model'=>$model
        	]);
        }
    }

    /**
     * Displays a single WpIschoolPastudent model.
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
     * Creates a new WpIschoolPastudent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WpIschoolPastudent();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionGrouppage()
    {
    	$model = new WpIschoolGroupMessage();
    	return $this->render("gsend",[
    			'model'=>$model
    	]);
    }
    //家长群组发送信息
    public function actionGroupsend()
    {
//        var_dump(\yii::$app->request->post());exit();
        Yii::trace(\yii::$app->request->post());
    	$model = new WXSendMsg();
    	$model->doSendMsg(\yii::$app->request->post(),'PARENT');
        return $this->render("@backend/views/import/page",[
                                "errorinfo"=>"操作成功",
				"location_url"=>"/parents/grouppage"
        ]);  
    
    }
    //单独家长发送信息
    public function actionJzsend()
    {
//        var_dump(\yii::$app->request->post());exit();
        $model = new WXSendMsg();
        $model->doSendMsg(\yii::$app->request->post(),'PARENT');
        return $this->render("@backend/views/import/page",[
            "errorinfo"=>"操作成功",
            "location_url"=>"/parents/index"
        ]);

    }
    /**
     * Updates an existing WpIschoolPastudent model.
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
     * Deletes an existing WpIschoolPastudent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // $model = $this->findModel($id);
        // $model -> is_deleted = 1;
        // $model -> update();
         $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }
	public function actionAjaxdelete($id)
	{
		$model = $this->findModel($id);
//		$model -> is_deleted = 1;
//		$model -> update();
		$model ->delete();
		if(\yii::$app->request->isAjax){
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			return ['status'=>1];
		}
		else return ['status'=>0];
	}
    public function actionAjaxsave()
    {
    	$post_params = \yii::$app->request->post();
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	if($post_params['parent_id'] > 0)
    	{
    		$model = WpIschoolPastudent::findOne($post_params['parent_id']);
    		if(!$model) return ['status'=>0];
    		$model -> Relation = $post_params['parent_relation'];
    		$model -> name = $post_params['parent_name'];
    		$model -> tel = $post_params['parent_tel'];
            $model -> isqqtel = 1;
    		if($model->update()) return ['status'=>1];
    		else return ['status'=>0];
    	}
    	else 
    	{
    		$stuModel = WpIschoolStudent::findOne($post_params['stu_id']);
    		if(!$stuModel) return ['status'=>0];
    		$parModel = new WpIschoolPastudent();
    		$parModel -> Relation = $post_params['parent_relation'];
    		$parModel -> name = $post_params['parent_name'];
    		$parModel -> tel = $post_params['parent_tel'];
    		$parModel -> sid = $stuModel->sid;
    		$parModel -> school = $stuModel -> school;
    		$parModel -> stu_id = $stuModel -> id;
    		$parModel -> stu_name = $stuModel -> name;
    		$parModel -> cid = $stuModel -> cid;
    		$parModel ->class = $stuModel ->class;
    		$parModel -> ctime = time();
            $parModel -> isqqtel = 1;
    		if($parModel->save()) return ['status'=>1];
    		else return ['status'=>0];
    	}
    }
    /**
     * Finds the WpIschoolPastudent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return WpIschoolPastudent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WpIschoolPastudent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
