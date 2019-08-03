<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ms_setting".
 *
 * @property string $key1
 * @property string $key2
 * @property string $value1
 * @property string $value2
 */
class MsSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key1'], 'required'],
            [['key1', 'key2', 'value1', 'value2'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'key1' => 'Key1',
            'key2' => 'Key2',
            'value1' => 'Value1',
            'value2' => 'Value2',
        ];
    }
}
