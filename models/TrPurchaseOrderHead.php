<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;
use yii\db\Expression;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
/**
 * This is the model class for table "tr_purchaseorderhead".
 *
 * @property string $purchaseNum
 * @property string $purchaseDate
 * @property integer $supplierID
 * @property string $currencyID
 * @property string $rate
 * @property integer $locationID
 * @property string $grandTotal
 * @property integer $paymentID
 * @property string $additionalInfo
 * @property string $authorizationNotes
 * @property string $purchaseName
 * @property string $purchaseApproval
 * @property integer $status
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property LkCurrency $currency
 * @property LkPaymentMethod $paymentmethod
 * @property MsLocation $location
 * @property MsSupplier $supplier
 * @property MsTax $tax
 */
class TrPurchaseOrderHead extends \yii\db\ActiveRecord
{
	public $joinPurchaseOrderDetail;
	public $purchasePhotos;
	public $paymentTotals;
	public $supplierNames;
	public $supplierIDs;
	public $activeStatus;
    public $statusKey;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_purchaseorderhead';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['purchaseNum','purchaseDate','dueDate', 'supplierID', 'currencyID', 'rate', 'locationID', 'grandTotal', 'paymentID', 'purchaseName', 'status', 'createdBy', 'createdDate'], 'required'],
            [['purchaseDate','dueDate', 'createdDate', 'editedDate'], 'safe'],
            [['supplierID', 'locationID', 'taxID', 'paymentID', 'status'], 'integer'],
            [['rate'], 'number'],
			[['taxRate'], 'string'],
            ['purchaseDate','validateDates'], 
            [['currencyID'], 'string', 'max' => 10],
            [['additionalInfo', 'authorizationNotes'], 'string', 'max' => 200],
            [['purchaseNum', 'purchaseName', 'purchaseApproval', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['purchasePhotos'], 'file', 'extensions' => 'png, jpg, pdf, txt, xlsx', 'maxFiles' => 3],
            [['saleNum', 'purchaseDate', 'dueDate', 'supplierID', 'currencyID', 'locationID', 'paymentID', 'grandTotal', 'status','paymentTotals','supplierNames','supplierIDs'], 'safe', 'on' => 'search'],
            [['joinPurchaseOrderDetail'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'purchaseNum' => 'Purchase Number',
            'purchaseDate' => 'Purchase Date',
            'dueDate' => 'Due Date',
            'supplierID' => 'Supplier',
            'currencyID' => 'Currency',
            'rate' => 'Rate',
            'locationID' => 'Location Name',
            'grandTotal' => 'Grand Total',
            'paymentID' => 'Payment Method',
            'taxID' => 'Tax Type',
            'taxRate' => 'Tax Rate',
            'additionalInfo' => 'Additional Information',
            'authorizationNotes' => 'Authorization Notes',
            'purchaseName' => 'Purchase Name',
            'purchaseApproval' => 'Purchase Approval',
            'status' => 'Status',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'purchasePhotos' => 'Attachment',
        ];
    }

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
	
	public function getSupplier()
    {
        return $this->hasOne(MsSupplier::className(), ['supplierID' => 'supplierID']);
    }
	
	public function getPurchaseOrderDetails()
	{
        return $this->hasMany(TrPurchaseOrderDetail::className(), ['purchaseNum' => 'purchaseNum']);
    }
    
    public function getStatus1()
	{
        return $this->hasOne(MsStatus::className(), ['statusID' => 'status'])->onCondition(['statusKey' => 'Purchase']);
    }
    
	 public function getUploadPurchaseDirectory($isBasePath = true)
    {
        if ($isBasePath) {
            return Yii::$app->basePath . '/assets_b/uploads/' . Yii::$app->user->identity->company->companyName . '/purchase-photos/'  . $this->purchaseNum . "/";
        } else {
            return Yii::$app->urlManager->baseUrl . '/assets_b/uploads/' . Yii::$app->user->identity->company->companyName . '/purchase-photos/' . $this->purchaseNum . "/";
        }
    }
	
	public function getSupplierPayment()
    {
        return $this->hasOne(TrSupplierPaymentDetail::className(), ['purchaseNum' => 'purchaseNum']);
    } 
	
	 
    public function search()
    {
		
    	$query = self::find()
    	->joinWith('currency')
    	->joinWith('location')
    	->joinWith('paymentMethod')
    	->joinWith('tax')
    	->joinWith('supplier')
        ->joinWith('supplierPayment')
        ->joinWith('status1')
      	->andFilterWhere(['like', 'tr_purchaseorderhead.purchaseNum', $this->purchaseNum])
    	->andFilterWhere(['=', "DATE_FORMAT(tr_purchaseorderhead.purchaseDate, '%d-%m-%Y')", $this->purchaseDate])
	->andFilterWhere(['=', "DATE_FORMAT(tr_purchaseorderhead.dueDate, '%d-%m-%Y')", $this->dueDate])
    	->andFilterWhere(['=', 'tr_purchaseorderhead.grandTotal', $this->grandTotal])
    	->andFilterWhere(['=', 'tr_purchaseorderhead.supplierID', $this->supplierID])
    	->andFilterWhere(['=', 'tr_purchaseorderhead.locationID', $this->locationID])
    	->andFilterWhere(['=', 'tr_purchaseorderhead.currencyID', $this->currencyID])
    	->andFilterWhere(['=', 'tr_purchaseorderhead.paymentMethodID', $this->paymentID])
        ->andFilterWhere(['in', 'tr_purchaseorderhead.status', $this->activeStatus])
	->andFilterWhere(['like', 'ms_supplier.supplierName', $this->supplierNames])
	->andFilterWhere(['=', 'ms_supplier.supplierID', $this->supplierIDs])
        ->andFilterWhere(['=', 'ms_status.statusID', $this->status]);
    	 
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'sort' => [
    					'defaultOrder' => ['purchaseNum' => SORT_DESC],
    					'attributes' => ['purchaseNum']
    			],
    	]);
		
