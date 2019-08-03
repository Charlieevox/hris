<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tr_accountpayable".
 *
 * @property integer $payableID
 * @property integer $supplierID
 * @property string $payableDate
 * @property string $currencyID
 * @property string $rate
 * @property string $referenceNum
 * @property string $payableDesc
 * @property string $payableAmount
 *
 * @property MsSupplier $supplier
 * @property LkCurrency $currency
 */
class TrAccountPayable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_accountpayable';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplierID', 'payableDate', 'currencyID', 'rate', 'referenceNum', 'payableDesc', 'payableAmount', 'locationID'], 'required'],
            [['supplierID', 'locationID'], 'integer'],
            [['payableDate'], 'safe'],
            [['rate', 'payableAmount'], 'number'],
            [['referenceNum'], 'string', 'max' => 50],
        	[['payableDesc'], 'string', 'max' => 100],
        	[['currencyID'], 'string', 'max' => 5],
        	[['supplierID', 'locationID'], 'safe', 'on'=>'search'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'payableID' => 'Payable ID',
            'supplierID' => 'Supplier',
            'payableDate' => 'Date',
            'currencyID' => 'Currency',
            'rate' => 'Rate',
            'referenceNum' => 'Reference Number',
            'payableDesc' => 'Description',
            'payableAmount' => 'Payable Amount',
            'locationID' => 'Location Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(MsSupplier::className(), ['supplierID' => 'supplierID']);
    }
    
    public function getCurrency()
    {
    	return $this->hasOne(LkCurrency::className(), ['currencyID' => 'currencyID']);
    }
    
    public function search()
    {
    	$query = self::find()
    	->joinWith('supplier')
    	->joinWith('currency')
    	->andFilterWhere(['=', "DATE_FORMAT(tr_accountpayable.payableDate, '%d-%m-%Y')", $this->payableDate])
    	->andFilterWhere(['=', 'tr_accountpayable.supplierID', $this->supplierID])
        ->andFilterWhere(['=', 'tr_accountpayable.locationID', $this->locationID])
    	->andFilterWhere(['like', 'tr_accountpayable.referenceNum', $this->referenceNum])
    	->andFilterWhere(['like', 'tr_accountpayable.payableDesc', $this->payableDesc])
    	->andFilterWhere(['=', 'tr_accountpayable.payableAmount', $this->payableAmount]);
    	 
    	$dataProvider = new ActiveDataProvider([
    		'query' => $query,
    		'sort' => [
    			'defaultOrder' => ['payableDate' => SORT_ASC],
    			'attributes' => ['payableDate']
    		],
			'pagination' => [
				'pageSize' => 0,
				],
    	]);
    	
        $dataProvider->sort->attributes['referenceNum'] = [
    		'asc' => [self::tableName() . '.referenceNum' => SORT_ASC],
    		'desc' => [self::tableName() . '.referenceNum' => SORT_DESC],
    	];
        
        $dataProvider->sort->attributes['payableDesc'] = [
    		'asc' => [self::tableName() . '.payableDesc' => SORT_ASC],
    		'desc' => [self::tableName() . '.payableDesc' => SORT_DESC],
    	];
        
    	$dataProvider->sort->attributes['payableAmount'] = [
    		'asc' => [self::tableName() . '.payableAmount' => SORT_ASC],
    		'desc' => [self::tableName() . '.payableAmount' => SORT_DESC],
    	];
    
    	return $dataProvider;
    }
    
    public function group()
    {
    	$query = self::find()
    	->select('tr_accountpayable.supplierID, SUM(tr_accountpayable.payableAmount*tr_accountpayable.rate) as payableTotal')
    	->joinWith('currency')
    	->joinWith('supplier')
    	->andFilterWhere(['=', 'tr_accountpayable.supplierID', $this->supplierID])
    	->andFilterWhere(['=', 'tr_accountpayable.currencyID', $this->currencyID])
    	->groupBy('tr_accountpayable.supplierID');
    	
    	$dataProvider = new ActiveDataProvider([
    		'query' => $query,
    		'sort' => [
    			'defaultOrder' => ['supplierID' => SORT_ASC],
    			'attributes' => ['supplierID']
    		],
    	]);
    		
    	$dataProvider->sort->attributes['payableTotal'] = [
    		'asc' => ['payableTotal' => SORT_ASC],
    		'desc' => ['payableTotal' => SORT_DESC],
    	];
    	return $dataProvider;
    }
}
