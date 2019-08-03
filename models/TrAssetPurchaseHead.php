<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "tr_assetpurchasehead".
 *
 * @property string $assetPurchaseNum
 * @property string $assetPurchaseDate
 * @property integer $clientID
 * @property string $currencyID
 * @property string $rate
 * @property integer $locationID
 * @property string $grandTotal
 * @property integer $paymentID
 * @property integer $taxID
 * @property string $taxRate
 * @property string $additionalInfo
 * @property string $authorizationNotes
 * @property string $assetPurchaseName
 * @property string $assetPurchaseApproval
 * @property integer $status
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property TrAssetpurchasedetail[] $trAssetpurchasedetails
 * @property MsClient $client
 * @property LkCurrency $currency
 * @property MsLocation $location
 * @property MsTax $tax
 */
class TrAssetPurchaseHead extends \yii\db\ActiveRecord
{
	public $joinAssetPurchaseDetail;
	public $joinAssetData;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
         return Yii::$app->user->identity->dbName.'.tr_assetpurchasehead';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assetPurchaseNum', 'assetPurchaseDate', 'supplierID', 'currencyID', 'rate', 'locationID', 'grandTotal', 'paymentID', 'assetPurchaseName', 'status', 'createdBy', 'createdDate'], 'required'],
            [['assetPurchaseDate', 'createdDate', 'editedDate'], 'safe'],
            [['supplierID', 'locationID', 'paymentID', 'taxID', 'status'], 'integer'],
            [['rate'], 'number'],
			[['grandTotal','taxRate'], 'string'],
            [['assetPurchaseNum', 'assetPurchaseName', 'assetPurchaseApproval', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['currencyID'], 'string', 'max' => 10],
            [['additionalInfo', 'authorizationNotes'], 'string', 'max' => 200],
			[['assetPurchaseNum', 'assetPurchaseDate', 'supplierID', 'currencyID', 'grandTotal'], 'safe', 'on' => 'search'],
            [['joinAssetPurchaseDetail'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assetPurchaseNum' => 'Asset Purchase Num',
            'assetPurchaseDate' => 'Asset Purchase Date',
            'supplierID' => 'Supplier Name',
            'currencyID' => 'Currency ID',
            'rate' => 'Rate',
            'locationID' => 'Location Name',
            'grandTotal' => 'Grand Total',
            'paymentID' => 'Payment ID',
            'taxID' => 'Tax ID',
            'taxRate' => 'Tax Rate',
            'additionalInfo' => 'Additional Info',
            'authorizationNotes' => 'Authorization Notes',
            'assetPurchaseName' => 'Asset Purchase Name',
            'assetPurchaseApproval' => 'Asset Purchase Approval',
            'status' => 'Status',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrAssetPurchaseDetails()
    {
        return $this->hasMany(TrAssetPurchaseDetail::className(), ['assetPurchaseNum' => 'assetPurchaseNum']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(MsSupplier::className(), ['supplierID' => 'supplierID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(LkCurrency::className(), ['currencyID' => 'currencyID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(MsLocation::className(), ['locationID' => 'locationID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTax()
    {
        return $this->hasOne(MsTax::className(), ['taxID' => 'taxID']);
    }
	
	 public function search()
    {
    	$query = self::find()
		->joinWith('trAssetPurchaseDetails')
    	->joinWith('currency')
    	->joinWith('location')
    	->joinWith('supplier')
    	->joinWith('tax')
    	->andFilterWhere(['like', 'tr_assetpurchasehead.assetPurchaseNum', $this->assetPurchaseNum])
    	->andFilterWhere(['=', "DATE_FORMAT(tr_assetpurchasehead.assetPurchaseDate, '%d-%m-%Y')", $this->assetPurchaseDate])
    	->andFilterWhere(['=', 'tr_assetpurchasehead.supplierID', $this->supplierID])
    	->andFilterWhere(['=', 'tr_assetpurchasehead.currencyID', $this->currencyID])
        ->andFilterWhere(['=', 'tr_assetpurchasehead.locationID', $this->locationID])
    	->andFilterWhere(['=', 'tr_assetpurchasehead.grandTotal', $this->grandTotal]);
    	 
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'sort' => [
    					'defaultOrder' => ['assetPurchaseNum' => SORT_DESC],
    					'attributes' => ['assetPurchaseNum']
    			],
    	]);
    
    	$dataProvider->sort->attributes['assetPurchaseDate'] = [
    			'asc' => [self::tableName() . '.assetPurchaseDate' => SORT_ASC],
    			'desc' => [self::tableName() . '.assetPurchaseDate' => SORT_DESC],
    	];
		
    	$dataProvider->sort->attributes['grandTotal'] = [
    			'asc' => [self::tableName() . '.grandTotal' => SORT_ASC],
    			'desc' => [self::tableName() . '.grandTotal' => SORT_DESC],
    	];
    
    	$dataProvider->sort->attributes['supplierID'] = [
    			'asc' => ['ms_supplier.supplierName' => SORT_ASC],
    			'desc' => ['ms_supplier.supplierName' => SORT_DESC],
    	];
    
    	$dataProvider->sort->attributes['currencyID'] = [
    			'asc' => ['lk_currency.currencyName' => SORT_ASC],
    			'desc' => ['lk_currency.currencyName' => SORT_DESC],
    	];
        
        $dataProvider->sort->attributes['locationID'] = [
                'asc' => ['ms_location.locationName' => SORT_ASC],
                'desc' => ['ms_location.locationName' => SORT_DESC],
    	];

    	return $dataProvider;
    }
	
	  public function afterFind(){
    	parent::afterFind();
        $this->assetPurchaseDate = AppHelper::convertDateTimeFormat($this->assetPurchaseDate, 'Y-m-d H:i:s', 'd-m-Y');
    	$this->joinAssetPurchaseDetail = [];
    	$i = 0;
    	foreach ($this->getTrAssetPurchaseDetails()->all() as $joinAssetPurchaseDetail) {
    		$this->joinAssetPurchaseDetail[$i]["assetCategoryID"] = $joinAssetPurchaseDetail->assetCategoryID;
			$this->joinAssetPurchaseDetail[$i]["assetCategory"] = $joinAssetPurchaseDetail->assetCategories->assetCategory;
    		$this->joinAssetPurchaseDetail[$i]["assetName"] = $joinAssetPurchaseDetail->assetName;
    		$this->joinAssetPurchaseDetail[$i]["qty"] = $joinAssetPurchaseDetail->qty;
    		$this->joinAssetPurchaseDetail[$i]["price"] = $joinAssetPurchaseDetail->price;
    		$this->joinAssetPurchaseDetail[$i]["discount"] = $joinAssetPurchaseDetail->discount;
    		$this->joinAssetPurchaseDetail[$i]["taxValue"] = $joinAssetPurchaseDetail->tax;
    		$this->joinAssetPurchaseDetail[$i]["tax"] = ($joinAssetPurchaseDetail->tax > 0 ? "checked" : "");
    		$this->joinAssetPurchaseDetail[$i]["subTotal"] = $joinAssetPurchaseDetail->subTotal;
    		$i += 1;
    	}
    }
}