        $dataProvider->sort->attributes['purchaseDate'] = [
                'asc' => [self::tableName() . '.purchaseDate' => SORT_ASC],
                'desc' => [self::tableName() . '.purchaseDate' => SORT_DESC],
    	];
		
        $dataProvider->sort->attributes['dueDate'] = [
                'asc' => [self::tableName() . '.dueDate' => SORT_ASC],
                'desc' => [self::tableName() . '.dueDate' => SORT_DESC],
    	];
    
    	$dataProvider->sort->attributes['grandTotal'] = [
    			'asc' => [self::tableName() . '.grandTotal' => SORT_ASC],
    			'desc' => [self::tableName() . '.grandTotal' => SORT_DESC],
    	];
    
    	$dataProvider->sort->attributes['supplierID'] = [
    			'asc' => ['ms_supplier.supplierName' => SORT_ASC],
    			'desc' => ['ms_supplier.supplierName' => SORT_DESC],
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
		
	$dataProvider->sort->attributes['paymentTotals'] = [
		 'asc' => ['paymentTotal' => SORT_ASC],
		 'desc' => ['paymentTotal' => SORT_DESC],
    	];
                 
         $dataProvider->sort->attributes['status'] = [
            'asc' => ['ms_status.description' => SORT_ASC],
            'desc' => ['ms_status.description' => SORT_DESC],
        ];

    	return $dataProvider;
    }
    
