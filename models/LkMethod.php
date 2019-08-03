<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lk_method".
 *
 * @property integer $methodID
 * @property string $methodName
 */
class LkMethod extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
         return Yii::$app->user->identity->dbName.'.lk_method';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['methodID', 'methodName'], 'required'],
            [['methodID'], 'integer'],
            [['methodName'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'methodID' => 'Method ID',
            'methodName' => 'Method Name',
        ];
    }
}
