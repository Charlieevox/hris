<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lk_paymentmethod".
 *
 * @property integer $paymentID
 * @property string $paymentName
 *
 * @property TrPurchaseOrderHead[] $trpurchaseorderhead
 * @property TrSalesOrderHead[] $trsalesorderhead
 */
class LkPaymentMethod extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.lk_paymentmethod';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['paymentID', 'paymentName'], 'required'],
            [['paymentID'], 'integer'],
            [['paymentName'], 'string', 'max' => 20],
            [['paymentID'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'paymentID' => 'Payment ID',
            'paymentName' => 'Payment Name',
        ];
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getTrPurchaseOrderHeads()
    {
        return $this->hasMany(TrPurchaseOrderHead::className(), ['paymentID' => 'paymentID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrSalesOrderHeads()
    {
        return $this->hasMany(TrSalesOrderHead::className(), ['paymentID' => 'paymentID']);
    }
}
