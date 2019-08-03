<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "tr_cashin".
 *
 * @property string $cashInNum
 * @property string $cashInDate
 * @property integer $incomeID
 * @property string $cashAccount
 * @property string $incomeAccount
 * @property integer $paymentID
 * @property string $cashInAmount
 * @property integer $taxID
 * @property string $taxRate
 * @property string $totalAmount
 * @property string $additionalInfo
 * @property string $authorizationNotes
 * @property string $cashInName
 * @property string $cashInApproval
 * @property integer $status
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property MsIncome $income
 * @property LkPaymentMethod $paymentMethod
 * @property MsTax $tax
 */
class TrCashIn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_cashin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cashInNum', 'cashInDate', 'cashAccount', 'incomeAccount', 'paymentID', 'cashInAmount', 'totalAmount', 'locationID', 'cashInName', 'status', 'createdBy', 'createdDate'], 'required'],
            [['cashInDate', 'createdDate', 'editedDate'], 'safe'],
            [['paymentID', 'taxID', 'status', 'locationID'], 'integer'],
            [['cashInAmount'], 'string'],
			['cashInAmount', 'compare', 'compareValue' => '0,00', 'operator' => '>'],
			[['totalAmount', 'taxRate'], 'string'],
			[['incomeAccount'], 'string', 'max' => 20],
            [['cashInNum', 'cashAccount', 'cashInName', 'cashInApproval', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['additionalInfo', 'authorizationNotes'], 'string', 'max' => 200],
			[['cashInNum','cashInDate','cashAccount','incomeAccount','paymentID','totalAmount','locationID'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cashInNum' => 'Cash In Number',
            'cashInDate' => 'Cash In Date',
            'cashAccount' => 'Cash Account',
            'incomeAccount' => 'Income Account',
            'paymentID' => 'Payment',
            'cashInAmount' => 'Cash In Amount',
            'taxID' => 'Tax ID',
            'taxRate' => 'Tax Rate',
            'totalAmount' => 'Total Amount',
            'additionalInfo' => 'Additional Information',
            'authorizationNotes' => 'Authorization Notes',
            'cashInName' => 'Cash In Name',
            'cashInApproval' => 'Cash In Approval',
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
    public function getCoaNo()
    {
        return $this->hasOne(MsCoa::className(), ['coaNo' => 'incomeAccount']);
    }
	
	  public function getCashAccounts()
    {
        return $this->hasOne(MsCoa::className(), ['coaNo' => 'cashAccount'])
		 ->from(['coa' => MsCoa::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(LkPaymentMethod::className(), ['paymentID' => 'paymentID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTax()
    {
        return $this->hasOne(MsTax::className(), ['taxID' => 'taxID']);
    }
	
    public function getStatus1()
    {
        return $this->hasOne(MsStatus::className(), ['statusID' => 'status'])->onCondition(['statusKey' => 'Cash In']);
    }
	public function search()
    {
        $query = self::find()
        ->joinWith('paymentMethod')
        ->joinWith('tax')
        ->joinWith('coaNo')
        ->joinWith('cashAccounts')
        ->joinWith('status1')
        ->andFilterWhere(['like', 'tr_cashin.cashInNum', $this->cashInNum])
        ->andFilterWhere(['=', "DATE_FORMAT(tr_cashin.cashInDate, '%d-%m-%Y')", $this->cashInDate])
        ->andFilterWhere(['=', 'tr_cashin.cashAccount', $this->cashAccount])
        ->andFilterWhere(['=', 'tr_cashin.incomeAccount', $this->incomeAccount])
        ->andFilterWhere(['=', 'tr_cashin.paymentID', $this->paymentID])
        ->andFilterWhere(['=', 'tr_cashin.totalAmount', $this->totalAmount])
        ->andFilterWhere(['=', 'tr_cashin.locationID', $this->locationID])
        ->andFilterWhere(['=', 'ms_status.statusID', $this->status]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['cashInDate' => SORT_DESC],
                'attributes' => ['cashInDate']
            ],
        ]);
		
	$dataProvider->sort->attributes['cashInNum'] = [
            'asc' => [self::tableName() . '.cashInNum' => SORT_ASC],
            'desc' => [self::tableName() . '.cashInNum' => SORT_DESC],
        ];
		
	$dataProvider->sort->attributes['totalAmount'] = [
            'asc' => [self::tableName() . '.totalAmount' => SORT_ASC],
            'desc' => [self::tableName() . '.totalAmount' => SORT_DESC],
	];
		
	$dataProvider->sort->attributes['paymentID'] = [
            'asc' => ['lk_paymentmethod.paymentName' => SORT_ASC],
            'desc' => ['lk_paymentmethod.paymentName' => SORT_DESC],
        ];
		
	$dataProvider->sort->attributes['incomeAccount'] = [
            'asc' => ['ms_coa.description' => SORT_ASC],
            'desc' => ['ms_coa.description' => SORT_DESC],
        ];
		
	$dataProvider->sort->attributes['cashAccount'] = [
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
		$this->cashInDate = AppHelper::convertDateTimeFormat($this->cashInDate, 'Y-m-d H:i:s', 'd-m-Y');
    }
}
