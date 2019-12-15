<?php

namespace app\models;

class MsLate extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'ms_late';
    }

    public function rules()
    {
        return [
            [['lateId','value'], 'int']
        ];
    }

    public function attributeLabels()
    {
        return [
            'lateId' => 'lateId',
            'value' => 'value',
        ];
    }
}
