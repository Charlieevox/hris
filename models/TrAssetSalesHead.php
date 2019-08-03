<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "tr_assetsaleshead".
 *
 * @property string $assetSalesNum
 * @property string $assetSalesDate
 * @property integer $clientID
 * @property integer $taxID
 * @property string $taxRate
 * @property string $grandTotal
 * @property integer $status
 * @property string $additionalInfo
 * @property string $authorizationNotes
 * @property string $assetSalesName
 * @property string $assetSalesApproval
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property TrAssetsalesdetail[] $trAssetsalesdetails
 * @property MsClient $client
 * @property TrAssetsalesdetail $assetSalesNum0
 */
class TrAssetSalesHead extends \yii\db\ActiveRecord
{
	public $joinAssetSalesDetail;
	public $joinAssetTransaction;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
       return Yii::$app->user->identity->dbName.'.tr_assetsaleshead';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assetSalesNum', 'assetSalesDate', 'clientID', 'locationID', 'grandTotal', 'status', 'createdBy', 'createdDate'], 'required'],
            [['assetSalesDate', 'createdDate', 'editedDate'], 'safe'],
            [['clientID', 'taxID', 'status', 'locationID'], 'integer'],
            [['taxRate', 'grandTotal'], 'string'],
            [['assetSalesNum', 'assetSalesName', 'assetSalesApproval', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['additionalInfo', 'authorizationNotes'], 'string', 'max' => 200],
			[['assetSalesNum','assetSalesDate','clientID','grandTotal','locationID'], 'safe', 'on'=>'search'],
			[['joinAssetSalesDetail'], 'safe'],
			[['joinAssetTransaction'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assetSalesNum' => 'Asset Sales Number',
            'assetSalesDate' => 'Asset Sales Date',
            'clientID' => 'Client Name',
            'taxID' => 'Tax ID',
            'taxRate' => 'Tax Rate',
            'grandTotal' => 'Grand Total',
            'status' => 'Status',
            'additionalInfo' => 'Additional Info',
            'authorizationNotes' => 'Authorization Notes',
            'assetSalesName' => 'Asset Sales Name',
            'assetSalesApproval' => 'Asset Sales Approval',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'locationID' => 'Location Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrAssetSalesDetails()
    {
        return $this->hasMany(TrAssetSalesDetail::className(), ['assetSalesNum' => 'assetSalesNum']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(MsClient::className(), ['clientID' => 'clientID']);
    }
    
    public function getLocation()
    {
        return $this->hasOne(MsLocation::className(), ['locationID' => 'locationID']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
	 
	 public function search()
    {
        $query = self::find()
            ->joinWith('trAssetSalesDetails')
            ->joinWith('client')
            ->joinWith('location')
            ->andFilterWhere(['like', 'tr_assetsaleshead.assetSalesNum', $this->assetSalesNum])
            ->andFilterWhere(['=', "DATE_FORMAT(tr_assetsaleshead.assetSalesDate, '%d-%m-%Y')", $this->assetSalesDate])
            ->andFilterWhere(['=', 'tr_assetsaleshead.clientID', $this->clientID])
            ->andFilterWhere(['=', 'tr_assetsaleshead.locationID', $this->locationID])
            ->andFilterWhere(['=', 'tr_assetsaleshead.grandTotal', $this->grandTotal]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['assetSalesDate' => SORT_DESC],
                'attributes' => ['assetSalesDate']
            ],
        ]);
		
        $dataProvider->sort->attributes['assetSalesNum'] = [
            'asc' => [self::tableName() . '.assetSalesNum' => SORT_ASC],
            'desc' => [self::tableName() . '.assetSalesNum' => SORT_DESC],
        ];
		
		
        $dataProvider->sort->attributes['grandTotal'] = [
            'asc' => [self::tableName() . '.grandTotal' => SORT_ASC],
            'desc' => [self::tableName() . '.grandTotal' => SORT_DESC],
        ];
		
        $dataProvider->sort->attributes['clientID'] = [
            'asc' => ['ms_client.clientName' => SORT_ASC],
            'desc' => ['ms_client.clientName' => SORT_DESC],
        ];
                
        $dataProvider->sort->attributes['locationID'] = [
                'asc' => ['ms_location.locationName' => SORT_ASC],
                'desc' => ['ms_location.locationName' => SORT_DESC],
    	];
		
        return $dataProvider;
    }
	
	
	public function afterFind(){
        parent::afterFind();
        $this->assetSalesDate = AppHelper::convertDateTimeFormat($this->assetSalesDate, 'Y-m-d H:i:s', 'd-m-Y');
        $this->joinAssetSalesDetail = [];
		$this->joinAssetTransaction = [];
        $i = 0;
        foreach ($this->getTrAssetSalesDetails()->all() as $joinAssetSalesDetail) {
            $this->joinAssetSalesDetail[$i]["assetID"] = $joinAssetSalesDetail->assetID;
			$this->joinAssetSalesDetail[$i]["assetName"] = $joinAssetSalesDetail->asset->assetName;
			$this->joinAssetSalesDetail[$i]["price"] = $joinAssetSalesDetail->price;
			$this->joinAssetSalesDetail[$i]["discount"] = $joinAssetSalesDetail->discount;
			$this->joinAssetSalesDetail[$i]["taxValue"] = $joinAssetSalesDetail->tax;
			$this->joinAssetSalesDetail[$i]["tax"] = ($joinAssetSalesDetail->tax > 0 ? "checked" : "");
			$this->joinAssetSalesDetail[$i]["subTotal"] = $joinAssetSalesDetail->subTotal;
            $i += 1;
        }
    }
	
}
