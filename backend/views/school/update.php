<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WpIschoolSchool */

$this->title = '学校更新' ;
$this->params['breadcrumbs'][] = ['label' => '学校管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '学校更新';
?>
<?php
    $half_money_arr = json_decode($model->half_money,true);
    $model->half_jiaxiao_money = isset($half_money_arr['jiaxiao'])?$half_money_arr['jiaxiao']:"";
    $model->half_jiaxiao_money = isset($half_money_arr['jiaxiao'])?$half_money_arr['jiaxiao']:"";
    $model->half_jiaxiao_money_ck = isset($half_money_arr['jiaxiaock'])?$half_money_arr['jiaxiaock']:"";
    $model->half_pingan_money = isset($half_money_arr['pingan'])?$half_money_arr['pingan']:"";
    $model->half_qinqing_money = isset($half_money_arr['qinqing'])?$half_money_arr['qinqing']:"";
    $model->half_canka_money = isset($half_money_arr['canka'])?$half_money_arr['canka']:"";
    $model->half_sss_money = isset($half_money_arr['sss'])?$half_money_arr['sss']:"";
    $model->half_ww_money = isset($half_money_arr['ww'])?$half_money_arr['ww']:"";
    $one_money_arr = json_decode($model->one_money,true);
    $model->one_jiaxiao_money = isset($one_money_arr['jiaxiao'])?$one_money_arr['jiaxiao']:"";
    $model->one_jiaxiao_money_ck = isset($one_money_arr['jiaxiaock'])?$one_money_arr['jiaxiaock']:"";
    $model->one_pingan_money = isset($one_money_arr['pingan'])?$one_money_arr['pingan']:"";
    $model->one_qinqing_money = isset($one_money_arr['qinqing'])?$one_money_arr['qinqing']:"";
    $model->one_canka_money = isset($one_money_arr['canka'])?$one_money_arr['canka']:"";
    $model->one_sss_money = isset($one_money_arr['sss'])?$one_money_arr['sss']:"";
    $model->one_ww_money = isset($one_money_arr['ww'])?$one_money_arr['ww']:"";
?>
<div class="wp-ischool-school-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
