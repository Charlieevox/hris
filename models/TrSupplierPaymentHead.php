<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;
/**
 * This is the model class for table "tr_supplierpaymenthead".
 *
 * @property string $paymentNum
 * @property string $paymentDate
 * @property integer $supplierID
 * @property string $currencyID
 * @property string $rate
 * @property string $grandTotal
 * @property integer $taxID
 * @property string $additionalInfo
 * @property string $authorizationNotes
 * @property string $paymentName
 * @property string $paymentApproval
 * @property integer $status
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property TrSupplierpaymentdetail[] $trSupplierpaymentdetails
 * @property LkCurrency $currency
 * @property MsSupplier $supplier
 * @property MsTax $tax
 */
class TrSupplierPaymentHead extends \yii\db\ActiveRecord
{
    public $joinSupplierPaymentDetail;
	public $statusNames;
	public $outstandingVals;
	public $grandTotals;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_supplierpaymenthead';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['paymentNum', 'paymentDate', 'supplierID', 'currencyID', 'rate', 'grandTotal','coaNo', 'locationID', 'paymentName', 'status', 'createdBy', 'createdDate'], 'required'],
            [['paymentDate', 'createdDate', 'editedDate'], 'safe'],
            [['supplierID', 'taxID', 'status', 'locationID'], 'integer'],
            [['rate'], 'number'],
            [['paymentNum', 'paymentName', 'paymentApproval', 'createdBy', 'editedBy', 'coaNo'], 'string', 'max' => 50],
            [['currencyID'], 'string', 'max' => 10],
            [['additionalInfo', 'authorizationNotes'], 'string', 'max' => 200],
            [['paymentNum','paymentDate','supplierID','currencyID','additionalInfo','grandTotal','coaNo','statusNames','locationID'], 'safe', 'on'=>'search'],
            [['joinSupplierPaymentDetail'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'paymentNum' => 'Payment Number',
            'paymentDate' => 'Payment Date',
            'supplierID' => 'Supplier',
            'currencyID' => 'Currency',
            'rate' => 'Rate',
            'grandTotal' => 'Grand Total',
			'coaNo' => 'Cash Account',
            'taxID' => 'Tax Type',
            'additionalInfo' => 'Additional Information',
            'authorizationNotes' => 'Authorization Notes',
            'paymentName' => 'Payment Name',
            'paymentApproval' => 'Payment Approval',
            'status' => 'Status',
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
    public function getSupplierpaymentdetails()
    {
        return $this->hasMany(TrSupplierPaymentDetail::className(), ['paymentNum' => 'paymentNum']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(LkCurrency::className(), ['currencyID' => 'currencyID']);
    }
    
     public function getLocation()
    {
        return $this->hasOne(MsLocation::className(), ['locationID' => 'locationID']);
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
    public function getTax()
    {
        return $this->hasOne(MsTax::className(), ['taxID' => 'taxID']);
    }
	
	 public function getCoaNos()
    {
        return $this->hasOne(MsCoa::className(), ['coaNo' => 'coaNo']);
    }
	
	 public function getStatus2()
	{
        return $this->hasOne(MsStatus::className(), ['statusID' => 'status'])->onCondition(['statusKey' => 'Supplier Payment']);
    }
    
     public function search()
    {
    	$query = self::find()
    	->joinWith('currency')
    	->joinWith('tax')
    	->joinWith('supplier')
	->joinWith('coaNos')
        ->joinWith('status2')
        ->joinWith('location')
    	->andFilterWhere(['like', 'tr_supplierpaymenthead.paymentNum', $this->paymentNum])
    	->andFilterWhere(['=', "DATE_FORMAT(tr_supplierpaymenthead.paymentDate, '%d-%m-%Y')", $this->paymentDate])
    	->andFilterWhere(['=', 'tr_supplierpaymenthead.grandTotal', $this->grandTotal])
    	->andFilterWhere(['=', 'tr_supplierpaymenthead.supplierID', $this->supplierID])
    	->andFilterWhere(['=', 'tr_supplierpaymenthead.currencyID', $this->currencyID])
        ->andFilterWhere(['=', 'tr_supplierpaymenthead.coaNo', $this->coaNo])
        ->andFilterWhere(['=', 'tr_supplierpaymenthead.locationID', $this->locationID])
        ->andFilterWhere(['like', 'tr_supplierpaymenthead.additionalInfo', $this->additionalInfo])
        ->andFilterWhere(['=', 'ms_status.statusID', $this->status]);
    	 
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'sort' => [
    					'defaultOrder' => ['paymentDate' => SORT_DESC],
    					'attributes' => ['paymentDate']
    			],
    	]);
    
    	$dataProvider->sort->attributes['paymentNum'] = [
    			'asc' => [self::tableName() . '.paymentNum' => SORT_ASC],
    			'desc' => [self::tableName() . '.paymentNum' => SORT_DESC],
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
    			'asc' => ['ms_currency.currencyID' => SORT_ASC],
    			'desc' => ['ms_currency.currencyID' => SORT_DESC],
    	];
    
    	$dataProvider->sort->attributes['additionalInfo'] = [
    			'asc' => [self::tableName() . '.additionalInfo' => SORT_ASC],
    			'desc' => [self::tableName() . '.additionalInfo' => SORT_DESC],
    	];
		
	$dataProvider->sort->attributes['coaNo'] = [
            'asc' => ['ms_coa.description' => SORT_ASC],
            'desc' => ['ms_coa.description' => SORT_DESC],
        ];
        
        $dataProvider->sort->attributes['status'] = [
            'asc' => ['ms_status.description' => SORT_ASC],
            'desc' => ['ms_status.description' => SORT_DESC],
        ];
    	return $dataProvider;
    }
    
    public function afterFind(){
    	parent::afterFind();
        $this->paymentDate = AppHelper::convertDateTimeFormat($this->paymentDate, 'Y-m-d H:i:s', 'd-m-Y');
    	$this->joinSupplierPaymentDetail = [];
    	$i = 0;
    	foreach ($this->getSupplierPaymentDetails()->all() as $joinSupplierPaymentDetail) {
    		$this->joinSupplierPaymentDetail[$i]["purchaseNum"] = $joinSupplierPaymentDetail->purchaseNum;
                $this->joinSupplierPaymentDetail[$i]["dueDate"] = $joinSupplierPaymentDetail->purchase->dueDate;
                $this->joinSupplierPaymentDetail[$i]["outstanding"] = 0;
                $this->joinSupplierPaymentDetail[$i]["paymentTotal"] = $joinSupplierPaymentDetail->paymentTotal;
      		$i += 1;
    	}
    }
}
