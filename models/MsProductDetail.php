<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ms_productdetail".
 *
 * @property string $barcodeNumber
 * @property integer $productID
 * @property integer $uomID
 * @property string $qty
 * @property string $buyPrice
 * @property string $sellPrice
 *
 * @property MsUom $uomName
 * @property MsProduct $product
 */
class MsProductDetail extends \yii\db\ActiveRecord
{
	public $productIsActive;
	public $productName;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.ms_productdetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['barcodeNumber', 'productID', 'uomID', 'qty', 'buyPrice', 'sellPrice'], 'required'],
            [['productID', 'uomID'], 'integer'],
            [['qty', 'buyPrice', 'sellPrice'], 'number'],
            [['barcodeNumber'], 'string', 'max' => 50],
			[['barcodeNumber','productID','qty','buyPrice','sellPrice','productIsActive','productName'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'barcodeNumber' => 'Barcode Number',
            'productID' => 'Product ID',
            'uomID' => 'Unit',
            'qty' => 'Qty',
            'buyPrice' => 'Buy Price',
            'sellPrice' => 'Sell Price',
			'product Name' => 'Product Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUom()
    {
        return $this->hasOne(MsUom::className(), ['uomID' => 'uomID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(MsProduct::className(), ['productID' => 'productID']);
    }
	
	public function search()
    {
        $query = self::find()
			->joinWith('product')
			->joinWith('uom')
            ->andFilterWhere(['like', 'ms_productdetail.barcodeNumber', $this->barcodeNumber])
			->andFilterWhere(['like', 'ms_product.productName', $this->productName])
			->andFilterWhere(['like', 'ms_product.productName', $this->productID])
			->andFilterWhere(['like', 'ms_uom.uomID', $this->uomID])
			->andFilterWhere(['=', 'ms_productdetail.qty', $this->qty])
			->andFilterWhere(['=', 'ms_productdetail.buyPrice', $this->buyPrice])
			->andFilterWhere(['=', 'ms_productdetail.sellPrice', $this->sellPrice])
        	->andFilterWhere(['=', 'ms_product.flagActive', $this->productIsActive]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['productName' => SORT_ASC],
                'attributes' => ['productName']
            ],
        ]);
		
		$dataProvider->sort->attributes['productName'] = [
            'asc' => ['ms_product.productName' => SORT_ASC],
            'desc' => ['ms_product.productName' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['productID'] = [
            'asc' => ['ms_product.productName' => SORT_ASC],
            'desc' => ['ms_product.productName' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['barcodeNumber'] = [
            'asc' => [self::tableName() . '.barcodeNumber' => SORT_ASC],
            'desc' => [self::tableName() . '.barcodeNumber' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['uomID'] = [
            'asc' => ['ms_uom.uomID' => SORT_ASC],
            'desc' => ['ms_uom.uomID' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['qty'] = [
            'asc' => [self::tableName() . '.qty' => SORT_ASC],
            'desc' => [self::tableName() . '.qty' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['buyPrice'] = [
            'asc' => [self::tableName() . '.buyPrice' => SORT_ASC],
            'desc' => [self::tableName() . '.buyPrice' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['sellPrice'] = [
            'asc' => [self::tableName() . '.sellPrice' => SORT_ASC],
            'desc' => [self::tableName() . '.sellPrice' => SORT_DESC],
        ];
        return $dataProvider;
    }
}
