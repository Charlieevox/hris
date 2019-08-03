<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tr_assetsalesdetail".
 *
 * @property integer $assetSalesID
 * @property string $assetSalesNum
 * @property string $assetID
 * @property string $price
 * @property string $discount
 * @property string $tax
 * @property string $subTotal
 * @property string $notes
 *
 * @property TrAssetdata $asset
 * @property TrAssetsaleshead $assetSalesNum0
 * @property TrAssetsaleshead $trAssetsaleshead
 */
class TrAssetSalesDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_assetsalesdetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assetSalesNum', 'assetID', 'price', 'discount', 'tax', 'subTotal'], 'required'],
            [['price', 'discount', 'tax', 'subTotal'], 'number'],
            [['assetSalesNum', 'assetID'], 'string', 'max' => 50],
            [['notes'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assetSalesID' => 'Asset Sales ID',
            'assetSalesNum' => 'Asset Sales Num',
            'assetID' => 'Asset ID',
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
    public function getAsset()
    {
        return $this->hasOne(TrAssetData::className(), ['assetID' => 'assetID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrAssetSalesHeads()
    {
        return $this->hasOne(TrAssetSalesHead::className(), ['assetSalesNum' => 'assetSalesNum']);
    }
}
