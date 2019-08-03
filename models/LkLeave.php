<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lk_education".
 *
 * @property string $educationId
 * @property string $educationDescription
 */
class LkLeave extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lk_leave';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['leaveId'], 'required'],
            [['leaveId', 'leaveDescription'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'leaveId' => 'leave ID',
            'leaveDescription' => 'Leave Description',
        ];
    }
}
