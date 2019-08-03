<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ms_status".
 *
 * @property integer $ID
 * @property string $statusKey
 * @property integer $statusID
 * @property string $description
 */
class MsStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID'], 'required'],
            [['ID', 'statusID'], 'integer'],
            [['statusKey'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'statusKey' => 'Status Key',
            'statusID' => 'Status ID',
            'description' => 'Description',
        ];
    }
}
