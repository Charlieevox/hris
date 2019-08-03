<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use kartik\mpdf\Pdf;

/**
 * This is the model class for table "tr_salesorderhead".
 *
 * @property string $salesNum
 * @property string $salesDate
 * @property integer $customerID
 * @property string $currencyID
 * @property string $rate
 * @property integer $locationID
 * @property string $grandTotal
 * @property integer $paymentID
 * @property integer $taxID
 * @property string $additionalInfo
 * @property string $authorizationNotes
 * @property string $salesName
 * @property string $salesApproval
 * @property integer $status
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property LkCurrency $currency
 * @property LkPaymentMethod $paymentmethod
 * @property MsLocation $location
 * @property MsCustomer $customer
 * @property MsTax $tax
 */
class TrSalesOrderHead extends \yii\db\ActiveRecord
{
	public $joinSalesOrderDetail;
	public $salesPhotos;
	public $settlementTotals;
	public $clientNames;
	public $clientIDs;
	public $activeStatus;
	public $priceSales;
	public $subTotalSales;
        public $jobIDs;
        public $projectNames;
        public $billingDate;
        public $billingTotal;
        public $paymentTotal;
        public $flagRecurring;
        public $flagClient;
        public $flagClientName;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_salesorderhead';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['salesNum','salesDate', 'dueDate', 'clientID', 'currencyID', 'rate', 'locationID', 'grandTotal', 'paymentID', 'salesName', 'status', 'createdBy', 'createdDate'], 'required'],
            [['salesDate', 'dueDate', 'createdDate', 'editedDate'], 'safe'],
            [['clientID', 'locationID', 'taxID', 'paymentID', 'status', 'jobID'], 'integer'],
            [['rate'], 'number'],
			[['taxRate'], 'string'],
			['salesDate','validateDates'], 
            [['currencyID'], 'string', 'max' => 10],
            [['additionalInfo', 'authorizationNotes'], 'string', 'max' => 200],
			[['salesPhotos'], 'file', 'extensions' => 'png, jpg, pdf, txt, xlsx', 'maxFiles' => 3],
            [['salesNum', 'salesName', 'salesApproval', 'createdBy', 'editedBy'], 'string', 'max' => 50],
			[['saleNum','salesDate','dueDate','clientID','currencyID','locationID','paymentID','grandTotal','status','settlementTotals','clientNames','clientIDs'], 'safe', 'on'=>'search'],
			[['joinSalesOrderDetail'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'salesNum' => 'Invoice Number',
            'salesDate' => 'Invoice Date',
            'dueDate' => 'Due Date',
            'clientID' => 'Client Name',
            'currencyID' => 'Currency',
            'rate' => 'Rate',
            'locationID' => 'Location Name',
            'grandTotal' => 'Grand Total',
            'paymentID' => 'Payment Method',
            'taxID' => 'Tax Type',
			'taxRate' => 'Tax Rate',
            'additionalInfo' => 'Additional Information',
            'authorizationNotes' => 'Authorization Notes',
            'salesName' => 'Created By',
            'salesApproval' => 'Authorized By',
            'status' => 'Status',
			'jobID' => 'Job ID',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'salesPhotos' => 'Attachment',
            'projectNames' => 'Project Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */ 
	 
    public function getCurrency()
    {
        return $this->hasOne(LkCurrency::className(), ['currencyID' => 'currencyID']);
    }
	
	public function getPaymentMethod()
    {
        return $this->hasOne(LkPaymentMethod::className(), ['paymentID' => 'paymentID']);
    }

    public function getLocation()
    {
        return $this->hasOne(MsLocation::className(), ['locationID' => 'locationID']);
    }
	
	public function getTax()
    {
        return $this->hasOne(MsTax::className(), ['taxID' => 'taxID']);
    }
	
	public function getCustomer()
    {
        return $this->hasOne(MsCustomer::className(), ['customerID' => 'customerID']);
    }
    
    public function getClient()
    {
        return $this->hasOne(MsClient::className(), ['clientID' => 'clientID']);
    }
	
	public function getSalesOrderDetails()
	{
        return $this->hasMany(TrSalesOrderDetail::className(), ['salesNum' => 'salesNum']);
    }
	
	  public function getProposals()
    {
        return $this->hasOne(TrProposalHead::className(), ['proposalNum' => 'proposalNum']);
    }
	
	  public function getJobs()
    {
        return $this->hasOne(TrJob::className(), ['jobID' => 'jobID']);
    }
	
	 public function getUploadSalesDirectory($isBasePath = true)
    {
        if ($isBasePath) {
            return Yii::$app->basePath . '/assets_b/uploads/' . Yii::$app->user->identity->company->companyName . '/sales-photos/' . $this->salesNum . "/";
        } else {
            return Yii::$app->urlManager->baseUrl . '/assets_b/uploads/' . Yii::$app->user->identity->company->companyName . '/sales-photos/' . $this->salesNum . "/";
        }
    }
	
	public function getClientSettlement()
    {
        return $this->hasOne(TrClientSettlementDetail::className(), ['salesNum' => 'salesNum']);
    }
    
        public function getStatus1()
	{
        return $this->hasOne(MsStatus::className(), ['statusID' => 'status'])->onCondition(['statusKey' => 'Invoice']);
    }
	
	public function search()
    {
        $query = self::find()
        ->joinWith('currency')
        ->joinWith('location')
        ->joinWith('paymentMethod')
        ->joinWith('tax')
        ->joinWith('client')
        ->joinWith('clientSettlement')
        ->joinWith('jobs')
        ->joinWith('status1')
        ->andFilterWhere(['like', 'tr_salesorderhead.salesNum', $this->salesNum])
        ->andFilterWhere(['=', "DATE_FORMAT(tr_salesorderhead.salesDate, '%d-%m-%Y')", $this->salesDate])
        ->andFilterWhere(['=', "DATE_FORMAT(tr_salesorderhead.dueDate, '%d-%m-%Y')", $this->dueDate])
        ->andFilterWhere(['=', 'tr_salesorderhead.grandTotal', $this->grandTotal])
        ->andFilterWhere(['=', 'tr_salesorderhead.clientID', $this->clientID])
        ->andFilterWhere(['=', 'tr_salesorderhead.locationID', $this->locationID])
        ->andFilterWhere(['=', 'tr_salesorderhead.currencyID', $this->currencyID])
        ->andFilterWhere(['=', 'tr_salesorderhead.paymentMethodID', $this->paymentID])
        ->andFilterWhere(['in', 'tr_salesorderhead.status', $this->activeStatus])
        ->andFilterWhere(['like', 'ms_client.clientName', $this->clientNames])
        ->andFilterWhere(['=', 'tr_salesorderhead.jobID', $this->jobID])
        ->andFilterWhere(['=', 'ms_client.clientID', $this->clientIDs])
        ->andFilterWhere(['=', 'ms_status.statusID', $this->status]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['salesNum' => SORT_DESC],
                'attributes' => ['salesNum']
            ],
        ]);
		
		$dataProvider->sort->attributes['salesDate'] = [
            'asc' => [self::tableName() . '.salesDate' => SORT_ASC],
            'desc' => [self::tableName() . '.salesDate' => SORT_DESC],
        ];
		
		$dataProvider->sort->attributes['dueDate'] = [
            'asc' => [self::tableName() . '.dueDate' => SORT_ASC],
            'desc' => [self::tableName() . '.dueDate' => SORT_DESC],
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
		
		$dataProvider->sort->attributes['currencyID'] = [
            'asc' => ['ms_currency.currencyID' => SORT_ASC],
            'desc' => ['ms_currency.currencyID' => SORT_DESC],
        ];
		
        $dataProvider->sort->attributes['paymentID'] = [
            'asc' => ['lk_paymentmethod.paymentName' => SORT_ASC],
            'desc' => ['lk_paymentmethod.paymentName' => SORT_DESC],
        ];
		
	    $dataProvider->sort->attributes['settlementTotals'] = [
		'asc' => ['settlementTotal' => SORT_ASC],
		'desc' => ['settlementTotal' => SORT_DESC],
        ];
            
        $dataProvider->sort->attributes['status'] = [
            'asc' => ['ms_status.description' => SORT_ASC],
            'desc' => ['ms_status.description' => SORT_DESC],
        ];
			
        return $dataProvider;
    }
	
	public function afterFind(){
        parent::afterFind();
        $this->salesDate = AppHelper::convertDateTimeFormat($this->salesDate, 'Y-m-d H:i:s', 'd-m-Y');
        $this->dueDate = AppHelper::convertDateTimeFormat($this->dueDate, 'Y-m-d H:i:s', 'd-m-Y');
        $query = (new \yii\db\Query())->from('tr_clientsettlementdetail')
            ->where('salesNum = :salesNum',[
                    ':salesNum' => $this->salesNum
            ]);
        $this->settlementTotals = $this->grandTotal - $query->sum('settlementTotal');
        $this->joinSalesOrderDetail = [];
        $i = 0;
        foreach ($this->getSalesOrderDetails()->all() as $joinSalesOrderDetail) {
            $this->joinSalesOrderDetail[$i]["barcodeNumber"] = $joinSalesOrderDetail->barcodeNumber;
            $this->joinSalesOrderDetail[$i]["productName"] = $joinSalesOrderDetail->product->productName;
            $this->joinSalesOrderDetail[$i]["uomName"] = $joinSalesOrderDetail->productDetail->uom->uomName;
            $this->joinSalesOrderDetail[$i]["qty"] = $joinSalesOrderDetail->qty;
            $this->joinSalesOrderDetail[$i]["price"] = $joinSalesOrderDetail->price;
            $this->joinSalesOrderDetail[$i]["discount"] = $joinSalesOrderDetail->discount;
            $this->joinSalesOrderDetail[$i]["taxValue"] = $joinSalesOrderDetail->tax;
            $this->joinSalesOrderDetail[$i]["tax"] = ($joinSalesOrderDetail->tax > 0 ? "checked" : "");
            $this->joinSalesOrderDetail[$i]["outstanding"] = 0;
            $this->joinSalesOrderDetail[$i]["subTotal"] = $joinSalesOrderDetail->subTotal;
            $i += 1;
        }
    }
	
	public function validateDates(){
		if(strtotime($this->dueDate) < strtotime($this->salesDate)){
			$this->addError('dueDate','Due Date must be greater than or equal to Sales Date');
			$this->addError('salesDate','Sales Date Must be less than or equal to Due Date ');
		}
	}

	
	 public function beforeSave($insert)
    {  
	if(parent::beforeSave($insert)) {
			
                FileHelper::createDirectory($this->getUploadSalesDirectory());
                foreach ($this->salesPhotos as $photo) {
                     $id = uniqid();
                     $result = $photo->saveAs($this->getUploadSalesDirectory() . $this->salesNum . '-' . $id . '.' . $photo->extension);
                }
		 return true;
        } else {
            return false;
        }
				
	}
    
			
	    public function afterDelete()
    {
        parent::afterDelete();
        FileHelper::removeDirectory($this->getUploadSalesDirectory());
    }
	
	
	 public function getPhotosInitialPreview()
    {
        $files = FileHelper::findFiles($this->getUploadSalesDirectory(), ['recursive' => false]);
        $image = [];
        if (isset($files[0])) {
            foreach ($files as $index => $file) {
				$temp1 = explode("/", $file);
                $file = end($temp1);
				$temp2 = explode("\\", $file);
                $file = end($temp2);
                if(StringHelper::startsWith($file, $this->salesNum, false)){
                    $file = $this->getUploadSalesDirectory(false) . $file;
                    $image[] = '<img src="' . $file . '" class="file-preview-image">';
                }
            }
        }
        return $image;
    }

    public function getPhotosInitialPreviewConfig()
    {
        $files = FileHelper::findFiles($this->getUploadSalesDirectory(), ['recursive' => false]);
        $image = [];
        if (isset($files[0])) {
            foreach ($files as $index => $file) {
                $temp1 = explode("/", $file);
                $file = end($temp1);
				$temp2 = explode("\\", $file);
                $file = end($temp2);
                if(StringHelper::startsWith($file, $this->salesNum, false)){
                    $image[] = [
                        'url' => Yii::$app->urlManager->createUrl(['sales/remove-image', 'id' => $this->salesNum]),
                        'key' => $file,
                        'extra' => ['key' => $file]
                    ];
                }
            }
        }
        return $image;
    }

    public function removeImage($imageID)
    {
        $filePath = $this->getUploadSalesDirectory() . $imageID;
        unlink($filePath);
    }
	
	 public function getImages($type)
    {
         $files = FileHelper::findFiles($this->getUploadSalesDirectory(), ['recursive' => false]);
         $image = [];
         if (isset($files[0])) {
             foreach ($files as $index => $file) {
                $temp1 = explode("/", $file);
                $file = end($temp1);
				$temp2 = explode("\\", $file);
                $file = end($temp2);

                 if($type <> 'All'){
                     if(StringHelper::startsWith($file, $type, false)){
                         $file = $this->getUploadSalesDirectory(false) . $file;
                         $image[] = $file;
                     }
                 }
                 else{
                     $file = $this->getUploadSalesDirectory(false) . $file;
                     $image[] = $file;
                 }
             }
         }
         return $image;
     }
	 
}
