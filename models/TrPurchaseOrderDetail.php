<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tr_purchaseorderdetail".
 *
 * @property integer $purchaseDetailID
 * @property string $purchaseNum
 * @property string $barcodeNumber
 * @property string $qty
 * @property string $price
 * @property string $discount
 * @property string $vat
 * @property string $subTotal
 * @property string $notes
 *
 * @property MsProductdetail $barcodeNumber0
 */
class TrPurchaseOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_purchaseorderdetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchaseNum', 'barcodeNumber', 'qty', 'price', 'discount', 'tax', 'subTotal'], 'required'],
            [['qty', 'price', 'discount', 'tax', 'subTotal'], 'number'],
            [['purchaseNum','barcodeNumber'], 'string', 'max' => 50],
            [['notes'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'purchaseDetailID' => 'Purchase Detail ID',
            'purchaseID' => 'Purchase Number',
            'barcodeNumber' => 'Barcode Number',
            'qty' => 'Qty',
            'price' => 'Price',
            'discount' => 'Discount',
            'vat' => 'Tax',
            'subTotal' => 'Sub Total',
            'notes' => 'Notes',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
	public function getProductDetail()
    {
        return $this->hasOne(MsProductdetail::className(), ['barcodeNumber' => 'barcodeNumber']);
    }
	
	public function getUom()
    {
        return $this->hasOne(MsUom::className(), ['uomID' => 'uomID']);
    }
	
	public function getProduct()
    {
        return $this->hasOne(MsProduct::className(), ['productID' => 'productID'])->viaTable('ms_productdetail', ['barcodeNumber' => 'barcodeNumber']);
    }
	
	public function getTrPurchaseOrderHead()
    {
        return $this->hasOne(TrPurchaseOrderHead::className(), ['purchaseNum' => 'purchaseNum']);
    }
}
