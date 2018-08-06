<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-03-17
 * Time: 10:22
 */
namespace app\models;
use Yii;
use dektrium\user\models\User;
class BaseModel extends \yii\db\ActiveRecord
{
    public $current_school_id = 0;
    public function init()
    {
        parent::init();
    }

}