<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lk_time".
 *
 * @property integer $timeID
 * @property string $unit
 * @property string $hourValue
 */
class LkTime extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lk_time';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unitValue'], 'number'],
            [['unit'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'timeID' => 'Time ID',
            'unit' => 'Unit',
            'unitValue' => 'Unit Value',
        ];
    }
}
