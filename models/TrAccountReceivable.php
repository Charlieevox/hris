<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tr_accountreceivable".
 *
 * @property integer $receivableID
 * @property integer $clientID
 * @property string $receivableDate
 * @property string $currencyID
 * @property string $rate
 * @property string $referenceNum
 * @property string $receivableDesc
 * @property string $receivableAmount
 *
 * @property MsCLient $client
 * @property LkCurrency $currency
 */
class TrAccountReceivable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->user->identity->dbName.'.tr_accountreceivable';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clientID', 'receivableDate', 'currencyID', 'rate', 'referenceNum', 'receivableDesc', 'receivableAmount', 'locationID'], 'required'],
            [['clientID', 'locationID'], 'integer'],
            [['receivableDate'], 'safe'],
            [['rate', 'receivableAmount'], 'number'],
            [['referenceNum'], 'string', 'max' => 50],
            [['receivableDesc'], 'string', 'max' => 100],
            [['currencyID'], 'string', 'max' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'receivableID' => 'Receivable ID',
            'clientID' => 'Client',
            'receivableDate' => 'Date',
            'currencyID' => 'Currency',
            'rate' => 'Rate',
            'referenceNum' => 'Reference Number',
            'receivableDesc' => 'Description',
            'receivableAmount' => 'Receivable Amount',
            'locationID' => 'Location Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(MsClient::className(), ['clientID' => 'clientID']);
    }
    
    public function getCurrency()
    {
    	return $this->hasOne(LkCurrency::className(), ['currencyID' => 'currencyID']);
    }
    
    public function search()
    {
    	$query = self::find()
    	->joinWith('client')
    	->joinWith('currency')
    	->andFilterWhere(['=', "DATE_FORMAT(tr_accountreceivable.receivableDate, '%d-%m-%Y')", $this->receivableDate])
    	->andFilterWhere(['=', 'tr_accountreceivable.clientID', $this->clientID])
    	->andFilterWhere(['like', 'tr_accountreceivable.referenceNum', $this->referenceNum])
    	->andFilterWhere(['like', 'tr_accountreceivable.receivableDesc', $this->receivableDesc])
        ->andFilterWhere(['=', 'tr_accountreceivable.locationID', $this->locationID])
    	->andFilterWhere(['=', 'tr_accountreceivable.receivableAmount', $this->receivableAmount]);
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'sort' => [
    					'defaultOrder' => ['receivableDate' => SORT_ASC],
    					'attributes' => ['receivableDate']
    			],
				'pagination' => [
				'pageSize' => 0,
				],
    	]);
    	
        $dataProvider->sort->attributes['referenceNum'] = [
    			'asc' => [self::tableName() . '.referenceNum' => SORT_ASC],
    			'desc' => [self::tableName() . '.referenceNum' => SORT_DESC],
    	];
        
        $dataProvider->sort->attributes['receivableDesc'] = [
    			'asc' => [self::tableName() . '.receivableDesc' => SORT_ASC],
    			'desc' => [self::tableName() . '.receivableDesc' => SORT_DESC],
    	];
        
    	$dataProvider->sort->attributes['receivableAmount'] = [
    			'asc' => [self::tableName() . '.receivableAmount' => SORT_ASC],
    			'desc' => [self::tableName() . '.receivableAmount' => SORT_DESC],
    	];
    
    	return $dataProvider;
    }
    
    public function group()
    {
    	$query = self::find()
    	->select('tr_accountreceivable.clientID, SUM(tr_accountreceivable.receivableAmount*tr_accountreceivable.rate) as receivableTotal')
    	->joinWith('currency')
    	->joinWith('client')
    	->andFilterWhere(['=', 'tr_accountreceivable.clientID', $this->clientID])
    	->andFilterWhere(['=', 'tr_accountreceivable.currencyID', $this->currencyID])
    	->groupBy('tr_accountreceivable.clientID');
    	 
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'sort' => [
    					'defaultOrder' => ['clientID' => SORT_ASC],
    					'attributes' => ['clientID']
    			],
    	]);
    
    	$dataProvider->sort->attributes['receivableTotal'] = [
    			'asc' => ['receivableTotal' => SORT_ASC],
    			'desc' => ['receivableTotal' => SORT_DESC],
    	];
    	return $dataProvider;
    }
}
