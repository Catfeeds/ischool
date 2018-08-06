<?php

namespace mobile\models;

use Yii;

/**
 * This is the model class for table "wp_ischool_school".
 *
 * @property string $id
 * @property string $name
 * @property string $pro
 * @property string $city
 * @property string $county
 * @property integer $ctime
 * @property string $address
 * @property string $jcname
 * @property string $schtype
 * @property string $pic
 * @property string $ispass
 * @property string $papass
 * @property string $jxpass
 * @property string $qqpass
 * @property string $ckpass
 * @property integer $rmoney
 * @property string $rmoney_note
 * @property integer $is_deleted
 * @property string $half_money
 * @property string $one_money
 * @property double $jgxsh
 * @property double $jgxsy
 */
class WpIschoolSchool extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wp_ischool_school';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ctime', 'rmoney', 'is_deleted'], 'integer'],
            [['jgxsh', 'jgxsy'], 'number'],
            [['name', 'schtype', 'ispass', 'papass', 'jxpass', 'qqpass', 'ckpass'], 'string', 'max' => 20],
            [['pro', 'city', 'county'], 'string', 'max' => 10],
            [['address', 'jcname'], 'string', 'max' => 50],
            [['pic'], 'string', 'max' => 100],
            [['rmoney_note', 'half_money', 'one_money'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'pro' => 'Pro',
            'city' => 'City',
            'county' => 'County',
            'ctime' => 'Ctime',
            'address' => 'Address',
            'jcname' => 'Jcname',
            'schtype' => 'Schtype',
            'pic' => 'Pic',
            'ispass' => 'Ispass',
            'papass' => 'Papass',
            'jxpass' => 'Jxpass',
            'qqpass' => 'Qqpass',
            'ckpass' => 'Ckpass',
            'rmoney' => 'Rmoney',
            'rmoney_note' => 'Rmoney Note',
            'is_deleted' => 'Is Deleted',
            'half_money' => 'Half Money',
            'one_money' => 'One Money',
            'jgxsh' => 'Jgxsh',
            'jgxsy' => 'Jgxsy',
        ];
    }
}
