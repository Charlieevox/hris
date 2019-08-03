<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tr_transactionlog".
 *
 * @property integer $transactionLogID
 * @property string $transactionLogDate
 * @property string $transactionLogDesc
 * @property string $refNum
 * @property string $username
 */
class TrTransactionLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_transactionlog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transactionLogDate', 'transactionLogDesc', 'refNum', 'username'], 'required'],
            [['transactionLogDate'], 'safe'],
            [['transactionLogDesc'], 'string', 'max' => 100],
            [['refNum', 'username'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transactionLogID' => 'Transaction Log ID',
            'transactionLogDate' => 'Transaction Log Date',
            'transactionLogDesc' => 'Transaction Log Desc',
            'refNum' => 'Ref Num',
            'username' => 'Username',
        ];
    }
}
