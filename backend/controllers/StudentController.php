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
use dosamigos\qrcode\QrCode;
/**
 * StudentController implements the CRUD actions for WpIschoolStudent model.
 */
class StudentController extends Controller
{
    /**
     * @inheritdoc
     */
        
//	private $hash_token = "9XxjVWfXV6NQo2Ki";
        public function beforeAction($action)
        {
		$this->viewPath = '@backend/views/student';
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

    public function actionQrcode()
    {
        return QrCode::png('http://www.yii-china.com');    //调用二维码生成方法
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
//                $card_no = $searchModel->getCard();
//                $array_keys[6] = [
//                    "attribute"=>"card.card_no",
//                ];
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
        $card_model = new WpIschoolStudentCard();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $post = Yii::$app->request->post();
            $post['WpIschoolStudentCard']['card_no']= empty($post['WpIschoolStudentCard']['card_no'])?Null:$post['WpIschoolStudentCard']['card_no'];
            $card_model->stu_id=$model->attributes['id'];
            $card_model->flag=1;
            $card_model->ctime=time();
            $card_model->card_no=$post['WpIschoolStudentCard']['card_no'];
            $card_model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,'card_model'=>$card_model
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
        $card_model = WpIschoolStudentCard::findOne(['stu_id'=>$model->id])?: new WpIschoolStudentCard();
        if(\yii::$app->request->isPost)
        {
            \yii::trace(Yii::$app->request->post());
            $post = Yii::$app->request->post();
            $post['WpIschoolStudent']['cardid'] = empty($post['WpIschoolStudent']['cardid'])?Null:$post['WpIschoolStudent']['cardid'];
            $model->load($post);
            $post['WpIschoolStudentCard']['card_no']= empty($post['WpIschoolStudentCard']['card_no'])?Null:$post['WpIschoolStudentCard']['card_no'];
            $card_model->stu_id=$model->id;
            $card_model->flag=1;
            $card_model->ctime=time();
            $card_model->card_no=$post['WpIschoolStudentCard']['card_no'];

            // $card_model->load(Yii::$app->request->post());
            if ( $model->save() && $card_model->save()) {
                return $this->redirect(['index']);
            }
            else {
                return $this->render('update', [
                    'model' => $model,'card_model'=>$card_model
                ]);
            }
        }
        else {
            return $this->render('update', [
                'model' => $model,'card_model'=>$card_model
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
    	$parent_model = WpIschoolPastudent::findOne(["stu_id"=>$id]);
        // if($parent_model != null)
        //     $parent_model->deleteAll(["stu_id"=>$id]);
        //     $this->findModel($id)->delete();
        if($parent_model == null)
        $this->findModel($id)->delete();
        $card= WpIschoolStudentCard::find()->where(['stu_id'=>$id])->one();
        if($card){
            $card->delete();
        }
//        $this->findModel($id)->delete();
        //$res = $this->findModel($id);
        //$res->is_deleted = '1';
        //$res->save();
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
