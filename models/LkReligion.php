<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lk_religion".
 *
 * @property string $religionId
 * @property string $religionDesc
 */
class LkReligion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lk_religion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['religionId'], 'required'],
            [['religionId', 'religionDesc'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'religionId' => 'Religion ID',
            'religionDesc' => 'Religion Desc',
        ];
    }
}
