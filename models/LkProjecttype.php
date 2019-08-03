<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lk_projecttype".
 *
 * @property integer $projecttypeID
 * @property string $projecttypeName
 */
class LkProjecttype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lk_projecttype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projecttypeID'], 'required'],
            [['projecttypeID'], 'integer'],
            [['flagRecurring'], 'boolean'],
            [['projecttypeName'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'projecttypeID' => 'Projecttype ID',
            'projecttypeName' => 'Projecttype Name',
            'flagRecurring' => 'Flag Recurring',
        ];
    }
}
