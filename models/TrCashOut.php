<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\components\AppHelper;

/**
 * This is the model class for table "tr_cashout".
 *
 * @property string $cashOutNum
 * @property string $cashOutDate
 * @property integer $expenseID
 * @property string $cashAccount
 * @property string $expenseAccount
 * @property integer $paymentID
 * @property string $cashOutAmount
 * @property integer $taxID
 * @property string $taxRate
 * @property string $totalAmount
 * @property string $additionalInfo
 * @property string $authorizationNotes
 * @property string $cashOutName
 * @property string $cashOutApproval
 * @property integer $status
 * @property string $createdBy
 * @property string $createdDate
 * @property string $editedBy
 * @property string $editedDate
 *
 * @property MsExpense $expense
 * @property LkPaymentmethod $paymentMethod
 * @property MsTax $tax
 */
class TrCashOut extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_cashout';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cashOutNum', 'cashOutDate', 'cashAccount', 'expenseAccount', 'paymentID', 'cashOutAmount', 'totalAmount', 'locationID', 'cashOutName', 'status', 'createdBy', 'createdDate'], 'required'],
            [['cashOutDate', 'createdDate', 'editedDate'], 'safe'],
            [['paymentID', 'taxID', 'status', 'locationID'], 'integer'],
            [['cashOutAmount'], 'string'],
			['cashOutAmount', 'compare', 'compareValue' => '0,00', 'operator' => '>'],
			[['totalAmount', 'taxRate'], 'string'],
			[['expenseAccount'], 'string', 'max' => 20],
            [['cashOutNum', 'cashAccount', 'expenseAccount', 'cashOutName', 'cashOutApproval', 'createdBy', 'editedBy'], 'string', 'max' => 50],
            [['additionalInfo', 'authorizationNotes'], 'string', 'max' => 200],
			[['cashOutNum','cashOutDate','cashAccount','expenseAccount','paymentID','totalAmount','locationID'], 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cashOutNum' => 'Cash Out Number',
            'cashOutDate' => 'Cash Out Date',
            'cashAccount' => 'Cash Account',
            'expenseAccount' => 'Expense Account',
            'paymentID' => 'Payment',
            'cashOutAmount' => 'Cash Out Amount',
            'taxID' => 'Tax ID',
            'taxRate' => 'Tax Rate',
            'totalAmount' => 'Total Amount',
            'additionalInfo' => 'Additional Information',
            'authorizationNotes' => 'Authorization Notes',
            'cashOutName' => 'Cash Out Name',
            'cashOutApproval' => 'Cash Out Approval',
            'status' => 'Status',
            'createdBy' => 'Created By',
            'createdDate' => 'Created Date',
            'editedBy' => 'Edited By',
            'editedDate' => 'Edited Date',
            'locationID'=> 'Location Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
     public function getCoaNo()
     {
        return $this->hasOne(MsCoa::className(), ['coaNo' => 'expenseAccount']);
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
        return $this->hasOne(MsStatus::className(), ['statusID' => 'status'])->onCondition(['statusKey' => 'Cash Out']);
    }
	public function search()
    {
        $query = self::find()
        ->joinWith('paymentMethod')
        ->joinWith('tax')
        ->joinWith('coaNo')
        ->joinWith('cashAccounts')
        ->joinWith('status1')
        ->andFilterWhere(['like', 'tr_cashout.cashOutNum', $this->cashOutNum])
        ->andFilterWhere(['=', "DATE_FORMAT(tr_cashout.cashOutDate, '%d-%m-%Y')", $this->cashOutDate])
        ->andFilterWhere(['=', 'tr_cashout.expenseAccount', $this->expenseAccount])
        ->andFilterWhere(['=', 'tr_cashout.cashAccount', $this->cashAccount])
        ->andFilterWhere(['=', 'tr_cashout.paymentID', $this->paymentID])
        ->andFilterWhere(['=', 'tr_cashout.locationID', $this->locationID])
        ->andFilterWhere(['=', 'tr_cashout.totalAmount', $this->totalAmount])
        ->andFilterWhere(['=', 'ms_status.statusID', $this->status]);
         
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['cashOutNum' => SORT_DESC],
                'attributes' => ['cashOutNum']
            ],
        ]);
		
            $dataProvider->sort->attributes['cashOutDate'] = [
            'asc' => [self::tableName() . '.cashOutDate' => SORT_ASC],
            'desc' => [self::tableName() . '.cashOutDate' => SORT_DESC],
        ];

            $dataProvider->sort->attributes['totalAmount'] = [
                            'asc' => [self::tableName() . '.totalAmount' => SORT_ASC],
                            'desc' => [self::tableName() . '.totalAmount' => SORT_DESC],
        ];

            $dataProvider->sort->attributes['paymentID'] = [
            'asc' => ['lk_paymentmethod.paymentName' => SORT_ASC],
            'desc' => ['lk_paymentmethod.paymentName' => SORT_DESC],
        ];

            $dataProvider->sort->attributes['expenseAccount'] = [
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
		$this->cashOutDate = AppHelper::convertDateTimeFormat($this->cashOutDate, 'Y-m-d H:i:s', 'd-m-Y');
    }
}
