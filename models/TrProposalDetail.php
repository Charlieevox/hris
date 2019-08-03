<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "tr_proposaldetail".
 *
 * @property integer $proposlDetailID
 * @property string $proposalNum
 * @property string $barcodeNumber
 * @property string $qty
 * @property string $price
 * @property string $discount
 * @property string $total
 */
class TrProposalDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
       return Yii::$app->user->identity->dbName.'.tr_proposaldetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['proposalNum', 'jobID', 'barcodeNumber', 'qty', 'price', 'discount', 'total'], 'required'],
            [['qty', 'price', 'discount', 'total'], 'number'],
			[['jobID'], 'integer'],
            [['proposalNum', 'barcodeNumber'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'proposlDetailID' => 'Proposl Detail ID',
            'proposalNum' => 'Proposal Num',
            'barcodeNumber' => 'Barcode Number',
            'qty' => 'Qty',
            'price' => 'Price',
            'discount' => 'Discount',
            'total' => 'Total',
        ];
    }
	
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
	
	public function getTrProposalHead()
    {
        return $this->hasOne(TrProposalHead::className(), ['proposalNum' => 'proposalNum']);
    }
	
	public function getJob()
    {
        return $this->hasOne(TrJob::className(), ['jobID' => 'jobID']);
    }
	
	public function getBudget()
    {
        return $this->hasOne(TrBudgetHead::className(), ['jobID' => 'jobID']);
    }
}