    public function afterFind(){
    	parent::afterFind();
        $this->purchaseDate = AppHelper::convertDateTimeFormat($this->purchaseDate, 'Y-m-d H:i:s', 'd-m-Y');
		$this->dueDate = AppHelper::convertDateTimeFormat($this->dueDate, 'Y-m-d H:i:s', 'd-m-Y');
								
		$query = (new \yii\db\Query())->from('tr_supplierpaymentdetail')
								->where('purchaseNum = :purchaseNum',[
									':purchaseNum' => $this->purchaseNum
								]);
								$this->paymentTotals =  $this->grandTotal - $query->sum('paymentTotal');
								
							
    	$this->joinPurchaseOrderDetail = [];
    	$i = 0;
    	foreach ($this->getPurchaseOrderDetails()->all() as $joinPurchaseOrderDetail) {
    		$this->joinPurchaseOrderDetail[$i]["barcodeNumber"] = $joinPurchaseOrderDetail->barcodeNumber;
    		$this->joinPurchaseOrderDetail[$i]["productName"] = $joinPurchaseOrderDetail->product->productName;
    		$this->joinPurchaseOrderDetail[$i]["uomName"] = $joinPurchaseOrderDetail->productDetail->uom->uomName;
    		$this->joinPurchaseOrderDetail[$i]["qty"] = $joinPurchaseOrderDetail->qty;
    		$this->joinPurchaseOrderDetail[$i]["price"] = $joinPurchaseOrderDetail->price;
    		$this->joinPurchaseOrderDetail[$i]["discount"] = $joinPurchaseOrderDetail->discount;
    		$this->joinPurchaseOrderDetail[$i]["taxValue"] = $joinPurchaseOrderDetail->tax;
    		$this->joinPurchaseOrderDetail[$i]["tax"] = ($joinPurchaseOrderDetail->tax > 0 ? "checked" : "");
    		$this->joinPurchaseOrderDetail[$i]["subTotal"] = $joinPurchaseOrderDetail->subTotal;
    		$i += 1;
    	}
    }
	
	public function validateDates(){
		if(strtotime($this->dueDate) < strtotime($this->purchaseDate)){
			$this->addError('dueDate','Due Date must be greater than or equal to Purchase Date');
			$this->addError('purchaseDate','Purchase Date Must be less than or equal to Due Date ');
		}
	}
	
	
    public function beforeSave($insert)
    {  
	if(parent::beforeSave($insert)) {
			
                FileHelper::createDirectory($this->getUploadPurchaseDirectory());
                 
                foreach ($this->purchasePhotos as $photo) {
                     $id = uniqid();
                     $result = $photo->saveAs($this->getUploadPurchaseDirectory() . $this->purchaseNum . '-' . $id . '.' . $photo->extension);
				
                }
		 return true;
        } else {
            return false;
        }
				
	}
    
			
	    public function afterDelete()
    {
        parent::afterDelete();
        FileHelper::removeDirectory($this->getUploadPurchaseDirectory());
    }
	
	
    public function getPhotosInitialPreview() {
        $files = FileHelper::findFiles($this->getUploadPurchaseDirectory(), ['recursive' => false]);
        $image = [];
        if (isset($files[0])) {
            foreach ($files as $index => $file) {
                $temp1 = explode("/", $file);
                $file = end($temp1);
				$temp2 = explode("\\", $file);
                $file = end($temp2);
                if (StringHelper::startsWith($file, $this->purchaseNum, false)) {
                    $file = $this->getUploadPurchaseDirectory(false) . $file;
                    $image[] = '<img src="' . $file . '" class="file-preview-image">';
                    // var_dump($image);
                    // yii::$app->end();
                }
            }
        }
        return $image;
    }

    public function getPhotosInitialPreviewConfig()
    {
        $files = FileHelper::findFiles($this->getUploadPurchaseDirectory(), ['recursive' => false]);
        $image = [];
        if (isset($files[0])) {
            foreach ($files as $index => $file) {
               $temp1 = explode("/", $file);
                $file = end($temp1);
				$temp2 = explode("\\", $file);
                $file = end($temp2);
                if(StringHelper::startsWith($file, $this->purchaseNum, false)){
                    $image[] = [
                        'url' => Yii::$app->urlManager->createUrl(['purchase/remove-image', 'id' => $this->purchaseNum]),
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
        $filePath = $this->getUploadPurchaseDirectory() . $imageID;
        unlink($filePath);
    }
	
	 public function getImages($type)
    {
         $files = FileHelper::findFiles($this->getUploadPurchaseDirectory(), ['recursive' => false]);
         $image = [];
         if (isset($files[0])) {
             foreach ($files as $index => $file) {
				$temp1 = explode("/", $file);
                $file = end($temp1);
				$temp2 = explode("\\", $file);
                $file = end($temp2);

                 if($type <> 'All'){
                     if(StringHelper::startsWith($file, $type, false)){
                         $file = $this->getUploadPurchaseDirectory(false) . $file;
                         $image[] = $file;
                     }
                 }
                 else{
                     $file = $this->getUploadPurchaseDirectory(false) . $file;
                     $image[] = $file;
                 }
             }
         }
         return $image;
     }
   
}
