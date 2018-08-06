<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\filters\VerbFilter;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\WpIschoolPastudentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '家长管理';

?>
<div class="wp-ischool-pastudent-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
    <?= Html::a('导出', [strpos(\yii::$app->request->url,"?")>-1? \yii::$app->request->url.'&type=export':\yii::$app->request->url.'?type=export'], ['class' => 'btn btn-danger pull-right','target'=>"_blank"]) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
        	'tel',
        	'stu_name',
        	'stu_id',
            'class',
        	'cid',
            'school',
            [
                "attribute"=>"openid",
                "label"=>"是否有openID",
                "value"=>function ($model)
                {
                    return !empty($model->openid)? "有" : "没有";
                },
                "filter"=>["有","没有"]
            ],
            [
            	'header'=>"操作",
            	'class' => 'yii\grid\ActionColumn',
            	'template' => '{update} {delete}{send}',
                    'buttons' => [
                        'send' => function ($url, $model, $key) {
                            return Html::a('发送', '#', [
                                'data-toggle' => 'modal',
//                                'data-target' => '#update-modal',
                                'class' => 'data-update',
                                'data-id' => $key,
                                 'openid' => $model->openid,
                                'onclick'=>'getOpid(this)',
                            ]);
                        },
                    ],
    		],
        ],
    ]);
?>

<?php
    // 更新操作
    $form = ActiveForm::begin([
        "method"=>"post",
        "action"=>"/parents/jzsend"
    ]);
    Modal::begin([
        'id' => 'update-modal',
        'header' => '<h4 class="modal-title">发送信息</h4>',
        'footer' => '<button type="submit" class="btn btn-primary">发送</button><a href="#" class="btn btn-primary" data-dismiss="modal">取消</a>',
    ]);
    ?>
    <div class="form-group">
        <?= $form->field($model, 'jzopid')->textInput()->hiddenInput(['value'=>''])->label(false)?>
        <?=$form->field($model, 'title')->label("标题"); ?>
        <?=$form->field($model, 'content')->textarea()->label("内容");?>
    </div>
    <?php
    Modal::end();
   ActiveForm::end();

    ?>
</div>

<script>
    function getOpid(t) {
        var opid = $(t).attr("openid");
        if (!opid){
            $('.data-update').attr('data-target','');
            alert("没有家长openid，不能发送信息！");
        }else {
            $("#wpischoolgroupmessage-jzopid").val(opid);
            $('.data-update').attr('data-target','#update-modal');
            $("#w1").attr("target","_blank");
        }
    }

</script>
