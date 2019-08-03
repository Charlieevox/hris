<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lk_greeting".
 *
 * @property integer $greetingID
 * @property string $greetingName
 *
 * @property MsClient[] $msClients
 */
class LkGreeting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.lk_greeting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['greetingName'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'greetingID' => 'Title ID',
            'greetingName' => 'Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMsClients()
    {
        return $this->hasMany(MsClient::className(), ['picGreet' => 'greetingID']);
    }
}
