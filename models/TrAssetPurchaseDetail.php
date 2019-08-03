<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tr_assetpurchasedetail".
 *
 * @property integer $assetPurchaseDetailID
 * @property string $assetPurchaseNum
 * @property integer $assetCategoryID
 * @property string $assetName
 * @property string $qty
 * @property string $price
 * @property string $discount
 * @property string $tax
 * @property string $subTotal
 * @property string $notes
 *
 * @property MsAssetcategory $assetCategory
 * @property TrAssetpurchasehead $assetPurchaseNum0
 */
class TrAssetPurchaseDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_assetpurchasedetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assetPurchaseNum', 'assetCategoryID', 'assetName', 'qty', 'price', 'discount', 'tax', 'subTotal'], 'required'],
            [['assetCategoryID'], 'integer'],
            [['qty', 'price', 'discount', 'tax', 'subTotal'], 'number'],
            [['assetPurchaseNum'], 'string', 'max' => 50],
            [['assetName', 'notes'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assetPurchaseDetailID' => 'Asset Purchase Detail ID',
            'assetPurchaseNum' => 'Asset Purchase Num',
            'assetCategoryID' => 'Asset Category ID',
            'assetName' => 'Asset Name',
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
    public function getAssetCategories()
    {
        return $this->hasOne(MsAssetCategory::className(), ['assetCategoryID' => 'assetCategoryID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssetPurchaseHead()
    {
        return $this->hasOne(TrAssetPurchaseHead::className(), ['assetPurchaseNum' => 'assetPurchaseNum']);
    }
}
