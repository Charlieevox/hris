<?php

namespace app\models;

use Yii;

class ReportTax extends \yii\db\ActiveRecord
{
    public $period;
    
    public static function tableName()
    {
        return 'ms_personnelhead';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['period','fullName'], 'required'],
            [['id', 'fullName'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'period' => 'Period',
            'id' => 'Full Name',
            'fullName' => 'Full Name',
        ];
    }
}
