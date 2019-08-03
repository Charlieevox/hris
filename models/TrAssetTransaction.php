<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tr_assettransaction".
 *
 * @property integer $transactionID
 * @property string $transactionDate
 * @property string $assetID
 * @property string $transactionDesc
 * @property string $assetValueBefore
 * @property string $transactionAmount
 * @property string $assetValueAfter
 * @property string $timeStamp
 */
class TrAssetTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_assettransaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transactionDate', 'assetID', 'transactionDesc', 'assetValueBefore', 'transactionAmount', 'assetValueAfter', 'timeStamp'], 'required'],
            [['transactionDate', 'timeStamp'], 'safe'],
            [['assetValueBefore', 'transactionAmount', 'assetValueAfter'], 'number'],
            [['assetID', 'transactionDesc'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transactionID' => 'Transaction ID',
            'transactionDate' => 'Transaction Date',
            'assetID' => 'Asset ID',
            'transactionDesc' => 'Transaction Desc',
            'assetValueBefore' => 'Asset Value Before',
            'transactionAmount' => 'Transaction Amount',
            'assetValueAfter' => 'Asset Value After',
            'timeStamp' => 'Time Stamp',
        ];
    }
}
