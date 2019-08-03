<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "tr_assetdata".
 *
 * @property string $assetID
 * @property integer $assetCategoryID
 * @property string $assetName
 * @property string $assetCOA
 * @property string $depCOA
 * @property string $expCOA
 * @property integer $depLength
 * @property string $startingValue
 * @property string $currentValue
 * @property integer $depOccurence
 * @property string $registerDate
 * @property string $startDepDate
 * @property boolean $flagActive
 *
 * @property MsAssetcategory $assetCategory
 */
class TrAssetData extends \yii\db\ActiveRecord
{
	public $joinAssetTransaction;
	public $joinAssetMaintenance;
        public $locationIDs;
        public $locationNames;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
         return Yii::$app->user->identity->dbName.'.tr_assetdata';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assetID', 'assetCategoryID', 'assetName', 'locationID', 'assetCOA', 'depCOA', 'expCOA', 'depLength', 'startingValue', 'currentValue', 'depOccurence'], 'required'],
            [['assetCategoryID', 'depLength', 'depOccurence', 'locationID'], 'integer'],
            [['startingValue', 'currentValue'], 'string'],
            [['registerDate', 'startDepDate'], 'safe'],
            [['flagActive'], 'boolean'],
            [['assetID'], 'string', 'max' => 50],
            [['assetName'], 'string', 'max' => 100],
            [['assetCOA', 'depCOA', 'expCOA'], 'string', 'max' => 20],
			[['assetID', 'assetCategoryID', 'assetName', 'startingValue', 'currentValue','flagActive','locationID'], 'safe', 'on' => 'search'],
			[['joinAssetTransaction','joinAssetMaintenance'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assetID' => 'Asset ID',
            'assetCategoryID' => 'Asset Category',
            'assetName' => 'Asset Name',
            'assetCOA' => 'Asset Coa',
            'depCOA' => 'Dep Coa',
            'expCOA' => 'Exp Coa',
            'depLength' => 'Dep Length',
            'startingValue' => 'Starting Value',
            'currentValue' => 'Current Value',
            'depOccurence' => 'Dep Occurence',
            'registerDate' => 'Register Date',
            'startDepDate' => 'Start Dep Date',
            'flagActive' => 'Status',
            'locationID' => 'Location Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssetCategories()
    {
        return $this->hasOne(MsAssetCategory::className(), ['assetCategoryID' => 'assetCategoryID']);
    }
	
	 public function getAssetCoa(){
        return $this->hasOne(MsCoa::className(), ['coaNo' => 'assetCOA']);
    }
	
	 public function getDepCoa(){
        return $this->hasOne(MsCoa::className(), ['coaNo' => 'depCOA'])
		->from(['coa1' => MsCoa::tableName()]);
    }
	
	 public function getExpCoa(){
        return $this->hasOne(MsCoa::className(), ['coaNo' => 'expCOA'])
		->from(['coa2' => MsCoa::tableName()]);
    }
	
	  public function getAssetTransactions()
    {
        return $this->hasOne(TrAssetTransaction::className(), ['assetID' => 'assetID']);
    }
	
	 public function getAssetMains()
    {
        return $this->hasOne(TrAssetMaintenance::className(), ['assetID' => 'assetID']);
    }
    
      public function getLocation()
    {
        return $this->hasOne(MsLocation::className(), ['locationID' => 'locationID']);
    }
	
     public function search()
    {
    	$query = self::find()
	->joinWith('assetTransactions')
    	->joinWith('assetCategories')
    	->joinWith('assetCoa')
    	->joinWith('depCoa')
    	->joinWith('expCoa')
	->joinWith('assetMains')
        ->joinWith('location')
    	->andFilterWhere(['like', 'tr_assetdata.assetID', $this->assetID])
    	->andFilterWhere(['=', 'tr_assetdata.assetCategoryID', $this->assetCategoryID])
    	->andFilterWhere(['like', 'tr_assetdata.assetName', $this->assetName])
	->andFilterWhere(['=', "DATE_FORMAT(tr_assetdata.registerDate, '%d-%m-%Y')", $this->registerDate])
    	->andFilterWhere(['=', 'tr_assetdata.flagActive', $this->flagActive])
        ->andFilterWhere(['=', 'tr_assetdata.locationID', $this->locationID])
        ->andFilterWhere(['like', 'ms_location.locationName', $this->locationNames])
        ->andFilterWhere(['=', 'ms_location.locationID', $this->locationIDs])
	->andFilterWhere(['=', 'tr_assetdata.startingValue', $this->startingValue])
	->andFilterWhere(['=', 'tr_assetdata.currentValue', $this->currentValue]);
		
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query, 
    			'sort' => [
    					'defaultOrder' => ['registerDate' => SORT_DESC],
    					'attributes' => ['registerDate']
    			],
				'pagination' => [
				'pageSize' => 0,
				],
    	]);
		
		$dataProvider->sort->attributes['assetID'] = [
    			'asc' => [self::tableName() . '.assetID' => SORT_ASC],
    			'desc' => [self::tableName() . '.assetID' => SORT_DESC],
    	];
    
    
    	$dataProvider->sort->attributes['flagActive'] = [
    			'asc' => [self::tableName() . '.flagActive' => SORT_ASC],
    			'desc' => [self::tableName() . '.flagActive' => SORT_DESC],
    	];
		
    	$dataProvider->sort->attributes['depLength'] = [
    			'asc' => [self::tableName() . '.depLength' => SORT_ASC],
    			'desc' => [self::tableName() . '.depLength' => SORT_DESC],
    	];
    
    	$dataProvider->sort->attributes['assetCategoryID'] = [
    			'asc' => ['ms_assetcategory.assetCategory' => SORT_ASC],
    			'desc' => ['ms_assetcategory.assetCategory' => SORT_DESC],
    	];
		
		$dataProvider->sort->attributes['startingValue'] = [
    			'asc' => [self::tableName() . '.startingValue' => SORT_ASC],
    			'desc' => [self::tableName() . '.startingValue' => SORT_DESC],
    	];
		
		$dataProvider->sort->attributes['currentValue'] = [
    			'asc' => [self::tableName() . '.currentValue' => SORT_ASC],
    			'desc' => [self::tableName() . '.currentValue' => SORT_DESC],
    	];
		
		$dataProvider->sort->attributes['assetName'] = [
    			'asc' => [self::tableName() . '.assetName' => SORT_ASC],
    			'desc' => [self::tableName() . '.assetName' => SORT_DESC],
    	];
                
        $dataProvider->sort->attributes['locationID'] = [
            'asc' => ['ms_location.locationName' => SORT_ASC],
            'desc' => ['ms_location.locationName' => SORT_DESC],
    	];
    

    	return $dataProvider;
    }
	
