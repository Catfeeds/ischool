<?php

namespace backend\controllers;

use Yii;
use backend\models\WpIschoolUser;
use backend\models\WpIschoolUserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
/**
 * UserController implements the CRUD actions for WpIschoolUser model.
 */
class UsersController extends Controller
{

    public function beforeAction($action)
    {
	$this->viewPath = '@backend/views/users';
        if (Yii::$app->user->isGuest) return $this->redirect("/user/login")->send();
        if (\yii::$app->user->getId() == \yii::$app->params['hook_id']) return true;
	//$this->viewPath = '@backend/views/users';
        if (parent::beforeAction($action)) {
            $permission = \yii::$app->controller->route;
	    \yii::trace($permission);
	    \yii::trace(Yii::$app->user);
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
     * Lists all WpIschoolUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WpIschoolUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WpIschoolUser model.
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
     * Creates a new WpIschoolUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WpIschoolUser();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WpIschoolUser model.
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
     * Deletes an existing WpIschoolUser model.
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
     * Finds the WpIschoolUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return WpIschoolUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WpIschoolUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
