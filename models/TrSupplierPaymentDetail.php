<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tr_supplierpaymentdetail".
 *
 * @property integer $paymentDetailID
 * @property string $paymentNum
 * @property string $purchaseNum
 * @property string $tax
 * @property string $paymentTotal
 *
 * @property TrSupplierpaymenthead $paymentNum0
 * @property TrPurchaseorderhead $purchaseNum0
 */
class TrSupplierPaymentDetail extends \yii\db\ActiveRecord
{
	public $dueDate;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_supplierpaymentdetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['paymentNum', 'purchaseNum', 'tax', 'paymentTotal'], 'required'],
            [['tax', 'paymentTotal'], 'number'],
            [['paymentNum', 'purchaseNum'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'paymentDetailID' => 'Payment Detail ID',
            'paymentNum' => 'Payment Num',
            'purchaseNum' => 'Purchase Num',
            'tax' => 'Tax',
            'paymentTotal' => 'Payment Total'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(TrSupplierPaymentHead::className(), ['paymentNum' => 'paymentNum']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchase()
    {
        return $this->hasOne(TrPurchaseOrderHead::className(), ['purchaseNum' => 'purchaseNum']);
    }
}