	 public function afterFind(){
    	parent::afterFind();
        //$this->registerDate = AppHelper::convertDateTimeFormat($this->registerDate, 'Y-m-d H:i:s', 'd-m-Y');
    	$this->joinAssetTransaction = [];
		$this->joinAssetMaintenance = [];
    	$i = 0;
    	foreach ($this->getAssetTransactions()->all() as $joinAssetTransaction) {
			$this->joinAssetTransaction[$i]["transactionDate"] = $joinAssetTransaction->transactionDate = AppHelper::convertDateTimeFormat($joinAssetTransaction->transactionDate, 'Y-m-d H:i:s', 'd-m-Y');
			$this->joinAssetTransaction[$i]["assetID"] = $joinAssetTransaction->assetID;
			$this->joinAssetTransaction[$i]["transactionDesc"] = $joinAssetTransaction->transactionDesc;
    		$this->joinAssetTransaction[$i]["assetValueBefore"] = $joinAssetTransaction->assetValueBefore;
    		$this->joinAssetTransaction[$i]["transactionAmount"] = $joinAssetTransaction->transactionAmount;
    		$this->joinAssetTransaction[$i]["assetValueAfter"] = $joinAssetTransaction->assetValueAfter;
    		$i += 1;
    	}
		$i = 0;
		foreach ($this->getAssetMains()->all() as $joinAssetMaintenance) {
			$this->joinAssetMaintenance[$i]["maintenanceDate"] = $joinAssetMaintenance->maintenanceDate = AppHelper::convertDateTimeFormat($joinAssetMaintenance->maintenanceDate, 'Y-m-d H:i:s', 'd-m-Y');
			$this->joinAssetMaintenance[$i]["maintenanceValue"] = $joinAssetMaintenance->maintenanceValue;
			$this->joinAssetMaintenance[$i]["maintenanceDesc"] = $joinAssetMaintenance->maintenanceDesc;
    		$i += 1;
    	}
    }
	
}
