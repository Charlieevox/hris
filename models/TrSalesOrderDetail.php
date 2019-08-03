<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "tr_salesorderdetail".
 *
 * @property integer $salesDetailID
 * @property string $salesNum
 * @property string $barcodeNumber
 * @property string $qty
 * @property string $price
 * @property string $discount
 * @property string $tax
 * @property string $subTotal
 * @property string $notes
 *
 * @property MsProductdetail $productdetail
 * @property MsUom $uom
 * @property TrSalesOrderHead $trsalesorderhead
 */
class TrSalesOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_salesorderdetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['salesNum', 'barcodeNumber', 'qty', 'price', 'discount', 'tax', 'subTotal'], 'required'],
            [['qty', 'price', 'discount', 'tax', 'subTotal'], 'number'],
            [['salesNum','barcodeNumber'], 'string', 'max' => 50],
            [['notes'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'salesDetailID' => 'Sales Detail ID',
            'salesNum' => 'Sales Number',
            'barcodeNumber' => 'Barcode Number',
            'qty' => 'Qty',
            'price' => 'Price',
            'discount' => 'Discount',
            'tax' => 'Tax',
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
	
	public function getTrSalesOrderHead()
    {
        return $this->hasOne(TrSalesOrderHead::className(), ['salesNum' => 'salesNum']);
    }
}
