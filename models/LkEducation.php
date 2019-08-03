<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lk_education".
 *
 * @property string $educationId
 * @property string $educationDescription
 */
class LkEducation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lk_education';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['educationId'], 'required'],
            [['educationId', 'educationDescription'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'educationId' => 'Education ID',
            'educationDescription' => 'Education Description',
        ];
    }
}
