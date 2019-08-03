<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "tr_job".
 *
 * @property integer $jobID
 * @property string $jobDate
 * @property integer $clientID
 * @property integer $picID
 * @property string $projectName
 * @property string $projectDesc
 * @property string $additionalInfo
 * @property integer $status
 * @property string $reason
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 */
class TrJob extends \yii\db\ActiveRecord
{
	public $uomNames;
	public $productNames;
	public $budgets;
	public $clientIDs;
	public $clientNames;
        public $prices;
        public $jobIDs;
        public $salesDates;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
         return Yii::$app->user->identity->dbName.'.tr_job';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jobDate', 'clientID', 'picClientID', 'barcodeNumber', 'projectDesc', 'locationID'], 'required'],
            [['jobDate', 'createdDate', 'editedDate'], 'safe'],
            [['clientID', 'picClientID', 'status', 'locationID'], 'integer'],
            [['projectName'], 'string', 'max' => 100],
            [['projectDesc', 'additionalInfo'], 'string', 'max' => 200],
            [['reason', 'createdBy', 'editedBy', 'barcodeNumber'], 'string', 'max' => 50],
            [['jobDate','clientID','picClientID','projectName','projectDesc', 'productNames', 'uomNames', 'budgets', 'clientNames', 'clientIDs', 'prices', 'locationID'], 'safe', 'on'=>'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jobID' => 'Job ID',
            'jobDate' => 'Job Date',
            'clientID' => 'Client Name',
            'picClientID' => 'Pic Name',
            'projectName' => 'Project Name',
            'projectDesc' => 'Project Desc',
            'additionalInfo' => 'Additional Info',
            'status' => 'Status',
            'reason' => 'Reason',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'productNames' => 'Product Name',
            'uomNames' => 'Unit',
            'locationID' => 'Location Name',
        ];
    }
	
	 public function getClient()
    {
        return $this->hasOne(MsClient::className(), ['clientID' => 'clientID']);
    }
    
    public function getLocation()
    {
        return $this->hasOne(MsLocation::className(), ['locationID' => 'locationID']);
    }
	
	 public function getPicClient()
    {
        return $this->hasOne(MsPicClient::className(), ['picClientID' => 'picClientID']);
    }
	
	  public function getProductDetail()
    {
        return $this->hasOne(MsProductDetail::className(), ['barcodeNumber' => 'barcodeNumber'])
		->from(['details' => MsProductDetail::tableName()]);
    }
	
	public function getProduct()
    {
        return $this->hasOne(MsProduct::className(), ['productID' => 'productID'])->viaTable('ms_productdetail', ['barcodeNumber' => 'barcodeNumber']);
    }
	
	public function getUom()
    {
        return $this->hasOne(MsUom::className(), ['uomID' => 'uomID'])->viaTable('ms_productdetail', ['barcodeNumber' => 'barcodeNumber']);
    }
	
	 public function getBudget()
    {
        return $this->hasOne(TrBudgetHead::className(), ['jobID' => 'jobID']);
    }
    
     public function getStatus1()
	{
        return $this->hasOne(MsStatus::className(), ['statusID' => 'status'])->onCondition(['statusKey' => 'Job']);
    }
    
     public function getProposalDetails()
    {
        return $this->hasOne(TrProposalDetail::className(), ['jobID' => 'jobID']);
    }
	
	public function search()
    {
        $query = self::find()
			->joinWith('client')
			->joinWith('picClient')
			->joinWith('productDetail')
			->joinWith('product')
			->joinWith('uom')
			->joinWith('budget')
                        ->joinWith('status1')
                        ->joinWith('proposalDetails')
                        ->joinWith('location')
			->andFilterWhere(['=', "DATE_FORMAT(tr_job.jobDate, '%d-%m-%Y')", $this->jobDate])
                        ->andFilterWhere(['like', 'tr_job.clientID', $this->clientID])
			->andFilterWhere(['like', 'tr_job.picClientID', $this->picClientID])
			->andFilterWhere(['like', 'tr_job.projectName', $this->projectName])
			->andFilterWhere(['in', 'tr_job.status', $this->status])
			->andFilterWhere(['=', 'tr_job.jobID', $this->jobID])
                        ->andFilterWhere(['=', 'tr_job.locationID', $this->locationID])
			->andFilterWhere(['like', 'ms_product.productName', $this->productNames])
			->andFilterWhere(['like', 'ms_uom.uomName', $this->uomNames])
			->andFilterWhere(['=', 'tr_budgethead.totalCost', $this->budgets])
                        ->andFilterWhere(['like', 'tr_job.projectDesc', $this->projectDesc])
			->andFilterWhere(['like', 'ms_client.clientName', $this->clientNames])
                         ->andFilterWhere(['=', 'tr_proposaldetail.price', $this->prices])
			->andFilterWhere(['=', 'ms_client.clientID', $this->clientIDs]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['jobDate' => SORT_DESC],
                'attributes' => ['jobDate']
            ],
        ]);
		
		$dataProvider->sort->attributes['clientID'] = [
            'asc' => ['ms_client.clientName' => SORT_ASC],
            'desc' => ['ms_client.clientName' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['picClientID'] = [
            'asc' => ['ms_pic.picName' => SORT_ASC],
            'desc' => ['ms_pic.picName' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['projectName'] = [
            'asc' => [self::tableName() . '.projectName' => SORT_ASC],
            'desc' => [self::tableName() . '.projectName' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['projectDesc'] = [
            'asc' => [self::tableName() . '.projectDesc' => SORT_ASC],
            'desc' => [self::tableName() . '.projectDesc' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['status'] = [
            'asc' => [self::tableName() . '.status' => SORT_ASC],
            'desc' => [self::tableName() . '.status' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['productNames'] = [
            'asc' => ['ms_product.productName' => SORT_ASC],
            'desc' => ['ms_product.productName' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['uomNames'] = [
            'asc' => ['ms_uom.uomName' => SORT_ASC],
            'desc' => ['ms_uom.uomName' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['budgets'] = [
            'asc' => ['tr_budgethead.totalCost' => SORT_ASC],
            'desc' => ['tr_budgethead.totalCost' => SORT_DESC],
        ];
                
             $dataProvider->sort->attributes['prices'] = [
            'asc' => ['tr_proposaldetail.price' => SORT_ASC],
            'desc' => ['tr_proposaldetail.price' => SORT_DESC],
        ];
		
		
        return $dataProvider;
    }
	
	public function afterFind(){
        parent::afterFind();
		$this->jobDate = AppHelper::convertDateTimeFormat($this->jobDate, 'Y-m-d H:i:s', 'd-m-Y');
    }
	
}
